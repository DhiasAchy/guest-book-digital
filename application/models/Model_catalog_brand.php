<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_catalog_brand extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getall()
	{
		return	$query = $this->db
			->select('*')
			->from('app_brand')
			->where('deleted_at', NULL)
			->order_by('position', 'asc')
			->get()
			->result_array();
	}
	
	public function getbyid($id=null)
	{
		return	$query = $this->db
			->select('*')
			->from('app_brand')
			->where('deleted_at', NULL)
			->where('id',$id)
			->order_by('id', 'desc')
			->get()
			->result_array();
	}
	
	public function getfield_byid($id=null,$field)
	{
		return	$query = $this->db
			->select($field)
			->from('app_brand')
			->where('deleted_at', NULL)
			>where('id', $id)
			->order_by('id', 'desc')
			->get()
			->result_array();
	}
	
	public function getbyslug($slug)
	{
		return	$query = $this->db
			->select('*')
			->from('app_brand')
			->where('deleted_at', NULL)
			->where('slug', $slug)
			->get()
			->row_array();
	}

}
