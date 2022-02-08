<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Model {

	public function getRndmSch()
	{
		$table = 'sys_sch_users';
		return $this->db->from($table)->order_by('nik','desc')->limit(1)->get()->row();
	}
	public function get_by_nik_employee($nik)
	{
		$table = 'pers_person';
		return $this->db->from($table)->where([
			$table.'.pin' => $nik
		])->get()->row();
	}

	public function _get_datatable_employee()
	{
		$table = 'pers_person';
		$join2 = 'auth_department';
		$join3 = 'acc_transaction_3a';
		$order = [$table.'.pin' => 'asc'];
		$column_order = [null,null,'name_spell','pin','shift',$join2.'.code'];
		$column_search = [$table.'.name','pin','shift',$join2.'.name'];
		$this->db->select("$table.name,$join2.name as dept_name,$table.name_spell,$table.pin,$join3.shift,$join2.code,$join2.id as dept_id")->from($table)->join($join2,$table.'.auth_dept_id = '.$join2.'.id', 'left')->join($join3,$table.'.pin = '.$join3.'.pin')->where([
			$join3.'.date' => $this->session->userdata('att_emp_date')
		]);
		if ($this->session->userdata('att_emp_shift')) {
			$this->db->where('shift', $this->session->userdata('att_emp_shift'));
		}
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
		$table = 'pers_person';
		$join2 = 'auth_department';
		$join3 = 'acc_transaction_3a';
		return $this->db->from($table)->join($join2,$table.'.auth_dept_id = '.$join2.'.id', 'left')->join($join3,$table.'.pin = '.$join3.'.pin')->where([
			$join3.'.date' => $this->session->userdata('att_emp_date')
		])->count_all_results();
	}

	public function _get_dt_detail_emp()
	{
		$table = 'acc_transaction_3a';
		$order = ['pin' => 'desc'];
		$column_order = ['date','shift','masuk','pulang','in_scan','out_scan','late_duration','out_duration','in_duration'];
		$column_search = ['CAST(date as varchar)','CAST(shift as varchar)','CAST(masuk as varchar)','CAST(pulang as varchar)','CAST(in_scan as varchar)','CAST(out_scan as varchar)','CAST(late_duration as varchar)','CAST(out_duration as varchar)','CAST(in_duration as varchar)'];
		$this->db->from($table)->where([
			'date' => $this->session->userdata('att_emp_date_search'),
			'pin' => $this->session->userdata('att_emp_nik')
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
	public function dt_detail_emp()
	{
		$this->_get_dt_detail_emp();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_detail_emp()
	{
		$this->_get_dt_detail_emp();
		return $this->db->count_all_results();
	}
	public function count_all_detail_emp()
	{
		$table = 'acc_transaction_3a';
		return $this->db->from($table)->where([
			'date' => $this->session->userdata('att_emp_date_search'),
			'pin' => $this->session->userdata('att_emp_nik')
		])->count_all_results();
	}

	public function _get_dt_history_emp()
	{
		$table = 'acc_transaction_2a';
		$order = [$table.'.event_time' => 'desc'];
		$column_order = [null,'event_time','dev_alias','shift','dev_alias'];
		$column_search = ['CAST(event_time as varchar)','dev_alias','shift'];
		$this->db->from($table)->where([
			'date' => $this->session->userdata('att_emp_date_search'),
			'pin' => $this->session->userdata('att_emp_nik')
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
	public function dt_history_emp()
	{
		$this->_get_dt_history_emp();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_history_emp()
	{
		$this->_get_dt_history_emp();
		return $this->db->count_all_results();
	}
	public function count_all_history_emp()
	{
		$table = 'acc_transaction_2a';
		return $this->db->from($table)->where([
			'date' => $this->session->userdata('att_emp_date_search'),
			'pin' => $this->session->userdata('att_emp_nik')
		])->count_all_results();
	}

}

/* End of file Attendance.php */
/* Location: ./application/models/Attendance.php */