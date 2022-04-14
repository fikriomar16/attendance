<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Model {

	public function _get_datatable_employee()
	{
		$table = 'sys_sch_users';
		$join2 = 'auth_department';
		$join3 = 'pers_person';
		$order = ['nik' => 'asc'];
		$column_order = [null,$table.'.nama',$table.'.nik',$join2.'.name'];
		$column_search = [$table.'.nama',$table.'.nik',$join2.'.name'];
		$this->db->select("$table.nama,$table.nik,$join2.name as dept_name")->from($table)->join($join3,"$table.nik = $join3.pin","left")->join($join2,"$join3.auth_dept_id = $join2.id")->group_by("nama,nik,dept_name");
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
		return $this->db->count_all_results();
	}
	public function count_all_employee()
	{
		$table = 'sys_sch_users';
		$join2 = 'auth_department';
		$join3 = 'pers_person';
		return $this->db->select("$table.nama,$table.nik,$join2.name as dept_name")->from($table)->join($join3,"$table.nik = $join3.pin","left")->join($join2,"$join3.auth_dept_id = $join2.id")->group_by("nama,nik,dept_name")->count_all_results();
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
		return $this->db->count_all_results();
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
		$table = 'pers_person';
		$table2 = 'auth_department';
		return $this->db->select("$table.*,$table2.code,$table2.name as dept_name")->from($table)->join($table2,$table.'.auth_dept_id = '.$table2.'.id', 'left')->order_by('code','asc')->get()->result();
	}
	public function checkShift()
	{
		return $this->db->get('sys_shift')->result();
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
	public function checkSch($nik,$masuk,$pulang)
	{
		return $this->db->get_where('sys_sch_users',[
			'nik' => $nik,
			'masuk' => $masuk,
			'pulang' => $pulang
		])->row();
	}
	public function get_allowed($shift)
	{
		return $this->db->get_where('sys_shift',[
			'shift_code' => $shift
		])->row();
	}
	public function insertFromImport($data)
	{
		return $this->db->insert_batch('sys_sch_users', $data);
	}

}

/* End of file Schedule.php */
/* Location: ./application/models/Schedule.php */