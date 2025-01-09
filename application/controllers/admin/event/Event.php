<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Event extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('admin');
        }

        $this->load->library('form_validation');
        $this->load->model('Model_event_event');
        $this->load->model('Model_event_guest');
    }
    
    // default
    public function index()
    {
        $data['event'] =  $this->Model_event_event->getall();
        $this->template->admin('admin/event/event/view',$data);
    }
    
    // open action
    public function detail($id = null,$json = null) 
    {
        if (!empty($id)) {
            $data['event'] = $this->db->get_where('app_event', ['id' => $id])->row_array();
            $data['guest'] = $this->Model_event_guest->getall_by_event($id);
            
            // if($json==null) {
                $data['return']['error'] = '3';
                $data['return']['msg'] = '';
            // } else {
                // $data['return'] = $json;
            // }
            
            $this->template->admin('admin/event/event/detail',$data);
        }
    }
    public function add()
    {
        $data['event'] =  $this->Model_event_event->getall();
        $this->template->admin('admin/event/event/add',$data);
    }
    public function edit($id = null)
    {
        $data['event'] = $this->db->get_where('app_event', ['id' => $id])->row_array();
        $this->template->admin('admin/event/event/edit',$data);
    }
    
    // action
    public function insert() 
    {
        $post = $this->input->post(null, false);
        $this->form_validation
            ->set_rules('event', 'Nama Event', 'trim|required')
            ->set_rules('date', 'Tanggal Event', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else { 
            _mkdir();
            
            // slug handle
            $slug = function ($title) {
                $check = $this->db->get('app_event')->result_array();
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
            
            // thumbnail hnadle
            $upload_bg = function () {
                $config['upload_path']   = './storage/event';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('image')) {
                    $return = ['error' => true, 'msg' => $this->upload->display_errors()];
                } else {
                    $return = ['error' => false, 'file_name' => $this->upload->data()['file_name']];
                }
                return $return;
            };
            
            // process handle
            if (!empty($_FILES['image']['name'])) {
                $hasil_slug = $slug($post['event']);
                $data = array(
                    'slug'          => $hasil_slug,
                    'url'           => base_url().$hasil_slug,
                    'name'          => $post['event'],
                    'event_date'    => $post['date'],
                    'event_address' => $post['address'],
                    'user_id'       => $this->session->userdata('user_id'),
                    'created_at'    => date('Y-m-d H:i:s')
                );
                
                $flag_err_bg     = null;
                $check_upload_bg = $upload_bg();
                if ($check_upload_bg['error'] === FALSE) : $data['image'] = 'storage/event/' . $check_upload_bg['file_name'];
                else : $flag_err_bg = $check_upload_bg['msg'];
                endif;
                
                if ($flag_err_bg == null) :
                    $this->db->insert('app_event', $data);
                    $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/event/event')];
                else :
                    if (isset($data['image'])) :
                        $path_bg  = './' . $data['image'];
                        is_file($path_bg) ? unlink($path_bg) : null;
                    endif;
                    
                    $json = ['error' => true, 'msg' => array($flag_err_bg)];
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
            ->set_rules('event', 'Nama Event', 'trim|required')
            ->set_rules('date', 'Tanggal Event', 'trim|required')
            ->set_error_delimiters('', '');
            
        if ($this->form_validation->run() == FALSE) {
            $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
        } else {
            _mkdir();
            
            // slug handle
            $slug = function ($title) {
                $check = $this->db->get('app_event')->result_array();
                $get_all_slug = array_column($check, 'slug');
                $toLowerTitle = url_title($title, 'dash', true);
                $x = 0;
                if (in_array($toLowerTitle, $get_all_slug)) : $x += 1;
                endif;
                $slug = $toLowerTitle;
                if ($x > 0) : $slug = $toLowerTitle . '-' . $x;
                endif;
                return $slug;
            };
            
            // image handle
            $upload_bg = function () {
                $config['upload_path']   = './storage/event';
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
            
            $hasil_slug = $slug($post['event']);
            $data = array(
                'slug'          => $hasil_slug,
                'url'           => base_url().$hasil_slug,
                'name'          => $post['event'],
                'event_date'    => $post['date'],
                'event_address' => $post['address'],
                'user_id'       => $this->session->userdata('user_id'),
                'updated_at'    => date('Y-m-d H:i:s')
            );
            $flag_err_bg = null;
            
            if (!empty($_FILES['image']['name'])) {
                $check_upload_bg = $upload_bg();
                if ($check_upload_bg['error'] === FALSE) :
                    $data['image'] = 'storage/event/' . $check_upload_bg['file_name'];
                else :
                    $flag_err_bg = $check_upload_bg['msg'];
                endif;
            }
            
            // echo "here";
            if ($flag_err_bg == null) :
                $this->db->update('app_event', $data, ['id' => $post['id']]);
                $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/event/event')];
            else :
                if (isset($data['image'])) :
                    $path_bg  = './' . $data['image'];
                    is_file($path_bg) ? unlink($path_bg) : null;
                endif;
                
                $json = ['error' => true, 'msg' => array($flag_err_bg,$flag_err_title)];
            endif;
           
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function delete($id=null) 
    {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('app_event', $data, array('id' => $id));
        $json['success'] = 0;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}

?>