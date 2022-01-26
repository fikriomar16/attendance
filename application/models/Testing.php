<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}

	public function test()
	{
		return [
			'tier1' => $this->db->select('*')->from('acc_transaction')->get()->result(),
			'tier2' => $this->db->select('*')->from('filter_transaction')->get()->result(),
			'tier3' => $this->db->select('*')->from('final_transaction')->get()->result()
		];
	}

	public function reset_table($table)
	{
		return $this->db->truncate($table);
	}

	public function truncate_all()
	{
		$this->db->truncate('acc_transaction');
		$this->db->truncate('filter_transaction');
		$this->db->truncate('final_transaction');
		return [
			'msg' => 'All Tables Truncated'
		];
	}

}

/* End of file Testing.php */
/* Location: ./application/models/Testing.php */