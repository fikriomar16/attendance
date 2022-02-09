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
			// 'tier1' => $this->db->select('*')->from('acc_transaction')->get()->result(),
			'tier_2a' => $this->db->select('*')->from('acc_transaction_2a')->get()->result(),
			'tier_2c' => $this->db->select('*')->from('acc_transaction_2c')->get()->result(),
			'tier_3a' => $this->db->select('*')->from('acc_transaction_3a')->get()->result(),
			'tier_3a' => $this->db->select('*')->from('acc_transaction_3c')->get()->result()
		];
	}

	public function reset_table($table)
	{
		return $this->db->truncate($table);
	}

	public function truncate_all()
	{
		// $this->db->truncate('acc_transaction');
		$this->db->truncate('acc_transaction_2a');
		$this->db->truncate('acc_transaction_2c');
		$this->db->truncate('acc_transaction_3a');
		$this->db->truncate('acc_transaction_3c');
		return [
			'msg' => 'All Tables Truncated'
		];
	}

}

/* End of file Testing.php */
/* Location: ./application/models/Testing.php */