<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_catalog_company extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getall()
	{
		return	$query = $this->db
			->select('*')
			->from('app_company')
			->where('deleted_at', NULL)
			->order_by('position', 'asc')
			->get()
			->result_array();
	}

}
