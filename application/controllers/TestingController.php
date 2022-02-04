<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestingController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Testing','testing');
	}

	public function index()
	{
		header('Content-Type: application/json');
		echo json_encode($this->testing->test(),JSON_PRETTY_PRINT);
	}

	public function tr_schedule()
	{
		header('Content-Type: application/json');
		echo json_encode($this->testing->reset_table('sys_sch_users'),JSON_PRETTY_PRINT);
	}

	public function tr_tier1()
	{
		header('Content-Type: application/json');
		echo json_encode($this->testing->reset_table('acc_transaction'),JSON_PRETTY_PRINT);
	}

	public function tr_tier2a()
	{
		header('Content-Type: application/json');
		echo json_encode($this->testing->reset_table('acc_transaction_2a'),JSON_PRETTY_PRINT);
	}

	public function tr_tier2c()
	{
		header('Content-Type: application/json');
		echo json_encode($this->testing->reset_table('acc_transaction_2c'),JSON_PRETTY_PRINT);
	}

	public function tr_tier3()
	{
		header('Content-Type: application/json');
		echo json_encode($this->testing->reset_table('acc_transaction_3a'),JSON_PRETTY_PRINT);
	}

	public function tr_all_tier()
	{
		header('Content-Type: application/json');
		echo json_encode($this->testing->truncate_all(),JSON_PRETTY_PRINT);
	}

	public function template()
	{
		$this->load->view('template');
	}

}

/* End of file TestingController.php */
/* Location: ./application/controllers/TestingController.php */