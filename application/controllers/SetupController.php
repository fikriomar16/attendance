<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetupController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Setup','setup');
	}

	public function index()
	{
		redirect('/');
	}

	public function duration()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Duration Setup',
			'nav_title' => 'Duration Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('setup/duration', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_duration()
	{
		$list = $this->setup->datatable_duration();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $dur) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $dur->name;
			$row[] = $dur->late_allowed;
			$row[] = $dur->out_allowed;
			$row[] = '<div class="btn-group btn-group-sm shadow-sm border-0" role="group">
			<button type="button" class="btn btn-primary btn-edit" data-id="'.$dur->id.'" onclick="angular.element(this).scope().edit('.$dur->id.')"><i class="fas fa-fw fa-pen"></i></button>
			<button type="button" class="btn btn-danger btn-delete" data-id="'.$dur->id.'" onclick="angular.element(this).scope().delete('.$dur->id.')"><i class="fas fa-fw fa-trash"></i></button>
			</div>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->setup->count_all_duration(),
			"recordsFiltered" => $this->setup->count_filtered_duration(),
			"data" => $data,
		];
		echo json_encode($output);
	}

	public function menu()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Menu Setup',
			'nav_title' => 'Menu Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('setup/menu', $data);
		$this->load->view('components/footer', $data);
	}

}

/* End of file SetupController.php */
/* Location: ./application/controllers/SetupController.php */