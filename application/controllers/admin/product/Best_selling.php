<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Best_selling extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('admin');
        }

        $this->load->library('form_validation');

        $this->load->model('Model_collection');
        $this->load->model('Model_brand');
    }
    public function index()
    {
        $this->db->select('best_selling.*,product.product,product.image,brand.brand,collection.collection');
        $this->db->join('product','product.product_id=best_selling.product_id');
        $this->db->join('brand','product.brand_id=brand.brand_id');
        $this->db->join('collection','brand.collection_id=collection.collection_id');
        $data['best_selling']      = $this->db->get('best_selling')->result_array();
        $this->template->admin('admin/product/best_selling/view',$data);
        // $this->load
        //     ->layout(false)
        //     ->view('admin/product/best_selling/view', $data);
    }

    // View action
    public function add()
    {
        $data['product'] =  $this->Model_product->getall();
        $this->template->admin('admin/product/best_selling/add',$data);
        // $this->load
        // ->layout(false)
        // ->view('admin/product/best_selling/add', $data);
    }
    
    public function edit($id = null)
    {
        if (!empty($id)) {
            $data['product'] =  $this->Model_product->getall();
            $data['best_selling'] =  $this->db->get_where('best_selling',['best_selling_id' => $id])->row_array();
            $this->template->admin('admin/product/best_selling/edit',$data);
            // $this->load
            //     ->layout(false)
            //     ->view('admin/product/best_selling/edit', $data);
        }
    }

    // Action


    public function insert()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('product_id', 'Product', 'trim|required')
            ->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            $this->db->insert('best_selling', ['product_id' => $post['product_id']]);
            $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/product/best_selling')];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function update()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('best_selling_id', 'Best Selling ID', 'trim|required')
            ->set_rules('product_id', 'Product', 'trim|required')
            ->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            $this->db->update('best_selling', ['link_ecommerce' => $post['link'],'product_id' => $post['product_id']],['best_selling_id' => $post['best_selling_id']]);
            $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/product/best_selling')];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function delete($id = null)
    {
        $data = array(
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('brand', $data, array('best_selling_id' => $id));
        $json['success'] = 1;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}
