<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$locale = 'id_ID.utf8';
		setlocale(LC_ALL, $locale);
		$this->load->model('Admin','admin');
	}

	public function index()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Dashboard'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('administrator/dashboard', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_dashboard()
	{
		$lists = $this->admin->datatable_dashboard();
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			if ($list->shift == NULL) {
				$type = '<span class="badge badge-pill badge-info">Visitor</span>';
			} else {
				$type = '<span class="badge badge-pill badge-success">Employee</span>';
			}
			if (explode("-",$list->dev_alias)[0] == "IN") {
				$io = '<span class="badge badge-pill badge-primary">'.explode("-",$list->dev_alias)[0].'</span>';
			} else if (explode("-",$list->dev_alias)[0] == "OUT") {
				$io = '<span class="badge badge-pill badge-danger">'.explode("-",$list->dev_alias)[0].'</span>';
			} else {
				$io = '<span class="badge badge-pill badge-info">'.$list->dev_alias.'</span>';
			}
			$row = [];
			$row[] = $list->event_time;
			$row[] = $list->name;
			$row[] = $type;
			$row[] = $io;

			$data[] = $row;
		}
		$output = [
			'draw' => $_POST['draw'],
			'recordsTotal' => $this->admin->count_all_dashboard(),
			'recordsFiltered' => $this->admin->count_filtered_dashboard(),
			'data' => $data,
		];
		echo json_encode($output);
	}
	public function countEmpVis()
	{
		echo json_encode([
			"countEmployee" => $this->admin->countEmpToday() ?? 0,
			"countVisitor" => $this->admin->countVisToday()->count_id ?? 0
		],JSON_PRETTY_PRINT);
	}
	public function dt_dashboard2()
	{
		$lists = $this->admin->datatable_dashboard2();
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			if ($list->shift == NULL) {
				$type = '<span class="badge badge-pill badge-info">Visitor</span>';
			} else {
				$type = '<span class="badge badge-pill badge-success">Employee</span>';
			}
			if (explode("-",$list->dev_alias)[0] == "IN") {
				$io = '<span class="badge badge-pill badge-primary">'.explode("-",$list->dev_alias)[0].'</span>';
			} else if (explode("-",$list->dev_alias)[0] == "OUT") {
				$io = '<span class="badge badge-pill badge-danger">'.explode("-",$list->dev_alias)[0].'</span>';
			} else {
				$io = '<span class="badge badge-pill badge-info">'.$list->dev_alias.'</span>';
			}
			$row = [];
			$row[] = $list->event_time;
			$row[] = $list->name;
			$row[] = $type;
			$row[] = $io;

			$data[] = $row;
		}
		$output = [
			'draw' => $_POST['draw'],
			'recordsTotal' => $this->admin->count_all_dashboard2(),
			'recordsFiltered' => $this->admin->count_filtered_dashboard2(),
			'data' => $data,
		];
		echo json_encode($output);
	}
	public function merged_db()
	{
		$list1 = $this->admin->datatable_dashboard();
		$list2 = $this->admin->datatable_dashboard2();
		$lists = array_merge($list1,$list2);
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			if (empty($list->shift)) {
				$type = '<span class="badge badge-pill badge-info">Visitor</span>';
			} else {
				$type = '<span class="badge badge-pill badge-success">Employee</span>';
			}
			if (explode("-",$list->dev_alias)[0] == "IN") {
				$io = '<span class="badge badge-pill badge-primary">'.explode("-",$list->dev_alias)[0].'</span>';
			} else if (explode("-",$list->dev_alias)[0] == "OUT") {
				$io = '<span class="badge badge-pill badge-danger">'.explode("-",$list->dev_alias)[0].'</span>';
			} else {
				$io = '<span class="badge badge-pill badge-info">'.$list->dev_alias.'</span>';
			}
			$row = [];
			$row[] = $list->event_time;
			$row[] = $list->name;
			$row[] = $type;
			$row[] = $io;

			$data[] = $row;
		}
		$output = [
			'draw' => $_POST['draw'],
			'recordsTotal' => $this->admin->count_all_dashboard() + $this->admin->count_all_dashboard2(),
			'recordsFiltered' => $this->admin->count_filtered_dashboard() + $this->admin->count_filtered_dashboard2(),
			'data' => $data,
		];
		echo json_encode($output);
	}

	public function late_page()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Late Notice'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('administrator/late', $data);
		$this->load->view('components/footer', $data);
	}

}

/* End of file AdminController.php */
/* Location: ./application/controllers/AdminController.php */