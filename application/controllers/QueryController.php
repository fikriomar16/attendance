<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QueryController extends CI_Controller {

	public function index()
	{
		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		if ($this->session->userdata('user')->is_spv != 1) {
			$this->session->set_flashdata('error', 'Peringatan: Anda tidak memiliki akses untuk ini !!');
			redirect('/');
		}
		$data = [
			'title' => 'Import SQL File',
			'nav_title' => 'IMPORT SQL FILE'
		];
		$this->load->view('components/header', $data);
		$this->load->view('components/sidebar', $data);
		$this->load->view('components/topbar', $data);
		$this->load->view('query', $data);
		$this->load->view('components/footer', $data);
	}

	public function importSQL()
	{
		$config['upload_path'] = './assets/import/';
		$config['allowed_types'] = 'sql';
		$config['max_size']  = '4096';
		$config['overwrite'] = true;
		$config['file_name'] = 'importsql';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('import_sql')){
			echo json_encode([
				'error' => $this->upload->display_errors(),
				'mime' => $this->upload->data('file_type')
			],JSON_PRETTY_PRINT);
		}
		else{
			$data = [
				'upload_data' => $this->upload->data()
			];
			$filename = $data['upload_data']['file_name'];
			$extension = $data['upload_data']['file_ext'];
			$filePath = $data['upload_data']['full_path'];
			$res = file_get_contents($filePath);
			unlink($filePath);
			echo json_encode([
				'success' => "Berhasil Mengimport File $extension",
				'data' => $res
			],JSON_PRETTY_PRINT);
		}
	}

	public function processImportSQL()
	{
		$form = file_get_contents("php://input");
		if ($form) {
			$q = $this->db->query($form);
			if ($q) {
				$query = $q->result();
				echo json_encode([
					'success' => 'Berhasil Mengeksekusi Query',
					'query' => $query ?? $q
				],JSON_PRETTY_PRINT);
			} else {
				echo json_encode([
					'success' => 'Gagal Mengeksekusi Query',
					'query' => $q
				],JSON_PRETTY_PRINT);
			}
		} else {
			echo json_encode([
				'error' => 'Error, No data found.',
				'form' => $form
			],JSON_PRETTY_PRINT);
		}
	}

}

/* End of file QueryController.php */
/* Location: ./application/controllers/QueryController.php */