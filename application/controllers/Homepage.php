<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    
    class Homepage extends CI_Controller
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
    		$this->load->helper('string');
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
    		$this->load->view('page_welcome', $data);
    	}
    	
    	public function goto_event() {
	        $post = $this->input->post(null, false);
    	    $slug = $post['event'];
    	    if (!empty($slug)) {
    	        $data['event'] = $this->db->get_where('app_event', ['event_code' => $slug])->row_array();
    	        $event_id = $data['event']['id'];
                $data['guest'] = $this->Model_event_guest->getall_by_event($event_id);
                $data['guest_query'] = $this->Model_event_guest->count_guest($event_id);
			    
			    $data['page_title'] = "Guest Book - ".$data['event']['name']." - ".$event_id;
        		$data['meta_title'] = "Guest Book - Digital - Wirawan Corp"; 
        		$data['meta_keywords'] = "guest book, aplication, wirawancorp";
        		$data['meta_description'] = ""; 
                
                // $this->load->view('page_home', $data);
                $json = ['error' => false, 'msg' => 'Event ditemukan, klik untuk melanjutkan.', 'redirect' => base_url('event/title/'.$data['event']['slug'])];
                
                $date = date('Ymd', strtotime($data['event']['event_date']));
                $date_ = strtotime($data['event']['event_date']);
                $date_today = date('Ymd');
                $remaining = $date_ - time();
                $days_remaining = floor($remaining / 86400);
                $hours_remaining = floor(($remaining % 86400) / 3600);
                // echo "There are $days_remaining days and $hours_remaining hours left";
                
                if($date>$date_today) {
                    $json = ['error' => true, 'msg' => 'Event belum mulai, Scan hanya dapat digunakan pada hari event berlangsung.'];
                } else if($date<$date_today) {
                    $json = ['error' => true, 'msg' => 'Event sudah selesai, Scan sudah tidak dapat digunakan.'];
                } else {
                    $json = ['error' => false, 'msg' => 'Event ditemukan, klik untuk melanjutkan.', 'redirect' => base_url('event/title/'.$data['event']['slug'])];
                }
            } else {
                // echo "not found ".$slug;
                $json = ['error' => true, 'Event tidak ditemukan, silahkan periksa kembali kode event'];
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($json));
    	}
    }
?>