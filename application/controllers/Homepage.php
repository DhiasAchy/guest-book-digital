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

		public function register($event=null)
		{
			$data['page_title'] = "Pendaftaran Tamu";
			$data['meta_title'] = "Pendaftaran Tamu - Guest Book - Wirawan Corp"; 
			$data['meta_keywords'] = "guest book, aplication, wirawancorp";
			$data['meta_description'] = ""; 

			if (!empty($event)) {
				// cari event ada atau tidak
				$find = $this->db->get_where('app_event', ['event_code' => $event])->row_array();

				if ($find) {
					$data['page_title'] = "Pendaftaran Tamu";
					$data['meta_title'] = "Pendaftaran Tamu - Guest Book - Wirawan Corp"; 
					$data['meta_keywords'] = "guest book, aplication, wirawancorp";
					$data['meta_description'] = ""; 
					$data['event'] = $find;
					$data['event_code'] = $event;

					$this->load->view('page_register', $data);
				} else { show_404(); }
			} else { show_404(); }
		}
		public function register_process($event=null)
		{
			$post = $this->input->post(null, false);

			$this->form_validation
				->set_rules('name', 'Nama', 'trim|required')
				->set_rules('email', 'Email', 'trim|required')
				->set_rules('phone', 'Telepon', 'trim|required')
				->set_error_delimiters('', '');
				
			if ($this->form_validation->run() == FALSE) {
				$json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
			} else { 
				//memanggil method _generate_data_qrcode untuk proses generate data qrcode
				$qrcode_data = $this->Model_event_guest->_generate_data_qrcode();

				$data = array(
					'name'       => $post['name'],
					'email'      => $post['email'],
					'phone'      => $post['phone'],
					'address'    => $post['address'],
					'user_id'    => $this->session->userdata('user_id'),
					'qrcode_path'=> $this->Model_event_guest->_generate_qrcode($this->input->post('name'),$qrcode_data), //memanggil method _generate_qrcode dengan mengirimkan dua parameter yaitu data fullname dan data qrcode
					'qrcode_data'=> $qrcode_data,
					// 'event_code' => $event,
					'created_at' => date('Y-m-d H:i:s')
				);

				// catat ke tabel app_guest
				$this->db->insert('app_guest', $data);
				$guest_id = $this->db->insert_id();

				// catat ke tabel app_event_guest
				$event_data = $this->db->get_where('app_event', ['event_code' => $event])->row_array();
				$event_id = $event_data['id'];
				$data_event = array(
					'event_id' => $event_id,
					'guest_id' => $guest_id,
					'created_at' => date('Y-m-d H:i:s')
				);
				$this->db->insert('app_guest_event', $data_event);

				$json = ['error' => false, 'data' => $data, 'msg' => 'data saved successfully', 'redirect' => base_url('print_qr.php?event='.$event_data['name'].'&name='.$post['name'].'&address='.$post['address'].'&phone='.$post['phone'].'&qrcode='.base_url('assets/img/qrcode/'.$data['qrcode_path']))];
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($json));
		}
    }
?>