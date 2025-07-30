<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brand extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('admin');
        }

        $this->load->library('form_validation');
        $this->load->model('Model_catalog_brand');
        $this->load->model('Model_catalog_file_pdf');
    }
    
    // default
    public function index()
    {
        $data['brand'] =  $this->Model_catalog_brand->getall();
        $this->template->admin('admin/catalog/brand/view',$data);
    }
    
    // open action
    public function detail($id = null,$json = null) 
    {
        if (!empty($id)) {
            // $data['brand'] =  $this->Model_catalog_brand->getall();
            $data['brand'] = $this->db->get_where('app_brand', ['id' => $id])->row_array();
            $data['catalog'] = $this->Model_catalog_file_pdf->get_catalog_by_brand($id);
            
            // if($json==null) {
                $data['return']['error'] = '3';
                $data['return']['msg'] = '';
            // } else {
                // $data['return'] = $json;
            // }
            
            $this->template->admin('admin/catalog/brand/detail',$data);
        }
    }
    public function add()
    {
        $data['brand'] =  $this->Model_catalog_brand->getall();
        $this->template->admin('admin/catalog/brand/add',$data);
    }
    public function edit($id = null)
    {
        if (!empty($id)) {
            // $data['brand'] =  $this->Model_catalog_brand->getall();
            $data['brand'] = $this->db->get_where('app_brand', ['id' => $id])->row_array();
            $this->template->admin('admin/catalog/brand/edit',$data);
        }
    }
    
    // action
    public function insert()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('brand', 'Nama Brand', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else { 
            _mkdir();
            
            $slug = function ($title) {
                $check = $this->db->get('app_brand')->result_array();
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
            
            $upload_bg = function () {
                $config['upload_path']   = './storage/catalog/brand';
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
            
            $upload_title = function () {
                $config['upload_path']   = './storage/catalog/brand';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image_title')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
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
            
            // if (!empty($_FILES['image']['name'])) {
            if (!empty($_FILES['image']['name']) && !empty($_FILES['image_title']['name'])) {
                $data = array(
                    'slug'       => $slug($post['brand']),
                    'name'       => $post['brand'],
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                $flag_err_bg        = null;
                $check_upload_bg    = $upload_bg();
                $flag_err_title     = null;
                $check_upload_title = $upload_title();
                $flag_err_pdf       = null;
                $check_upload_pdf   = $upload_pdf();
                
                if ($check_upload_bg['error'] === FALSE) : $data['image'] = 'storage/catalog/brand/' . $check_upload_bg['file_name'];
                else : $flag_err_bg = $check_upload_bg['msg'];
                endif;
                
                if ($check_upload_title['error'] === FALSE) : $data['image_title'] = 'storage/catalog/brand/' . $check_upload_title['file_name'];
                else : $flag_err_title = $check_upload_title['msg'];
                endif;
                
                if ($check_upload_pdf['error'] === FALSE) : $data['pdf'] = 'storage/catalog/pdf/' . $check_upload_pdf['file_name'];
                else : $flag_err_pdf = $check_upload_pdf['msg'];
                endif;
                
                // echo $flag_err_pdf;
                
                if ($flag_err_bg == null && $flag_err_title == null) :
                    $this->db->insert('app_brand', $data);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/catalog/brand')];
                else : 
                    if (isset($data['image'])) :
                        $path_bg  = './' . $data['image'];
                        is_file($path_bg) ? unlink($path_bg) : null;
                    endif;
                    
                    if (isset($data['image_title'])) :
                        $path_title  = './' . $data['image_title'];
                        is_file($path_title) ? unlink($path_title) : null;
                    endif;
                    
                    if (isset($data['file_name'])) :
                        $path_pdf  = './' . $data['file'];
                        is_file($path_pdf) ? unlink($path_pdf) : null;
                    endif;
                    
                    $json = ['error' => true, 'msg' => array($flag_err_bg, $flag_err_title)];
                endif;
            } else {
                $json = ['error' => true, 'msg' => 'Please upload file image!'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function update()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('id', 'Id', 'trim|required')
            ->set_rules('brand', 'Nama Brand', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            _mkdir();
            
            // slug
            $slug = function ($title) {
                $check = $this->db->get('app_brand')->result_array();
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
            
            $upload_bg = function () {
                $config['upload_path']   = './storage/catalog/brand';
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
            
            $upload_title = function () {
                $config['upload_path']   = './storage/catalog/brand';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image_title')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
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
            
            $data = array(
                'slug'       => $slug($post['brand']),
                'name'       => $post['brand'],
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            $flag_err_bg = null;
            $flag_err_title = null;
            $flag_err_pdf = null;
            
            if (!empty($_FILES['image']['name'])) {
                $check_upload_bg = $upload_bg();
                if ($check_upload_bg['error'] === FALSE) :
                    $data['image'] = 'storage/catalog/brand/' . $check_upload_bg['file_name'];
                else :
                    $flag_err_bg = $check_upload_bg['msg'];
                endif;
            }
            
            if (!empty($_FILES['image_title']['name'])) {
                $check_upload_title = $upload_title();
                if ($check_upload_title['error'] === FALSE) :
                    $data['image_title'] = 'storage/catalog/brand/' . $check_upload_title['file_name'];
                else :
                    $flag_err_title = $check_upload_title['msg'];
                endif;
            }
            
            if (!empty($_FILES['file_name']['name'])) {
                $check_upload_pdf = $upload_pdf();
                if ($check_upload_pdf['error'] === FALSE) :
                    $data['pdf'] = 'storage/catalog/pdf/' . $check_upload_pdf['file_name'];
                    echo "pdf ada";
                else :
                    $flag_err_pdf = $check_upload_pdf['msg'];
                    echo "pdf tidak ada";
                endif;
            }
            
            // print_r($data);
            
            if ($flag_err_bg == null && $flag_err_title == null && $flag_err_pdf == null) :
                $this->db->update('app_brand', $data, ['id' => $post['id']]);
                $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/catalog/brand')];
            else :
                if (isset($data['image'])) :
                    $path_bg  = './' . $data['image'];
                    is_file($path_bg) ? unlink($path_bg) : null;
                endif;
                
                if (isset($data['image_title'])) :
                    $path_title  = './' . $data['image_title'];
                    is_file($path_title) ? unlink($path_title) : null;
                endif;
                
                if (isset($data['file_name'])) :
                    $path_pdf  = './' . $data['file_name'];
                    is_file($path_pdf) ? unlink($path_pdf) : null;
                endif;
                
                $json = ['error' => true, 'msg' => array($flag_err_bg,$flag_err_title)];
            endif;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function delete($id = null)
    {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('app_brand', $data, array('id' => $id));
        $json['success'] = 1;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function upload_catalog() 
    {
        $post = $this->input->post(null, false);
        $brand_id = $post['brand_id'];
        $this->form_validation
            ->set_rules('name', 'Nama Catalog', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json['return'] = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else { 
            _mkdir(); // open folder
            
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
            
            // file pdf
                $upload_pdf = function () {
                    $config['upload_path']   = './storage/catalog/pdf';
                    $config['allowed_types'] = 'pdf|jpg|png';
                    $config['max_size']      = '90000';
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
                    $config['allowed_types'] = 'pdf|jpg|jpeg|png';
                    $config['max_size']      = '8000';
                    $config['encrypt_name']  = TRUE;
    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image_cover')) {
                        $return = ['error' => true, 'msg' => "thumbnail file error, ".$this->upload->display_errors()];
                    } else {
                        $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                    }
                    return $return;
                };
                
            if (!empty($_FILES['file_name']['name']) && !empty($_FILES['image_cover']['name'])) {
                $flag_err_pdf            = null;
                $check_upload_pdf        = $upload_pdf();
                $flag_err_thumbnail      = null;
                $check_upload_thumbnail  = $upload_thumbnail();
                
                if ($check_upload_pdf['error'] === FALSE) : $pdf = 'storage/catalog/pdf/' . $check_upload_pdf['file_name'];
                else : $flag_err_pdf = $check_upload_pdf['msg'];
                endif;
                
                if ($check_upload_thumbnail['error'] === FALSE) : $thumbnail = 'storage/catalog/pdf/thumbnail/' . $check_upload_thumbnail['file_name'];
                else : $flag_err_thumbnail = $check_upload_thumbnail['msg'];
                endif;
                
                if ($flag_err_thumbnail == null && $flag_err_pdf == null) :
                    $data = array(
                        // 'slug'       => $slug($post['company']),
                        'brand_id'   => $post['brand_id'],
                        'name'       => $post['name'],
                        'image'      => $thumbnail,
                        'file'       => $pdf,
                        'user_id'    => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    
                    $this->db->insert('app_catalog', $data);
                    $json['return'] = ['error' => 0, 'msg' => 'data saved successfully'];
                else :
                    if (isset($data['file'])) :
                        $path_pdf  = './' . $data['file'];
                        is_file($path_pdf) ? unlink($path_pdf) : null;
                    endif;
                    
                    if (isset($data['image'])) :
                        $path_thumbnail  = './' . $data['image'];
                        is_file($path_thumbnail) ? unlink($path_thumbnail) : null;
                    endif;
                    
                    $json['return'] = ['error' => 1, 'msg' => array($flag_err_thumbnail,$flag_err_pdf)];
                endif;
            } else {
                $json['return'] = ['error' => 1, 'msg' => 'Harap isi file pdf dan thumbnail'];
            }
        }
        
        // $this->output->set_content_type('application/json')->set_output(json_encode($json));
        // $json['catalog'] = $this->Model_catalog_file_pdf->get_catalog_by_brand($brand_id);
        // $this->template->admin('admin/catalog/brand/detail',$json);
        // $this->detail($brand_id,$json);
        
        $json['brand'] = $this->db->get_where('app_brand', ['id' => $brand_id])->row_array();
        $json['catalog'] = $this->Model_catalog_file_pdf->get_catalog_by_brand($brand_id);
        $this->template->admin('admin/catalog/brand/detail',$json);
        // $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function delete_catalog($id = null) 
    {
        $data = array(
            'user_id'    => $this->session->userdata('user_id'),
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('app_catalog', $data, array('id' => $id));
        $json['success'] = 1;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function update_position() 
    {
        $post = $this->input->post(null, false);
        $brand = $post['brand_id'];
        $position = $post['position'];
        
        $jumlah = count($brand);
        // echo $jumlah;
        
        $x = 0;
        while($x<$jumlah) {
            // echo "brand".$brand[$x].", posisi ".$position[$x];
            $data = array(
                'position' => $position[$x],
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->db->update('app_brand', $data, ['id' => $brand[$x]]);
            $x++;
        }
        
        // print_r($brand); 
        $json = ['error' => false, 'msg' => 'Update success'];
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}
