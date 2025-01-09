<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('admin');
        }

        $this->load->library('form_validation');
        $this->load->model('Model_user_sales');
        $this->load->model('Model_catalog_brand');
    }
    
    // default
    public function index()
    {
        $data['sales'] =  $this->Model_user_sales->getall();
        $this->template->admin('admin/user/sales/view',$data);
    }
    
    // open action
    public function add()
    {
        $data['sales'] =  $this->Model_user_sales->getall();
        $data['brand'] =  $this->Model_catalog_brand->getall();
        $this->template->admin('admin/user/sales/add',$data);
    }
    public function edit($id = null)
    {
        if (!empty($id)) {
            $data['sales'] = $this->db->get_where('app_sales', ['id' => $id])->row_array();
            $data['brand'] =  $this->Model_catalog_brand->getall();
            $this->template->admin('admin/user/sales/edit',$data);
        }
    }
    
    // action
    public function insert()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('code', 'Kode', 'trim|required')
            ->set_rules('name', 'Nama', 'trim|required')
            ->set_rules('phone', 'Telepon', 'trim|required')
            ->set_error_delimiters('', '');
    
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            $list_code = function () {
                $code = $this->db->get_where('app_sales', ['deleted_at' => null])->result_array();
                return array_map('strtolower', array_column($code, 'code'));
            };
            $list_phone = function () {
                $phone = $this->db->get_where('app_sales', ['deleted_at' => null])->result_array();
                return array_map('strtolower', array_column($phone, 'phone'));
            };
            
            if (in_array(strtolower($post['code']), $list_code())) {
                $json = ['error' => true, 'msg' => 'Code already exists ', 'redirect' => base_url('admin/user/sales')];
            } else if (in_array(strtolower($post['code']), $list_phone())) {
                $json = ['error' => true, 'msg' => 'Phone already exists ', 'redirect' => base_url('admin/user/sales')];
            } else {
                $data = [
                    'code' => $post['code'],
                    'name' => $post['name'],
                    'phone' => $post['phone'],
                    'brand_id' => json_encode($post['brand']),
                    'user_id' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('app_sales', $data);
                $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/user/sales')];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function update()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('code', 'Kode', 'trim|required')
            ->set_rules('name', 'Nama', 'trim|required')
            ->set_rules('phone', 'Telepon', 'trim|required')
            ->set_error_delimiters('', '');
    
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            // cek
            $id = $post['id'];
            $cek = $this->db->get_where('app_sales', ['deleted_at' => null, 'code' => $post['code']])->row_array();
            
            if($cek['id']==$post['id']) {
                $data = [
                    'code' => $post['code'],
                    'name' => $post['name'],
                    'phone' => $post['phone'],
                    'brand_id' => json_encode($post['brand']),
                    'user_id' => $this->session->userdata('user_id'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $this->db->update('app_sales', $data,['id' => $post['id']]);
                $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/user/sales')];
            } else {
                $json = ['error' => true, 'msg' => 'code already exists ', 'redirect' => base_url('admin/user/sales')];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
  }
    public function delete($id = null)
    {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('app_sales', $data, array('id' => $id));
        ($this->db->affected_rows() > 0) ? $return = TRUE : $return = FALSE; 
        
        if($return==TRUE) {
            $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/user/sales')];
        } else {
            $json = ['error' => true, 'msg' => 'data saved failed', 'redirect' => base_url('admin/user/sales')];
        }
        // $json['success'] = 1;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
}
?>