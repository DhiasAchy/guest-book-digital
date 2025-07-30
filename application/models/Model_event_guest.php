<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_event_guest extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = 'app_guest';
		$this->db = $this->load->database('default',TRUE);
	}
	
	public function getall()
	{
		return	$query = $this->db
			->select('*')
			->from('app_guest')
			->where('deleted_at', NULL)
			->order_by('created_at', 'asc')
			->get()
			->result_array();
	}
	public function getall_withevent()
	{
		return $query = $this->db
		->select('app_guest.*, (SELECT name FROM `app_event` WHERE id=app_guest.event_id) AS event_name')
		->from('app_guest')
		// ->join('app_event','app_event.id=app_guest.event_id','right')
		->where('app_guest.deleted_at', NULL)
		->get()
		->result_array();
	}

	public function getall_by_event($event=null)
	{
		return	$query = $this->db
			->select('*')
			->from('app_guest')
			->where('deleted_at', NULL)
			->where('event_id', $event)
			->order_by('created_at', 'asc')
			->get()
			->result_array();
	}
	public function getallguest_byevent($event=null)
	{
		return	$query = $this->db
			->select('app_guest_event.*,app_guest.name AS guest_name,app_guest.qrcode_data,app_guest.qrcode_path,address,event_attendance')
			->from('app_guest_event')
			->join('app_guest','app_guest.id=app_guest_event.guest_id')
			->where('app_guest_event.event_id', $event)
			->order_by('app_guest_event.created_at', 'asc')
			->get()
			->result_array();
	}
	public function getall_by_parameter($value=null)
	{
		return $query = $this->db
			->select('*')
			->from('app_guest')
			->where('deleted_at', NULL)
			// ->where('qr_id', $value)
			->where('qrcode_data', $value)
			->order_by('created_at', 'asc')
			->get()
			->row_array();
		return $query = $this->db
		    ->query("SELECT * FROM `app_guest` WHERE `qr_id`='$value' NULL AND `deleted_at` IS NULL ")
		    ->result_array();
	}
	public function getguest_checkin_data($qr=null,$event_id)
	{
		return $query = $this->db
			->select('app_guest.id,app_guest.name,app_guest.address,app_guest.qrcode_path,app_guest.qrcode_data,app_guest.created_at,app_guest_event.id as event_id,app_guest_event.guest_id as guest_id,app_guest_event.event_id as event_id,app_guest_event.created_at as event_create,app_guest_event.updated_at as event_update')
			->from('app_guest')
			->join('app_guest_event','app_guest_event.guest_id=app_guest.id')
			->where('app_guest.deleted_at', NULL)
			// ->where('qr_id', $value)
			->where('app_guest.qrcode_data', $qr)
			->where('app_guest_event.event_id', $event_id)
			->order_by('app_guest.created_at', 'asc')
			->get()
			->result_array();
	}
	public function guestlog_by_event($event=null) {
		return $query = $this->db
		    ->query("SELECT lg.id, lg.event_id, lg.created_at, ev.name, gu.name AS guest_name, gu.address AS guest_address FROM `app_guest_log` AS lg LEFT JOIN `app_event` AS ev ON ev.id = lg.event_id LEFT JOIN `app_guest` AS gu ON gu.id = lg.guest_id WHERE lg.event_id='$event'")
		    ->result_array();
	}
	public function count_guest($event) { 
	    // guest_event = total guest terdaftar event tersebut
	    // guest_total = total guest yang datang event tsb, baik terdaftar / tidak
	    $query = $this->db->query("SELECT (SELECT COUNT(id) FROM `app_guest` WHERE `event_id`='$event' AND `deleted_at` IS NULL) AS guest_event, (SELECT COUNT(id) FROM `app_guest_log` WHERE `event_id`='$event' AND `guest_event_id`='$event') AS guest_datang, (SELECT COUNT(id) FROM `app_guest_log` WHERE `event_id`='$event' AND `guest_event_id`!='$event') AS guest_salah, COUNT(id) AS guest_total FROM `app_guest_log` WHERE `event_id`='$event'")->row_array();
	    return $query;
	}
	public function findguest_byqr($id) {
	    $t = $this->db->where('qr_id',$id)->count_all_results('app_guest');
        return ($t > 0);
	}
	public function getguest_event($guest_id)
	{ // get all event have by guest
		return $query = $this->db
			->select('app_guest_event.event_id,app_event.name')
			->from('app_guest_event')
			->join('app_event','app_event.id=app_guest_event.event_id')
			->where('app_guest_event.guest_id',$guest_id)
			->get()->result_array();
	}
	public function getguest_log($id)
	{
		return $query = $this->db
			// ->select('app_guest.*, (SELECT name FROM `app_event` WHERE id=app_guest.event_id) AS event_name')
			->select('app_guest_log.*, (SELECT name FROM `app_event` WHERE id=app_guest_log.event_id) AS event_name')
			->from('app_guest_log')
			->where('app_guest_log.guest_id',$id)
			->get()->result_array();
	}

	/* -- Qr Code -- */
	public function get_data($qrcode_data="")
	{
		$this->db->select('*')->from('app_guest');
	
		if(!empty($qrcode_data)){
			$this->db->where('qrcode_data', $qrcode_data);
		}
	
		$res = $this->db->get();
		return $res->result_array();
	}
	//generate qrcode data
	public function _generate_data_qrcode()
	{
		$this->load->helper('string');
		$code = strtoupper(random_string('alnum', 6));
		//proses cek data qrcode untuk memastikan data qrcode bersifat unik
		$cek_data=$this->get_data($code); 
		if(!empty($cek_data)){
			//jika data qrcode ada yang sama, maka karakter terakhir dari data qrcode
			//akan di-replace dengan angka jumlah data yang sama + 1
			$code = substr_replace($code, count($cek_data)+1, 5);
		}
		return $code;
	}
   	//generate image qrcode
	public function _generate_qrcode($fullname, $data_code)
	{
	  	//load libraru qrcode
	   	$this->load->library('ciqrcode');
   
	   	//persiapan direktori untuk menyimpan image qrcode hasil generate. 
	   	//Path dan nama direktori bisa kalian sesuaikan dengan kebutuhan kalian
	   	$directory = "./assets/img/qrcode";
		//persiapan filename untuk image qrcode. Diambil dari data fullname tanpa spasi + 3 digit angka random
		$file_name = str_replace(" ", "", strtolower($fullname)).rand(pow(10, 2), pow(10, 3)-1);
	
		//pembuatan direktori jika belum ada
		if (!is_dir($directory)) {
			mkdir($directory, 0777, TRUE);
		}
   
		$config['cacheable']    = true; //boolean, the default is true
		$config['quality']      = true; //boolean, the default is true
		$config['size']         = '1024'; //interger, the default is 1024
		$config['black']        = array(224,255,255); // array, default is array(255,255,255)
		$config['white']        = array(70,130,180); // array, default is array(0,0,0)
		$this->ciqrcode->initialize($config);
	
		//menyisipkan ekstensi png pada filename qrcode
		$image_name=$file_name.'.png';
	
		$params['data'] = $data_code; //data yang akan di jadikan QR CODE
		$params['level'] = 'H'; //H=High
		$params['size'] = 10;
		$params['savename'] = $directory.'/'.$image_name;
		
		$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
		
		return  $image_name;
	}

	// test import php excel
	public function add($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	
	public function get($where = 0) {
		if($where) 
		$this->db->where($where);
		$query = $this->db->get($this->table);
		return $query->row();
	}
	
	public function add_batch($data) {
		return $this->db->insert_batch($this->table, $data);
	}
}
?>