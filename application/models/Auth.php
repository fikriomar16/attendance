<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Model {

	public function check_user($username,$password)
	{
		return $this->db->get_where('sys_users',[
			'username' => $username,
			'password' => $password
		])->row();
	}

}

/* End of file Auth.php */
/* Location: ./application/models/Auth.php */