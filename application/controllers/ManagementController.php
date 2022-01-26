<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ManagementController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Management','management');
	}

	public function index()
	{
		redirect('/');
	}

	public function employee()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Employee Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('management/employee', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_employee()
	{
		$list = $this->management->datatable_employee();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			if ($emp->whitelist == 0) {
				$active = '';
			} elseif ($emp->whitelist == 1) {
				$active = 'checked';
			}
			$no++;
			$row = [];
			$row[] = '<div class="custom-control custom-checkbox mx-auto text-center">
			<input type="checkbox" id="chk_'.$emp->id.'" class="custom-control-input chk_1"/>
			<label class="custom-control-label" for="chk_'.$emp->id.'"></label>
			</div>';
			$row[] = $emp->nama;
			$row[] = $emp->pid;
			$row[] = $emp->name;
			$row[] = '<div class="custom-control custom-switch">
			<input type="checkbox" class="custom-control-input" id="wl'.$emp->id.'" '.$active.'>
			<label class="custom-control-label" for="wl'.$emp->id.'"></label>
			</div>';
			$row[] = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-id="'.$emp->id.'" onclick="angular.element(this).scope().edit('.$emp->id.')"><i class="fas fa-fw fa-edit"></i></button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->management->count_all_employee(),
			"recordsFiltered" => $this->management->count_filtered_employee(),
			"data" => $data,
		];
		echo json_encode($output);
	}

	public function internal()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Internal Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('management/internal', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_internal()
	{
		$list = $this->management->datatable_internal();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $int) {
			if ($int->whitelist == 0) {
				$active = '';
			} elseif ($int->whitelist == 1) {
				$active = 'checked';
			}
			$no++;
			$row = [];
			$row[] = '<div class="custom-control custom-checkbox mx-auto text-center">
			<input type="checkbox" id="chk_'.$int->id.'" class="custom-control-input chk_1"/>
			<label class="custom-control-label" for="chk_'.$int->id.'"></label>
			</div>';
			$row[] = $int->nama;
			$row[] = $int->pid;
			$row[] = $int->name;
			$row[] = '<div class="custom-control custom-switch">
			<input type="checkbox" class="custom-control-input" id="wl'.$int->id.'" '.$active.'>
			<label class="custom-control-label" for="wl'.$int->id.'"></label>
			</div>';
			$row[] = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-id="'.$int->id.'" onclick="angular.element(this).scope().edit('.$int->id.')"><i class="fas fa-fw fa-edit"></i></button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->management->count_all_internal(),
			"recordsFiltered" => $this->management->count_filtered_internal(),
			"data" => $data,
		];
		echo json_encode($output);
	}

	public function visitor()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Visitor Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('management/visitor', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_visitor()
	{
		$list = $this->management->datatable_visitor();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $vis) {
			if ($vis->whitelist == 0) {
				$active = '';
			} elseif ($vis->whitelist == 1) {
				$active = 'checked';
			}
			$no++;
			$row = [];
			$row[] = '<div class="custom-control custom-checkbox mx-auto text-center">
			<input type="checkbox" id="chk_'.$vis->id.'" class="custom-control-input chk_1"/>
			<label class="custom-control-label" for="chk_'.$vis->id.'"></label>
			</div>';
			$row[] = $vis->nama;
			$row[] = $vis->pid;
			$row[] = $vis->name;
			$row[] = '<div class="custom-control custom-switch">
			<input type="checkbox" class="custom-control-input" id="wl'.$vis->id.'" '.$active.'>
			<label class="custom-control-label" for="wl'.$vis->id.'"></label>
			</div>';
			$row[] = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-id="'.$vis->id.'" onclick="angular.element(this).scope().edit('.$vis->id.')"><i class="fas fa-fw fa-edit"></i></button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->management->count_all_visitor(),
			"recordsFiltered" => $this->management->count_filtered_visitor(),
			"data" => $data,
		];
		echo json_encode($output);
	}

	public function departement()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Departement Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('management/departement', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_dept()
	{
		$list = $this->management->datatable_dept();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $dept) {
			$no++;
			$row = [];
			$row[] = '<div class="custom-control custom-checkbox mx-auto text-center">
			<input type="checkbox" id="chk_'.$dept->id.'" class="custom-control-input chk_1"/>
			<label class="custom-control-label" for="chk_'.$dept->id.'"></label>
			</div>';
			$row[] = $dept->name;
			$row[] = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-id="'.$dept->id.'" onclick="angular.element(this).scope().edit('.$dept->id.')"><i class="fas fa-fw fa-edit"></i></button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->management->count_all_dept(),
			"recordsFiltered" => $this->management->count_filtered_dept(),
			"data" => $data,
		];
		echo json_encode($output);
	}

}

/* End of file ManagementController.php */
/* Location: ./application/controllers/ManagementController.php */