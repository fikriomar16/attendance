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
			'att_emp_nik' => $this->attendance->getRndmSch('employee')->nik
		]);
		$this->session->unset_userdata('att_emp_shift');
		$data = [
			'title' => 'Employee Attendance'
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
			$row[] = '<span class="badge badge-pill badge-success">Scan</span>';
			$row[] = $emp->nama;
			$row[] = $emp->pid;
			$row[] = $emp->shift;
			$row[] = $emp->name;
			$row[] = '<button type="button" class="btn btn-info btn-sm btn-show" data-id="'.$emp->pid.'" onclick="angular.element(this).scope().show('.$emp->pid.')"><i class="fas fa-fw fa-list-alt"></i></button>';

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
			'getName' => $this->attendance->get_by_nik_employee($nik)->nama,
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
			$row[] = '<span class="badge badge-pill badge-success">Scan</span>';
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
		// code...
	}

	public function internal()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Internal Attendance'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('attendance/internal', $data);
		$this->load->view('components/footer', $data);
	}

	public function Visitor()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Visitor Tracer'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('attendance/visitor', $data);
		$this->load->view('components/footer', $data);
	}

}

/* End of file AttendanceController.php */
/* Location: ./application/controllers/AttendanceController.php */