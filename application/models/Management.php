<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Management extends CI_Model {

	public function _get_datatable_dept()
	{
		$table = 'sys_departements';
		$order = ['id' => 'asc'];
		$column_order = [null,'name'];
		$column_search = ['name'];
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

	public function datatable_dept()
	{
		$this->_get_datatable_dept();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}

	public function count_filtered_dept()
	{
		$this->_get_datatable_dept();
		return $this->db->get()->num_rows();
	}

	public function count_all_dept()
	{
		return $this->db->from('sys_departements')->count_all_results();
	}

	public function get_by_id_dept($id)
	{
		return $this->db->get_where('sys_departements',[
			'id' => $id
		])->row();
	}

}

/* End of file Management.php */
/* Location: ./application/models/Management.php */