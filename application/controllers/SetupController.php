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

	public function menu()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$data = [
			'title' => 'Menu Setup',
			'nav_title' => 'Menu Manager'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('setup/menu', $data);
		$this->load->view('components/footer', $data);
	}

}

/* End of file SetupController.php */
/* Location: ./application/controllers/SetupController.php */