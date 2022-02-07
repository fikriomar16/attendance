<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Model {

	public function _get_dt_dashboard()
	{
		$table = 'acc_transaction_2a';
		$table2 = 'acc_transaction_2c';
		$order = ["$table.event_time" => 'desc',"$table2.event_time" => 'desc'];
		$column_order = ['event_time','name','shift','dev_alias'];
		$column_search = ['event_time','name','shift','dev_alias'];
		$this->db->from([$table,$table2])->where([
			"CAST($table.event_time as date) =" => date('Y-m-d'),
			"CAST($table2.event_time as date) =" => date('Y-m-d')
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
		return $this->db->get()->num_rows();
	}
	public function count_all_dashboard()
	{
		$table = 'acc_transaction_2a';
		$table2 = 'acc_transaction_2c';
		return $this->db->from([$table,$table2])->where([
			"CAST($table.event_time as date) =" => date('Y-m-d'),
			"CAST($table2.event_time as date) =" => date('Y-m-d')
		])->limit(50)->count_all_results();
	}

	public function countEmpToday()
	{
		return $this->db->get_where('acc_transaction_3a',[
			"CAST(event_time as date) =" => date('Y-m-d')
		])->num_rows();
	}
	public function countVisToday()
	{
		return $this->db->select('count(id) as count_id')->from('acc_transaction_2c')->where([
			"CAST(event_time as date) =" => date('Y-m-d')
		])->group_by('id')->get()->row();
	}

}

/* End of file Admin.php */
/* Location: ./application/models/Admin.php */