<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Model {

	public function _get_datatable_employee()
	{
		$table = 'sys_users';
		$join2 = 'sys_departements';
		$order = ['pid' => 'asc'];
		$column_order = [null,'nama','pid',$join2.'.id'];
		$column_search = ['nama','CAST(pid as varchar)',$join2.'.name'];
		$this->db->from($table)->join($join2,$table.'.departement_id = '.$join2.'.id', 'left');
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
		return $this->db->from($table)->count_all_results();
	}
	public function get_by_id_employee($id)
	{
		$table = 'sys_users';
		return $this->db->from($table)->where([
			$table.'.id' => $id
		])->get()->row();
	}
	public function get_by_nik_employee($nik)
	{
		$table = 'sys_users';
		return $this->db->from($table)->where([
			$table.'.nik' => $nik
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
	public function get_by_id_employee_sch($nik)
	{
		$table = 'sys_sch_users';
		return $this->db->from($table)->where([
			'tanggal' => $this->session->userdata('employee_sch')['date'],
			$table.'.pid' => $nik
		])->get()->row();
	}
	// get random data for initiating datatable for schedule
	public function set_emp_sch_rndm()
	{
		$table = 'sys_sch_users';
		return $this->db->from($table)->order_by('nik','desc')->limit(1)->get()->row();
	}

}

/* End of file Schedule.php */
/* Location: ./application/models/Schedule.php */