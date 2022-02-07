<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scanlog extends CI_Model {

	public function _get_dt_scanlog()
	{
		$table = 'acc_transaction';
		$order = ['event_time' => 'desc'];
		$column_order = ['dept_name','dev_alias','event_time','name','pin','verify_mode_name'];
		$column_search = ['dept_name','dev_alias','event_time','name','pin','verify_mode_name'];
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
		return $this->db->get()->num_rows();
	}
	public function count_all_scanlog()
	{
		$table = 'acc_transaction';
		return $this->db->from($table)->count_all_results();
	}

}

/* End of file Scanlog.php */
/* Location: ./application/models/Scanlog.php */