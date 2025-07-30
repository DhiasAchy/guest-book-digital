<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_achievement extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getall()
	{
		return	$query = $this->db
			->select('*')
			->from('app_achievement')
			->where('deleted_at', NULL)
			->order_by('id', 'desc')
			->get()
			->result_array();
	}
	
	public function getall_with_limit()
	{
		return	$query = $this->db
			->select('*')
			->from('app_achievement')
			->where('deleted_at', NULL)
			->limit(4,2)
			->order_by('id', 'desc')
			->get()
			->result_array();
	}

	public function getbyid($id = null)
	{
		return	$query = $this->db
			->select('*')
			->from('app_achievement')
			->where('deleted_at', NULL)
			->where('id', $id)
			->get()
			->row_array();
	}

	public function userlogin($username, $password = null)
	{
		return $this->db
			->select('*')
			->from('app_achievement')
			->where('deleted_at', NULL)
			->where('username', $username)
			->where('password', $password)
			->get()
			->result_array();
	}
}
