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
			$row[] = '<button type="button" class="btn btn-primary btn-sm btn-sch shadow-sm" data-id="'.$emp->pin.'" onclick="angular.element(this).scope().getsch('.$emp->pin.')"><i class="fas fa-fw fa-calendar-alt"></i></button>';

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
		$form = json_decode(file_get_contents("php://input"));
		if (empty($form->nik) || empty($form->shift) || empty($form->masuk) || empty($form->pulang)) {
			echo json_encode([
				'error' => 'Seluruh Data Wajib Diisi'
			],JSON_PRETTY_PRINT);
		} else {
			if (empty($form->id)) {
				$add = $this->schedule->create_schedule([
					'nik' => $form->nik,
					'nama' => $this->schedule->get_by_nik_employee($form->nik)->name,
					'shift' => $form->shift,
					'tanggal' => date('Y-m-d',strtotime($form->masuk)),
					'masuk' => date('Y-m-d H:i:s',strtotime($form->masuk)),
					'pulang' => date('Y-m-d H:i:s',strtotime($form->pulang)),
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
					'shift' => $form->shift,
					'tanggal' => date('Y-m-d',strtotime($form->masuk)),
					'masuk' => date('Y-m-d H:i:s',strtotime($form->masuk)),
					'pulang' => date('Y-m-d H:i:s',strtotime($form->pulang)),
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

}

/* End of file ScheduleController.php */
/* Location: ./application/controllers/ScheduleController.php */