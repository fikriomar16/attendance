<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Model {

	public function _get_dt_dashboard()
	{
		$table = 'acc_transaction_2a';
		$order = ["event_time" => 'desc'];
		$column_order = ['event_time','name','shift','dev_alias'];
		$column_search = ['event_time','name','shift','dev_alias'];
		$this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
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
	public function datatable_dashboard()
	{
		$this->_get_dt_dashboard();
		if($_POST['length'] != -1)
			// $this->db->limit($_POST['length'], $_POST['start']);
			$this->db->limit(50);
		return $this->db->get()->result();
	}
	public function count_filtered_dashboard()
	{
		$this->_get_dt_dashboard();
		return $this->db->count_all_results();
	}
	public function count_all_dashboard()
	{
		$table = 'acc_transaction_2a';
		return $this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
		])->limit(50)->count_all_results();
	}

	public function countEmpToday()
	{
		return $this->db->get_where('acc_transaction_3a',[
			"date" => date('Y-m-d')
		])->num_rows();
	}
	public function countEmpTotal()
	{
		return $this->db->select('*')->from('sys_sch_users')->join('pers_person','sys_sch_users.nik=pers_person.pin','left')->join('auth_department','pers_person.auth_dept_id=auth_department.id','left')->where('sys_sch_users.tanggal',date('Y-m-d'))->where("auth_department.id IN (select id from auth_department where lower(left(name,4))='prod')")->count_all_results();
	}
	public function countOffToday()
	{
		return $this->db->get_where('acc_transaction_3b',[
			"date" => date('Y-m-d')
		])->num_rows();
	}
	public function countOffTotal()
	{
		return $this->db->select('*')->from('sys_sch_users')->join('pers_person','sys_sch_users.nik=pers_person.pin','left')->join('auth_department','pers_person.auth_dept_id=auth_department.id','left')->where('sys_sch_users.tanggal',date('Y-m-d'))->where("auth_department.id NOT IN (select id from auth_department where lower(left(name,4))='prod')")->count_all_results();
	}
	public function countVisToday()
	{
		return $this->db->select('count(id) as count_id')->from('acc_transaction_3c')->where([
			"CAST(first_scan as date) =" => date('Y-m-d')
		])->group_by('id')->get()->row();
	}
	// Per Dept.
	public function countDWSAccA($deptcode)
	{
		return $this->db->select('*')->from('acc_transaction_3a')->join('pers_person','acc_transaction_3a.pin=pers_person.pin','left')->join('auth_department','pers_person.auth_dept_id=auth_department.id','left')->where([
			"acc_transaction_3a.date" => date('Y-m-d'),
			"CAST(auth_department.code as integer) = " => $deptcode
		])->count_all_results();
	}
	public function countDWSAccB($deptcode)
	{
		return $this->db->select('*')->from('acc_transaction_3b')->join('pers_person','acc_transaction_3b.pin=pers_person.pin','left')->join('auth_department','pers_person.auth_dept_id=auth_department.id','left')->where([
			"acc_transaction_3b.date" => date('Y-m-d'),
			"CAST(auth_department.code as integer) = " => $deptcode
		])->count_all_results();
	}
	public function countDWSDept($deptcode)
	{
		$dwsA = $this->countDWSAccA($deptcode) ?? 0;
		$dwsB = $this->countDWSAccB($deptcode) ?? 0;
		return $dwsA + $dwsB;
	}
	public function countDWSDeptTotal($deptcode)
	{
		return $this->db->select('*')->from('sys_sch_users')->join('pers_person','sys_sch_users.nik=pers_person.pin','left')->join('auth_department','pers_person.auth_dept_id=auth_department.id','left')->where([
			"sys_sch_users.tanggal" => date('Y-m-d'),
			"CAST(auth_department.code as integer) = " => $deptcode
		])->count_all_results();
	}

	public function _get_dt_dashboard2()
	{
		$table = 'acc_transaction_2c';
		$order = ["event_time" => 'desc'];
		$column_order = ['event_time','name',NULL,'dev_alias'];
		$column_search = ['event_time','name',NULL,'dev_alias'];
		$this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
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
	public function datatable_dashboard2()
	{
		$this->_get_dt_dashboard2();
		if($_POST['length'] != -1)
			// $this->db->limit($_POST['length'], $_POST['start']);
			$this->db->limit(50);
		return $this->db->get()->result();
	}
	public function count_filtered_dashboard2()
	{
		$this->_get_dt_dashboard2();
		return $this->db->count_all_results();
	}
	public function count_all_dashboard2()
	{
		$table = 'acc_transaction_2c';
		return $this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
		])->limit(50)->count_all_results();
	}

	public function _get_dt_dashboard3()
	{
		$table = 'acc_transaction_2b';
		$order = ["event_time" => 'desc'];
		$column_order = ['event_time','name',NULL,'dev_alias'];
		$column_search = ['event_time','name',NULL,'dev_alias'];
		$this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
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
	public function datatable_dashboard3()
	{
		$this->_get_dt_dashboard3();
		if($_POST['length'] != -1)
			// $this->db->limit($_POST['length'], $_POST['start']);
			$this->db->limit(50);
		return $this->db->get()->result();
	}
	public function count_filtered_dashboard3()
	{
		$this->_get_dt_dashboard3();
		return $this->db->count_all_results();
	}
	public function count_all_dashboard3()
	{
		$table = 'acc_transaction_2b';
		return $this->db->from($table)->where([
			"CAST(event_time as date) =" => date('Y-m-d')
		])->limit(50)->count_all_results();
	}

	public function getLate3a()
	{
		$table = 'acc_transaction_3a';
		$date = date('Y-m-d');
		return $this->db->select('*,in_scan as first_scan,out_scan as last_scan')->from($table)->where([
			'late_duration !=' => null,
			'date' => $date
		])->order_by('in_scan','desc')->limit(50)->get()->result();
	}
	public function getOut3a()
	{
		$table = 'acc_transaction_3a';
		$date = date('Y-m-d');
		$limit = 50;
		return $this->db->from($table)->where('date',$date)->where('out_duration > out_allowed')->limit($limit)->get()->result();
	}

	public function getLate3b()
	{
		$table = 'acc_transaction_3b';
		$date = date('Y-m-d');
		return $this->db->from($table)->where([
			'late_duration !=' => null,
			'date' => $date
		])->order_by('first_scan','desc')->limit(50)->get()->result();
	}

	public function checkShowMenu($dept_code)
	{
		return $this->db->from('auth_department')->where("code IN (SELECT code from auth_department where code = $dept_code and code in (2,3,4,12))")->get()->result();
	}

	public function _get_dt_late_emp()
	{
		$table = 'acc_transaction_3a';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		$order = ["date" => 'asc'];
		$column_order = ["$table.pin","$table.name",'shift','date',"dept_name","late_duration"];
		$column_search = ["$table.pin","$table.name",'shift','date',"dept_name","CAST(late_duration as varchar)"];
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where('dept_name', $this->session->userdata('user')->dept_name);
		} else if (!empty($this->session->userdata('late_dept'))) {
			$this->db->where('dept_name', $this->session->userdata('late_dept'));
		}
		$this->db->select("$table.*,$table.in_scan as first_scan,$table.out_scan as last_scan,$table2.auth_dept_id,$table3.code as dept_code")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where([
			"date >=" => $this->session->userdata('late_date_start') ?? date('Y-m-d'),
			"date <=" => $this->session->userdata('late_date_end') ?? date('Y-m-d'),
			"late_duration !=" => null
		])->where("CAST($table3.code as integer) IN (2,3,4,12)");
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
	public function datatable_late_emp()
	{
		$this->_get_dt_late_emp();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_late_emp()
	{
		$this->_get_dt_late_emp();
		return $this->db->count_all_results();
	}
	public function count_all_late_emp()
	{
		$table = 'acc_transaction_3a';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where('dept_name', $this->session->userdata('user')->dept_name);
		} else if (!empty($this->session->userdata('late_dept'))) {
			$this->db->where('dept_name', $this->session->userdata('late_dept'));
		}
		return $this->db->select("$table.*,$table.in_scan as first_scan,$table.out_scan as last_scan,$table2.auth_dept_id,$table3.code as dept_code")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where([
			"date >=" => $this->session->userdata('late_date_start') ?? date('Y-m-d'),
			"date <=" => $this->session->userdata('late_date_end') ?? date('Y-m-d'),
			"late_duration !=" => null
		])->where("CAST($table3.code as integer) IN (2,3,4,12)")->count_all_results();
	}

	public function _get_dt_late_off()
	{
		$table = 'acc_transaction_3b';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		$order = ["date" => 'asc'];
		$column_order = ["$table.pin","$table.name",'shift','date',"dept_name","late_duration"];
		$column_search = ["$table.pin","$table.name",'shift','date',"dept_name","CAST(late_duration as varchar)"];
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where('dept_name', $this->session->userdata('user')->dept_name);
		} else if (!empty($this->session->userdata('late_dept'))) {
			$this->db->where('dept_name', $this->session->userdata('late_dept'));
		}
		$this->db->select("$table.*,$table2.auth_dept_id,$table3.code as dept_code")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where([
			"date >=" => $this->session->userdata('late_date_start') ?? date('Y-m-d'),
			"date <=" => $this->session->userdata('late_date_end') ?? date('Y-m-d'),
			"late_duration !=" => null
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
	public function datatable_late_off()
	{
		$this->_get_dt_late_off();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_late_off()
	{
		$this->_get_dt_late_off();
		return $this->db->count_all_results();
	}
	public function count_all_late_off()
	{
		$table = 'acc_transaction_3b';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where('dept_name', $this->session->userdata('user')->dept_name);
		} else if (!empty($this->session->userdata('late_dept'))) {
			$this->db->where('dept_name', $this->session->userdata('late_dept'));
		}
		return $this->db->select("$table.*,$table2.auth_dept_id,$table3.code as dept_code")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where([
			"date >=" => $this->session->userdata('late_date_start') ?? date('Y-m-d'),
			"date <=" => $this->session->userdata('late_date_end') ?? date('Y-m-d'),
			"late_duration !=" => null
		])->count_all_results();
	}

	public function _get_dt_out_emp()
	{
		$table = 'acc_transaction_3a';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		$order = ["date" => 'asc'];
		$column_order = ["$table.pin","$table.name",'shift','date',"dept_name","out_duration"];
		$column_search = ["$table.pin","$table.name",'shift','date',"dept_name"];
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where('dept_name', $this->session->userdata('user')->dept_name);
		}
		$this->db->select("$table.*,$table.in_scan as first_scan,$table2.auth_dept_id,$table3.code as dept_code")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where([
			"date >=" => $this->session->userdata('out_date_start') ?? date('Y-m-d'),
			"date <=" => $this->session->userdata('out_date_end') ?? date('Y-m-d')
		])->where("out_duration > out_allowed")->where("CAST($table3.code as integer) IN (2,3,4,12)");
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
	public function datatable_out_emp()
	{
		$this->_get_dt_out_emp();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_out_emp()
	{
		$this->_get_dt_out_emp();
		return $this->db->count_all_results();
	}
	public function count_all_out_emp()
	{
		$table = 'acc_transaction_3a';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where('dept_name', $this->session->userdata('user')->dept_name);
		}
		return $this->db->select("$table.*,$table.in_scan as first_scan,$table2.auth_dept_id,$table3.code as dept_code")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where([
			"date >=" => $this->session->userdata('out_date_start') ?? date('Y-m-d'),
			"date <=" => $this->session->userdata('out_date_end') ?? date('Y-m-d')
		])->where("out_duration > out_allowed")->where("CAST($table3.code as integer) IN (2,3,4,12)")->count_all_results();
	}

	public function deptLists()
	{
		return $this->db->select('id, code, name')->from('auth_department')->get()->result();
	}

	public function lateReportQuery()
	{
		$date_start = $this->session->userdata('late_date_start') ?? date('Y-m-d');
		$date_end = $this->session->userdata('late_date_end') ?? date('Y-m-d');
		$andwhere = '';
		if ($this->session->userdata('user')->is_spv != 1) {
			$andwhere = "AND dept_name = '".$this->session->userdata('user')->dept_name."'";
		} else {
			if (!empty($this->session->userdata('late_dept'))) {
				$andwhere = "AND dept_name = '".$this->session->userdata('late_dept')."'";
			}
		}
		$query = "SELECT dept_name,acc_transaction_3a.name,acc_transaction_3a.pin,shift,date,masuk,pulang,in_scan as first_scan,out_scan as last_scan,late_duration FROM acc_transaction_3a LEFT JOIN pers_person ON acc_transaction_3a.pin=pers_person.pin LEFT JOIN auth_department ON pers_person.auth_dept_id=auth_department.id WHERE late_duration IS NOT NULL AND CAST(auth_department.code IN as integer) (2,3,4,12) AND date >= '$date_start' AND date <= '$date_end' $andwhere
		UNION
		SELECT dept_name,name,pin,shift,date,masuk,pulang,first_scan,last_scan,late_duration FROM acc_transaction_3b WHERE late_duration IS NOT NULL AND date >= '$date_start' AND date <= '$date_end' $andwhere
		ORDER BY date ASC";
		return $this->db->query($query);
	}
	public function getDataLateReport()
	{
		return $this->lateReportQuery()->result();
	}
	public function getCountLateReport()
	{
		return $this->lateReportQuery()->num_rows();
	}

	public function _outReportQuery()
	{
		$table = 'acc_transaction_3a';
		$table2 = 'pers_person';
		$table3 = 'auth_department';
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->db->where('dept_name', $this->session->userdata('user')->dept_name);
		}
		return $this->db->select("$table.*,$table.in_scan as first_scan,$table.out_scan as last_scan,$table2.auth_dept_id,$table3.code as dept_code")->from($table)->join($table2,"$table.pin=$table2.pin","left")->join($table3,"$table2.auth_dept_id=$table3.id")->where([
			"date >=" => $this->session->userdata('out_date_start') ?? date('Y-m-d'),
			"date <=" => $this->session->userdata('out_date_end') ?? date('Y-m-d')
		])->where("out_duration > out_allowed")->where("CAST($table3.code as integer) IN (2,3,4,12)");
	}
	public function getDataOutReport()
	{
		return $this->_outReportQuery()->get()->result();
	}
	public function getCountOutReport()
	{
		return $this->_outReportQuery()->count_all_results();
	}

	public function getOutDiff($duration,$allowed)
	{
		$query = "SELECT AGE(
			(SELECT CONCAT('2012-12-12',' ','$duration')::timestamp),
			(SELECT CONCAT('2012-12-12',' ','$allowed')::timestamp)
		) as out_diff";
		return $this->db->query($query)->row();
	}

}

/* End of file Admin.php */
/* Location: ./application/models/Admin.php */