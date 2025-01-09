<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  public function __construct()
  {
      parent::__construct();      
      if($this->session->userdata('logged_in') != 1){
        redirect('admin/login');
      }
  }
	public function index()
	{
        $this->template->admin('admin/dashboard');
	}
}
