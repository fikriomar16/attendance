<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Model {

	public function _get_datatable_duration()
	{
		$table = 'sys_duration';
		$order = ['departement_id' => 'asc'];
		$column_order = [null,'departement_id','late_allowed','out_allowed'];
		$column_search = ['sys_departements.name','CAST(late_allowed as varchar)','CAST(out_allowed as varchar)'];
		$this->db->from($table)->join('sys_departements', 'sys_duration.departement_id = sys_departements.id', 'left');
		$i = 0;
		foreach ($column_search as $item) // loop column
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($column_search) - 1 == $i) //last loop
				{
					$this->db->group_end(); //close bracket
				}
			}
			$i++;
		}
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($order))
		{
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	public function datatable_duration()
	{
		$this->_get_datatable_duration();
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		return $this->db->get()->result();
	}
	public function count_filtered_duration()
	{
		$this->_get_datatable_duration();
		return $this->db->get()->num_rows();
	}
	public function count_all_duration()
	{
		return $this->db->from('sys_duration')->join('sys_departements', 'sys_duration.departement_id = sys_departements.id', 'left')->count_all_results();
	}
	public function get_by_id_duration($id)
	{
		return $this->db->get_where('sys_duration',[
			'id' => $id
		])->get()->row();
	}

}

/* End of file Setup.php */
/* Location: ./application/models/Setup.php */