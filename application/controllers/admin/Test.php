<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
          redirect('admin');
        }
    
        $this->load->library('form_validation');
        $this->load->model('Model_catalog_company');
        $this->load->model('Model_catalog_brand');
        $this->load->model('Model_catalog_file_pdf');
    }
    public function index()
    {
        $data['catalog'] = $this->Model_catalog_file_pdf->getall();
        $data['company'] =  $this->Model_catalog_company->getall();
        $data['brand']   =  $this->Model_catalog_brand->getall();
    
        $this->template->admin('admin/test/add',$data);
    }

    // View action

    // Action
    public function insert()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('name', 'Nama', 'trim|required')
            ->set_error_delimiters('', '');
    
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true,'msg' => $this->form_validation->get_errors(),'form_validation' => true];
        } else {
            _mkdir();
            
            // slug
            $slug = function ($title) {
                $check = $this->db->get('app_catalog')->result_array();
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
            
            // Thumbnail PDF
            $upload_icon = function () {
                $config['upload_path']   = './storage/catalog/pdf/thumbnail';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
            // File Pdf
            $upload_pdf = function () {
                $config['upload_path']   = './storage/catalog/pdf';
                $config['allowed_types'] = 'pdf|jpg|png';
                $config['max_size']      = '5000';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('file')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()."|".$_FILES['pdf']['name']];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
            // print_r($_FILES['pdf']);
            
            // if (!empty($_FILES['image']['name']) && !empty($_FILES['pdf']['name'])) {
            if (!empty($_FILES['image']['name']) && !empty($_FILES['pdf']['name'])) {
                $data = array(
                    'company_id' => $post['company'],
                    'brand_id'   => $post['brand'],
                    'name'       => $post['name'],
                    'user_id'    => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                $flag_err_icon      = null;
                $check_upload_icon  = $upload_icon();
                $flag_err_pdf      = null;
                $check_upload_pdf  = $upload_pdf();
                
                /*if ($check_upload_icon['error'] === FALSE) : $data['image'] = 'storage/catalog/pdf/thumbnail/' . $check_upload_icon['file_name'];
                else : $flag_err_icon = $check_upload_icon['msg'];
                endif;
                
                if ($check_upload_pdf['error'] === FALSE) : $data['file'] = 'storage/catalog/pdf/' . $check_upload_pdf['file_name'];
                else : $flag_err_pdf = $check_upload_pdf['msg'];
                endif;
                
                if ($flag_err_icon == null && $flag_err_pdf == null) :
                    $this->db->insert('app_catalog', $data);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/test')];
                else : 
                    if (isset($data['image'])) :
                        $path_icon  = './' . $data['image'];
                        is_file($path_icon) ? unlink($path_icon) : null;
                    endif;
                    
                    if (isset($data['pdf'])) :
                        $path_pdf  = './' . $data['file'];
                        is_file($path_pdf) ? unlink($path_pdf) : null;
                    endif;
                    
                    $json = ['error' => true, 'msg' => array($flag_err_icon,$flag_err_pdf)];
                endif;*/
            } else {
                $json = ['error' => true, 'msg' => 'Please upload file!'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
      }
      
    public function insert_() 
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('name', 'Nama', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true,'msg' => $this->form_validation->get_errors(),'form_validation' => true];
        } else {
            
            // file pdf
                $upload_pdf = function () {
                    $config['upload_path']   = './storage/catalog/pdf';
                    $config['allowed_types'] = 'pdf|jpg|png';
                    $config['max_size']      = '5000';
                    $config['encrypt_name']  = TRUE;
    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('file_name')) {
                        $return = ['error' => true, 'msg' => "pdf file error, ".$this->upload->display_errors()];
                    } else {
                        $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                    }
                    return $return;
                };
                
            // thumbnail
                $upload_thumbnail = function () {
                    $config['upload_path']   = './storage/catalog/pdf/thumbnail';
                    $config['allowed_types'] = 'pdf|jpg|png';
                    $config['max_size']      = '5000';
                    $config['encrypt_name']  = TRUE;
    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        $return = ['error' => true, 'msg' => "pdf file error, ".$this->upload->display_errors()];
                    } else {
                        $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                    }
                    return $return;
                };
                
            if (!empty($_FILES['image']['name']) && !empty($_FILES['file_name']['name'])) {
                $flag_err_thumbnail      = null;
                $check_upload_thumbnail  = $upload_thumbnail();
                $flag_err_pdf      = null;
                $check_upload_pdf  = $upload_pdf();
                
                if ($check_upload_thumbnail['error'] === FALSE) : $thumbnail = 'storage/catalog/pdf/thumbnail/' . $check_upload_thumbnail['file_name'];
                else : $flag_err_thumbnail = $check_upload_thumbnail['msg'];
                endif;
                
                if ($check_upload_pdf['error'] === FALSE) : $pdf = 'storage/catalog/pdf/' . $check_upload_pdf['file_name'];
                else : $flag_err_pdf = $check_upload_pdf['msg'];
                endif;
                
                if ($flag_err_thumbnail == null && $flag_err_pdf == null) :
                    
                    // $pdf       = $this->upload->file_name;
                    // $thumbnail = $this->upload->image;
                    $data = array(
                        'company_id' => $post['company'],
                        'brand_id'   => $post['brand'],
                        'name'       => $post['name'],
                        'image'      => $thumbnail,
                        'file'       => $pdf,
                        'user_id'    => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    
                    $this->db->insert('app_catalog', $data);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/test')];
                else : 
                    if (isset($data['image'])) :
                        $path_thumbnail  = './' . $data['image'];
                        is_file($path_thumbnail) ? unlink($path_thumbnail) : null;
                    endif;
                    
                    if (isset($data['file_name'])) :
                        $path_pdf  = './' . $data['file'];
                        is_file($path_pdf) ? unlink($path_pdf) : null;
                    endif;
                    
                    $json = ['error' => true, 'msg' => array($flag_err_thumbnail,$flag_err_pdf)];
                endif;
            } else {
                $json = ['error' => true, 'msg' => 'Please upload file!'];
            }
            
            
            /*$config['upload_path']   = './storage/catalog/pdf';
            $config['allowed_types'] = 'pdf|jpg|png';
            $config['max_size']      = '5000';
            $config['encrypt_name']  = TRUE;
            $this->load->library('upload',$config);
            $this->upload->initialize($config);  
            if(!$this->upload->do_upload('file_name'))  {
                echo $this->upload->display_errors();
                $return = ['error' => false, 'msg' => $this->upload->display_errors()];
            } else  {
                $pdf       = $this->upload->file_name;
                $thumbnail = $this->upload->image;
                $data = array(
                    'company_id' => $post['company'],
                    'brand_id'   => $post['brand'],
                    'name'       => $post['name'],
                    'image'      => $thumbnail,
                    'file'       => $pdf,
                    'user_id'    => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->db->insert('app_catalog',$data);
                $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/test')];
            }*/
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
