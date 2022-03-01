<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime(date("Y-m-d")))
		]);
	}

	public function employee()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$this->session->set_userdata([
			'att_emp_date' => date("Y-m-d"),
			'att_emp_date_search' => date("Y-m-d"),
			'att_emp_nik' => $this->attendance->getRndmSch()->nik,
			'recap_month' => date('m'),
			'recap_year' => date('Y')
		]);
		$this->session->unset_userdata('att_emp_shift');
		$path_port = '8098';
		$data = [
			'title' => 'Employee Attendance',
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
	public function set_shift($shift = 0)
	{
		if ($shift == 0) {
			$this->session->unset_userdata('att_emp_shift');
		} else {
			$this->session->set_userdata('att_emp_shift',$shift);
		}
		echo json_encode([
			'shift' => $shift
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
			if (explode("-",$emp->dev_alias)[0] == "IN") {
				$io = '<span class="badge badge-pill badge-primary">'.explode("-",$emp->dev_alias)[0].'</span>';
			} else if (explode("-",$emp->dev_alias)[0] == "OUT") {
				$io = '<span class="badge badge-pill badge-danger">'.explode("-",$emp->dev_alias)[0].'</span>';
			} else {
				$io = '<span class="badge badge-pill badge-info">'.$emp->dev_alias.'</span>';
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
		$this->session->set_userdata([
			'att_off_date' => date("Y-m-d"),
			'att_off_date_search' => date("Y-m-d"),
			'att_off_nik' => $this->attendance->getRndmSch()->nik,
			'recap_month_off' => date('m'),
			'recap_year_off' => date('Y')
		]);
		$this->session->unset_userdata('att_off_shift');
		$path_port = '8098';
		$data = [
			'title' => 'Management Attendance',
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
	public function set_shift_off($shift = 0)
	{
		if ($shift == 0) {
			$this->session->unset_userdata('att_off_shift');
		} else {
			$this->session->set_userdata('att_off_shift',$shift);
		}
		echo json_encode([
			'shift' => $shift
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
			$row[] = $emp->in_scan;
			$row[] = $emp->out_scan;
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
			$row[] = $emp->in_scan;
			$row[] = $emp->out_scan;
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
			if (explode("-",$emp->dev_alias)[0] == "IN") {
				$io = '<span class="badge badge-pill badge-primary">'.explode("-",$emp->dev_alias)[0].'</span>';
			} else if (explode("-",$emp->dev_alias)[0] == "OUT") {
				$io = '<span class="badge badge-pill badge-danger">'.explode("-",$emp->dev_alias)[0].'</span>';
			} else {
				$io = '<span class="badge badge-pill badge-info">'.$emp->dev_alias.'</span>';
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
		$data = [
			"title" => "Laporan Kehadiran Karyawan",
			"lists" => $this->attendance->dataReportAttEmp(),
			"shift" => $this->session->userdata('att_emp_shift') ?? 'All',
			"date" => $this->session->userdata('att_emp_date')
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Kehadiran-Karyawan_'.$this->session->userdata('att_emp_date').$shift;
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
		$data = [
			"title" => "Laporan Kehadiran Office",
			"lists" => $this->attendance->dataReportAttOff(),
			"shift" => $this->session->userdata('att_off_shift') ?? 'All',
			"date" => $this->session->userdata('att_off_date')
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Kehadiran-Office'.$this->session->userdata('att_off_date').$shift;
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
		$bulan = strftime('%B', strtotime($m))."-".$this->session->userdata('recap_year');
		$month = strftime('%B', strtotime($m)).", ".$this->session->userdata('recap_year');
		$nik = $this->session->userdata('att_emp_nik');
		$name = $this->attendance->get_by_nik_employee($nik)->name;
		$data = [
			"title" => "Rekap Kehadiran Bulanan Karyawan",
			"lists" => $this->attendance->dataRecapSumEmp(),
			"month" => $month,
			"nik" => $nik,
			"name" => $name
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Kehadiran-Bulanan-Karyawan_'.$bulan."-".$nik."_".$name;
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "potrait";
		$page = $this->load->view('report/rekapBulananKaryawan', $data,true);
		$this->pdfgenerator->generate($page,$file_pdf,$paper,$orientation);
	}
	public function rekapBulananOffice()
	{
		$m = date('F', mktime(0, 0, 0, $this->session->userdata('recap_month_off'),1,$this->session->userdata('recap_year_off')));
		$bulan = strftime('%B', strtotime($m))."-".$this->session->userdata('recap_year_off');
		$month = strftime('%B', strtotime($m)).", ".$this->session->userdata('recap_year_off');
		$nik = $this->session->userdata('att_off_nik');
		$name = $this->attendance->get_by_nik_employee($nik)->name;
		$data = [
			"title" => "Rekap Kehadiran Bulanan Office",
			"lists" => $this->attendance->dataRecapSumOff(),
			"month" => $month,
			"nik" => $nik,
			"name" => $name
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Kehadiran-Bulanan-Office'.$bulan."-".$nik."_".$name;
		// setting paper
		$paper = 'A4';
		//orientasi paper potrait / landscape
		$orientation = "potrait";
		$page = $this->load->view('report/rekapBulananOffice', $data,true);
		$this->pdfgenerator->generate($page,$file_pdf,$paper,$orientation);
	}

	public function historyScanKaryawan()
	{
		$lists = $this->attendance->dataRecapScanEmp();
		$nik = $this->session->userdata('att_emp_nik');
		$name = $lists[0]->name;
		$date = $this->session->userdata('att_emp_date');
		$shift = $lists[0]->shift;
		$data = [
			"title" => "Riwayat Scan Karyawan",
			"lists" => $lists,
			"nik" => $nik,
			"name" => $name,
			"date" => $date,
			"shift" => $shift
		];
		$this->load->library('pdfgenerator');
		$file_pdf = 'Laporan-Riwayat-Scan-Karyawan_'.$nik."-".$name."_".$date;
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
		$title = 'Laporan-Kehadiran-Karyawan_'.$this->session->userdata('att_emp_date').$shift;
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=".$title.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		$handle = fopen('php://output', 'w');
		$header = ['No','NIK','Name','Department','Shift','In Schedule','Out Schedule','First Scan','Last Scan','Late Duration','Out Duration','In Duration'];
		$lists = $this->attendance->dataReportAttEmp();
		$no = 0;
		fputcsv($handle, $header);
		foreach ($lists as $list) {
			$collect = [];
			$collect[] = ++$no;
			$collect[] = $list->pin;
			$collect[] = $list->name;
			$collect[] = $list->dept_name;
			$collect[] = $list->shift;
			$collect[] = $list->masuk;
			$collect[] = $list->pulang;
			$collect[] = $list->in_scan;
			$collect[] = $list->out_scan;
			$collect[] = $list->late_duration;
			$collect[] = $list->out_duration;
			$collect[] = $list->in_duration;
			fputcsv($handle, $collect);
		}
		fclose($handle);
		exit;
	}
	public function exportCSV_off()
	{
		if ($this->session->userdata('att_off_shift')) {
			$shift = '_Shift_'.$this->session->userdata('att_off_shift');
		} else {
			$shift = '';
		}
		$title = 'Laporan-Kehadiran-Office_'.$this->session->userdata('att_off_date').$shift;
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=".$title.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		$handle = fopen('php://output', 'w');
		$header = ['No','NIK','Name','Department','Shift','In Schedule','Out Schedule','First Scan','Last Scan','Late Duration'];
		$lists = $this->attendance->dataReportAttOff();
		$no = 0;
		fputcsv($handle, $header);
		foreach ($lists as $list) {
			$collect = [];
			$collect[] = ++$no;
			$collect[] = $list->pin;
			$collect[] = $list->name;
			$collect[] = $list->dept_name;
			$collect[] = $list->shift;
			$collect[] = $list->masuk;
			$collect[] = $list->pulang;
			$collect[] = $list->in_scan;
			$collect[] = $list->out_scan;
			$collect[] = $list->late_duration;
			fputcsv($handle, $collect);
		}
		fclose($handle);
		exit;
	}
	public function exportCSV_vis()
	{
		$title = 'Laporan-Kedatangan-Pengunjung_'.$this->session->userdata('att_vis_date');
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=".$title.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		$handle = fopen('php://output', 'w');
		$lists = $this->attendance->dataReportAttVis();
		$no = 0;
		$header = ['No','Name','First Scan', 'Last Scan'];
		fputcsv($handle, $header);
		foreach ($lists as $list) {
			$collect = [];
			$collect[] = ++$no;
			$collect[] = $list->name;
			$collect[] = $list->first_scan;
			$collect[] = $list->last_scan;
			fputcsv($handle, $collect);
		}
		fclose($handle);
		exit;
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
			if (explode("-",$list->dev_alias)[0] == "IN") {
				$io = '<span class="badge badge-pill badge-primary">'.explode("-",$list->dev_alias)[0].'</span>';
			} else if (explode("-",$list->dev_alias)[0] == "OUT") {
				$io = '<span class="badge badge-pill badge-danger">'.explode("-",$list->dev_alias)[0].'</span>';
			} else {
				$io = '<span class="badge badge-pill badge-info">'.$list->dev_alias.'</span>';
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