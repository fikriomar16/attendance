<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetupController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$locale = 'id_ID.utf8';
		setlocale(LC_ALL, $locale);
		$this->load->model('Setup','setup');
	}

	public function index()
	{
		redirect('/');
	}

	public function duration()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Duration Setup',
			'nav_title' => 'Duration Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('setup/duration', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_duration()
	{
		$list = $this->setup->datatable_duration();
		$data = [];
		$no = $_POST['start'];
		foreach ($list as $dur) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $dur->name;
			$row[] = $dur->late_allowed;
			$row[] = $dur->out_allowed;
			$row[] = $dur->out_allowed_friday;
			$row[] = $dur->out_allowed_saturday;
			$row[] = '<div class="btn-group btn-group-sm shadow-sm border-0" role="group">
			<button type="button" class="btn btn-primary btn-edit" data-id="'.$dur->id.'" onclick="angular.element(this).scope().edit('.$dur->id.')"><i class="fas fa-fw fa-pen"></i></button>
			<button type="button" class="btn btn-danger btn-delete" data-id="'.$dur->id.'" onclick="angular.element(this).scope().delete('.$dur->id.')"><i class="fas fa-fw fa-trash"></i></button>
			</div>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->setup->count_all_duration(),
			"recordsFiltered" => $this->setup->count_filtered_duration(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function deptList()
	{
		$list = $this->setup->checkDept();
		echo json_encode($list,JSON_PRETTY_PRINT);
	}
	public function deptListExcept($id)
	{
		$list = $this->setup->checkDeptExcept($id);
		echo json_encode($list,JSON_PRETTY_PRINT);
	}
	public function get_by_id_duration($id)
	{
		$data = $this->setup->get_by_id_duration($id);
		echo json_encode($data,JSON_PRETTY_PRINT);
	}
	public function saveDuration()
	{
		$form = json_decode(file_get_contents("php://input"));
		$error = [];
		if (empty($form->auth_dept_id) || empty($form->late_allowed) || empty($form->out_allowed)) {
			if (empty($form->auth_dept_id)) {
				$error[] = "Departement Wajib Dipilih";
			}
			if (empty($form->late_allowed)) {
				$error[] = "Batas Keterlambatan Wajib Diisi";
			}
			if (empty($form->out_allowed)) {
				$error[] = "Batas Durasi di Luar Wajib Diisi";
			}
			echo json_encode([
				'error' => $error
			],JSON_PRETTY_PRINT);
		} else {
			$insert = [
				'auth_dept_id' => $form->auth_dept_id,
				'late_allowed' => date('H:i:s',strtotime($form->late_allowed)),
				'out_allowed' => date('H:i:s',strtotime($form->out_allowed)),
			];
			if (empty($form->out_allowed_friday)) {
				$insert['out_allowed_friday'] = date('H:i:s',strtotime($form->out_allowed));
			} else {
				$insert['out_allowed_friday'] = date('H:i:s',strtotime($form->out_allowed_friday));
			}
			if (empty($form->out_allowed_saturday)) {
				$insert['out_allowed_saturday'] = date('H:i:s',strtotime($form->out_allowed));
			} else {
				$insert['out_allowed_saturday'] = date('H:i:s',strtotime($form->out_allowed_saturday));
			}
			if (empty($form->id)) {
				$add = $this->setup->create_duration($insert);
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
				$edit = $this->setup->update_duration($form->id,$insert);
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
	public function deleteDuration($id)
	{
		$delete = $this->setup->delete_duration($id);
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

	public function shift()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Shift Setup',
			'nav_title' => 'Shift Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('setup/shift', $data);
		$this->load->view('components/footer', $data);
	}
	public function dt_shift()
	{
		$lists = $this->setup->datatable_shift();
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = [];
			$row[] = '<h6 class="text-center">'.$no.'</h6>';
			$row[] = '<h6 class="text-center">'.$list->shift_code.'</h6>';
			$row[] = '<h5 class="text-center"><span class="badge badge-pill badge-success">'.$list->work_time.'</span></h5>';
			$row[] = '<h5 class="text-center"><span class="badge badge-pill badge-primary">'.$list->work_start.'</span></h5>';
			$row[] = '<h5 class="text-center"><span class="badge badge-pill badge-primary">'.$list->work_end.'</span></h5>';
			$row[] = '<h5 class="text-center"><span class="badge badge-pill badge-info">'.$list->out_allowed.'</span></h5>';
			$row[] = '<div class="btn-group btn-group-sm shadow-sm border-0 ml-3 mr-0" role="group">
			<button type="button" class="btn btn-primary btn-edit" data-id="'.$list->id.'" onclick="angular.element(this).scope().edit('.$list->id.')"><i class="fas fa-fw fa-pen"></i></button>
			<button type="button" class="btn btn-danger btn-delete" data-id="'.$list->id.'" onclick="angular.element(this).scope().delete('.$list->id.')"><i class="fas fa-fw fa-trash"></i></button>
			</div>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->setup->count_all_shift(),
			"recordsFiltered" => $this->setup->count_filtered_shift(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	public function get_by_id_shift($id)
	{
		$data = $this->setup->get_by_id_shift($id);
		echo json_encode($data,JSON_PRETTY_PRINT);
	}
	public function deleteShift($id)
	{
		$delete = $this->setup->delete_shift($id);
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
	public function saveShift()
	{
		$form = json_decode(file_get_contents("php://input"));
		$error = [];
		if (empty($form->shift_code) || empty($form->work_start) || empty($form->work_end) || empty($form->work_time)) {
			if (empty($form->shift_code)) {
				$error[] = "Kode Shift Harus Diisi";
			}
			if (empty($form->work_time)) {
				$error[] = "Durasi Jam Kerja Harus Diisi";
			}
			if (empty($form->work_start)) {
				$error[] = "Jam Mulai Kerja Harus Diisi";
			}
			if (empty($form->work_end)) {
				$error[] = "Jam Selesai Kerja Harus Diisi";
			}
			echo json_encode([
				'error' => $error
			],JSON_PRETTY_PRINT);
		} else {
			$wTime = date_create($form->work_time);
			$wStart = date_create($form->work_start);
			$wEnd = date_create($form->work_end);
			$insert = [
				'shift_code' => $form->shift_code,
				'work_time' => date('H:i:s',strtotime($form->work_time)),
				'work_start' => date('H:i:s',strtotime($form->work_start)),
				'work_end' => date('H:i:s',strtotime($form->work_end)),
			];
			if (empty($form->id)) {
				$add = $this->setup->create_shift($insert);
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
				$edit = $this->setup->update_shift($form->id,$insert);
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
	public function importDWS()
	{
		$config['upload_path'] = './assets/import/';
		$config['allowed_types'] = 'csv|xls|xlsx';
		$config['max_size']  = '4096';
		$config['overwrite'] = true;
		$config['file_name'] = 'importDWS';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('import_dws')){
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
			foreach ($sheetData as $row) {
				$collect = [];
			}
			unlink($filePath);
			echo json_encode([
				'success' => "Berhasil Mengimport File $extension",
				'result' => $sheetData,
				'collect' => $collects
			],JSON_PRETTY_PRINT);
		}
	}

}

/* End of file SetupController.php */
/* Location: ./application/controllers/SetupController.php */