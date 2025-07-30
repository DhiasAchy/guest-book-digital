<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Achievement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
          redirect('admin');
        }
    
        $this->load->library('form_validation');
        $this->load->model('Model_achievement');

    }
    public function index()
    {
        $data['achievement'] = $this->Model_achievement->getall();
    
        $this->template->admin('admin/achievement/view',$data);
        // $this->load
        //   ->layout(false)
        //   ->view('admin/achievement/view', $data);
    }

    // View action
    public function add()
    {
        $this->template->admin('admin/achievement/add');
        // $this->load
        //   ->layout(false)
        //   ->view('admin/achievement/add');
    }
  
    public function edit($id = null)
    {
        if (!empty($id)) {
            $data['achievement'] = $this->Model_achievement->getbyid($id);
            $this->template->admin('admin/achievement/edit',$data);
            // $this->load
            //   ->layout(false)
            //   ->view('admin/achievement/edit', $data);
        }
    }

    // Action
    public function insert()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('title', 'Title', 'trim|required')
            ->set_error_delimiters('', '');
    
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true,'msg' => $this->form_validation->get_errors(),'form_validation' => true];
        } else {
            if (!empty($_FILES['image']['name'])) {
                _mkdir();
                
                // slug 
                $slug = function ($title) {
                    $check = $this->db->get('app_achievement')->result_array();
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
            
                $config['upload_path']          = './storage/achievement';
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
                        'slug'       => $slug($post['title']),
                        'name'       => $post['title'],
                        'image'      => 'storage/achievement/'.$file_name,
                        'created_at' => date('Y-m-d H:i:s')
                    );
        
                    $this->db->insert('app_achievement', $data);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/achievement')];
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
            ->set_rules('title', 'Title', 'trim|required')
            ->set_error_delimiters('', '');
    
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true,'msg' => $this->form_validation->get_errors(),'form_validation' => true];
        } else {
            // slug 
            $slug = function ($title) {
                $check = $this->db->get('app_achievement')->result_array();
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
                
                $config['upload_path']          = './storage/achievement';
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
                        'slug'       => $slug($post['title']),
                        'name'       => $post['title'],
                        'image'      => 'storage/achievement/'.$file_name,
                        'created_at' => date('Y-m-d H:i:s')
                    );
        
                    $this->db->update('app_achievement', $data,['id' => $post['id']]);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/achievement')];
                }
            } else {
                $data = array(
                    'slug'       => $slug($post['title']),
                    'name'       => $post['title'],
                    'created_at' => date('Y-m-d H:i:s')
                );
            
                $this->db->update('app_achievement', $data,['id' => $post['id']]);
                $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/achievement')];
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
        $this->db->update('app_achievement', $data, array('id' => $id));
        $json['success'] = 1;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}
