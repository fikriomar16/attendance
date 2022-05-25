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

	public function sideToggle($status)
	{
		if ($status == 'deact') {
			$this->session->set_userdata('sideToggle',FALSE);
		} else if ($status == 'act') {
			$this->session->set_userdata('sideToggle',TRUE);
		}
		echo json_encode(['status' => $this->session->userdata('sideToggle')],JSON_PRETTY_PRINT);
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

	public function countEmpVis()
	{
		// code lists
		// 2: Minyak
		// 3: Sayur
		// 4: Bumbu
		// 12: Bawang
		// 5: Accounting
		// 6: HR
		// 7: Quality Control
		// 8: PPIC
		// 9: Technic
		// 10: Warehouse
		// 11: Purchasing
		echo json_encode([
			"countEmployee" => $this->admin->countEmpToday() ?? 0,
			"countEmployeeTotal" => $this->admin->countEmpTotal() ?? 0,
			"countOffice" => $this->admin->countOffToday() ?? 0,
			"countOfficeTotal" => $this->admin->countOffTotal() ?? 0,
			"countVisitor" => $this->admin->countVisToday()->count_id ?? 0,
			"countEmployeeMinyak" => $this->admin->countDWSDept(2) ?? 0,
			"countEmployeeMinyakTotal" => $this->admin->countDWSDeptTotal(2) ?? 0,
			"countEmployeeSayur" => $this->admin->countDWSDept(3) ?? 0,
			"countEmployeeSayurTotal" => $this->admin->countDWSDeptTotal(3) ?? 0,
			"countEmployeeBumbu" => $this->admin->countDWSDept(4) ?? 0,
			"countEmployeeBumbuTotal" => $this->admin->countDWSDeptTotal(4) ?? 0,
			"countEmployeeBawang" => $this->admin->countDWSDept(12) ?? 0,
			"countEmployeeBawangTotal" => $this->admin->countDWSDeptTotal(12) ?? 0,
			"countOfficeAccounting" => $this->admin->countDWSDept(5) ?? 0,
			"countOfficeAccountingTotal" => $this->admin->countDWSDeptTotal(5) ?? 0,
			"countOfficeHR" => $this->admin->countDWSDept(6) ?? 0,
			"countOfficeHRTotal" => $this->admin->countDWSDeptTotal(6) ?? 0,
			"countOfficeQC" => $this->admin->countDWSDept(7) ?? 0,
			"countOfficeQCTotal" => $this->admin->countDWSDeptTotal(7) ?? 0,
			"countOfficePPIC" => $this->admin->countDWSDept(8) ?? 0,
			"countOfficePPICTotal" => $this->admin->countDWSDeptTotal(8) ?? 0,
			"countOfficeTechnic" => $this->admin->countDWSDept(9) ?? 0,
			"countOfficeTechnicTotal" => $this->admin->countDWSDeptTotal(9) ?? 0,
			"countOfficeWarehouse" => $this->admin->countDWSDept(10) ?? 0,
			"countOfficeWarehouseTotal" => $this->admin->countDWSDeptTotal(10) ?? 0,
			"countOfficePurchasing" => $this->admin->countDWSDept(11) ?? 0,
			"countOfficePurchasingTotal" => $this->admin->countDWSDeptTotal(11) ?? 0,
		],JSON_PRETTY_PRINT);
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
				$type = '<h5><span class="badge badge-secondary shadow">Visitor</span></h5>';
			} else {
				if (substr($list->dept_name,0,4) == "Prod") {
					$type = '<h5><span class="badge badge-success shadow">Employee</span></h5>';
				} else {
					$type = '<h5><span class="badge badge-primary shadow">Office</span></h5>';
				}
			}
			if (explode("-",$list->dev_alias)[0] == "IN") {
				$io = '<h5><span class="badge badge-pill badge-primary shadow">'.explode("-",$list->dev_alias)[0].'</span></h5>';
			} else if (explode("-",$list->dev_alias)[0] == "OUT") {
				$io = '<h5><span class="badge badge-pill badge-danger shadow">'.explode("-",$list->dev_alias)[0].'</span></h5>';
			} else {
				$io = '<h5><span class="badge badge-pill badge-info shadow">'.$list->dev_alias.'</span></h5>';
			}
			$row = [];
			$row[] = date('H:i:s', strtotime($list->event_time));
			$row[] = "$list->pin / $list->name";
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
		$this->session->set_userdata([
			'late_date' => date("Y-m-d"),
			'late_search_date' => date("Y-m-d"),
			'late_dept' => "Prod. Minyak"
		]);
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
		$list1 = $this->admin->datatable_late_emp();
		$list2 = $this->admin->datatable_late_off();
		$lists = array_merge($list1,$list2);

		$event = array_column($lists, 'first_scan');
		array_multisort($event, SORT_DESC, $lists);
		$data = [];
		$no = $_POST['start'];

		foreach ($lists as $list) {
			$row = [];
			$row[] = $list->pin;
			$row[] = $list->name;
			$row[] = $list->dept_name;
			$row[] = '<h5><span class="badge badge-warning text-dark shadow p-1">Keterlambatan : '.$list->late_duration.'</span></h5>';

			$data[] = $row;
		}
		$output = [
			'draw' => $_POST['draw'],
			'recordsTotal' => $this->admin->count_all_late_emp() + $this->admin->count_all_late_off(),
			'recordsFiltered' => $this->admin->count_filtered_late_emp() + $this->admin->count_filtered_late_off(),
			'data' => $data,
		];
		echo json_encode($output,JSON_PRETTY_PRINT);
	}

	public function out_page()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$this->session->set_userdata([
			'out_date' => date("Y-m-d"),
			'out_search_date' => date("Y-m-d"),
			'out_dept' => "Prod. Minyak"
		]);
		$data = [
			'title' => 'Out Notice'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('administrator/out', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_out()
	{
		$lists = $this->admin->datatable_out_emp();
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$outDur = date_create($list->out_duration);
			$outAllow = date_create($list->out_allowed);
			$difference = date_diff($outDur,$outAllow);
			$diffMin = $difference->days * 24 * 60 + $difference->h * 60 + $difference->i;
			$diffSec = $diffMin * 60 + $difference->s;
			if ($diffMin == 0) {
				$status = "$diffSec Detik";
			} else {
				$status = "$diffMin Menit";
			}
			$no++;
			$row = [];
			$row[] = $list->pin;
			$row[] = $list->name;
			$row[] = $list->shift;
			$row[] = $list->dept_name;
			$row[] = '<h5><span class="badge badge-danger shadow p-2">Keluar Lewat '.$status.' </span></h5>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->admin->count_all_out_emp(),
			"recordsFiltered" => $this->admin->count_filtered_out_emp(),
			"data" => $data,
		];
		echo json_encode($output);
	}

	public function showMenuFor()
	{
		$user = $this->session->userdata('user');
		echo json_encode($user);
	}

	public function dumpOutLate()
	{
		$list2 = $this->admin->getOut3a();
		$outDur = $list2->out_duration;
		$list1 = $this->admin->getLate3a();
		$list3 = $this->admin->getLate3b();
		$lists = array_merge_recursive($list2,$list1,$list3);
		$first = array_column($lists, 'first_scan');
		if (count($lists) > 0) {
			$data = [];
			$tbody = '';
			$i = 0;
			$limit = 50;
			foreach ($lists as $list) {
				if ($i < $limit) {
					$outAllow = $list->out_allowed;
					$outDur = $list->out_duration;
					if ($list->late_duration == null && $outDur > $outAllow) {
						$dur = date_create($outDur);
						$allowed = date_create($outAllow);
						$difference = date_diff($dur,$allowed);
						$raw_status = $difference->days * 24 * 60 + $difference->h * 60 + $difference->i;
						if ($raw_status != 0) {
							$status = '<h5><span class="badge badge-danger shadow p-2">Keluar Lewat '.$raw_status.' Menit</span></h5>';
						} else {
							$status = '<h5><span class="badge badge-danger shadow p-2">Keluar Lewat '.($raw_status * 60 + $difference->s).' Detik</span></h5>';
						}
					} else if ($list->late_duration !== null)  {
						$raw_status = $list->late_duration;
						$status = '<h5><span class="badge badge-warning text-dark shadow p-2">Terlambat : '.$raw_status.'</span></h5>';
					}
					$row = [];
					$row[] = $list->name;
					$row[] = $list->pin;
					$row[] = $list->dept_name;
					$row[] = $raw_status;
					$data[] = $row;

					$tbody.='<tr>';
					$tbody.='<td class="text-primary font-weight-bold">'.$list->name.'</td>';
					$tbody.='<td class="text-danger font-weight-bold">'.$list->pin.'</td>';
					$tbody.='<td class="font-weight-bold">'.$list->dept_name.'</td>';
					$tbody.='<td class="text-center">'.$status.'</td>';
					$tbody.='</tr>';
					$i++;
				}
			}
			echo json_encode([
				'success' => 'Data Ditemukan',
				'raw' => $data,
				'data' => $tbody,
				'list1' => $list1,
				'list2' => $list2,
				'list3' => $list3
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