<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom404 extends CI_Controller {

	public function index()
	{
		$this->output->set_status_header('404');
		$this->load->view('404page');
	}

}

/* End of file Custom404.php */
/* Location: ./application/controllers/Custom404.php */