<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Import extends CI_Controller
    {
    
    	/**
    	 * Index Page for this controller.
    	 *
    	 * Maps to the following URL
    	 * 		http://example.com/index.php/welcome
    	 *	- or -
    	 * 		http://example.com/index.php/welcome/index
    	 *	- or -
    	 * Since this controller is set as the default controller in
    	 * config/routes.php, it's displayed at http://example.com/
    	 *
    	 * So any other public methods not prefixed with an underscore will
    	 * map to /index.php/welcome/<method_name>
    	 * @see https://codeigniter.com/user_guide/general/urls.html
    	 */
    	public function __construct()
    	{
    		parent::__construct();
    		$this->load->library('form_validation');
    		$this->load->helper('string');
			$this->load->model('Model_import');
    	}
    	
    	public function index() 
    	{
    	    // load data achievement
    	    $data['page_title'] = "Guest Book - Digital";
    		$data['meta_title'] = "Guest Book - Digital - Wirawan Corp"; 
    		$data['meta_keywords'] = "guest book, aplication, wirawancorp";
    		$data['meta_description'] = ""; 
			$data['data'] = $this->Model_import->getData();
    		$this->load->view('page_import', $data);
    	}
    }
?>