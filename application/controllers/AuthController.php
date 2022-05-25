<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Auth','auth');
	}

	public function index()
	{
		if ($this->session->userdata('user')) {
			redirect('/dashboard');
		}
		$data = [
			'title' => 'Log In'
		];
		$this->load->view('auth', $data);
	}

	public function check_auth()
	{
		$form = json_decode(file_get_contents("php://input"));
		if (empty($form->username) || empty($form->password)) {
			echo json_encode([
				'error' => 'Username dan Password Wajib Diisi'
			],JSON_PRETTY_PRINT);
		} else {
			$username = $form->username;
			$password = $form->password;
			$auth = $this->auth->check_user($username,$password);
			if ($auth) {
				if ($auth->active == 1) {
					$this->session->set_userdata('user',$this->auth->get_auth($username,$password));
					$this->session->set_flashdata('success', "Berhasil Login, Selamat Datang ".$this->session->userdata('user')->nama." !!");
					echo json_encode([
						'success' => 'Berhasil Login',
						'session' => $this->session->userdata('user')
					],JSON_PRETTY_PRINT);
				} else {
					echo json_encode([
						'error' => 'Akun anda tidak diaktifkan. Harap menghubungi pihak administrator !!'
					],JSON_PRETTY_PRINT);
				}
			} else {
				echo json_encode([
					'error' => 'Username atau Password Tidak Ditemukan !!'
				],JSON_PRETTY_PRINT);
			}
		}
	}

	public function logout()
	{
		// $this->session->sess_destroy();
		$this->session->unset_userdata('user');
		redirect('login');
	}

	public function manage()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->session->set_flashdata('error', 'Peringatan: Anda tidak memiliki akses untuk ini !!');
			redirect('/');
		}
		$data = [
			'title' => 'Authorization',
			'nav_title' => 'Authorization Manager',
			'lists' => $this->auth->deptLists()
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('access/access', $data);
		$this->load->view('components/footer', $data);
	}

	public function dt_auth()
	{
		$lists = $this->auth->datatable_acc();
		$data = [];
		$no = $_POST['start'];
		foreach ($lists as $list) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $list->nama;
			$row[] = $list->username;
			$row[] = $list->dept_name;
			$row[] = ($list->active == 1) ? '<span class="badge badge-pill badge-primary shadow">Aktif</span>' : '<span class="badge badge-pill badge-secondary shadow">Tidak Aktif</span>';
			$row[] = '<div class="btn-group btn-group-sm shadow-sm text-xs border-0 d-inline-flex" role="group">
			<button type="button" class="btn btn-sm btn-warning btn-edit" data-id="'.$list->id.'" onclick="angular.element(this).scope().edit('.$list->id.')"><i class="fas fa-fw fa-pen text-xs"></i> <span class="text-xs">Edit</span></button>
			<button type="button" class="btn btn-sm btn-danger btn-delete" data-id="'.$list->id.'" onclick="angular.element(this).scope().delete('.$list->id.')"><i class="fas fa-fw fa-trash text-xs"></i> <span class="text-xs">Hapus</span></button>
			</div>';

			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->auth->count_all_acc(),
			"recordsFiltered" => $this->auth->count_filtered_acc(),
			"data" => $data,
		];
		echo json_encode($output);
	}

	public function get_by_id_acc($id)
	{
		echo json_encode($this->auth->get_by_id_acc($id),JSON_PRETTY_PRINT);
	}

	public function saveAccount()
	{
		$error = [];
		$form = json_decode(file_get_contents("php://input"));
		if (empty($form->username) || empty($form->password)) {
			if (empty($form->username)) {
				$error[] = "Username Wajib Diisi";
			}
			if (empty($form->password)) {
				$error[] = "Password Wajib Diisi";
			}
			if (empty($form->nama)) {
				$error[] = "Untuk Nama Sebaiknya Diisi";
			}
			echo json_encode([
				"error" => $error
			],JSON_PRETTY_PRINT);
		} else {
			$data = [
				"auth_dept_id" => $form->auth_dept_id,
				"nama" => $form->nama,
				"username" => $form->username,
				"password" => $form->password,
				"active" => ($form->active == TRUE) ? 1 : 0,
				"is_spv" => ($form->is_spv == TRUE) ? 1 : 0
			];
			if (empty($form->id)) {
				$add = $this->auth->create_account($data);
				if ($add) {
					echo json_encode([
						"success" => "Data $form->username Berhasil Ditambahkan"
					],JSON_PRETTY_PRINT);
				} else {
					echo json_encode([
						"error" => "Gagal Menambahkan Data $form->username"
					],JSON_PRETTY_PRINT);
				}
			} else {
				$edit = $this->auth->update_account($form->id,$data);
				if ($edit) {
					echo json_encode([
						"success" => "Data $form->username Berhasil Diperbarui"
					],JSON_PRETTY_PRINT);
				} else {
					echo json_encode([
						"error" => "Gagal Memperbarui Data $form->username"
					],JSON_PRETTY_PRINT);
				}
			}
		}
	}

	public function deleteAccount($id)
	{
		$delete = $this->auth->delete_account($id);
		if ($delete) {
			echo json_encode([
				"success" => "Data Berhasil Dihapus"
			],JSON_PRETTY_PRINT);
		} else {
			echo json_encode([
				"error" => "Gagal Menghapus Data"
			],JSON_PRETTY_PRINT);
		}
	}

	public function checkUsername($username)
	{
		if ($this->auth->checkUsername($username)) {
			echo json_encode([
				"error" => "Username $username Telah Digunakan"
			],JSON_PRETTY_PRINT);
		} else {
			echo json_encode([
				"success" => "Username $username Bisa Digunakan"
			],JSON_PRETTY_PRINT);
		}
	}

}

/* End of file AuthController.php */
/* Location: ./application/controllers/AuthController.php */