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
			redirect('/');
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
				$this->session->set_userdata('user',$auth);
				echo json_encode([
					'success' => 'Berhasil Login',
					'session' => $this->session->userdata('user')
				],JSON_PRETTY_PRINT);
			} else {
				echo json_encode([
					'error' => 'Username atau Password Tidak Ditemukan'
				],JSON_PRETTY_PRINT);
			}
		}
	}

	public function logout($value='')
	{
		// $this->session->sess_destroy();
		$this->session->unset_userdata('user');
		redirect('login');
	}

}

/* End of file AuthController.php */
/* Location: ./application/controllers/AuthController.php */