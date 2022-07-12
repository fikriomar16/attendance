<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scanlog extends CI_Model {

	public function _get_dt_scanlog()
	{
		$table = 'acc_transaction';
		$order = ['event_time' => 'desc'];
		$column_order = ['dept_name','dev_alias','event_time','name','pin','verify_mode_name'];
		$column_search = ['dept_name','dev_alias','CAST(event_time as varchar)','name','pin','verify_mode_name'];
		$this->db->from($table);
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where("dept_name", $this->session->userdata('user')->dept_name);
		}
		$i = 0;
		foreach ($column_search as $item) // loop column
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($column_search) - 1 == $i) //last loop
				{
					$this->db->group_end(); //close bracket
				}
			}
			$i++;
		}
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($order))
		{
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	public function datatable_scanlog()
	{
		$this->_get_dt_scanlog();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_scanlog()
	{
		$this->_get_dt_scanlog();
		return $this->db->count_all_results();
	}
	public function count_all_scanlog()
	{
		$table = 'acc_transaction';
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where("dept_name", $this->session->userdata('user')->dept_name);
		}
		return $this->db->from($table)->count_all_results();
	}

	public function getRndmScanPin()
	{
		$table = 'acc_transaction';
		return $this->db->from($table)->order_by('event_time','asc')->limit(1)->get()->row();
	}
	public function collectNamePin()
	{
		$table = 'acc_transaction';
		return $this->db->select('pin,name')->from($table)->where([
			'pin !=' => ''
		])->group_by('pin,name')->order_by('pin','asc')->get()->result();
	}
	public function get_resume()
	{
		$table = 'acc_transaction';
		return $this->db->select("area_name,dept_name,name,pin,(select event_time from acc_transaction where pin='".$this->session->userdata('pin')."' and (select split_part(dev_alias, '-', 1))='IN' and event_time>='".$this->session->userdata('start_search')."' and event_time<='".$this->session->userdata('end_search')."' order by event_time asc limit 1) as first_scan,(select event_time from acc_transaction where pin='".$this->session->userdata('pin')."' and (select split_part(dev_alias, '-', 1))='OUT' and event_time>='".$this->session->userdata('start_search')."' and event_time<='".$this->session->userdata('end_search')."' order by event_time desc limit 1) as last_scan")->from($table)->where([
			'event_time >=' => $this->session->userdata('start_search'),
			'event_time <=' => $this->session->userdata('end_search'),
			'pin' => $this->session->userdata('pin')
		])->limit(1)->get()->row();
	}
	public function _get_dt_filter()
	{
		$table = 'acc_transaction';
		$order = ['event_time' => 'asc'];
		$column_order = ['pin','name','event_time','dev_alias','dev_alias'];
		$column_search = ['name','pin','CAST(event_time as varchar)','dev_alias'];
		$this->db->select("area_name,dept_name,dev_alias,event_time,name,pin")->from($table)->where([
			'event_time >=' => $this->session->userdata('start_search'),
			'event_time <=' => $this->session->userdata('end_search'),
			'pin' => $this->session->userdata('pin')
		]);
		$i = 0;
		foreach ($column_search as $item) // loop column
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($column_search) - 1 == $i) //last loop
				{
					$this->db->group_end(); //close bracket
				}
			}
			$i++;
		}
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($order))
		{
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	public function dt_filter()
	{
		$this->_get_dt_filter();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_filter()
	{
		$this->_get_dt_filter();
		return $this->db->count_all_results();
	}
	public function count_all_filter()
	{
		$table = 'acc_transaction';
		return $this->db->from($table)->where([
			'event_time >=' => $this->session->userdata('start_search'),
			'event_time <=' => $this->session->userdata('end_search'),
			'pin' => $this->session->userdata('pin')
		])->count_all_results();
	}

}

/* End of file Scanlog.php */
/* Location: ./application/models/Scanlog.php */