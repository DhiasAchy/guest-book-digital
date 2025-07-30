<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('admin');
        }

        $this->load->library('form_validation');
        $this->load->model('Model_catalog_company');
    }
    
    // default
    public function index()
    {
        $data['return'] = '';
        $data['company'] =  $this->Model_catalog_company->getall();
        $this->template->admin('admin/catalog/company/view',$data);
    }
    
    // open action
    public function add()
    {
        $data['company'] =  $this->Model_catalog_company->getall();
        $this->template->admin('admin/catalog/company/add',$data);
    }
    public function edit($id = null)
    {
        if (!empty($id)) {
            $data['company'] = $this->db->get_where('app_company', ['id' => $id])->row_array();
            $this->template->admin('admin/catalog/company/edit',$data);
        }
    }
    
    // action
    public function insert_old()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('company', 'Nama Brand', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else { 
            _mkdir();
            $slug = function ($title) {
                $check = $this->db->get('app_company')->result_array();
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
            
            // Logo Company
            $upload_bg = function () {
                $config['upload_path']   = './storage/catalog/company';
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
            
            // thumbnail PDF
            $upload_icon = function () {
                $config['upload_path']   = './storage/catalog/company';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image_cover')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
            // file PDF
            $upload_pdf = function () {
                $config['upload_path']   = './storage/catalog/pdf';
                $config['allowed_types'] = 'pdf';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('pdf')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
            if (!empty($_FILES['image']['name'])) {
                $data = array(
                    'name'       => $post['company'],
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                $flag_err_icon      = null;
                $flag_err_bg        = null;
                $flag_err_pdf       = null;
                $check_upload_icon  = $upload_icon();
                $check_upload_bg    = $upload_bg();
                $check_upload_pdf   = $upload_pdf();
                
                if ($check_upload_icon['error'] === FALSE) : $data['image_cover'] = 'storage/catalog/company/' . $check_upload_icon['file_name'];
                else : $flag_err_icon = $check_upload_icon['msg'];
                endif;
                
                // $data['image'] = nama tabel
                if ($check_upload_bg['error'] === FALSE) : $data['image'] = 'storage/catalog/company/' . $check_upload_bg['file_name'];
                else : $flag_err_bg = $check_upload_bg['msg'];
                endif;
                
                if ($check_upload_pdf['error'] === FALSE) : $data['pdf'] = 'storage/catalog/pdf/' . $check_upload_pdf['file_name'];
                else : $flag_err_pdf = $check_upload_pdf['msg'];
                endif;
                
                if ($flag_err_icon == null && $flag_err_bg == null && $flag_err_pdf == null) :
                    $this->db->insert('app_company', $data);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/catalog/company')];
                else : 
                    if (isset($data['image'])) :
                        $path_bg  = './' . $data['image'];
                        is_file($path_bg) ? unlink($path_bg) : null;
                    endif;
                    
                    if (isset($data['image_cover'])) :
                        $path_icon  = './' . $data['image_cover'];
                        is_file($path_icon) ? unlink($path_icon) : null;
                    endif;
                    
                    if (isset($data['pdf'])) :
                        $path_pdf  = './' . $data['pdf'];
                        is_file($path_pdf) ? unlink($path_pdf) : null;
                    endif;
                    
                    $json = ['error' => true, 'msg' => array($flag_err_bg,$flag_err_icon,$flag_err_pdf)];
                endif;
            } else {
                $json = ['error' => true, 'msg' => 'Please upload file image!'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function insert_old_2()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('company', 'Nama Brand', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else { 
            _mkdir(); // open folder
            
            // slug
            $slug = function ($title) {
                $check = $this->db->get('app_company')->result_array();
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
            
            // Logo Company
            $upload_bg = function () {
                $config['upload_path']   = './storage/catalog/company';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                // $this->upload->initialize($config);
                if (!$this->upload->do_upload('image')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
            // thumbnail PDF
            $upload_icon = function () {
                $config['upload_path']   = './storage/catalog/company';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image_cover')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
            // PDF
            $upload_icon = function () {
                $config['upload_path']   = './storage/catalog/pdf';
                $config['allowed_types'] = 'pdf';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                if (!$this->upload->do_upload('image_cover')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
            if (!empty($_FILES['image']['name'])) {
                $data = array(
                    'slug'       => $slug($post['company']),
                    'name'       => $post['company'],
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                $flag_err_icon      = null;
                $flag_err_bg        = null;
                $check_upload_icon  = $upload_icon();
                $check_upload_bg    = $upload_bg();
                
                if ($check_upload_icon['error'] === FALSE) : $data['image_cover'] = 'storage/catalog/company/' . $check_upload_icon['file_name'];
                else : $flag_err_icon = $check_upload_icon['msg'];
                endif;
                
                // $data['image'] = nama tabel
                if ($check_upload_bg['error'] === FALSE) : $data['image'] = 'storage/catalog/company/' . $check_upload_bg['file_name'];
                else : $flag_err_bg = $check_upload_bg['msg'];
                endif;
                
                if ($flag_err_icon == null && $flag_err_bg == null) :
                    $this->db->insert('app_company', $data);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/catalog/company')];
                else : 
                    if (isset($data['image'])) :
                        $path_bg  = './' . $data['image'];
                        is_file($path_bg) ? unlink($path_bg) : null;
                    endif;
                    
                    if (isset($data['image_cover'])) :
                        $path_icon  = './' . $data['image_cover'];
                        is_file($path_icon) ? unlink($path_icon) : null;
                    endif;
                    
                    $json = ['error' => true, 'msg' => array($flag_err_bg,$flag_err_icon)];
                endif;
            } else {
                $json = ['error' => true, 'msg' => 'Please upload file image!'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function insert()
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('company', 'Nama Company', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else { 
            _mkdir(); // open folder
            
            // slug
            $slug = function ($title) {
                $check = $this->db->get('app_company')->result_array();
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
                    if (!$this->upload->do_upload('image_cover')) {
                        $return = ['error' => true, 'msg' => "thumbnail file error, ".$this->upload->display_errors()];
                    } else {
                        $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                    }
                    return $return;
                };
                
            // logo
                $upload_logo = function () {
                    $config['upload_path']   = './storage/catalog/company';
                    $config['allowed_types'] = 'pdf|jpg|png';
                    $config['max_size']      = '5000';
                    $config['encrypt_name']  = TRUE;
    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        $return = ['error' => true, 'msg' => "logo file error, ".$this->upload->display_errors()];
                    } else {
                        $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                    }
                    return $return;
                };
                
            if (!empty($_FILES['file_name']['name']) && !empty($_FILES['image_cover']['name']) && !empty($_FILES['image']['name'])) {
                $flag_err_pdf            = null;
                $check_upload_pdf        = $upload_pdf();
                $flag_err_thumbnail      = null;
                $check_upload_thumbnail  = $upload_thumbnail();
                $flag_err_logo           = null;
                $check_upload_logo       = $upload_logo();
                
                if ($check_upload_pdf['error'] === FALSE) : $pdf = 'storage/catalog/pdf/' . $check_upload_pdf['file_name'];
                else : $flag_err_pdf = $check_upload_pdf['msg'];
                endif;
                
                if ($check_upload_thumbnail['error'] === FALSE) : $thumbnail = 'storage/catalog/pdf/thumbnail/' . $check_upload_thumbnail['file_name'];
                else : $flag_err_thumbnail = $check_upload_thumbnail['msg'];
                endif;
                
                if ($check_upload_logo['error'] === FALSE) : $logo = 'storage/catalog/company/' . $check_upload_logo['file_name'];
                else : $flag_err_logo = $check_upload_logo['msg'];
                endif;
                
                if ($flag_err_thumbnail == null && $flag_err_pdf == null && $flag_err_logo == null) :
                    $data = array(
                        'slug'       => $slug($post['company']),
                        'name'       => $post['company'],
                        'image'      => $logo,
                        'image_cover'=> $thumbnail,
                        'pdf'        => $pdf,
                        'user_id'    => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    
                    $this->db->insert('app_company', $data);
                    $json['return'] = ['error' => 0, 'msg' => 'data saved successfully'];
                    // $redirect = base_url('admin/catalog/company');
                    
                    /* echo '
                        <script>
                            swal("Success!", "data saved successfully", "success")
                        		.then((value) => {
                        			window.location.replace('.$redirect.');
                        		});
                        </script>
                    ';*/
                else :
                    if (isset($data['file_name'])) :
                        $path_pdf  = './' . $data['file'];
                        is_file($path_pdf) ? unlink($path_pdf) : null;
                    endif;
                    
                    if (isset($data['image'])) :
                        $path_thumbnail  = './' . $data['image'];
                        is_file($path_thumbnail) ? unlink($path_thumbnail) : null;
                    endif;
                    
                    if (isset($data['image_cover'])) :
                        $path_logo  = './' . $data['image_cover'];
                        is_file($path_logo) ? unlink($path_logo) : null;
                    endif;
                    
                    $json['return'] = ['error' => 1, 'msg' => array($flag_err_thumbnail,$flag_err_pdf,$flag_err_logo)];
                endif;
            }
        }
        // $this->output->set_content_type('application/json')->set_output(json_encode($json));
        $json['company'] =  $this->Model_catalog_company->getall();
        $this->template->admin('admin/catalog/company/view',$json);
    }
    public function update() 
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('id', 'Id', 'trim|required')
            ->set_rules('company', 'Nama Company', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            _mkdir();
            
            // slug
            $slug = function ($title) {
                $check = $this->db->get('app_company')->result_array();
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
            
            // Logo Company
            $upload_logo = function () {
                $config['upload_path']   = './storage/catalog/company';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('image')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
            // thumbnail PDF
            $upload_thumbnail = function () {
                $config['upload_path']   = './storage/catalog/pdf/thumbnail';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('image_cover')) {
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
            
            /*$data = array(
                'slug'       => $slug($post['company']),
                'name'       => $post['company'],
                'updated_at' => date('Y-m-d H:i:s')
            );*/
            
            $flag_err_logo          = null;
            $check_upload_logo      = $upload_logo();
            $flag_err_thumbnail     = null;
            $check_upload_thumbnail = $upload_thumbnail();
            $flag_err_pdf           = null;
            $check_upload_pdf       = $upload_pdf();
            $data                   = array();
            
            $data = array(
                'slug'       => $slug($post['company']),
                'name'       => $post['company'],
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            //  print_r($data);
                
            if (!empty($_FILES['image']['name'])) {
                $check_upload_logo = $upload_logo();
                if ($check_upload_logo['error'] === FALSE) :
                    $data['image'] = 'storage/catalog/company/' . $check_upload_logo['file_name'];
                    echo "logo ada";
                else :
                    $flag_err_logo = $check_upload_logo['msg'];
                    echo "logo tidak ada";
                endif;
            }
            if (!empty($_FILES['image_cover']['name'])) {
                $check_upload_thumbnail = $upload_thumbnail();
                if ($check_upload_thumbnail['error'] === FALSE) :
                    $data['image_cover'] = 'storage/catalog/pdf/thumbnail/' . $check_upload_thumbnail['file_name'];
                    echo "thumbnail ada";
                else :
                    $flag_err_thumbnail = $check_upload_thumbnail['msg'];
                    echo "thumbnail tidak ada";
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
            // print_r(array($_FILES['image']['name'],$_FILES['image_cover']['name'],$_FILES['file_name']['name']));
            
            if ($flag_err_thumbnail == null && $flag_err_logo == null && $flag_err_pdf == null) :
                $this->db->update('app_company', $data, ['id' => $post['id']]);
                $json['return'] = ['error' => 0, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/catalog/company')];
            else :
                if (isset($data['image'])) :
                    $path_logo  = './' . $data['image'];
                    is_file($path_logo) ? unlink($path_logo) : null;
                endif;
                if (isset($data['image_cover'])) :
                    $path_thumbnail  = './' . $data['image_cover'];
                    is_file($path_thumbnail) ? unlink($path_thumbnail) : null;
                endif;
                if (isset($data['file_name'])) :
                    $path_pdf  = './' . $data['file_name'];
                    is_file($path_pdf) ? unlink($path_pdf) : null;
                endif;
                $json['return'] = ['error' => 1, 'msg' => array($flag_err_logo,$flag_err_thumbnail,$flag_err_pdf)];
            endif;
            
        }
        // $this->output->set_content_type('application/json')->set_output(json_encode($json));
        $json['company'] =  $this->Model_catalog_company->getall();
        $this->template->admin('admin/catalog/company/view',$json);
    }
    public function delete($id=null) 
    {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('app_company', $data, array('id' => $id));
        $json['success'] = 1;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}

?>