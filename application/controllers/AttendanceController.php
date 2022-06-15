<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class AttendanceController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$locale = 'id_ID.utf8';
		setlocale(LC_ALL, $locale);
		$this->load->model('Attendance','attendance');
	}

	public function index()
	{
		$date = $this->session->userdata('search_date') ?? date("Y-m-d");
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime($date))
		]);
	}

	public function employee()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		if (in_array($this->session->userdata('user')->dept_code, [2,3,4,12]) || $this->session->userdata('user')->is_spv == 1) {
		} else {
			$this->session->set_flashdata('error', 'Peringatan: Anda tidak memiliki akses untuk ini !!');
			redirect('/');
		}
		$this->session->set_userdata([
			'att_emp_date' => $this->session->userdata('search_date') ?? date("Y-m-d"),
			'att_emp_date_search' => date("Y-m-d"),
			'att_emp_nik' => $this->session->userdata('search_nik') ?? $this->attendance->getRndmSch()->nik,
			'recap_month' => date('m'),
			'recap_year' => date('Y'),
		]);
		$this->session->unset_userdata('att_emp_shift');
		$this->session->unset_userdata(['att_emp_dept_id','att_emp_dept_code','att_emp_dept_name']);
		$path_port = '8098';
		$data = [
			'title' => 'Employee Attendance',
			'deptlists' => $this->attendance->prodEmpList(),
			'path_local' => "http://localhost:$path_port",
			'path_url' => "http://10.126.25.150:$path_port"
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('attendance/employee', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_employee()
	{
		$list = $this->attendance->datatable_employee();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $emp->name;
			$row[] = $emp->pin;
			$row[] = $emp->shift;
			$row[] = $emp->dept_name;
			$row[] = '<button type="button" class="btn btn-info btn-sm btn-show" data-id="'.$emp->pin.'" onclick="angular.element(this).scope().show('.$emp->pin.')"><i class="fas fa-fw fa-list-alt"></i> Detail</button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_employee(),
			"recordsFiltered" => $this->attendance->count_filtered_employee(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function set_shift($shift)
	{
		$this->session->set_userdata('att_emp_shift',$shift);
		if ($shift == '0') {
			$this->session->unset_userdata('att_emp_shift');
		}
		echo json_encode([
			'shift' => $shift,
			'current_shift' => $this->session->userdata('att_emp_shift')
		]);
	}
	public function set_dept($id)
	{
		$data = $this->attendance->get_by_id_dept($id);
		if ($id == '0') {
			$this->session->unset_userdata('att_emp_dept_id');
		} else if ($data) {
			$this->session->set_userdata([
				'att_emp_dept_id' => $data->id,
				'att_emp_dept_code' => $data->code,
				'att_emp_dept_name' => $data->name,
			]);
		}
		echo json_encode([
			'dept' => $id,
			'current_dept' => $this->session->userdata('att_emp_dept_id')
		]);
	}
	public function getShiftList()
	{
		echo json_encode([
			'lists' => $this->attendance->collectShift()
		],JSON_PRETTY_PRINT);
	}
	public function att_yesterday_emp()
	{
		$get_date = new DateTime($this->session->userdata('att_emp_date'));
		$date = $get_date->modify('-1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_emp_date' => $date,
				'att_emp_date_search' => $date,
				'recap_month' => ltrim(date('m',strtotime($date)),'0'),
				'recap_year' => ltrim(date('Y',strtotime($date)),'0')
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_today_emp()
	{
		$date = date("Y-m-d");
		$this->session->set_userdata([
			'att_emp_date' => $date,
			'att_emp_date_search' => $date,
			'recap_month' => ltrim(date('m'),'0'),
			'recap_year' => ltrim(date('Y'),'0')
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime(date("Y-m-d")))
		]);
	}
	public function att_tomorrow_emp()
	{
		$get_date = new DateTime($this->session->userdata('att_emp_date'));
		$date = $get_date->modify('+1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_emp_date' => $date,
				'att_emp_date_search' => $date,
				'recap_month' => ltrim(date('m',strtotime($date)),'0'),
				'recap_year' => ltrim(date('Y',strtotime($date)),'0')
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_yesterday_emp_detail()
	{
		$get_date = new DateTime($this->session->userdata('att_emp_date_search'));
		$date = $get_date->modify('-1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_emp_date_search' => $date,
				'recap_month' => ltrim(date('m',strtotime($date)),'0'),
				'recap_year' => ltrim(date('Y',strtotime($date)),'0')
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_today_emp_detail()
	{
		$this->session->set_userdata([
			'att_emp_date_search' => date("Y-m-d"),
			'recap_month' => ltrim(date('m'),'0'),
			'recap_year' => ltrim(date('Y'),'0')
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime(date("Y-m-d")))
		]);
	}
	public function att_tomorrow_emp_detail()
	{
		$get_date = new DateTime($this->session->userdata('att_emp_date_search'));
		$date = $get_date->modify('+1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_emp_date_search' => $date,
				'recap_month' => ltrim(date('m',strtotime($date)),'0'),
				'recap_year' => ltrim(date('Y',strtotime($date)),'0')
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function attresume_emp($nik)
	{
		$this->session->set_userdata([
			'att_emp_date_search' => $this->session->userdata('att_emp_date'),
			'att_emp_nik' => $nik,
			'recap_month' => ltrim(date('m',strtotime($this->session->userdata('att_emp_date_search'))),'0'),
			'recap_year' => ltrim(date('Y',strtotime($this->session->userdata('att_emp_date_search'))),'0')
		]);
		echo json_encode([
			'getNIK' => $this->session->userdata('att_emp_nik'),
			'getName' => $this->attendance->get_by_nik_employee($nik)->name,
			'getSearchDate' => strftime('%A, %d %B %Y', strtotime($this->session->userdata('att_emp_date_search')))
		]);
	}
	public function attresume_off($nik)
	{
		$this->session->set_userdata([
			'att_off_date_search' => $this->session->userdata('att_off_date'),
			'att_off_nik' => $nik,
			'recap_month_off' => ltrim(date('m',strtotime($this->session->userdata('att_off_date_search'))),'0'),
			'recap_year_off' => ltrim(date('Y',strtotime($this->session->userdata('att_off_date_search'))),'0')
		]);
		echo json_encode([
			'getNIK' => $this->session->userdata('att_off_nik'),
			'getName' => $this->attendance->get_by_nik_employee($nik)->name,
			'getSearchDate' => strftime('%A, %d %B %Y', strtotime($this->session->userdata('att_off_date_search')))
		]);
	}
	public function att_getDate_emp($date)
	{
		$this->session->set_userdata([
			'att_emp_date' => $date,
			'att_emp_date_search' => $date,
			'recap_month' => ltrim(date('m',strtotime($date)),'0'),
			'recap_year' => ltrim(date('Y',strtotime($date)),'0')
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime($date))
		]);
	}
	public function att_getDate_vis($date)
	{
		$this->session->set_userdata([
			'att_vis_date' => $date
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime($date))
		]);
	}
	public function recapSumEmp($param)
	{
		$getMonth = explode("-",$param)[0];
		$month = ltrim($getMonth,'0');
		$year = explode("-",$param)[1];
		$this->session->set_userdata([
			'recap_month' => $month,
			'recap_year' => $year
		]);
		echo json_encode([
			'month' => $month,
			'year' => $year
		],JSON_PRETTY_PRINT);
	}
	public function att_sum_emp()
	{
		$list = $this->attendance->dt_sum_emp();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$no++;
			$row = [];
			$row[] = $emp->date;
			$row[] = $emp->shift;
			$row[] = $emp->in_scan;
			$row[] = $emp->out_scan;
			$row[] = $emp->late_duration;
			$row[] = $emp->out_duration;
			$row[] = $emp->in_duration;

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_detail_emp(),
			"recordsFiltered" => $this->attendance->count_filtered_detail_emp(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function att_det_recap_emp()
	{
		$list = $this->attendance->dt_detail_emp();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $emp->date;
			$row[] = $emp->shift;
			$row[] = $emp->masuk;
			$row[] = $emp->pulang;
			$row[] = $emp->in_scan;
			$row[] = $emp->out_scan;
			$row[] = $emp->late_duration;
			$row[] = $emp->out_duration;
			$row[] = $emp->in_duration;

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_detail_emp(),
			"recordsFiltered" => $this->attendance->count_filtered_detail_emp(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function att_hist_scan_emp()
	{
		$path_port = '8098';
		$local = 'localhost';
		$url = '10.126.25.150';
		$path = "http://$url:$path_port";
		$list = $this->attendance->dt_history_emp();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$inout = explode("-",$emp->dev_alias)[0];
			if ($inout == "IN") {
				$io = '<span class="badge badge-pill badge-primary">'.$inout.'</span>';
			} else if ($inout == "OUT") {
				$io = '<span class="badge badge-pill badge-danger">'.$inout.'</span>';
			} else {
				$io = '<span class="badge badge-pill badge-info">Gate '.$emp->dev_alias.'</span>';
			}
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $emp->event_time;
			$row[] = $emp->dev_alias;
			$row[] = $emp->shift;
			$row[] = $io;
			$row[] = '<button type="button" class="btn btn-success btn-sm btn-photo" data-path="'.$path.$emp->vid_linkage_handle.'" onclick="angular.element(this).scope().showPhoto(\''.$path.$emp->vid_linkage_handle.'\')"><i class="fas fa-fw fa-image"></i> Photo</button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_history_emp(),
			"recordsFiltered" => $this->attendance->count_filtered_history_emp(),
			"data" => $data,
		];
		echo json_encode($output);
	}

	public function office()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		if (!in_array($this->session->userdata('user')->dept_code, [2,3,4,12]) || $this->session->userdata('user')->is_spv == 1) {
		} else {
			$this->session->set_flashdata('error', 'Peringatan: Anda tidak memiliki akses untuk ini !!');
			redirect('/');
		}
		$this->session->set_userdata([
			'att_off_date' => $this->session->userdata('search_date') ?? date("Y-m-d"),
			'att_off_date_search' => date("Y-m-d"),
			'att_off_nik' => $this->session->userdata('search_nik') ?? $this->attendance->getRndmSch()->nik,
			'recap_month_off' => date('m'),
			'recap_year_off' => date('Y'),
		]);
		$this->session->unset_userdata('att_off_shift');
		$this->session->unset_userdata(['att_off_dept_id','att_off_dept_code','att_off_dept_name']);
		$path_port = '8098';
		$data = [
			'title' => 'Office Attendance',
			'deptlists' => $this->attendance->nonProdEmpList(),
			'path_local' => "http://localhost:$path_port",
			'path_url' => "http://10.126.25.150:$path_port"
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('attendance/office', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_office()
	{
		$lists = $this->attendance->datatable_office();
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $list->name;
			$row[] = $list->pin;
			$row[] = $list->shift;
			$row[] = $list->dept_name;
			$row[] = '<button type="button" class="btn btn-info btn-sm btn-show" data-id="'.$list->pin.'" onclick="angular.element(this).scope().show('.$list->pin.')"><i class="fas fa-fw fa-list-alt"></i> Detail</button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_office(),
			"recordsFiltered" => $this->attendance->count_filtered_office(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function att_yesterday_off()
	{
		$get_date = new DateTime($this->session->userdata('att_off_date'));
		$date = $get_date->modify('-1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_off_date' => $date,
				'att_off_date_search' => $date,
				'recap_month_off' => ltrim(date('m',strtotime($date)),'0'),
				'recap_year_off' => ltrim(date('Y',strtotime($date)),'0')
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_today_off()
	{
		$date = date("Y-m-d");
		$this->session->set_userdata([
			'att_off_date' => $date,
			'att_off_date_search' => $date,
			'recap_month_off' => ltrim(date('m'),'0'),
			'recap_year_off' => ltrim(date('Y'),'0')
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime(date("Y-m-d")))
		]);
	}
	public function att_tomorrow_off()
	{
		$get_date = new DateTime($this->session->userdata('att_off_date'));
		$date = $get_date->modify('+1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_off_date' => $date,
				'att_off_date_search' => $date,
				'recap_month_off' => ltrim(date('m',strtotime($date)),'0'),
				'recap_year_off' => ltrim(date('Y',strtotime($date)),'0')
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_yesterday_off_detail()
	{
		$get_date = new DateTime($this->session->userdata('att_off_date_search'));
		$date = $get_date->modify('-1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_off_date_search' => $date,
				'recap_month_off' => ltrim(date('m',strtotime($date)),'0'),
				'recap_year_off' => ltrim(date('Y',strtotime($date)),'0')
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_today_off_detail()
	{
		$this->session->set_userdata([
			'att_off_date_search' => date("Y-m-d"),
			'recap_month_off' => ltrim(date('m'),'0'),
			'recap_year_off' => ltrim(date('Y'),'0')
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime(date("Y-m-d")))
		]);
	}
	public function att_tomorrow_off_detail()
	{
		$get_date = new DateTime($this->session->userdata('att_off_date_search'));
		$date = $get_date->modify('+1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_off_date_search' => $date,
				'recap_month_off' => ltrim(date('m',strtotime($date)),'0'),
				'recap_year_off' => ltrim(date('Y',strtotime($date)),'0')
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_getDate_off($date)
	{
		$this->session->set_userdata([
			'att_off_date' => $date,
			'att_off_date_search' => $date,
			'recap_month_off' => ltrim(date('m',strtotime($date)),'0'),
			'recap_year_off' => ltrim(date('Y',strtotime($date)),'0')
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime($date))
		]);
	}
	public function set_shift_off($shift)
	{
		$this->session->set_userdata('att_off_shift',$shift);
		if ($shift == '0') {
			$this->session->unset_userdata('att_off_shift');
		}
		echo json_encode([
			'shift' => $shift,
			'current_shift' => $this->session->userdata('att_off_shift')
		]);
	}
	public function set_dept_off($id)
	{
		$data = $this->attendance->get_by_id_dept($id);
		if ($id == '0') {
			$this->session->unset_userdata('att_off_dept_id');
		} else if ($data) {
			$this->session->set_userdata([
				'att_off_dept_id' => $data->id,
				'att_off_dept_code' => $data->code,
				'att_off_dept_name' => $data->name,
			]);
		}
		echo json_encode([
			'dept' => $id,
			'current_dept' => $this->session->userdata('att_off_dept_id')
		]);
	}
	public function att_sum_off()
	{
		$list = $this->attendance->dt_sum_off();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$no++;
			$row = [];
			$row[] = $emp->date;
			$row[] = $emp->shift;
			$row[] = $emp->first_scan;
			$row[] = $emp->last_scan;
			$row[] = $emp->late_duration;

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_detail_off(),
			"recordsFiltered" => $this->attendance->count_filtered_detail_off(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function att_det_recap_off()
	{
		$list = $this->attendance->dt_detail_off();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $emp->date;
			$row[] = $emp->shift;
			$row[] = $emp->masuk;
			$row[] = $emp->pulang;
			$row[] = $emp->first_scan;
			$row[] = $emp->last_scan;
			$row[] = $emp->late_duration;

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_detail_off(),
			"recordsFiltered" => $this->attendance->count_filtered_detail_off(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function att_hist_scan_off()
	{
		$path_port = '8098';
		$local = 'localhost';
		$url = '10.126.25.150';
		$path = "http://$url:$path_port";
		$list = $this->attendance->dt_history_off();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$inout = explode("-",$emp->dev_alias)[0];
			if ($inout == "IN") {
				$io = '<span class="badge badge-pill badge-primary">'.$inout.'</span>';
			} else if ($inout == "OUT") {
				$io = '<span class="badge badge-pill badge-danger">'.$inout.'</span>';
			} else {
				$io = '<span class="badge badge-pill badge-info">Gate '.$emp->dev_alias.'</span>';
			}
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $emp->event_time;
			$row[] = $emp->dev_alias;
			$row[] = $emp->shift;
			$row[] = $io;
			$row[] = '<button type="button" class="btn btn-success btn-sm btn-photo" data-path="'.$path.$emp->vid_linkage_handle.'" onclick="angular.element(this).scope().showPhoto(\''.$path.$emp->vid_linkage_handle.'\')"><i class="fas fa-fw fa-image"></i> Photo</button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_history_off(),
			"recordsFiltered" => $this->attendance->count_filtered_history_off(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function recapSumOff($param)
	{
		$getMonth = explode("-",$param)[0];
		$month = ltrim($getMonth,'0');
		$year = explode("-",$param)[1];
		$this->session->set_userdata([
			'recap_month_off' => $month,
			'recap_year_off' => $year
		]);
		echo json_encode([
			'month' => $month,
			'year' => $year
		],JSON_PRETTY_PRINT);
	}

	public function printAttendanceEmp()
	{
		if ($this->session->userdata('att_emp_shift')) {
			$shift = '_Shift_'.$this->session->userdata('att_emp_shift');
		} else {
			$shift = '';
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$dept = $this->session->userdata('user')->dept_name;
		} else {
			if (!empty($this->session->userdata('att_emp_dept_id'))) {
				$dept = $this->session->userdata('att_emp_dept_name');
			} else {
				$dept = 'All Dept.';
			}
		}
		$dept_title = "_".$dept;
		$data = [
			"title" => "Laporan Kehadiran Employee",
			"lists" => $this->attendance->dataReportAttEmp(),
			"shift" => $this->session->userdata('att_emp_shift') ?? 'All',
			"date" => $this->session->userdata('att_emp_date'),
			"dept" => $dept ?? 'All'
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Kehadiran-Employee_'.$this->session->userdata('att_emp_date').$shift.$dept_title;
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "potrait";
		$page = $this->load->view('report/reportAttEmp', $data,true);
		$this->pdfgenerator->generate($page,$file_pdf,$paper,$orientation);
	}
	public function printAttendanceOff()
	{
		if ($this->session->userdata('att_off_shift')) {
			$shift = '_Shift_'.$this->session->userdata('att_off_shift');
		} else {
			$shift = '';
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$dept = $this->session->userdata('user')->dept_name;
		} else {
			if (!empty($this->session->userdata('att_off_dept_id'))) {
				$dept = $this->session->userdata('att_off_dept_name');
			} else {
				$dept = 'All Dept.';
			}
		}
		$dept_title = "_".$dept;
		$data = [
			"title" => "Laporan Kehadiran Office",
			"lists" => $this->attendance->dataReportAttOff(),
			"shift" => $this->session->userdata('att_off_shift') ?? 'All',
			"date" => $this->session->userdata('att_off_date'),
			"dept" => $dept ?? 'All'
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Kehadiran-Office'.$this->session->userdata('att_off_date').$shift.$dept_title;
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "potrait";
		$page = $this->load->view('report/reportAttOff', $data,true);
		$this->pdfgenerator->generate($page,$file_pdf,$paper,$orientation);
	}

	public function rekapBulananKaryawan()
	{
		$m = date('F', mktime(0, 0, 0, $this->session->userdata('recap_month'),1,$this->session->userdata('recap_year')));
		$bulan = $m."-".$this->session->userdata('recap_year');
		$month = $m.", ".$this->session->userdata('recap_year');
		$nik = $this->session->userdata('att_emp_nik');
		$name = $this->attendance->get_by_nik_employee($nik)->name;
		$lists = $this->attendance->dataRecapSumEmp();
		$filename = 'Laporan-Kehadiran-Bulanan-Employee_'.$bulan."-".$nik."_".$name;
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getColumnDimension('A')->setWidth(16);
		$sheet->getColumnDimension('B')->setWidth(15);
		$sheet->getColumnDimension('C')->setWidth(10);
		$sheet->getColumnDimension('D')->setWidth(18);
		$sheet->getColumnDimension('E')->setWidth(18);
		$sheet->getColumnDimension('F')->setWidth(18);
		$sheet->getColumnDimension('G')->setWidth(18);
		$sheet->getColumnDimension('H')->setWidth(13);
		$sheet->getColumnDimension('I')->setWidth(13);
		$sheet->getColumnDimension('J')->setWidth(13);
		$sheet->getColumnDimension('K')->setWidth(13);
		$sheet->getColumnDimension('L')->setWidth(13);
		$sheet->setCellValue('A1','Bulan : ');
		$sheet->setCellValue('B1',$month);
		$sheet->setCellValue('A2','NIK : ');
		$sheet->setCellValue('B2',$nik);
		$sheet->setCellValue('A3','Nama : ');
		$sheet->setCellValue('B3',$name);
		$sheet->setCellValue('A5','No');
		$sheet->setCellValue('B5','Date');
		$sheet->setCellValue('C5','Shift');
		$sheet->setCellValue('D5','DWS IN');
		$sheet->setCellValue('E5','DWS OUT');
		$sheet->setCellValue('F5','First Scan');
		$sheet->setCellValue('G5','Last Scan');
		$sheet->setCellValue('H5','Late Duration');
		$sheet->setCellValue('I5','In Duration');
		$sheet->setCellValue('J5','Out Duration');
		$sheet->setCellValue('K5','Out Allowed');
		$sheet->setCellValue('L5',"Kedisiplinan");
		$counter = 6;
		$idx = 1;
		$lateTotal = $outPass = 0;
		$lateDur = $passDur = [];
		foreach ($lists as $list) {
			$sheet->setCellValue("A$counter",$idx);
			$sheet->setCellValue("B$counter",$list->date);
			$sheet->setCellValue("C$counter",$list->shift);
			$sheet->setCellValue("D$counter",$list->masuk);
			$sheet->setCellValue("E$counter",$list->pulang);
			$sheet->setCellValue("F$counter",$list->in_scan);
			$sheet->setCellValue("G$counter",$list->out_scan);
			$sheet->setCellValue("H$counter",$list->late_duration);
			$sheet->setCellValue("I$counter",$list->in_duration);
			$sheet->setCellValue("J$counter",$list->out_duration);
			$sheet->setCellValue("K$counter",$list->out_allowed);
			if ($list->out_duration > $list->out_allowed) {
				$sheet->setCellValue("L$counter",$this->attendance->getOutDiff($list->out_duration,$list->out_allowed)->out_diff);
				$outPass++;
				array_push($passDur, $this->attendance->getOutDiff($list->out_duration,$list->out_allowed)->out_diff);
			}
			if (!empty($list->late_duration)) {
				$lateTotal++;
				array_push($lateDur, $list->late_duration);
			}
			$counter++;
			$idx++;
		}
		$sheet->setCellValue("A".($counter+2),"Total Terlambat: ");
		$sheet->setCellValue("B".($counter+2),$lateTotal);
		$sheet->setCellValue("A".($counter+3),"Total Kedisiplinan: ");
		$sheet->setCellValue("B".($counter+3),$outPass);
		if (!empty($lateDur)) {
			$sheet->setCellValue("A".($counter+4),"Total Durasi Terlambat: ");
			$sheet->setCellValue("B".($counter+4),$this->attendance->countTotalDuration($lateDur)->total);
		}
		if (!empty($passDur)) {
			$sheet->setCellValue("A".($counter+5),"Total Durasi Kedisiplinan: ");
			$sheet->setCellValue("B".($counter+5),$this->attendance->countTotalDuration($passDur)->total);
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
	public function rekapBulananOffice()
	{
		$m = date('F', mktime(0, 0, 0, $this->session->userdata('recap_month_off'),1,$this->session->userdata('recap_year_off')));
		$bulan = $m."-".$this->session->userdata('recap_year_off');
		$month = $m.", ".$this->session->userdata('recap_year_off');
		$nik = $this->session->userdata('att_off_nik');
		$name = $this->attendance->get_by_nik_employee($nik)->name;
		$lists = $this->attendance->dataRecapSumOff();
		$filename = 'Laporan-Kehadiran-Bulanan-Office_'.$bulan."-".$nik."_".$name;
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getColumnDimension('A')->setWidth(16);
		$sheet->getColumnDimension('B')->setWidth(15);
		$sheet->getColumnDimension('C')->setWidth(10);
		$sheet->getColumnDimension('D')->setWidth(18);
		$sheet->getColumnDimension('E')->setWidth(18);
		$sheet->getColumnDimension('F')->setWidth(18);
		$sheet->getColumnDimension('G')->setWidth(18);
		$sheet->getColumnDimension('H')->setWidth(13);
		$sheet->getColumnDimension('I')->setWidth(13);
		$sheet->getColumnDimension('J')->setWidth(13);
		$sheet->getColumnDimension('K')->setWidth(13);
		$sheet->getColumnDimension('L')->setWidth(13);
		$sheet->setCellValue('A1','Bulan : ');
		$sheet->setCellValue('B1',$month);
		$sheet->setCellValue('A2','NIK : ');
		$sheet->setCellValue('B2',$nik);
		$sheet->setCellValue('A3','Nama : ');
		$sheet->setCellValue('B3',$name);
		$sheet->setCellValue('A5','No');
		$sheet->setCellValue('B5','Date');
		$sheet->setCellValue('C5','Shift');
		$sheet->setCellValue('D5','DWS IN');
		$sheet->setCellValue('E5','DWS OUT');
		$sheet->setCellValue('F5','First Scan');
		$sheet->setCellValue('G5','Last Scan');
		$sheet->setCellValue('H5','Late Duration');
		$counter = 6;
		$idx = 1;
		$lateTotal = 0;
		$lateDur = [];
		foreach ($lists as $list) {
			$sheet->setCellValue("A$counter",$idx);
			$sheet->setCellValue("B$counter",$list->date);
			$sheet->setCellValue("C$counter",$list->shift);
			$sheet->setCellValue("D$counter",$list->masuk);
			$sheet->setCellValue("E$counter",$list->pulang);
			$sheet->setCellValue("F$counter",$list->first_scan);
			$sheet->setCellValue("G$counter",$list->last_scan);
			$sheet->setCellValue("H$counter",$list->late_duration);
			if (!empty($list->late_duration)) {
				$lateTotal++;
				array_push($lateDur, $list->late_duration);
			}
			$counter++;
			$idx++;
		}
		$sheet->setCellValue("A".($counter+2),"Total Terlambat: ");
		$sheet->setCellValue("B".($counter+2),$lateTotal);
		if (!empty($lateDur)) {
			$sheet->setCellValue("A".($counter+4),"Total Durasi Terlambat: ");
			$sheet->setCellValue("B".($counter+4),$this->attendance->countTotalDuration($lateDur)->total);
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function historyScanKaryawan()
	{
		$lists = $this->attendance->dataRecapScanEmp();
		$nik = $this->session->userdata('att_emp_nik');
		$name = $lists[0]->name;
		$date = $this->session->userdata('att_emp_date');
		$shift = $lists[0]->shift;
		$data = [
			"title" => "Riwayat Scan Employee",
			"lists" => $lists,
			"nik" => $nik,
			"name" => $name,
			"date" => $date,
			"shift" => $shift
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Riwayat-Scan-Employee_'.$nik."-".$name."_".$date;
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "potrait";
		$page = $this->load->view('report/historyScanKaryawan', $data,true);
		$this->pdfgenerator->generate($page,$file_pdf,$paper,$orientation);
	}
	public function historyScanOffice()
	{
		$lists = $this->attendance->dataRecapScanOff();
		$nik = $this->session->userdata('att_off_nik');
		$name = $lists[0]->name;
		$date = $this->session->userdata('att_off_date');
		$shift = $lists[0]->shift;
		$data = [
			"title" => "Riwayat Scan Office",
			"lists" => $lists,
			"nik" => $nik,
			"name" => $name,
			"date" => $date,
			"shift" => $shift
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Riwayat-Scan-Office'.$nik."-".$name."_".$date;
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "potrait";
		$page = $this->load->view('report/historyScanKaryawan', $data,true);
		$this->pdfgenerator->generate($page,$file_pdf,$paper,$orientation);
	}
	public function historyScanVisitor()
	{
		$lists = $this->attendance->dataRecapScanVis();
		$nik = $this->session->userdata('att_vis_pin');
		$name = $lists[0]->name;
		$date = $this->session->userdata('att_vis_date');
		$data = [
			"title" => "Riwayat Scan Visitor",
			"lists" => $lists,
			"nik" => $nik,
			"name" => $name,
			"date" => $date
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Riwayat-Scan-Pengunjung'.$name."_".$date;
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "potrait";
		$page = $this->load->view('report/historyScanVisitor', $data,true);
		$this->pdfgenerator->generate($page,$file_pdf,$paper,$orientation);
	}

	public function printAttendanceVis()
	{
		$data = [
			"title" => "Laporan Kedatangan Pengunjung",
			"lists" => $this->attendance->dataReportAttVis(),
			"date" => $this->session->userdata('att_vis_date')
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Kedatangan-Pengunjung_'.$this->session->userdata('att_vis_date');
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "portrait";
		$page = $this->load->view('report/reportAttVis', $data,true);
		$this->pdfgenerator->generate($page,$file_pdf,$paper,$orientation);
	}

	public function exportCSV_emp()
	{
		if ($this->session->userdata('att_emp_shift')) {
			$shift = '_Shift_'.$this->session->userdata('att_emp_shift');
		} else {
			$shift = '';
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$dept = $this->session->userdata('user')->dept_name;
		} else {
			if (!empty($this->session->userdata('att_emp_dept_id'))) {
				$dept = $this->session->userdata('att_emp_dept_name');
			} else {
				$dept = 'All Dept.';
			}
		}
		$dept_title = "_".$dept;
		$title = 'Laporan-Kehadiran-Employee_'.$this->session->userdata('att_emp_date').$shift.$dept_title;
		$lists = $this->attendance->dataReportAttEmp();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getColumnDimension('A')->setWidth(20);
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(20);
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(25);
		$sheet->getColumnDimension('G')->setWidth(25);
		$sheet->getColumnDimension('H')->setWidth(25);
		$sheet->getColumnDimension('I')->setWidth(25);
		$sheet->getColumnDimension('J')->setWidth(15);
		$sheet->getColumnDimension('K')->setWidth(15);
		$sheet->getColumnDimension('L')->setWidth(15);
		$sheet->getColumnDimension('M')->setWidth(15);
		$sheet->getColumnDimension('N')->setWidth(15);
		$sheet->setCellValue('A1','Department : ');
		$sheet->setCellValue('B1',$dept);
		$sheet->setCellValue('A2','Date : ');
		$sheet->setCellValue('B2',date('F j, Y',strtotime($this->session->userdata('att_emp_date'))));
		$sheet->setCellValue('A3','Shift : ');
		$sheet->setCellValue('B3',$this->session->userdata('att_emp_shift') ?? 'All');
		$sheet->setCellValue('A5','No');
		$sheet->setCellValue('B5','Pers Number');
		$sheet->setCellValue('C5','Employee Name');
		$sheet->setCellValue('D5','Department');
		$sheet->setCellValue('E5','Shift');
		$sheet->setCellValue('F5','DWS IN');
		$sheet->setCellValue('G5','DWS OUT');
		$sheet->setCellValue('H5','First Scan');
		$sheet->setCellValue('I5','Last Scan');
		$sheet->setCellValue('J5','Late Duration');
		$sheet->setCellValue('K5','Out Duration');
		$sheet->setCellValue('L5','In Duration');
		$sheet->setCellValue('M5','Out Allowed');
		$sheet->setCellValue('N5',"Passing Out");
		$counter = 6;
		$idx = 1;
		$lateTotal = $outPass = 0;
		foreach ($lists as $list) {
			$sheet->setCellValue("A$counter",$idx);
			$sheet->setCellValue("B$counter",$list->pin);
			$sheet->setCellValue("C$counter",$list->name);
			$sheet->setCellValue("D$counter",$list->dept_name);
			$sheet->setCellValue("E$counter",$list->shift);
			$sheet->setCellValue("F$counter",$list->masuk);
			$sheet->setCellValue("G$counter",$list->pulang);
			$sheet->setCellValue("H$counter",$list->in_scan);
			$sheet->setCellValue("I$counter",$list->out_scan);
			$sheet->setCellValue("J$counter",$list->late_duration);
			$sheet->setCellValue("K$counter",$list->out_duration);
			$sheet->setCellValue("L$counter",$list->in_duration);
			$sheet->setCellValue("M$counter",$list->out_allowed);
			if ($list->out_duration > $list->out_allowed) {
				$sheet->setCellValue("N$counter",$this->attendance->getOutDiff($list->out_duration,$list->out_allowed)->out_diff);
				$outPass++;
			}
			if (!empty($list->late_duration)) {
				$lateTotal++;
			}
			$counter++;
			$idx++;
		}
		$sheet->setCellValue("A".($counter+2),"Total Terlambat: ");
		$sheet->setCellValue("B".($counter+2),$lateTotal);
		$sheet->setCellValue("A".($counter+3),"Total Passing Out: ");
		$sheet->setCellValue("B".($counter+3),$outPass);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$title.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
	public function exportCSV_off()
	{
		if ($this->session->userdata('att_off_shift')) {
			$shift = '_Shift_'.$this->session->userdata('att_off_shift');
		} else {
			$shift = '';
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$dept = $this->session->userdata('user')->dept_name;
		} else {
			if (!empty($this->session->userdata('att_off_dept_id'))) {
				$dept = $this->session->userdata('att_off_dept_name');
			} else {
				$dept = 'All Dept.';
			}
		}
		$dept_title = "_".$dept;
		$lists = $this->attendance->dataReportAttOff();
		$filename = 'Laporan-Kehadiran-Office_'.$this->session->userdata('att_off_date').$shift.$dept_title;
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getColumnDimension('A')->setWidth(20);
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(20);
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(25);
		$sheet->getColumnDimension('G')->setWidth(25);
		$sheet->getColumnDimension('H')->setWidth(25);
		$sheet->getColumnDimension('I')->setWidth(25);
		$sheet->getColumnDimension('J')->setWidth(15);
		$sheet->setCellValue('A1','Department : ');
		$sheet->setCellValue('B1',$dept);
		$sheet->setCellValue('A2','Date : ');
		$sheet->setCellValue('B2',date('F j, Y',strtotime($this->session->userdata('att_off_date'))));
		$sheet->setCellValue('A3','Shift : ');
		$sheet->setCellValue('B3',$this->session->userdata('att_off_shift') ?? 'All');
		$sheet->setCellValue('A5','No');
		$sheet->setCellValue('B5','Pers Number');
		$sheet->setCellValue('C5','Employee Name');
		$sheet->setCellValue('D5','Department');
		$sheet->setCellValue('E5','Shift');
		$sheet->setCellValue('F5','DWS IN');
		$sheet->setCellValue('G5','DWS OUT');
		$sheet->setCellValue('H5','First Scan');
		$sheet->setCellValue('I5','Last Scan');
		$sheet->setCellValue('J5','Late Duration');
		$counter = 6;
		$idx = 1;
		$lateTotal = $outPass = 0;
		foreach ($lists as $list) {
			$sheet->setCellValue("A$counter",$idx);
			$sheet->setCellValue("B$counter",$list->pin);
			$sheet->setCellValue("C$counter",$list->name);
			$sheet->setCellValue("D$counter",$list->dept_name);
			$sheet->setCellValue("E$counter",$list->shift);
			$sheet->setCellValue("F$counter",$list->masuk);
			$sheet->setCellValue("G$counter",$list->pulang);
			$sheet->setCellValue("H$counter",$list->first_scan);
			$sheet->setCellValue("I$counter",$list->last_scan);
			$sheet->setCellValue("J$counter",$list->late_duration);
			$counter++;
			$idx++;
			if (!empty($list->late_duration)) {
				$lateTotal++;
			}
		}
		$sheet->setCellValue("A".($counter+2),"Total Terlambat: ");
		$sheet->setCellValue("B".($counter+2),$lateTotal);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
	public function exportCSV_vis()
	{
		$filename = 'Laporan-Kedatangan-Visitor_'.$this->session->userdata('att_vis_date');
		$lists = $this->attendance->dataReportAttVis();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getColumnDimension('B')->setWidth(15);
		$sheet->getColumnDimension('C')->setWidth(18);
		$sheet->getColumnDimension('D')->setWidth(18);
		$sheet->setCellValue('A1','No');
		$sheet->setCellValue('B1','Name');
		$sheet->setCellValue('C1','First Scan');
		$sheet->setCellValue('D1','Last Scan');
		$no = 0;
		$counter = 2;
		foreach ($lists as $list) {
			$sheet->setCellValue("A$counter",++$no);
			$sheet->setCellValue("B$counter",$list->name);
			$sheet->setCellValue("C$counter",$list->first_scan);
			$sheet->setCellValue("D$counter",$list->last_scan);
			$counter++;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function visitor()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$this->session->set_userdata([
			'att_vis_date' => date("Y-m-d"),
			'att_vis_date_search' => date("Y-m-d"),
			'att_vis_pin' => $this->attendance->getRndmVis()->pin,
			'att_vis_first_scan' => date("Y-m-d H:i:s"),
			'att_vis_scan_6hour' => date("Y-m-d H:i:s", strtotime('+6 hours'))
		]);
		$data = [
			'title' => 'Visitor Tracer'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('attendance/visitor', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_visitor()
	{
		$lists = $this->attendance->datatable_visitor();
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = '<span class="badge badge-pill badge-info">Card Scan</span>';
			$row[] = $list->name;
			$row[] = $list->first_scan;
			$row[] = $list->last_scan;
			$row[] = '<button type="button" class="btn btn-info btn-sm btn-show" data-id="'.$list->id.'" onclick="angular.element(this).scope().show('.$list->id.')"><i class="fas fa-fw fa-list-alt"></i> Detail</button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_visitor(),
			"recordsFiltered" => $this->attendance->count_filtered_visitor(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function attresume_vis($id)
	{
		$data = $this->attendance->get_vis_att_by_id($id);
		$get_last = $data->last_scan ?? $data->first_scan;
		$this->session->set_userdata([
			'att_vis_pin' => $data->pin,
			'att_vis_date_search' => date("Y-m-d",strtotime($data->first_scan)),
			'att_vis_first_scan' => $data->first_scan,
			'att_vis_scan_6hour' => date("Y-m-d H:i:s", strtotime($get_last.'+6 hours'))
		]);
		echo json_encode([
			'getName' => $data->name,
			'getPIN' => $data->pin,
			'getSearchDate' => strftime('%A, %d %B %Y', strtotime($data->first_scan))
		],JSON_PRETTY_PRINT);
	}
	public function att_yesterday_vis()
	{
		$get_date = new DateTime($this->session->userdata('att_vis_date'));
		$date = $get_date->modify('-1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_vis_date' => $date
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_today_vis()
	{
		$date = date("Y-m-d");
		$this->session->set_userdata([
			'att_vis_date' => $date
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime(date("Y-m-d")))
		]);
	}
	public function att_tomorrow_vis()
	{
		$get_date = new DateTime($this->session->userdata('att_vis_date'));
		$date = $get_date->modify('+1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_vis_date' => $date
			]);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_hist_scan_vis()
	{
		$path_port = '8098';
		$local = 'localhost';
		$url = '10.126.25.150';
		$path = "http://$url:$path_port";
		$lists = $this->attendance->dt_history_vis();
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$inout = explode("-",$list->dev_alias)[0];
			if ($inout == "IN") {
				$io = '<span class="badge badge-pill badge-primary">'.$inout.'</span>';
			} else if ($inout == "OUT") {
				$io = '<span class="badge badge-pill badge-danger">'.$inout.'</span>';
			} else {
				$io = '<span class="badge badge-pill badge-info">Gate '.$list->dev_alias.'</span>';
			}
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $list->event_time;
			$row[] = $list->dev_alias;
			$row[] = $io;
			$row[] = '<button type="button" class="btn btn-success btn-sm btn-photo" data-path="'.$path.$list->vid_linkage_handle.'" onclick="angular.element(this).scope().showPhoto(\''.$path.$list->vid_linkage_handle.'\')"><i class="fas fa-fw fa-image"></i> Photo</button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->attendance->count_all_history_vis(),
			"recordsFiltered" => $this->attendance->count_filtered_history_vis(),
			"data" => $data,
		];
		echo json_encode($output);
	}

}

/* End of file AttendanceController.php */
/* Location: ./application/controllers/AttendanceController.php */