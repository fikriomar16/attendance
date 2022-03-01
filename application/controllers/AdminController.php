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
			"countOffice" => $this->admin->countOffToday() ?? 0,
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
		$list3 = $this->admin->datatable_dashboard3();
		$lists = array_merge($list1,$list2,$list3);

		$event = array_column($lists, 'event_time');
		array_multisort($event, SORT_DESC, $lists);
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			if (empty($list->shift)) {
				$type = '<span class="badge badge-pill badge-info">Visitor</span>';
			} else {
				if (substr($list->dept_name,0,3) == "Prod") {
					$type = '<span class="badge badge-pill badge-success">Employee</span>';
				} else {
					$type = '<span class="badge badge-pill badge-primary">Office</span>';
				}
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
			'recordsTotal' => $this->admin->count_all_dashboard() + $this->admin->count_all_dashboard2() + $this->admin->count_all_dashboard3(),
			'recordsFiltered' => $this->admin->count_filtered_dashboard() + $this->admin->count_filtered_dashboard2() + $this->admin->count_filtered_dashboard3(),
			'data' => $data,
		];
		echo json_encode($output,JSON_PRETTY_PRINT);
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

	public function dt_late()
	{
		$list1 = $this->admin->getLate3a();
		$list2 = $this->admin->getOut3a();
		$list3 = $this->admin->getLate3b();
		$lists = array_merge($list1,$list2,$list3);
		$first = array_column($lists, 'first_scan');
		array_multisort($first, SORT_DESC, $lists);
		$i = 0;
		if (count($lists) > 0) {
			$data = [];
			$tbody = '';
			for ($i; $i < 50; $i++) {
				if ($lists[i]) {
					if (!empty($lists[i]->out_allowed)) {
						$status = '<span class="badge badge-danger">Keluar Lebih Dari '.round(abs($lists[i]->out_duration - $lists[i]->out_allowed) / 60,2).' Menit</span>';
					}
					if ($lists[i]->late_duration !== null) {
						$status = '<span class="badge badge-warning">Terlambat '.$lists[i]->late_duration.' Menit</span>';;
					}
					$row = [];
					$row[] = $lists[i]->name;
					$row[] = $lists[i]->pin;
					$row[] = $lists[i]->dept_name;
					$row[] = $status;
					$i++;
					$data[] = $row;

					$tbody.='<tr>';
					$tbody.='<td>'.$lists[i]->name.'</td>';
					$tbody.='<td>'.$lists[i]->pin.'</td>';
					$tbody.='<td>'.$lists[i]->dept_name.'</td>';
					$tbody.='<td>'.$status.'</td>';
					$tbody.='</tr>';
				}
			}
			echo json_encode([
				'success' => 'Data Ditemukan',
				'raw' => $data,
				'data' => $tbody
			],JSON_PRETTY_PRINT);
		} else {
			$row = '';
			$msg = 'Data Tidak Ditemukan';
			$row.='<tr>';
			$row.='<td colspan=4 class="text-danger font-weight-bold text-center">';
			$row.=$msg;
			$row.='</td>';
			$row.='</tr>';
			echo json_encode([
				'error' => $msg,
				'data' => $row
			],JSON_PRETTY_PRINT);
		}
	}

}

/* End of file AdminController.php */
/* Location: ./application/controllers/AdminController.php */