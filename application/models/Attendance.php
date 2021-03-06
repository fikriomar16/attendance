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
	public function get_by_id_dept($id)
	{
		return $this->db->get_where("auth_department",[
			"id" => $id
		])->row();
	}

	public function _get_datatable_employee()
	{
		$table = 'acc_transaction_3a';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		$order = ['pin' => 'asc'];
		$column_order = [null,"$table.name","$table.pin",'shift',"$table3.name"];
		$column_search = ["$table.name","$table.pin",'shift',"$table3.name"];
		$this->db->select("$table.name,$table3.name as dept_name,$table.pin,shift")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where("CAST($table3.code as integer) IN (2,3,4,12)")->where([
			'date' => $this->session->userdata('att_emp_date')
		]);
		if ($this->session->userdata('att_emp_shift')) {
			$this->db->where('left(shift,2)', $this->session->userdata('att_emp_shift'));
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where("$table3.name", $this->session->userdata('user')->dept_name);
		} else {
			if (!empty($this->session->userdata('att_emp_dept_id'))) {
				$this->db->where("$table2.auth_dept_id", $this->session->userdata('att_emp_dept_id'));
			}
		}
		$this->db->group_by("$table.name,$table3.name,$table.pin,shift");
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
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where("$table3.name", $this->session->userdata('user')->dept_name);
		} else {
			if (!empty($this->session->userdata('att_emp_dept_id'))) {
				$this->db->where("$table2.auth_dept_id", $this->session->userdata('att_emp_dept_id'));
			}
		}
		return $this->db->select("$table.name,$table3.name as dept_name,$table.pin,shift")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where("CAST($table3.code as integer) IN (2,3,4,12)")->where([
			'date' => $this->session->userdata('att_emp_date')
		])->group_by("$table.name,$table3.name,$table.pin,shift")->count_all_results();
	}

	public function _get_dt_sum_emp()
	{
		$table = 'acc_transaction_3a';
		$order = ['pin' => 'desc'];
		$column_order = ['date','shift','in_scan','out_scan','late_duration','out_duration','out_allowed','in_duration'];
		$column_search = ['CAST(date as varchar)','CAST(shift as varchar)','CAST(masuk as varchar)','CAST(pulang as varchar)','CAST(in_scan as varchar)','CAST(out_scan as varchar)','CAST(late_duration as varchar)','CAST(out_duration as varchar)','CAST(out_allowed as varchar)','CAST(in_duration as varchar)'];
		$this->db->select("$table.date,$table.shift,$table.in_scan,$table.out_scan,$table.late_duration,$table.out_duration,$table.out_allowed,AGE($table.out_scan,$table.masuk) as in_duration
			")->from($table)->where([
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
		return $this->db->select("$table.date,$table.shift,$table.in_scan,$table.out_scan,$table.late_duration,$table.out_duration,$table.out_allowed,AGE($table.out_scan,$table.masuk) as in_duration
			")->from($table)->where([
			"date_part('month',date)" => $this->session->userdata('recap_month') ?? ltrim(date('m'),'0'),
			"date_part('year',date)" => $this->session->userdata('recap_year') ?? ltrim(date('Y'),'0'),
			"pin" => $this->session->userdata('att_emp_nik')
		])->count_all_results();
	}

	public function _get_dt_detail_emp()
	{
		$table = 'acc_transaction_3a';
		$order = ['pin' => 'desc'];
		$column_order = ['date','shift','masuk','pulang','in_scan','out_scan','late_duration','out_duration','out_allowed','in_duration'];
		$column_search = ['CAST(date as varchar)','CAST(shift as varchar)','CAST(masuk as varchar)','CAST(pulang as varchar)','CAST(in_scan as varchar)','CAST(out_scan as varchar)','CAST(late_duration as varchar)','CAST(out_duration as varchar)','CAST(out_allowed as varchar)','CAST(in_duration as varchar)'];
		$this->db->select("
			$table.pin,$table.name,$table.dept_name,$table.shift,$table.date,$table.masuk,$table.pulang,$table.in_scan,$table.out_scan,$table.late_duration,$table.out_duration,AGE($table.out_scan,$table.masuk) as in_duration,$table.out_allowed
			")->from($table)->where([
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
		return $this->db->select("
			$table.pin,$table.name,$table.dept_name,$table.shift,$table.date,$table.masuk,$table.pulang,$table.in_scan,$table.out_scan,$table.late_duration,$table.out_duration,AGE($table.out_scan,$table.masuk) as in_duration,$table.out_allowed
			")->from($table)->where([
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

	public function rndmDept($isprod)
	{
		($isprod == TRUE) ? $qs = "" : $qs = "NOT";
		return $this->db->select("id,code,name")->from('auth_department')->where("code $qs IN (2,3,4,12)")->order_by("name","asc")->limit(1)->get()->row();
	}
	public function getOutDiff($duration,$allowed)
	{
		$query = "SELECT AGE(
			(SELECT CONCAT('2012-12-12',' ','$duration')::timestamp),
			(SELECT CONCAT('2012-12-12',' ','$allowed')::timestamp)
		) as out_diff";
		return $this->db->query($query)->row();
	}

	public function dataReportAttEmp()
	{
		$table = 'acc_transaction_3a';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		$this->db->select("
			$table.pin,$table.name,$table.dept_name,$table.shift,$table.masuk,$table.pulang,$table.in_scan,$table.out_scan,$table.late_duration,$table.out_duration,AGE($table.out_scan,$table.masuk) as in_duration,$table.out_allowed
			")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where("CAST($table3.code as integer) IN (2,3,4,12)")->where('date',$this->session->userdata('att_emp_date'));
		if ($this->session->userdata('att_emp_shift')) {
			$this->db->where('left(shift,2)', $this->session->userdata('att_emp_shift'));
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where("$table3.name", $this->session->userdata('user')->dept_name);
		} else {
			if (!empty($this->session->userdata('att_emp_dept_id'))) {
				$this->db->where("$table2.auth_dept_id", $this->session->userdata('att_emp_dept_id'));
			}
		}
		return $this->db->get()->result();
	}
	public function dataReportAttOff()
	{
		$table = 'acc_transaction_3b';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		$this->db->select("$table.*")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where('date',$this->session->userdata('att_off_date'));
		if ($this->session->userdata('att_off_shift')) {
			$this->db->where('left(shift,2)',$this->session->userdata('att_off_shift'));
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where("$table3.name", $this->session->userdata('user')->dept_name);
		} else {
			if (!empty($this->session->userdata('att_off_dept_id'))) {
				$this->db->where("$table2.auth_dept_id", $this->session->userdata('att_off_dept_id'));
			}
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
		return $this->db->select("
			$table.pin,$table.name,$table.dept_name,$table.shift,$table.date,$table.masuk,$table.pulang,$table.in_scan,$table.out_scan,$table.late_duration,$table.out_duration,AGE($table.out_scan,$table.masuk) as in_duration,$table.out_allowed
			")->from($table)->where([
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
	public function countTotalDuration($data)
	{
		$tmpQuery = "";
		$no = 0;
		foreach ($data as $dt) {
			if ($no == 0) {
				$tmpQuery .= "sum('$dt'::time)";
			} else {
				$tmpQuery .= "+sum('$dt'::time)";
			}
			$no++;
		}
		$execQuery = "SELECT $tmpQuery AS total";
		return $this->db->query($execQuery)->row();
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
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		$order = ['pin' => 'asc'];
		$column_order = [null,"$table.name","$table.pin",'shift',"$table3.name"];
		$column_search = ["$table.name","$table.pin",'shift',"$table3.name"];
		$this->db->select("$table.name,$table3.name as dept_name,$table.pin,shift")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id","left")->where([
			'date' => $this->session->userdata('att_off_date')
		]);
		if ($this->session->userdata('att_off_shift')) {
			$this->db->where('left(shift,2)', $this->session->userdata('att_off_shift'));
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where("$table3.name", $this->session->userdata('user')->dept_name);
		} else {
			if (!empty($this->session->userdata('att_off_dept_id'))) {
				$this->db->where("$table2.auth_dept_id", $this->session->userdata('att_off_dept_id'));
			}
		}
		$this->db->group_by("$table.name,$table3.name,$table.pin,shift");
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
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where("$table3.name", $this->session->userdata('user')->dept_name);
		} else {
			if (!empty($this->session->userdata('att_off_dept_id'))) {
				$this->db->where("$table2.auth_dept_id", $this->session->userdata('att_off_dept_id'));
			}
		}
		return $this->db->select("$table.name,$table3.name as dept_name,$table.pin,shift")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id","left")->where([
			'date' => $this->session->userdata('att_off_date')
		])->group_by("$table.name,$table3.name,$table.pin,shift")->count_all_results();
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

	public function prodEmpList()
	{
		return $this->db->select('id, code, name')->from('auth_department')->where([
			'lower(left(name,4))' => 'prod'
		])->get()->result();
	}

	public function nonProdEmpList()
	{
		return $this->db->select('id, code, name')->from('auth_department')->where([
			'lower(left(name,4)) !=' => 'prod'
		])->get()->result();
	}

}

/* End of file Attendance.php */
/* Location: ./application/models/Attendance.php */