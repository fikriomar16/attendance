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
			'att_emp_nik' => $this->attendance->getRndmSch()->nik
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
			$row[] = '<span class="badge badge-pill badge-success">Fingerprint</span>';
			$row[] = $emp->name_spell;
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
	public function att_yesterday_emp()
	{
		$get_date = new DateTime($this->session->userdata('att_emp_date'));
		$date = $get_date->modify('-1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata([
				'att_emp_date' => $date,
				'att_emp_date_search' => $date,
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
			$this->session->set_userdata('att_emp_date_search',$date);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function att_today_emp_detail()
	{
		$this->session->set_userdata('att_emp_date_search',date("Y-m-d"));
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime(date("Y-m-d")))
		]);
	}
	public function att_tomorrow_emp_detail()
	{
		$get_date = new DateTime($this->session->userdata('att_emp_date_search'));
		$date = $get_date->modify('+1 day')->format('Y-m-d');
		if ($date) {
			$this->session->set_userdata('att_emp_date_search',$date);
			echo json_encode([
				'date' => strftime('%A, %d %B %Y', strtotime($date))
			]);
		}
	}
	public function attresume_emp($nik)
	{
		$this->session->set_userdata([
			'att_emp_date_search' => $this->session->userdata('att_emp_date'),
			'att_emp_nik' => $nik
		]);
		echo json_encode([
			'getNIK' => $this->session->userdata('att_emp_nik'),
			'getName' => $this->attendance->get_by_nik_employee($nik)->name,
			'getSearchDate' => strftime('%A, %d %B %Y', strtotime($this->session->userdata('att_emp_date_search')))
		]);
	}
	public function att_sum_emp()
	{
		$list = $this->attendance->dt_detail_emp();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$no++;
			$row = [];
			$row[] = $emp->date;
			$row[] = '<span class="badge badge-pill badge-success">Fingerprint</span>';
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
		$orientation = "portrait";
		$page = $this->load->view('report/reportAttEmp', $data,true);
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
		$file_pdf = 'Laporan-Kedatangan-Pengunjung'.$this->session->userdata('att_vis_date');
		// setting paper
		$paper = 'A4';
        //orientasi paper potrait / landscape
		$orientation = "portrait";
		$page = $this->load->view('report/reportAttVis', $data,true);
		$this->pdfgenerator->generate($page,$file_pdf,$paper,$orientation);
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
		$this->session->set_userdata([
			'att_vis_pin' => $data->pin,
			'att_vis_date_search' => date("Y-m-d",strtotime($data->first_scan)),
			'att_vis_first_scan' => $data->first_scan,
			'att_vis_scan_6hour' => date("Y-m-d H:i:s", strtotime($data->first_scan.'+6 hours'))
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