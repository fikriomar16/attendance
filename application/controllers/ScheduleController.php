<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class ScheduleController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$locale = 'id_ID.utf8';
		setlocale(LC_ALL, $locale);
		$this->load->model('Schedule','schedule');
	}

	public function index()
	{
		redirect('/schedule/employee');
	}

	public function get_yesterday_emp()
	{
		$get_ytd = new DateTime($this->session->userdata('employee_sch')['date']);
		$ytd = $get_ytd->modify('-1 day')->format('Y-m-d');
		if ($ytd) {
			$this->session->set_userdata('employee_sch',[
				'date' => $ytd,
				'nik' => $this->session->userdata('employee_sch')['nik']
			]);
		}
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime($ytd))
		]);
	}
	public function get_tomorrow_emp()
	{
		$get_tmr = new DateTime($this->session->userdata('employee_sch')['date']);
		$tmr = $get_tmr->modify('+1 day')->format('Y-m-d');
		if ($tmr) {
			$this->session->set_userdata('employee_sch',[
				'date' => $tmr,
				'nik' => $this->session->userdata('employee_sch')['nik']
			]);
		}
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime($tmr))
		]);
	}
	public function get_today_emp()
	{
		$tdy = date("Y-m-d");
		$this->session->set_userdata('employee_sch',[
			'date' => $tdy,
			'nik' => $this->session->userdata('employee_sch')['nik']
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime($tdy))
		]);
	}
	public function getresume_emp($nik)
	{
		$this->session->set_userdata('employee_sch',[
			'nik' => $nik,
			'date' => $this->session->userdata('employee_sch')['date']
		]);
		echo json_encode([
			'getSchName' => $this->schedule->get_by_nik_employee($nik)->name ?? '',
			'getSchDate' => strftime('%A, %d %B %Y', strtotime($this->session->userdata('employee_sch')['date'])),
			'schId' => $nik
		]);
	}
	public function getDate_sch($date)
	{
		$this->session->set_userdata('employee_sch',[
			'nik' => $this->session->userdata('employee_sch')['nik'],
			'date' => $date
		]);
		echo json_encode([
			'date' => strftime('%A, %d %B %Y', strtotime($date))
		]);
	}

	public function employee()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$this->session->set_userdata('employee_sch',[
			'nik' => $this->schedule->set_emp_sch_rndm()->nik,
			'date' => date("Y-m-d")
		]);
		$data = [
			'title' => 'Employee Schedule',
			'nav_title' => 'Employee Schedule Manager',
			'deptlists' => $this->schedule->deptLists()
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('schedule/employee', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_employee()
	{
		$list = $this->schedule->datatable_employee();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $emp->nama;
			$row[] = $emp->nik;
			$row[] = $emp->dept_name;
			$row[] = '<button type="button" class="btn btn-primary btn-sm btn-sch shadow-sm" data-id="'.$emp->nik.'" onclick="angular.element(this).scope().getsch('.$emp->nik.')"><i class="fas fa-fw fa-calendar-alt"></i> Schedule</button>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->schedule->count_all_employee(),
			"recordsFiltered" => $this->schedule->count_filtered_employee(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function sch_employee()
	{
		$list = $this->schedule->datatable_employee_sch();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $emp) {
			$no++;
			$row = [];
			$row[] = strftime('%A', strtotime($emp->tanggal));
			$row[] = $emp->tanggal;
			$row[] = $emp->shift;
			$row[] = $emp->masuk;
			$row[] = $emp->pulang;
			$row[] = '<div class="btn-group btn-group-sm shadow-sm border-0" role="group">
			<button type="button" class="btn btn-primary btn-edit" data-id="'.$emp->id.'" onclick="angular.element(this).scope().edit('.$emp->id.')"><i class="fas fa-fw fa-pen"></i></button>
			<button type="button" class="btn btn-danger btn-delete" data-id="'.$emp->id.'" onclick="angular.element(this).scope().delete('.$emp->id.')"><i class="fas fa-fw fa-trash"></i></button>
			</div>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->schedule->count_all_employee_sch(),
			"recordsFiltered" => $this->schedule->count_filtered_employee_sch(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function empList()
	{
		$list = $this->schedule->checkEmp();
		echo json_encode($list,JSON_PRETTY_PRINT);
	}
	public function shiftList()
	{
		$list = $this->schedule->checkShift();
		echo json_encode($list,JSON_PRETTY_PRINT);
	}

	public function get_by_id_employee_sch($id)
	{
		$data = $this->schedule->get_by_id_employee_sch($id);
		echo json_encode($data,JSON_PRETTY_PRINT);
	}
	public function saveSchedule()
	{
		$subMasuk = '-1 hours';
		$subPulang = '+4 hours';
		$error = [];
		$form = json_decode(file_get_contents("php://input"));
		if (empty($form->nik) || empty($form->shift) || empty($form->work_time) || empty($form->masuk) || empty($form->pulang)) {
			if (empty($form->nik)) {
				$error[] = "Karyawan Wajib Dipilih";
			}
			if (empty($form->shift)) {
				$error[] = "Shift Wajib Diisi";
			}
			if (empty($form->work_time)) {
				$error[] = "Durasi Jam Kerja Wajib Diisi";
			}
			if (empty($form->masuk)) {
				$error[] = "Tanggal & Jam Masuk Wajib Diisi";
			}
			if (empty($form->pulang)) {
				$error[] = "Tanggal & Jam Pulang Wajib Diisi";
			}
			echo json_encode([
				'error' => $error
			],JSON_PRETTY_PRINT);
		} else {
			$shift = strtoupper($form->shift);
			$allowed = $this->schedule->countAllowed($form->masuk,$form->pulang,$form->work_time);
			if (empty($form->id)) {
				$data_add = [
					'nik' => $form->nik,
					'nama' => $this->schedule->get_by_nik_employee($form->nik)->name,
					'shift' => $shift,
					'tanggal' => date('Y-m-d',strtotime($form->masuk)),
					'masuk' => date('Y-m-d H:i:s',strtotime($form->masuk)),
					'pulang' => date('Y-m-d H:i:s',strtotime($form->pulang)),
					'sub_masuk' => date('Y-m-d H:i:s',strtotime($form->masuk.$subMasuk)),
					'sub_pulang' => date('Y-m-d H:i:s',strtotime($form->pulang.$subPulang)),
					'work_time' => $form->work_time,
				];
				if ($allowed) {
					$data_add['out_allowed'] = $allowed->out_allowed;
				}
				$add = $this->schedule->create_schedule($data_add);
				if ($add) {
					echo json_encode([
						'success' => 'Data Berhasil Ditambahkan'
					],JSON_PRETTY_PRINT);
				} else {
					echo json_encode([
						'error' => 'Terjadi Kesalahan',
						'res' => $add,
						'data' => $data_add
					],JSON_PRETTY_PRINT);
				}
			} else {
				$data_edit = [
					'shift' => $shift,
					'tanggal' => date('Y-m-d',strtotime($form->masuk)),
					'masuk' => date('Y-m-d H:i:s',strtotime($form->masuk)),
					'pulang' => date('Y-m-d H:i:s',strtotime($form->pulang)),
					'sub_masuk' => date('Y-m-d H:i:s',strtotime($form->masuk.$subMasuk)),
					'sub_pulang' => date('Y-m-d H:i:s',strtotime($form->pulang.$subPulang)),
					'work_time' => $form->work_time,
				];
				if ($allowed) {
					$data_edit['out_allowed'] = $allowed->out_allowed;
				}
				$edit = $this->schedule->update_schedule($form->id,$data_edit);
				if ($edit) {
					echo json_encode([
						'success' => 'Data Berhasil Diperbarui'
					],JSON_PRETTY_PRINT);
				} else {
					echo json_encode([
						'error' => 'Terjadi Kesalahan'
					],JSON_PRETTY_PRINT);
				}
			}
		}
	}
	public function deleteSchedule($id)
	{
		$delete = $this->schedule->delete_schedule($id);
		if ($delete) {
			echo json_encode([
				'success' => 'Data Berhasil Dihapus'
			],JSON_PRETTY_PRINT);
		} else {
			echo json_encode([
				'error' => 'Terjadi Kesalahan'
			],JSON_PRETTY_PRINT);
		}
	}

	public function exportSchTemplate()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet(); 
		$sheet->setCellValue('A1','Pers Number');
		$sheet->setCellValue('B1','Employee Name');
		$sheet->setCellValue('C1','DWS Date');
		$sheet->setCellValue('D1','DWS');
		$sheet->setCellValue('E1','DWS IN');
		$sheet->setCellValue('F1','DWS OUT');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Import Jadwal.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function importSchCSV()
	{
		$config['upload_path'] = './assets/import/';
		$config['allowed_types'] = 'csv|xls|xlsx';
		$config['max_size']  = '4096';
		$config['overwrite'] = true;
		$config['file_name'] = 'importschedule';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('import_sch')){
			echo json_encode([
				'error' => $this->upload->display_errors()
			]);
		} else {
			$data = [
				'upload_data' => $this->upload->data()
			];
			$filename = $data['upload_data']['file_name'];
			$extension = $data['upload_data']['file_ext'];
			$filePath = $data['upload_data']['full_path'];
			if ($extension == '.xls') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			}
			if ($extension == '.xlsx') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			if ($extension == '.csv') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			}
			$spreadsheet = $reader->load($filePath);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$collects = [];
			$i = 0;
			$subMasuk = '-1 hours';
			$subPulang = '+4 hours';
			foreach ($sheetData as $row) {
				$collect = [];
				$nik = strval($row[0]);
				$nama = $row[1];
				$shift = $row[3];
				$tgl = date('Y-m-d',strtotime(str_replace(".", "-", $row[2])));
				$msk = date('H:i:s',strtotime($row[4]));
				$plg = date('H:i:s',strtotime($row[5]));
				$masuk = $tgl.' '.$msk;
				$pulang = ($msk>$plg) ? date('Y-m-d', strtotime($tgl.'+1 day')).' '.$plg : $tgl.' '.$plg;
				if (!$this->schedule->checkSch($nik,$masuk,$pulang) &&  $this->schedule->get_by_nik_employee($nik)) {
					if ($i > 0 && $msk !== "00:00:00" && $plg !== "00:00:00") {
						$collect = [
							'nik' => $nik,
							'nama' => $nama,
							'shift' => $shift,
							'tanggal' => $tgl,
							'masuk' => $masuk,
							'pulang' => $pulang,
							'sub_masuk' => date('Y-m-d H:i:s',strtotime($masuk.$subMasuk)),
							'sub_pulang' => date('Y-m-d H:i:s',strtotime($pulang.$subPulang)),
						];
						$collects[] = $collect;
					}
					$i++;
				}
			}
			unset($sheetData[0]);
			unlink($filePath);
			echo json_encode([
				'success' => "Berhasil Mengimport File $extension",
				'result' => $sheetData,
				'collect' => $collects
			],JSON_PRETTY_PRINT);
		}
	}
	public function processImport()
	{
		$form = json_decode(file_get_contents("php://input"));
		$import = $this->schedule->insertFromImport($form);
		if ($import) {
			echo json_encode([
				'success' => 'Berhasil Mengimport Data, Data Telah Disimpan'
			],JSON_PRETTY_PRINT);
		} else {
			echo json_encode([
				'error' => 'Terjadi Kesalahan',
				'res' => $import,
				'data' => $form
			],JSON_PRETTY_PRINT);
		}
		
	}

}

/* End of file ScheduleController.php */
/* Location: ./application/controllers/ScheduleController.php */