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
		$table = 'sys_users';
		return $this->db->from($table)->where([
			$table.'.nik' => $nik
		])->get()->row();
	}

	public function _get_datatable_employee()
	{
		$table = 'sys_users';
		$join2 = 'sys_departements';
		$join3 = 'final_transaction';
		$order = ['pid' => 'asc'];
		$column_order = [null,null,'nama','pid','shift',$join2.'.id'];
		$column_search = ['nama','CAST(pid as varchar)','CAST(shift as varchar)',$join2.'.name'];
		$this->db->from($table)->join($join2,$table.'.departement_id = '.$join2.'.id', 'left')->join($join3,$table.'.id = '.$join3.'.user_id')->where([
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
		return $this->db->get()->num_rows();
	}
	public function count_all_employee()
	{
		$table = 'sys_users';
		$join2 = 'sys_departements';
		$join3 = 'final_transaction';
		return $this->db->from($table)->join($join2,$table.'.departement_id = '.$join2.'.id', 'left')->join($join3,$table.'.id = '.$join3.'.user_id')->where([
			$join3.'.date' => $this->session->userdata('att_emp_date')
		])->count_all_results();
	}

	public function _get_dt_detail_emp()
	{
		$table = 'final_transaction';
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
		return $this->db->get()->num_rows();
	}
	public function count_all_detail_emp()
	{
		$table = 'final_transaction';
		return $this->db->from($table)->where([
			'date' => $this->session->userdata('att_emp_date_search'),
			'pin' => $this->session->userdata('att_emp_nik')
		])->count_all_results();
	}

	public function _get_dt_history_emp()
	{
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
		return $this->db->get()->num_rows();
	}
	public function count_all_history_emp()
	{
	}

}

/* End of file Attendance.php */
/* Location: ./application/models/Attendance.php */