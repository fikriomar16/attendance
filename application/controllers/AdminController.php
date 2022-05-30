<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
			'title' => 'Late Notice',
			'deptlists' => $this->admin->deptLists()
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
			$row[] = $list->shift;
			$row[] = $list->dept_name;
			$row[] = '<h6 class="text-center"><span class="badge badge-warning text-dark shadow p-1">Keterlambatan : '.$list->late_duration.'</span></h6>';

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
	public function get_late()
	{
		$form = json_decode(file_get_contents("php://input"));
		$error = [];
		if (empty($form->date)) {
			$error[] = "Tanggal Wajib Diisi";
		}
		if ($error) {
			echo json_encode([
				'error' => $error
			],JSON_PRETTY_PRINT);
		} else {
			$this->session->set_userdata('late_search_date',$form->date);
			if ($this->session->userdata('user')->is_spv != 1) {
				$session = $this->session->userdata('user')->dept_name;
			} else {
				if (!empty($form->dept)) {
					$this->session->set_userdata('late_dept',$form->dept);
					$session = $this->session->userdata('late_dept');
				} else {
					$this->session->set_userdata('late_dept','');
					$session = 'All Departments';
				}
			}
			echo json_encode([
				'success' => 'Menampilkan Data....',
				'option' => $form->option,
				'dept' => $session,
				'date' => date('j F, Y',strtotime($this->session->userdata('late_search_date'))),
				'url' => base_url('late/reportLate')
			],JSON_PRETTY_PRINT);
		}
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
			'title' => 'Out Notice',
			'deptlists' => $this->admin->deptLists()
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
			$row[] = '<h6 class="text-center"><span class="badge badge-danger shadow p-2">Keluar Lewat '.$status.' </span></h6>';

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
	public function get_out()
	{
		$form = json_decode(file_get_contents("php://input"));
		$error = [];
		if (empty($form->date)) {
			$error[] = "Tanggal Wajib Diisi";
		}
		if ($error) {
			echo json_encode([
				'error' => $error
			],JSON_PRETTY_PRINT);
		} else {
			$this->session->set_userdata('out_search_date',$form->date);
			if ($this->session->userdata('user')->is_spv != 1) {
				$session = $this->session->userdata('user')->dept_name;
			} else {
				if (!empty($form->dept)) {
					$this->session->set_userdata('out_dept',$form->dept);
					$session = $this->session->userdata('out_dept');
				} else {
					$this->session->set_userdata('out_dept','');
					$session = 'All Departments';
				}
			}
			echo json_encode([
				'success' => 'Menampilkan Data....',
				'option' => $form->option,
				'dept' => $session,
				'date' => date('j F, Y',strtotime($this->session->userdata('out_search_date'))),
				'url' => base_url('out/reportOut')
			],JSON_PRETTY_PRINT);
		}
	}

	public function reportLate()
	{
		$tanggal = $this->session->userdata('late_search_date') ?? date('Y-m-d');
		if ($this->session->userdata('user')->is_spv != 1) {
			$dept = $this->session->userdata('user')->dept_name;
		} else {
			if (!empty($this->session->userdata('late_dept'))) {
				$dept = $this->session->userdata('late_dept');
			} else {
				$dept = 'All Departments';
			}
		}
		$lists = $this->admin->getDataLateReport();
		$lateTotal = $this->admin->getCountLateReport();
		$filename = "Report_Late_".$dept."_$tanggal";
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet(); 
		$sheet->setCellValue('A1','Department : ');
		$sheet->setCellValue('B1',$dept);
		$sheet->setCellValue('A2','Date : ');
		$sheet->setCellValue('B2',date('F j, Y',strtotime($tanggal)));
		$sheet->setCellValue('A3','Late Total : ');
		$sheet->setCellValue('B3',$lateTotal);
		$sheet->setCellValue('A5','Pers Number');
		$sheet->setCellValue('B5','Employee Name');
		$sheet->setCellValue('C5','Shift');
		$sheet->setCellValue('D5','Department');
		$sheet->setCellValue('E5','Late Duration');
		$counter = 6;
		foreach ($lists as $list) {
			$sheet->setCellValue("A$counter",$list->pin);
			$sheet->setCellValue("B$counter",$list->name);
			$sheet->setCellValue("C$counter",$list->shift);
			$sheet->setCellValue("D$counter",$list->dept_name);
			$sheet->setCellValue("E$counter",$list->late_duration);
			$counter++;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function reportOut()
	{
		$tanggal = $this->session->userdata('out_search_date') ?? date('Y-m-d');
		if ($this->session->userdata('user')->is_spv != 1) {
			$dept = $this->session->userdata('user')->dept_name;
		} else {
			if (!empty($this->session->userdata('out_dept'))) {
				$dept = $this->session->userdata('out_dept');
			} else {
				$dept = 'All Departments';
			}
		}
		$lists = $this->admin->getDataOutReport();
		$lateTotal = $this->admin->getCountOutReport();
		$filename = "Report_Out_".$dept."_$tanggal";
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet(); 
		$sheet->setCellValue('A1','Department : ');
		$sheet->setCellValue('B1',$dept);
		$sheet->setCellValue('A2','Date : ');
		$sheet->setCellValue('B2',date('F j, Y',strtotime($tanggal)));
		$sheet->setCellValue('A3',"Difference Duration Total : ");
		$sheet->setCellValue('B3',$lateTotal);
		$sheet->setCellValue('A5','Pers Number');
		$sheet->setCellValue('B5','Employee Name');
		$sheet->setCellValue('C5','Shift');
		$sheet->setCellValue('D5','Department');
		$sheet->setCellValue('E5','Difference Duration');
		$counter = 6;
		foreach ($lists as $list) {
			$sheet->setCellValue("A$counter",$list->pin);
			$sheet->setCellValue("B$counter",$list->name);
			$sheet->setCellValue("C$counter",$list->shift);
			$sheet->setCellValue("D$counter",$list->dept_name);
			$sheet->setCellValue("E$counter",$this->admin->getOutDiff($list->out_duration,$list->out_allowed)->out_diff);
			$counter++;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

}

/* End of file AdminController.php */
/* Location: ./application/controllers/AdminController.php */