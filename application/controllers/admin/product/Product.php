<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('admin');
        }

        $this->load->library('form_validation');

        $this->load->model('Model_brand');
        $this->load->model('Model_product');
    }
    public function index()
    {
        $data['product']      =  $this->Model_product->getall_join_with_brand(null, null, null, 'DESC')->result_array();
        $this->template->admin('admin/product/product/view',$data);
        // $this->load
        //     ->layout(false)
        //     ->view('admin/product/product/view', $data);
    }

    // View action
    public function add()
    {
        $data['brand'] =  $this->Model_brand->getall_join_with_collection()->result_array();
        $this->template->admin('admin/product/product/add',$data);
        // $this->load
        //     ->layout(false)
        //     ->view('admin/product/product/add', $data);
    }

    public function edit($id = null)
    {
        if (!empty($id)) {
            $data['brand']       =  $this->Model_brand->getall_join_with_collection()->result_array();
            $data['product']     =  $this->Model_product->getall_join_with_brand($id, null, null, 'DESC')->row_array();
            $this->template->admin('admin/product/product/edit',$data);
        //    $this->load
        //         ->layout(false)
        //         ->view('admin/product/product/edit', $data);
        }
    }

    // Action


    public function insert()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('product', 'Product', 'trim|required')
            ->set_rules('brand_id', 'Brand', 'trim|required')
            ->set_rules('description', 'Description', 'trim|required')
            ->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            _mkdir();

            $slug = function ($title) {
                $check = $this->db->get('product')->result_array();
                $get_all_slug = array_column($check, 'slug');
                $toLowerTitle = url_title($title, 'dash', true);
                $x = 0;
                if (in_array($toLowerTitle, $get_all_slug)) :
                    $x += 1;
                endif;
                $slug = $toLowerTitle;
                if ($x > 0) :
                    $slug = $toLowerTitle . '-' . $x;
                endif;
                return $slug;
            };
            if (!empty($_FILES['image']['name'])) {
                _mkdir();
                $config['upload_path']          = './storage/product';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['encrypt_name'] = TRUE;
                // $config['max_size']             = 100;
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('image')) {
                    $json = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $file_name = $this->upload->data()['file_name'];
                    $data = array(
                        'product' => $post['product'],
                        'brand_id' => $post['brand_id'],
                        'image' => 'storage/product/' . $file_name,
                        'slug' => $slug($post['product']),
                        'description' => nl2br($post['description']),
                        'created_at' => date('Y-m-d H:i:s')
                    );

                    $this->db->insert('product', $data);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/product/product')];
                }
            } else {
                $json = ['error' => true, 'msg' => 'Please upload file!'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function update()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('product_id', 'Product ID', 'trim|required')
            ->set_rules('product', 'Product', 'trim|required')
            ->set_rules('brand_id', 'Brand', 'trim|required')
            ->set_rules('description', 'Description', 'trim|required')
            ->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            _mkdir();

            $slug = function ($title) {
                $toLowerTitle = url_title($title, 'dash', true);
                $slug = $toLowerTitle;
                $changed = $this->db->get_where('product', ['slug' =>$toLowerTitle]);
                if ($changed->num_rows() < 1) :
                    $check = $this->db->get('product')->result_array();
                    $get_all_slug = array_column($check, 'slug');
                    $x = 0;
                    if (in_array($toLowerTitle, $get_all_slug)) :
                        $x += 1;
                    endif;
                    if ($x > 0) :
                        $slug = $toLowerTitle . '-' . $x;
                    endif;
                endif;
                return $slug;
            };
            if (!empty($_FILES['image']['name'])) {
                _mkdir();
                $config['upload_path']          = './storage/product';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['encrypt_name'] = TRUE;
                // $config['max_size']             = 100;
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('image')) {
                    $json = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $file_name = $this->upload->data()['file_name'];
                    $data = array(
                        'product' => $post['product'],
                        'brand_id' => $post['brand_id'],
                        'image' => 'storage/product/' . $file_name,
                        'slug' => $slug($post['product']),
                        'description' => nl2br($post['description']),
                        'created_at' => date('Y-m-d H:i:s')
                    );

                    $this->db->update('product', $data,['product_id' => $post['product_id']]);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/product/product')];
                }
            } else {
                $data = array(
                    'product' => $post['product'],
                    'brand_id' => $post['brand_id'],
                    'slug' => $slug($post['product']),
                    'description' => nl2br($post['description']),
                    'created_at' => date('Y-m-d H:i:s')
                );

                $this->db->update('product', $data,['product_id' => $post['product_id']]);
                $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/product/product')];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function delete($id = null)
    {
        $data = array(
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('brand', $data, array('brand_id' => $id));
        $json['success'] = 1;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}
