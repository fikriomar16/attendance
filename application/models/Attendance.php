<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Model {

	public function getRndmSch($get_role = 'employee')
	{
		$table = 'sys_sch_users';
		$join = 'sys_roles';
		$role = $get_role;
		return $this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->where([
			$join.'.slug' => $role
		])->order_by('nik','desc')->limit(1)->get()->row();
	}

	public function _get_datatable_employee()
	{
		$table = 'sys_users';
		$join = 'sys_roles';
		$join2 = 'sys_departements';
		$join3 = 'final_transaction';
		$role = 'employee';
		$order = ['pid' => 'asc'];
		$column_order = [null,null,'nama','pid','shift',$join2.'.id'];
		$column_search = ['nama','CAST(pid as varchar)','CAST(shift as varchar)',$join2.'.name'];
		$this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->join($join2,$table.'.departement_id = '.$join2.'.id', 'left')->join($join3,$table.'.id = '.$join3.'.user_id')->where([
			$join.'.slug' => $role,
			$join3.'.date' => $this->session->userdata('att_emp_date')
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
		$join2 = 'sys_departements';
		$join3 = 'final_transaction';
		$role = 'employee';
		return $this->db->from($table)->join($join,$table.'.role_id = '.$join.'.id', 'left')->join($join2,$table.'.departement_id = '.$join2.'.id', 'left')->join($join3,$table.'.id = '.$join3.'.user_id')->where([
			$join.'.slug' => $role,
			$join3.'.date' => $this->session->userdata('att_emp_date')
		])->count_all_results();
	}

}

/* End of file Attendance.php */
/* Location: ./application/models/Attendance.php */