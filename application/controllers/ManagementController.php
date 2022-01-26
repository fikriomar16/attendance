<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ManagementController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
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