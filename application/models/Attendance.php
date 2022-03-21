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
	public function collectShift()
	{
		$table = 'sys_sch_users';
		return $this->db->select('shift')->from($table)->group_by('shift')->order_by('shift','asc')->get()->result();
	}

	public function _get_datatable_employee()
	{
		$table = 'acc_transaction_3a';
		$order = ['pin' => 'asc'];
		$column_order = [null,'name','pin','shift','dept_name'];
		$column_search = ['.name','.pin','shift','dept_name'];
		$this->db->select("name,dept_name,pin,shift")->from($table)->where([
			'date' => $this->session->userdata('att_emp_date')
		]);
		if ($this->session->userdata('att_emp_shift')) {
			$this->db->where('shift', $this->session->userdata('att_emp_shift'));
		}
		$this->db->group_by('name,dept_name,pin,shift');
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
		$table = 'acc_transaction_3a';
		return $this->db->select("name,dept_name,pin,shift")->from($table)->where([
			'date' => $this->session->userdata('att_emp_date')
		])->group_by('name,dept_name,pin,shift')->count_all_results();
	}

	public function _get_dt_sum_emp()
	{
		$table = 'acc_transaction_3a';
		$order = ['pin' => 'desc'];
		$column_order = ['date','shift','in_scan','out_scan','late_duration','out_duration','in_duration'];
		$column_search = ['CAST(date as varchar)','CAST(shift as varchar)','CAST(masuk as varchar)','CAST(pulang as varchar)','CAST(in_scan as varchar)','CAST(out_scan as varchar)','CAST(late_duration as varchar)','CAST(out_duration as varchar)','CAST(in_duration as varchar)'];
		$this->db->from($table)->where([
			"date_part('month',date)" => $this->session->userdata('recap_month') ?? ltrim(date('m'),'0'),
			"date_part('year',date)" => $this->session->userdata('recap_year') ?? ltrim(date('Y'),'0'),
			"pin" => $this->session->userdata('att_emp_nik')
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
	public function dt_sum_emp()
	{
		$this->_get_dt_sum_emp();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_sum_emp()
	{
		$this->_get_dt_sum_emp();
		return $this->db->count_all_results();
	}
	public function count_all_sum_emp()
	{
		$table = 'acc_transaction_3a';
		return $this->db->from($table)->where([
			"date_part('month',date)" => $this->session->userdata('recap_month') ?? ltrim(date('m'),'0'),
			"date_part('year',date)" => $this->session->userdata('recap_year') ?? ltrim(date('Y'),'0'),
			"pin" => $this->session->userdata('att_emp_nik')
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
		$order = [$table.'.event_time' => 'asc'];
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

	public function dataReportAttEmp()
	{
		$table = 'acc_transaction_3a';
		$this->db->select('*')->from($table)->where('date',$this->session->userdata('att_emp_date'));
		if ($this->session->userdata('att_emp_shift')) {
			$this->db->where('shift',$this->session->userdata('att_emp_shift'));
		}
		return $this->db->get()->result();
	}
	public function dataReportAttOff()
	{
		$table = 'acc_transaction_3b';
		$this->db->select('*')->from($table)->where('date',$this->session->userdata('att_off_date'));
		if ($this->session->userdata('att_off_shift')) {
			$this->db->where('shift',$this->session->userdata('att_off_shift'));
		}
		return $this->db->get()->result();
	}
	public function dataReportAttVis()
	{
		$table = 'acc_transaction_3c';
		return $this->db->get_where($table,[
			'CAST(first_scan as date) =' => $this->session->userdata('att_vis_date')
		])->result();
	}
	public function dataRecapScanEmp()
	{
		$table = 'acc_transaction_2a';
		return $this->db->from($table)->where([
			'date' => $this->session->userdata('att_emp_date_search'),
			'pin' => $this->session->userdata('att_emp_nik')
		])->order_by('event_time','asc')->get()->result();
	}
	public function dataRecapScanOff()
	{
		$table = 'acc_transaction_2b';
		return $this->db->from($table)->where([
			'date' => $this->session->userdata('att_off_date_search'),
			'pin' => $this->session->userdata('att_off_nik')
		])->order_by('event_time','asc')->get()->result();
	}
	public function dataRecapScanVis()
	{
		$table = 'acc_transaction_2c';
		return $this->db->from($table)->where([
			'pin' => $this->session->userdata('att_vis_pin'),
			'event_time >=' => $this->session->userdata('att_vis_first_scan'),
			'event_time <=' => $this->session->userdata('att_vis_scan_6hour')
		])->order_by('event_time','asc')->get()->result();
	}

	public function dataRecapSumEmp()
	{
		$table = 'acc_transaction_3a';
		return $this->db->from($table)->where([
			"date_part('month',date)" => $this->session->userdata('recap_month') ?? ltrim(date('m'),'0'),
			"date_part('year',date)" => $this->session->userdata('recap_year') ?? ltrim(date('Y'),'0'),
			"pin" => $this->session->userdata('att_emp_nik')
		])->order_by('in_scan','desc')->get()->result();
	}
	public function dataRecapSumOff()
	{
		$table = 'acc_transaction_3b';
		return $this->db->from($table)->where([
			"date_part('month',date)" => $this->session->userdata('recap_month_off') ?? ltrim(date('m'),'0'),
			"date_part('year',date)" => $this->session->userdata('recap_year_off') ?? ltrim(date('Y'),'0'),
			"pin" => $this->session->userdata('att_off_nik')
		])->order_by('first_scan','desc')->get()->result();
	}

	public function get_by_pin_visitor($pin)
	{
		$table = 'pers_person';
		return $this->db->get_where($table,[
			'pin' => $pin
		])->row();
	}
	public function getRndmVis()
	{
		$table = 'pers_person';
		return $this->db->from($table)->order_by('pin','desc')->limit(1)->get()->row();
	}
	public function _get_datatable_visitor()
	{
		$table = 'acc_transaction_3c';
		$order = ['pin' => 'desc'];
		$column_order = ['pin',NULL,'name','first_scan','last_scan'];
		$column_search = ['pin','name','CAST(first_scan as varchar)','CAST(last_scan as varchar)'];
		$this->db->from($table)->where([
			'CAST(first_scan as date) =' => $this->session->userdata('att_vis_date')
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
		return $this->db->count_all_results();
	}
	public function count_all_visitor()
	{
		$table = 'acc_transaction_3c';
		return $this->db->from($table)->where([
			'CAST(first_scan as date) =' => $this->session->userdata('att_vis_date')
		])->count_all_results();
	}
	public function get_vis_att_by_id($id)
	{
		$table = 'acc_transaction_3c';
		return $this->db->get_where($table,[
			'id' => $id
		])->row();
	}

	public function _get_dt_history_vis()
	{
		$table = 'acc_transaction_2c';
		$order = ['event_time' => 'asc'];
		$column_order = [null,'event_time','dev_alias','dev_alias'];
		$column_search = ['CAST(event_time as varchar)','dev_alias'];
		$this->db->from($table)->where([
			'pin' => $this->session->userdata('att_vis_pin'),
			'event_time >=' => $this->session->userdata('att_vis_first_scan'),
			'event_time <=' => $this->session->userdata('att_vis_scan_6hour')
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
	public function dt_history_vis()
	{
		$this->_get_dt_history_vis();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_history_vis()
	{
		$this->_get_dt_history_vis();
		return $this->db->count_all_results();
	}
	public function count_all_history_vis()
	{
		$table = 'acc_transaction_2c';
		return $this->db->from($table)->where([
			'pin' => $this->session->userdata('att_vis_pin'),
			'event_time >=' => $this->session->userdata('att_vis_first_scan'),
			'event_time <=' => $this->session->userdata('att_vis_scan_6hour')
		])->count_all_results();
	}

	public function _get_datatable_office()
	{
		$table = 'acc_transaction_3b';
		$order = ['pin' => 'asc'];
		$column_order = [null,'name','pin','shift','dept_name'];
		$column_search = ['name','pin','shift','dept_name'];
		$this->db->select("name,dept_name,pin,shift")->from($table)->where([
			'date' => $this->session->userdata('att_off_date')
		]);
		if ($this->session->userdata('att_off_shift')) {
			$this->db->where('shift', $this->session->userdata('att_off_shift'));
		}
		$this->db->group_by('name,dept_name,pin,shift');
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
	public function datatable_office()
	{
		$this->_get_datatable_office();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_office()
	{
		$this->_get_datatable_office();
		return $this->db->count_all_results();
	}
	public function count_all_office()
	{
		$table = 'acc_transaction_3b';
		return $this->db->select("name,dept_name,pin,shift")->from($table)->where([
			'date' => $this->session->userdata('att_off_date')
		])->group_by('name,dept_name,pin,shift')->count_all_results();
	}

	public function _get_dt_sum_off()
	{
		$table = 'acc_transaction_3b';
		$order = ['pin' => 'desc'];
		$column_order = ['date','shift','first_scan','last_scan','late_duration'];
		$column_search = ['CAST(date as varchar)','CAST(shift as varchar)','CAST(masuk as varchar)','CAST(pulang as varchar)','CAST(first_scan as varchar)','CAST(last_scan as varchar)','CAST(late_duration as varchar)'];
		$this->db->from($table)->where([
			"date_part('month',date)" => $this->session->userdata('recap_month_off') ?? ltrim(date('m'),'0'),
			"date_part('year',date)" => $this->session->userdata('recap_year_off') ?? ltrim(date('Y'),'0'),
			"pin" => $this->session->userdata('att_off_nik')
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
	public function dt_sum_off()
	{
		$this->_get_dt_sum_off();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_sum_off()
	{
		$this->_get_dt_sum_off();
		return $this->db->count_all_results();
	}
	public function count_all_sum_off()
	{
		$table = 'acc_transaction_3b';
		return $this->db->from($table)->where([
			"date_part('month',date)" => $this->session->userdata('recap_month_off') ?? ltrim(date('m'),'0'),
			"date_part('year',date)" => $this->session->userdata('recap_year_off') ?? ltrim(date('Y'),'0'),
			"pin" => $this->session->userdata('att_off_nik')
		])->count_all_results();
	}

	public function _get_dt_detail_off()
	{
		$table = 'acc_transaction_3b';
		$order = ['pin' => 'desc'];
		$column_order = ['date','shift','masuk','pulang','first_scan','last_scan','late_duration'];
		$column_search = ['CAST(date as varchar)','CAST(shift as varchar)','CAST(masuk as varchar)','CAST(pulang as varchar)','CAST(first_scan as varchar)','CAST(last_scan as varchar)','CAST(late_duration as varchar)'];
		$this->db->from($table)->where([
			'date' => $this->session->userdata('att_off_date_search'),
			'pin' => $this->session->userdata('att_off_nik')
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
	public function dt_detail_off()
	{
		$this->_get_dt_detail_off();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_detail_off()
	{
		$this->_get_dt_detail_off();
		return $this->db->count_all_results();
	}
	public function count_all_detail_off()
	{
		$table = 'acc_transaction_3b';
		return $this->db->from($table)->where([
			'date' => $this->session->userdata('att_off_date_search'),
			'pin' => $this->session->userdata('att_off_nik')
		])->count_all_results();
	}

	public function _get_dt_history_off()
	{
		$table = 'acc_transaction_2b';
		$order = [$table.'.event_time' => 'asc'];
		$column_order = [null,'event_time','dev_alias','shift','dev_alias'];
		$column_search = ['CAST(event_time as varchar)','dev_alias','shift'];
		$this->db->from($table)->where([
			'date' => $this->session->userdata('att_off_date_search'),
			'pin' => $this->session->userdata('att_off_nik')
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
	public function dt_history_off()
	{
		$this->_get_dt_history_off();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_history_off()
	{
		$this->_get_dt_history_off();
		return $this->db->count_all_results();
	}
	public function count_all_history_off()
	{
		$table = 'acc_transaction_2b';
		return $this->db->from($table)->where([
			'date' => $this->session->userdata('att_off_date_search'),
			'pin' => $this->session->userdata('att_off_nik')
		])->count_all_results();
	}

}

/* End of file Attendance.php */
/* Location: ./application/models/Attendance.php */