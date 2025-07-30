<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_catalog_file_pdf extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getall()
	{
		return	$query = $this->db
			->select('*')
			->from('app_catalog')
			->where('deleted_at', NULL)
			->order_by('id', 'desc')
			->get()
			->result_array();
	}
	
	public function getbyid($id=null)
	{
		return	$query = $this->db
			->select('*')
			->from('app_catalog')
			->where('deleted_at', NULL)
			->where('id',$id)
			->order_by('id', 'desc')
			->get()
			->result_array();
	}
	
	public function get_catalog_by_brand($id=null)
	{
	    return	$query = $this->db
			->select('*')
			->from('app_catalog')
			->where('deleted_at', NULL)
			->where('brand_id',$id)
			->order_by('id', 'desc')
			->get()
			->result_array();
	}
	
	public function getall_join($id = null, $company_id = null, $brand_id = null)
	{
	   $this->db
			->select('app_catalog.*,app_company.name,app_brand.name')
			->from('app_catalog')
			->join('app_company', 'app_catalog.company_id=app_company.id')
			->join('app_brand', 'app_catalog.brand_id=app_brand.id')
			->where('app_catalog.deleted_at', null)
			->where('app_catalog.deleted_at', null);
		return $this->db->get();
	}

}
