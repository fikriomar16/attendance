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
				$type = '<h5><span class="badge badge-info">Visitor</span></h5>';
			} else {
				if (substr($list->dept_name,0,4) == "Prod") {
					$type = '<h5><span class="badge badge-success">Employee</span></h5>';
				} else {
					$type = '<h5><span class="badge badge-primary">Office</span></h5>';
				}
			}
			if (explode("-",$list->dev_alias)[0] == "IN") {
				$io = '<h5><span class="badge badge-pill badge-primary">'.explode("-",$list->dev_alias)[0].'</span></h5>';
			} else if (explode("-",$list->dev_alias)[0] == "OUT") {
				$io = '<h5><span class="badge badge-pill badge-danger">'.explode("-",$list->dev_alias)[0].'</span></h5>';
			} else {
				$io = '<h5><span class="badge badge-pill badge-secondary">'.$list->dev_alias.'</span></h5>';
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
		if (date('l') == 'Friday') {
			$list2 = $this->admin->getOut3a_friday();
		} elseif (date('l') == 'Saturday') {
			$list2 = $this->admin->getOut3a_saturday();
		} else {
			$list2 = $this->admin->getOut3a();
		}
		$list1 = $this->admin->getLate3a();
		$list3 = $this->admin->getLate3b();
		$lists = array_merge_recursive($list2,$list1,$list3);
		$first = array_column($lists, 'first_scan');
		// array_multisort($first, SORT_ASC, $lists);
		if (count($lists) > 0) {
			$data = [];
			$tbody = '';
			$i = 0;
			$limit = 50;
			foreach ($lists as $list) {
				if ($i < $limit) {
					if (!empty($list->out_allowed)) {
						$dur = date_create($list->out_duration);
						$allowed = date_create($list->out_allowed);
						$difference = date_diff($dur,$allowed);
						$raw_status = $difference->days * 24 * 60 + $difference->h * 60 + $difference->i;
						$status = '<h5><span class="badge badge-danger shadow p-2">Keluar Lewat '.$raw_status.' Menit</span></h5>';
					} else {
						if ($list->late_duration !== null) {
							$raw_status = $list->late_duration;
							$status = '<h5><span class="badge badge-warning text-dark shadow p-2">Terlambat : '.$raw_status.'</span></h5>';
						}
					}
					$row = [];
					$row[] = $list->name;
					$row[] = $list->pin;
					$row[] = $list->dept_name;
					$row[] = $raw_status;
					$i++;
					$data[] = $row;

					$tbody.='<tr>';
					$tbody.='<td>'.$list->name.'</td>';
					$tbody.='<td>'.$list->pin.'</td>';
					$tbody.='<td>'.$list->dept_name.'</td>';
					$tbody.='<td class="text-center">'.$status.'</td>';
					$tbody.='</tr>';
					$i++;
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