<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScanlogController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$locale = 'id_ID.utf8';
		setlocale(LC_ALL, $locale);
		$this->load->model('Scanlog','scanlog');
	}

	public function index()
	{
		$data = [
			'title' => 'Scan Log',
			'nav_title' => 'Scan Log Data'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('scanlog/scanlog', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_scanlog()
	{
		$lists = $this->scanlog->datatable_scanlog();
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$row = [];
			$row[] = $list->dept_name;
			$row[] = $list->dev_alias;
			$row[] = $list->event_time;
			$row[] = $list->name;
			$row[] = $list->pin;
			$row[] = $list->verify_mode_name;
			$data[] = $row;
		}
		$output = [
			'draw' => $_POST['draw'],
			'recordsTotal' => $this->scanlog->count_all_scanlog(),
			'recordsFiltered' => $this->scanlog->count_filtered_scanlog(),
			'data' => $data,
		];
		echo json_encode($output);
	}

}

/* End of file ScanlogController.php */
/* Location: ./application/controllers/ScanlogController.php */