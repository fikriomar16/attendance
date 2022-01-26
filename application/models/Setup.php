<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Model {

	public function _get_datatable_gate()
	{
		$table = 'sys_gates';
		$order = ['io' => 'desc'];
		$column_order = [null,'sn','building','ip_camera','io'];
		$column_search = ['sn','building','ip_camera','CAST(io as TEXT)'];
		$this->db->from($table);
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
	public function datatable_gate()
	{
		$this->_get_datatable_gate();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_gate()
	{
		$this->_get_datatable_gate();
		return $this->db->get()->num_rows();
	}
	public function count_all_gate()
	{
		return $this->db->from('sys_gates')->count_all_results();
	}
	public function get_by_id_gate($id)
	{
		return $this->db->get_where('sys_gates',[
			'id' => $id
		])->row();
	}

	public function _get_datatable_duration()
	{
		$table = 'sys_duration';
		$order = ['departement_id' => 'asc'];
		$column_order = [null,'departement_id','late_allowed','out_allowed'];
		$column_search = ['sys_departements.name','CAST(late_allowed as varchar)','CAST(out_allowed as varchar)'];
		$this->db->from($table)->join('sys_departements', 'sys_duration.departement_id = sys_departements.id', 'left');
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
	public function datatable_duration()
	{
		$this->_get_datatable_duration();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_duration()
	{
		$this->_get_datatable_duration();
		return $this->db->get()->num_rows();
	}
	public function count_all_duration()
	{
		return $this->db->from('sys_duration')->join('sys_departements', 'sys_duration.departement_id = sys_departements.id', 'left')->count_all_results();
	}
	public function get_by_id_duration($id)
	{
		return $this->db->get_where('sys_duration',[
			'id' => $id
		])->row();
	}

	public function _get_datatable_card()
	{
		$table = 'sys_cards';
		$order = ['id' => 'asc'];
		$column_order = [null,'pid','card_number','rfid'];
		$column_search = ['CAST(pid as varchar)','card_number','CAST(rfid as varchar)'];
		$this->db->from($table);
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
	public function datatable_card()
	{
		$this->_get_datatable_card();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_card()
	{
		$this->_get_datatable_card();
		return $this->db->get()->num_rows();
	}
	public function count_all_card()
	{
		return $this->db->from('sys_cards')->count_all_results();
	}
	public function get_by_id_card($id)
	{
		return $this->db->get_where('sys_cards',[
			'id' => $id
		])->row();
	}

}

/* End of file Setup.php */
/* Location: ./application/models/Setup.php */