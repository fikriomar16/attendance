<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Model {

	public function _get_dt_dashboard()
	{
		$table = 'acc_transaction_2a';
		$order = ["event_time" => 'desc'];
		$column_order = ['event_time','name','shift','dev_alias'];
		$column_search = ['event_time','name','shift','dev_alias'];
		$this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
		])->limit(50);
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
	public function datatable_dashboard()
	{
		$this->_get_dt_dashboard();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_dashboard()
	{
		$this->_get_dt_dashboard();
		return $this->db->count_all_results();
	}
	public function count_all_dashboard()
	{
		$table = 'acc_transaction_2a';
		return $this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
		])->limit(50)->count_all_results();
	}

	public function countEmpToday()
	{
		return $this->db->get_where('acc_transaction_3a',[
			"CAST(in_scan as date) =" => date('Y-m-d')
		])->num_rows();
	}
	public function countVisToday()
	{
		return $this->db->select('count(id) as count_id')->from('acc_transaction_2c')->where([
			"CAST(event_time as date) =" => date('Y-m-d')
		])->group_by('id')->get()->row();
	}

	public function _get_dt_dashboard2()
	{
		$table = 'acc_transaction_2c';
		$order = ["event_time" => 'desc'];
		$column_order = ['event_time','name',NULL,'dev_alias'];
		$column_search = ['event_time','name',NULL,'dev_alias'];
		$this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
		])->limit(50);
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
	public function datatable_dashboard2()
	{
		$this->_get_dt_dashboard2();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_dashboard2()
	{
		$this->_get_dt_dashboard2();
		return $this->db->count_all_results();
	}
	public function count_all_dashboard2()
	{
		$table = 'acc_transaction_2c';
		return $this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
		])->limit(50)->count_all_results();
	}

}

/* End of file Admin.php */
/* Location: ./application/models/Admin.php */