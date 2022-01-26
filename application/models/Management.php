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

	public function _get_datatable_employee()
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$join2 = 'sys_departements';
		$role = 'employee';
		$order = ['pid' => 'asc'];
		$column_order = [null,'nama','pid',$join2.'.id','whitelist'];
		$column_search = ['nama','CAST(pid as varchar)',$join2.'.name','CAST(whitelist as varchar)'];;
		$this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->join($join2,$table.'.departement_id = '.$join2.'.id', 'left')->where($join.'.slug',$role);
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
	public function datatable_employee()
	{
		$this->_get_datatable_employee();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_employee()
	{
		$this->_get_datatable_employee();
		return $this->db->get()->num_rows();
	}
	public function count_all_employee()
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$role = 'employee';
		return $this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->where($join.'.slug',$role)->count_all_results();
	}
	public function get_by_id_employee($id)
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$role = 'employee';
		return $this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->where([
			$join.'.slug' => $role,
			'id' => $id
		])->row();
	}

	public function _get_datatable_internal()
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$join2 = 'sys_departements';
		$role = 'internal';
		$order = ['pid' => 'asc'];
		$column_order = [null,'nama','pid',$join2.'.id','whitelist'];
		$column_search = ['nama','CAST(pid as varchar)',$join2.'.name','CAST(whitelist as varchar)'];;
		$this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->join($join2,$table.'.departement_id = '.$join2.'.id', 'left')->where($join.'.slug',$role);
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
	public function datatable_internal()
	{
		$this->_get_datatable_internal();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_internal()
	{
		$this->_get_datatable_internal();
		return $this->db->get()->num_rows();
	}
	public function count_all_internal()
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$role = 'internal';
		return $this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->where($join.'.slug',$role)->count_all_results();
	}
	public function get_by_id_internal($id)
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$role = 'internal';
		return $this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->where([
			$join.'.slug' => $role,
			'id' => $id
		])->row();
	}

	public function _get_datatable_visitor()
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$join2 = 'sys_departements';
		$role = 'visitor';
		$order = ['pid' => 'asc'];
		$column_order = [null,'nama','pid',$join2.'.id','whitelist'];
		$column_search = ['nama','CAST(pid as varchar)',$join2.'.name','CAST(whitelist as varchar)'];;
		$this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->join($join2,$table.'.departement_id = '.$join2.'.id', 'left')->where($join.'.slug',$role);
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
	public function datatable_visitor()
	{
		$this->_get_datatable_visitor();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_visitor()
	{
		$this->_get_datatable_visitor();
		return $this->db->get()->num_rows();
	}
	public function count_all_visitor()
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$role = 'visitor';
		return $this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->where($join.'.slug',$role)->count_all_results();
	}
	public function get_by_id_visitor($id)
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$role = 'visitor';
		return $this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->where([
			$join.'.slug' => $role,
			'id' => $id
		])->row();
	}

}

/* End of file Management.php */
/* Location: ./application/models/Management.php */