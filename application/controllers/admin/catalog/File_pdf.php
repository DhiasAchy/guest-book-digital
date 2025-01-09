<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File_pdf extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('admin');
        }

        $this->load->library('form_validation');
        $this->load->model('Model_catalog_file_pdf');
        $this->load->model('Model_catalog_company');
        $this->load->model('Model_catalog_brand');
    }
    
    // default
    public function index()
    {
        $data['pdf'] =  $this->Model_catalog_file_pdf->getall();
        $this->template->admin('admin/catalog/file_pdf/view',$data);
    }
    
    // open action
    public function add()
    {
        $data['pdf']     =  $this->Model_catalog_file_pdf->getall();
        $data['company'] =  $this->Model_catalog_company->getall();
        $data['brand']   =  $this->Model_catalog_brand->getall();
        $this->template->admin('admin/catalog/file_pdf/add',$data);
    }
    public function edit($id = null)
    {
        if (!empty($id)) {
            $data['pdf'] = $this->db->get_where('app_catalog', ['id' => $id])->row_array();
            $this->template->admin('admin/catalog/file_pdf/edit',$data);
        }
    }
}
?>