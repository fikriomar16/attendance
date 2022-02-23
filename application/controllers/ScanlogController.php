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

	public function filter()
	{
		$now = date('Y-m-d H:i:s');
		$this->session->set_userdata([
			'pin' => $this->scanlog->getRndmScanPin()->pin,
			'start_search' => $now,
			'end_search' => date('Y-m-d H:i:s', strtotime($now.'+6 hours'))
		]);
		$data = [
			'title' => 'Filter Kehadiran',
			'nav_title' => 'Filter Data Kehadiran Karyawan'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('scanlog/filter', $data);
		$this->load->view('components/footer', $data);
	}
	public function getPinName()
	{
		echo json_encode($this->scanlog->collectNamePin(),JSON_PRETTY_PRINT);
	}
	public function getResume()
	{
		echo json_encode($this->scanlog->get_resume(),JSON_PRETTY_PRINT);
	}
	public function dt_filter()
	{
		$lists = $this->scanlog->dt_filter();
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
			$row = [];
			$row[] = $list->pin;
			$row[] = $list->name;
			$row[] = $list->event_time;
			$row[] = $list->dev_alias;
			$row[] = $io;
			$data[] = $row;
		}
		$output = [
			'draw' => $_POST['draw'],
			'recordsTotal' => $this->scanlog->count_all_filter(),
			'recordsFiltered' => $this->scanlog->count_filtered_filter(),
			'data' => $data,
		];
		echo json_encode($output);
	}
	public function getFilterData()
	{
		$error = [];
		$form = json_decode(file_get_contents("php://input"));
		if (empty($form->masuk) || empty($form->pulang) || empty($form->pin)) {
			if (empty($form->masuk)) {
				$error[] = "Tanggal & Jam Masuk Wajib Diisi";
			}
			if (empty($form->pulang)) {
				$error[] = "Tanggal & Jam Pulang Wajib Diisi";
			}
			if (empty($form->pin)) {
				$error[] = "Karyawan Wajib Dipilih";
			}
			echo json_encode([
				'error' => $error
			],JSON_PRETTY_PRINT);
		} else {
			$masuk = $form->masuk;
			$pulang = $form->pulang;
			$pin = $form->pin;
			$this->session->set_userdata([
				'pin' => $pin,
				'start_search' => $masuk,
				'end_search' => $pulang
			]);
			echo json_encode([
				'success' => 'Berhasil Memfilter Data'
			],JSON_PRETTY_PRINT);
		}
	}

}

/* End of file ScanlogController.php */
/* Location: ./application/controllers/ScanlogController.php */