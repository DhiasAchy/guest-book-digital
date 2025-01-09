<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guest extends CI_Controller
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
        $data['guest'] =  $this->Model_event_guest->getall();
        $this->template->admin('admin/event/guest/view',$data);
    }
    
    // open action
    public function add($id=null)
    {
        $data['guest'] =  $this->Model_event_guest->getall();
        $data['event'] =  $this->Model_event_event->getbyid($id);
        $data['event_id'] =  $id;
        $this->template->admin('admin/event/guest/add',$data);
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
            $data = array(
                'url'           => base_url().$hasil_slug,
                'name'          => $post['event'],
                'event_date'    => $post['date'],
                'event_address' => $post['address'],
                'user_id'       => $this->session->userdata('user_id'),
                'created_at'    => date('Y-m-d H:i:s')
            );
        }
    }
}

?>