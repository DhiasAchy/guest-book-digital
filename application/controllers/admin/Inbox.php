<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends CI_Controller {
  public function __construct()
  {
      parent::__construct();
      if($this->session->userdata('logged_in') != 1){
        redirect('admin');
      }

      $this->load->library('form_validation');

      $this->load->model('Model_inbox');
  }
	public function index()
	{
    $inbox = $this->Model_inbox->getall();

    $data['inbox'] =  $inbox;
    
    $this->load
    ->layout(false)
    ->view('admin/inbox/inbox', $data);
  }
  
  // View action
  public function tambahuser()
  { 
    $this->load
    ->layout(false)
    ->view('admin/user/tambah_user');
  }

  public function edituser($id = null)
  {
     if(!empty($id)){
        $user = $this->Model_inbox->getbyid($id);
        
        $data['user'] = $user;

        $this->load
        ->layout(false)
        ->view('admin/user/edit_user', $data);
     }
  
  }

  public function lihatinbox($id = null)
  {
     if(!empty($id)){
        $user = $this->Model_inbox->getbyid($id);
        
        $data['user'] = $user;

        $this->load
        ->layout(false)
        ->view('admin/inbox/lihat_inbox', $data);
     }
  
  }


  // Action


  public function savedata()
  {
    $post = $this->input->post(null,false);

    $this->form_validation
            ->set_rules('username', 'Username', 'trim|required')
            ->set_rules('nama', 'Name', 'trim|required')
            ->set_rules('password', 'Password', 'trim|required|min_length[4]')
            ->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]')
            ->set_error_delimiters('', '');

    if ($this->form_validation->run() == FALSE) {
      $json['error'] =  $this->form_validation->get_errors();
    }else{
        $data = array(
          'nama' => $post['nama'],
          'username' => $post['username'],
          'password' => hash('sha512',$post['password']),
          'date_added' => date('Y-m-d H:i:s')
        );
        $this->db->insert('tb_user', $data);
        $json['success'] = 1;
        $json['redirect'] = base_url('admin/user');
    }  
    $this->output->set_content_type('application/json')->set_output(json_encode($json));
  }


  public function updatedata()
  {
    $post = $this->input->post(null,false);

    $id = $post['user_id'];
    $this->form_validation
            ->set_rules('username', 'Username', 'trim|required')
            ->set_rules('nama', 'Name', 'trim|required')
            ->set_rules('password', 'Password', 'trim|min_length[8]')
            ->set_rules('passconf', 'Password Confirmation', 'trim|matches[password]')
            ->set_error_delimiters('', '');

    if ($this->form_validation->run() == FALSE) {
      $json['error'] =  $this->form_validation->get_errors();
    }else{
      
      if(empty($post['password'])){
        $data = array(
          'nama' => $post['nama'],
          'username' => $post['username'],
          'date_updated' => date('Y-m-d H:i:s')
        );
        $this->db->update('tb_user', $data, array('id' => $id));
        $json['success'] = 1;
      }else{
        $data = array(
          'nama' => $post['nama'],
          'username' => $post['username'],
          'password' => hash('sha512',$post['password']),
          'date_updated' => date('Y-m-d H:i:s')
        );
        $this->db->update('tb_user', $data, array('id' => $id));
        $json['success'] = 1;
      }

    }  
    $this->output->set_content_type('application/json')->set_output(json_encode($json));
  }

  public function lihatdata()
  {
    $post = $this->input->post(null,false);

    $id = $post['user_id'];
    $this->form_validation
            ->set_rules('username', 'Username', 'trim|required')
            ->set_rules('nama', 'Name', 'trim|required')
            ->set_rules('password', 'Password', 'trim|min_length[8]')
            ->set_rules('passconf', 'Password Confirmation', 'trim|matches[password]')
            ->set_error_delimiters('', '');

    if ($this->form_validation->run() == FALSE) {
      $json['error'] =  $this->form_validation->get_errors();
    }else{
      
      if(empty($post['password'])){
        $data = array(
          'nama' => $post['nama'],
          'username' => $post['username'],
          'date_updated' => date('Y-m-d H:i:s')
        );
        $this->db->update('tb_user', $data, array('id' => $id));
        $json['success'] = 1;
      }else{
        $data = array(
          'nama' => $post['nama'],
          'username' => $post['username'],
          'password' => hash('sha512',$post['password']),
          'date_updated' => date('Y-m-d H:i:s')
        );
        $this->db->update('tb_user', $data, array('id' => $id));
        $json['success'] = 1;
      }

    }  
    $this->output->set_content_type('application/json')->set_output(json_encode($json));
  }


  public function delete($id = null)
  {
    $data = array(
      'date_updated' => date('Y-m-d H:i:s'),
      'date_deleted' => date('Y-m-d H:i:s')
    );
    $this->db->update('tb_inbox', $data, array('id' => $id));
    $json['success'] = 1;     
    $this->output->set_content_type('application/json')->set_output(json_encode($json));
  }

}
