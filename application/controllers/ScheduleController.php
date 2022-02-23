<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
		redirect('/');
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
			'date' => date("Y-m-d")
		]);
		echo json_encode([
			'getSchName' => $this->schedule->get_by_nik_employee($nik)->name ?? '',
			'getSchDate' => strftime('%A, %d %B %Y', strtotime($this->session->userdata('employee_sch')['date'])),
			'schId' => $nik
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
			'nav_title' => 'Employee Schedule Manager'
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
			$row[] = $emp->name_spell;
			$row[] = $emp->pin;
			$row[] = $emp->name;
			$row[] = '<button type="button" class="btn btn-primary btn-sm btn-sch shadow-sm" data-id="'.$emp->pin.'" onclick="angular.element(this).scope().getsch('.$emp->pin.')"><i class="fas fa-fw fa-calendar-alt"></i> Schedule</button>';

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
		if (empty($form->nik) || empty($form->masuk) || empty($form->pulang)) {
			if (empty($form->nik)) {
				$error[] = "Karyawan Wajib Dipilih";
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
			if (empty($form->id)) {
				$add = $this->schedule->create_schedule([
					'nik' => $form->nik,
					'nama' => $this->schedule->get_by_nik_employee($form->nik)->name,
					'shift' => date('H',strtotime($form->masuk)).'-'.date('H',strtotime($form->pulang)),
					'tanggal' => date('Y-m-d',strtotime($form->masuk)),
					'masuk' => date('Y-m-d H:i:s',strtotime($form->masuk)),
					'pulang' => date('Y-m-d H:i:s',strtotime($form->pulang)),
					'sub_masuk' => date('Y-m-d H:i:s',strtotime($form->masuk.$subMasuk)),
					'sub_pulang' => date('Y-m-d H:i:s',strtotime($form->pulang.$subPulang)),
				]);
				if ($add) {
					echo json_encode([
						'success' => 'Data Berhasil Ditambahkan'
					],JSON_PRETTY_PRINT);
				} else {
					echo json_encode([
						'error' => 'Terjadi Kesalahan'
					],JSON_PRETTY_PRINT);
				}
			} else {
				$edit = $this->schedule->update_schedule($form->id,[
					'shift' => date('H',strtotime($form->masuk)).'-'.date('H',strtotime($form->pulang)),
					'tanggal' => date('Y-m-d',strtotime($form->masuk)),
					'masuk' => date('Y-m-d H:i:s',strtotime($form->masuk)),
					'pulang' => date('Y-m-d H:i:s',strtotime($form->pulang)),
					'sub_masuk' => date('Y-m-d H:i:s',strtotime($form->masuk.$subMasuk)),
					'sub_pulang' => date('Y-m-d H:i:s',strtotime($form->pulang.$subPulang)),
				]);
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
		$title = 'Template_Import_Jadwal';
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=".$title.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		$handle = fopen('php://output', 'w');
		$head = ['NIK','Nama','Shift','Tanggal','Tanggal Masuk','Jam Masuk','Tanggal Pulang','Jam Pulang'];
		fputcsv($handle, $head);
		fclose($handle);
		exit;
	}

	public function importSchCSV()
	{
		$path = './assets/import/';
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'csv|xls|xlsx';
		$config['max_size']  = '4096';
		$config['overwrite'] = true;
		$config['file_name'] = 'importschedule';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('import_sch')){
			echo json_encode([
				'error' => $this->upload->display_errors()
			]);
		}
		else {
			$data = [
				'upload_data' => $this->upload->data()
			];
			$filename = $data['upload_data']['file_name'];
			$extension = $data['upload_data']['file_ext'];
			if ($extension == '.csv') {
				// make sure its csv file
				$handle = fopen($path.$filename, "r");
				$head = ['NIK','Nama','Shift','Tanggal','Tanggal Masuk','Jam Masuk','Tanggal Pulang','Jam Pulang'];
				$i = 0;
				$result = [];
				$collects = [];
				$strep = "/";
				while (($row = fgetcsv($handle, 4096, ",")) != FALSE) {
					// $row[3] = date('Y-m-d',strtotime(str_replace($strep, "-", $row[3])));
					// $row[4] = date('Y-m-d',strtotime(str_replace($strep, "-", $row[4])));
					// $row[5] = date('H:i:s',strtotime($row[5]));
					// $row[6] = date('Y-m-d',strtotime(str_replace($strep, "-", $row[6])));
					// $row[7] = date('H:i:s',strtotime($row[7]));
					$result[] = $row;
					$collect = [];
					$collect = [
						'nik' => $row[0],
						'nama' => $row[1],
						'shift' => $row[2],
						'tanggal' => date('Y-m-d',strtotime(str_replace($strep, "-", $row[3]))),
						'masuk' => date('Y-m-d',strtotime(str_replace($strep, "-", $row[4]))).' '.date('H:i:s',strtotime($row[5])),
						'pulang' => date('Y-m-d',strtotime(str_replace($strep, "-", $row[6]))).' '.date('H:i:s',strtotime($row[7]))
					];
					$collects[] = $collect;
				}
				unset($result[0]);
				unset($collects[0]);
				fclose($handle);

				unlink($path.$filename);
				echo json_encode([
					'success' => 'Berhasil Mengimport CSV',
					'result' => $result,
					'collect' => $collects
				]);
			}
			if ($extension == '.xls' || $extension == '.xlsx') {
				// make sure its xls or xlsx file
				unlink($path.$filename);
				echo json_encode([
					'success' => 'Berhasil Mengimport Excel File'
				]);
			}
		}
	}

}

/* End of file ScheduleController.php */
/* Location: ./application/controllers/ScheduleController.php */