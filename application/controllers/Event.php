<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Event extends CI_Controller
    {
    
    	/**
    	 * Index Page for this controller.
    	 *
    	 * Maps to the following URL
    	 * 		http://example.com/index.php/welcome
    	 *	- or -
    	 * 		http://example.com/index.php/welcome/index
    	 *	- or -
    	 * Since this controller is set as the default controller in
    	 * config/routes.php, it's displayed at http://example.com/
    	 *
    	 * So any other public methods not prefixed with an underscore will
    	 * map to /index.php/welcome/<method_name>
    	 * @see https://codeigniter.com/user_guide/general/urls.html
    	 */
    	public function __construct()
    	{
    		parent::__construct();
    
    		$this->load->library('form_validation');
    		$this->load->model('Model_event_event');
            $this->load->model('Model_event_guest');
    	}
    	
    	public function index() 
    	{
    	    // load data achievement
    	    $data['page_title'] = "Guest Book - Digital";
    		$data['meta_title'] = "Guest Book - Digital - Wirawan Corp"; 
    		$data['meta_keywords'] = "guest book, aplication, wirawancorp";
    		$data['meta_description'] = ""; 
    		$this->load->view('page_home', $data);
    	}
    	
    	public function title($slug=null) {
    	    if (!empty($slug)) {
    	        $data['event'] = $this->db->get_where('app_event', ['slug' => $slug])->row_array();
    	        $event_id = $data['event']['id'];
                $data['guest'] = $this->Model_event_guest->guestlog_by_event($event_id);
                $data['guest_query'] = $this->Model_event_guest->count_guest($event_id);
			    
			    $data['page_title'] = "Guest Book - ".$data['event']['name']." - ".$event_id;
        		$data['meta_title'] = "Guest Book - Digital - Wirawan Corp"; 
        		$data['meta_keywords'] = "guest book, aplication, wirawancorp";
        		$data['meta_description'] = ""; 
                
                $this->load->view('page_home', $data);
            } else {
                echo "not found";
            }
    	}
    	public function check_in() {
    	    $post = $this->input->post(null, false);
    	    $guest_qr = $post['qr'];
    	    $event_id = $post['event_id'];
    	    
    	    // search guest
    	    $guest_data = $this->Model_event_guest->getall_by_parameter($guest_qr);
    	    
    	    // if found write to guest log
    	    if($guest_data!=null) {
    	        if($guest_data['event_attendance']==null) {
    	            $data = array(
                        'event_id'       => $event_id,
                        'guest_id'       => $guest_data['id'],
                        'guest_event_id' => $guest_data['event_id']
                    );
                    $this->db->insert('app_guest_log', $data);
                    $this->db->update('app_guest', ['event_attendance' => date('Y-m-d H:i:s')], ['id' => $guest_data['id']]);
        	        $json = ['error' => false, 'msg' => 'Tamu terdaftar'];
    	        } else {
    	            $json = ['error' => true, 'msg' => 'Tamu sudah pernah scan QR.'];
    	        }
    	    } else {
    	        $json = ['error' => true, 'msg' => 'Tamu tidak terdaftar.'];
    	    }
    	    $this->output->set_content_type('application/json')->set_output(json_encode($json));
    	}
    	
    }
?>