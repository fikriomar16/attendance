<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScheduleController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
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
			'title' => 'Employee Schedule',
			'nav_title' => 'Employee Schedule Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('schedule/employee', $data);
		$this->load->view('components/footer', $data);
	}

	public function internal()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Internal Schedule',
			'nav_title' => 'Internal Schedule Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('schedule/internal', $data);
		$this->load->view('components/footer', $data);
	}

}

/* End of file ScheduleController.php */
/* Location: ./application/controllers/ScheduleController.php */