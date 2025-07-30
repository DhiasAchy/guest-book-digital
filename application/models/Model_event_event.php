<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_event_event extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getall()
	{
		return	$query = $this->db
			->select('*')
			->from('app_event')
			->where('deleted_at', NULL)
			->order_by('created_at', 'asc')
			->get()
			->result_array();
	}
	public function getbyid($id=null)
	{
		return	$query = $this->db
			->select('*')
			->from('app_event')
			->where('deleted_at', NULL)
			->where('id', $id)
			->order_by('created_at', 'asc')
			->get()
			->row_array();
	}
}

?>