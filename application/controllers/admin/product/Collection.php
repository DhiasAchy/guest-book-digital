<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Collection extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('admin');
        }

        $this->load->library('form_validation');

        $this->load->model('Model_collection');
    }
    public function index()
    {
        $collection = $this->Model_collection->getall();

        $data['collection'] =  $collection;
        $this->template->admin('admin/product/collection/view',$data);
        // $this->load
        //     ->layout(false)
        //     ->view('admin/product/collection/view', $data);
    }

    // View action
    public function add()
    {
        $this->template->admin('admin/product/collection/add');
        // $this->load
        //     ->layout(false)
        //     ->view('admin/product/collection/add');
    }

    public function edit($id = null)
    {
        if (!empty($id)) {
            $data['collection'] = $this->Model_collection->getbyid($id);
            $this->template->admin('admin/product/collection/edit',$data);
            // $this->load
            //     ->layout(false)
            //     ->view('admin/product/collection/edit', $data);
        }
    }

    // Action


    public function insert()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('collection', 'Collection', 'trim|required')
            ->set_rules('description', 'Description', 'trim|required')
            ->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            _mkdir();
            $slug = function ($title) {
                $check = $this->db->get('collection')->result_array();
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
            $upload_icon = function () {
                $config['upload_path']          = './storage/product/collection';
                $config['allowed_types']        = 'gif|jpg|png|svg';
                $config['encrypt_name']         = TRUE;
                // $config['max_size']             = 100;
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('icon')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };

            $upload_bg = function () {
                $config['upload_path']          = './storage/product/collection';
                $config['allowed_types']        = 'gif|jpg|png|svg';
                $config['encrypt_name']         = TRUE;
                // $config['max_size']             = 100;
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('bg_image')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };


            if (!empty($_FILES['icon']['name']) && !empty($_FILES['bg_image']['name'])) {

                $data = array(
                    'collection' => $post['collection'],
                    'slug' => $slug($post['collection']),
                    'description' => nl2br($post['description']),
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $flag_err_icon      = null;
                $flag_err_bg        = null;
                $check_upload_icon  = $upload_icon();
                $check_upload_bg    = $upload_bg();
                if ($check_upload_icon['error'] === FALSE) :
                    $data['icon'] = 'storage/product/collection/' . $check_upload_icon['file_name'];
                else :
                    $flag_err_icon = $check_upload_icon['msg'];
                endif;
                if ($check_upload_bg['error'] === FALSE) :
                    $data['bg_image'] = 'storage/product/collection/' . $check_upload_bg['file_name'];
                else :
                    $flag_err_bg = $check_upload_bg['msg'];
                endif;

                if ($flag_err_icon == null && $flag_err_bg == null) :
                    $this->db->insert('collection', $data);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/product/collection')];
                else :
                    if (isset($data['icon'])) :
                        $path_icon  = './' . $data['icon'];
                        is_file($path_icon) ? unlink($path_icon) : null;
                    endif;
                    if (isset($data['bg_image'])) :
                        $path_bg  = './' . $data['bg_image'];
                        is_file($path_bg) ? unlink($path_bg) : null;
                    endif;
                    $json = ['error' => true, 'msg' => array($flag_err_icon, $flag_err_bg)];
                endif;
            } else {
                $json = ['error' => true, 'msg' => 'Please upload file icon and background image!'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function update()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('collection_id', 'Collection ID', 'trim|required')
            ->set_rules('collection', 'Collection', 'trim|required')
            ->set_rules('description', 'Description', 'trim|required')
            ->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            _mkdir();
            $slug = function ($title) {
                $toLowerTitle = url_title($title, 'dash', true);
                $slug = $toLowerTitle;
                $changed = $this->db->get_where('collection', ['slug' =>$toLowerTitle]);
                if ($changed->num_rows() < 1) :
                    $check = $this->db->get('collection')->result_array();
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
            $upload_icon = function () {
                $config['upload_path']          = './storage/product/collection';
                $config['allowed_types']        = 'gif|jpg|png|svg';
                $config['encrypt_name']         = TRUE;
                // $config['max_size']             = 100;
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('icon')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };

            $upload_bg = function () {
                $config['upload_path']          = './storage/product/collection';
                $config['allowed_types']        = 'gif|jpg|png|svg';
                $config['encrypt_name']         = TRUE;
                // $config['max_size']             = 100;
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('bg_image')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };


            $data = array(
                'collection' => $post['collection'],
                'slug' => $slug($post['collection']),
                'description' => nl2br($post['description']),
                'created_at' => date('Y-m-d H:i:s')
            );

            $flag_err_icon      = null;
            $flag_err_bg        = null;
            if (!empty($_FILES['icon']['name'])) {
                $check_upload_icon  = $upload_icon();
                if ($check_upload_icon['error'] === FALSE) :
                    $data['icon'] = 'storage/product/collection/' . $check_upload_icon['file_name'];
                else :
                    $flag_err_icon = $check_upload_icon['msg'];
                endif;
            }
            if (!empty($_FILES['bg_image']['name'])) {
                $check_upload_bg    = $upload_bg();
                if ($check_upload_bg['error'] === FALSE) :
                    $data['bg_image'] = 'storage/product/collection/' . $check_upload_bg['file_name'];
                else :
                    $flag_err_bg = $check_upload_bg['msg'];
                endif;
            }



            if ($flag_err_icon == null && $flag_err_bg == null) :
                $this->db->update('collection', $data, ['collection_id' => $post['collection_id']]);
                $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/product/collection')];
            else :
                if (isset($data['icon'])) :
                    $path_icon  = './' . $data['icon'];
                    is_file($path_icon) ? unlink($path_icon) : null;
                endif;
                if (isset($data['bg_image'])) :
                    $path_bg  = './' . $data['bg_image'];
                    is_file($path_bg) ? unlink($path_bg) : null;
                endif;
                $json = ['error' => true, 'msg' => array($flag_err_icon, $flag_err_bg)];
            endif;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function delete($id = null)
    {
        $data = array(
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('collection', $data, array('collection_id' => $id));
        $json['success'] = 1;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

}
