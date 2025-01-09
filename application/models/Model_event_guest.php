<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_event_guest extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
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
	public function getall_by_parameter($value=null)
	{
		return $query = $this->db
			->select('*')
			->from('app_guest')
			->where('deleted_at', NULL)
			->where('qr_id', $value)
			->order_by('created_at', 'asc')
			->get()
			->row_array();
		return $query = $this->db
		    ->query("SELECT * FROM `app_guest` WHERE `qr_id`='$value' NULL AND `deleted_at` IS NULL ")
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
}
?>