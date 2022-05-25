<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Model {

	public function check_user($username,$password)
	{
		return $this->db->get_where('sys_users',[
			'username' => $username,
			'password' => $password
		])->row();
	}

	public function get_auth($username,$password)
	{
		$table = "sys_users";
		$table2 = "auth_department";
		return $this->db->select("$table.*, $table2.code as dept_code, $table2.name as dept_name")->from($table)->join($table2,"$table.auth_dept_id=$table2.id","left")->where([
			"$table.username" => $username,
			"$table.password" => $password
		])->get()->row();
	}

	public function deptLists()
	{
		return $this->db->select('id, code, name')->from('auth_department')->get()->result();
	}

	public function _get_datatable_acc()
	{
		$table = 'sys_users';
		$table2 = 'auth_department';
		$order = ['id' => 'asc'];
		$column_order = [null,'nama','username',"$table2.name",'active','id'];
		$column_search = ['nama','username','password',"$table2.name"];
		$this->db->select("$table.*, $table2.code as dept_code, $table2.name as dept_name")->from($table)->join($table2,"$table.auth_dept_id=$table2.id","left")->where([
			'username !=' => 'master'
		]);
		$i = 0;
		foreach ($column_search as $item) // loop column
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like("LOWER($item)",strtolower($_POST['search']['value']));
				}
				else
				{
					$this->db->or_like("LOWER($item)",strtolower($_POST['search']['value']));
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
	public function datatable_acc()
	{
		$this->_get_datatable_acc();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_acc()
	{
		$this->_get_datatable_acc();
		return $this->db->count_all_results();
	}
	public function count_all_acc()
	{
		$table = 'sys_users';
		$table2 = 'auth_department';
		return $this->db->select("$table.*, $table2.code as dept_code, $table2.name as dept_name")->from($table)->join($table2,"$table.auth_dept_id=$table2.id","left")->where([
			'username !=' => 'master'
		])->count_all_results();
	}

	public function get_by_id_acc($id)
	{
		return $this->db->get_where('sys_users',[
			'id' => $id
		])->row();
	}

	public function create_account($data)
	{
		$this->db->insert('sys_users',$data);
		return $this->db->insert_id();
	}
	public function update_account($id,$data)
	{
		return $this->db->where('id',$id)->update('sys_users',$data);
	}
	public function delete_account($id)
	{
		return $this->db->where('id', $id)->delete('sys_users');
	}

	public function checkUsername($username)
	{
		return $this->db->get_where('sys_users', [
			'username' => $username
		])->row();
	}

}

/* End of file Auth.php */
/* Location: ./application/models/Auth.php */