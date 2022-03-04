<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Model {

	public function _get_datatable_duration()
	{
		$table = 'sys_duration';
		$table2 = 'auth_department';
		$order = [$table2.'.name' => 'asc'];
		$column_order = [null,$table2.'.name','late_allowed','out_allowed','out_allowed_friday','out_allowed_saturday'];
		$column_search = [$table2.'.name','CAST(late_allowed as varchar)','CAST(out_allowed as varchar)','CAST(out_allowed_friday as varchar)','CAST(out_allowed_saturday as varchar)'];
		$this->db->select(
			"$table.*,$table2.id as dept_id,$table2.name,$table2.code"
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
		return $this->db->count_all_results();
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

	public function _get_datatable_shift()
	{
		$table = 'sys_shift';
		$order = ['shift_code' => 'asc'];
		$column_order = [null,'shift_code','work_time','work_start','work_end','out_allowed'];
		$column_search = ['shift_code','CAST(work_time as varchar)','CAST(work_start as varchar)','CAST(work_end as varchar)','CAST(out_allowed as varchar)'];
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
	public function datatable_shift()
	{
		$this->_get_datatable_shift();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_shift()
	{
		$this->_get_datatable_shift();
		return $this->db->count_all_results();
	}
	public function count_all_shift()
	{
		$table = 'sys_shift';
		return $this->db->from($table)->count_all_results();
	}
	public function get_by_id_shift($id)
	{
		return $this->db->get_where('sys_shift',[
			'id' => $id
		])->row();
	}
	public function create_shift($data)
	{
		$this->db->insert('sys_shift',$data);
		return $this->db->insert_id();
	}
	public function update_shift($id,$data)
	{
		return $this->db->where('id',$id)->update('sys_shift',$data);
	}
	public function delete_shift($id)
	{
		return $this->db->where('id', $id)->delete('sys_shift');
	}

}

/* End of file Setup.php */
/* Location: ./application/models/Setup.php */