<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Model {

	public function _get_datatable_duration()
	{
		$table = 'sys_duration';
		$table2 = 'auth_department';
		$order = ['auth_dept_id' => 'asc'];
		$column_order = [null,'auth_dept_id','late_allowed','out_allowed'];
		$column_search = [$table2.'.name','CAST(late_allowed as varchar)','CAST(out_allowed as varchar)'];
		$this->db->select(
			"$table.id,$table2.id as dept_id,$table.auth_dept_id,$table.late_allowed,$table.out_allowed,$table2.name,$table2.code"
		)->from($table)->join($table2, $table.'.auth_dept_id = '.$table2.'.id', 'left');
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
		$table = 'sys_duration';
		$table2 = 'auth_department';
		return $this->db->from($table)->join($table2, $table.'.auth_dept_id = '.$table2.'.id', 'left')->count_all_results();
	}
	public function get_by_id_duration($id)
	{
		return $this->db->get_where('sys_duration',[
			'id' => $id
		])->row();
	}
	public function checkDept()
	{
		$table = 'auth_department';
		$current = 'sys_duration';
		return $this->db->select("$table.id,code,name")->from($table)->where(
			"id NOT IN (select auth_dept_id from $current)",NULL,FALSE
		)->get()->result();
	}
	public function checkDeptExcept($id)
	{
		$table = 'auth_department';
		$current = 'sys_duration';
		return $this->db->select("$table.id,code,name")->from($table)->where(
			"id NOT IN (select auth_dept_id from $current where id != $id)",NULL,FALSE
		)->get()->result();
	}

	public function create_duration($data)
	{
		$this->db->insert('sys_duration',$data);
		return $this->db->insert_id();
	}
	public function update_duration($id,$data)
	{
		return $this->db->where('id',$id)->update('sys_duration',$data);
	}
	public function delete_duration($id)
	{
		return $this->db->where('id', $id)->delete('sys_duration');
	}

}

/* End of file Setup.php */
/* Location: ./application/models/Setup.php */