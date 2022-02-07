<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Model {

	public function _get_datatable_employee()
	{
		$table = 'pers_person';
		$join2 = 'auth_department';
		$order = ['pin' => 'asc'];
		$column_order = [null,$table.'.name',$table.'.pin',$join2.'.code'];
		$column_search = [$table.'.name',$table.'.name_spell',$table.'.pin',$join2.'.name'];
		$this->db->from($table)->join($join2,$table.'.auth_dept_id = '.$join2.'.id', 'left');
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
		$table = 'pers_person';
		return $this->db->from($table)->count_all_results();
	}
	public function get_by_id_employee($id)
	{
		$table = 'pers_person';
		return $this->db->from($table)->where([
			$table.'.id' => $id
		])->get()->row();
	}
	public function get_by_nik_employee($pin)
	{
		$table = 'pers_person';
		return $this->db->from($table)->where([
			$table.'.pin' => $pin
		])->get()->row();
	}

	public function _get_datatable_employee_sch()
	{
		$table = 'sys_sch_users';
		$order = ['nik' => 'asc'];
		$column_order = [null,'tanggal','shift','masuk','pulang'];
		$column_search = ['tanggal','CAST(shift as varchar)','masuk','pulang'];
		$this->db->from($table)->where([
			'tanggal' => $this->session->userdata('employee_sch')["date"],
			'nik' => $this->session->userdata('employee_sch')["nik"]
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
	public function datatable_employee_sch()
	{
		$this->_get_datatable_employee_sch();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_employee_sch()
	{
		$this->_get_datatable_employee_sch();
		return $this->db->get()->num_rows();
	}
	public function count_all_employee_sch()
	{
		$table = 'sys_sch_users';
		return $this->db->from($table)->where([
			'tanggal' => $this->session->userdata('employee_sch')['date'],
			'nik' => $this->session->userdata('employee_sch')['nik']
		])->count_all_results();
	}
	public function get_by_id_employee_sch($id)
	{
		$table = 'sys_sch_users';
		return $this->db->from($table)->where([
			'tanggal' => $this->session->userdata('employee_sch')['date'],
			$table.'.id' => $id
		])->get()->row();
	}
	// get random data for initiating datatable for schedule
	public function set_emp_sch_rndm()
	{
		$table = 'sys_sch_users';
		return $this->db->from($table)->order_by('nik','desc')->limit(1)->get()->row();
	}

	public function checkEmp()
	{
		return $this->db->get('pers_person')->result();
	}

	public function create_schedule($data)
	{
		$this->db->insert('sys_sch_users',$data);
		return $this->db->insert_id();
	}
	public function update_schedule($id,$data)
	{
		return $this->db->where('id',$id)->update('sys_sch_users',$data);
	}
	public function delete_schedule($id)
	{
		return $this->db->where('id', $id)->delete('sys_sch_users');
	}

}

/* End of file Schedule.php */
/* Location: ./application/models/Schedule.php */