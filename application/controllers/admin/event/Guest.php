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
        // $data['guest'] =  $this->Model_event_guest->getall();
        // $data['guest'] =  $this->Model_event_guest->getall_join();
        $data['guest'] =  $this->Model_event_guest->getall_withevent();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        $new_data = array();
        foreach ($data['guest'] as $key => $value) {
            $event_data = $this->Model_event_guest->getguest_event($value['id']);

            // echo $key."<br>";
            $new_data['guest'][] = array(
                "id"   => $value['id'],
                "name" => $value['name'],
                "address" => $value['address'],
                "qrcode_path" => $value['qrcode_path'],
                "qrcode_data" => $value['qrcode_data'],
                "event_attendance" => $value['event_attendance'],
                "date" => $value['created_at'],
                "event_data" => $event_data
            );
        }
        $this->template->admin('admin/event/guest/view',$new_data);

        // echo "<pre>";
        // print_r($new_data);
        // echo "</pre>";
    }
    
    // open action
    public function add($id=null)
    {
        $data['guest'] =  $this->Model_event_guest->getall();
        $data['event'] =  $this->Model_event_event->getbyid($id);
        $data['event_id'] =  $id;
        $this->template->admin('admin/event/guest/add',$data);
    }
    public function detail($id=null)
    {
        if (!empty($id)) {
            // get all guest data by id
            $data['guest'] = $this->db->get_where('app_guest', ['id' => $id])->row_array();
            
            // get data guest event yg dimiliki
            $data['guest']['event_data'] = $this->Model_event_guest->getguest_event($data['guest']['id']);

            // get guest log
            $data['guest']['log'] = $this->Model_event_guest->getguest_log($data['guest']['id']);

            $data['return']['error'] = '3';
            $data['return']['msg'] = '';
            $this->template->admin('admin/event/guest/detail',$data);
        }
    }
    public function edit($id=null)
    {
        $data['guest'] = $this->db->get_where('app_guest', ['id' => $id])->row_array();
        $this->template->admin('admin/event/guest/edit',$data);
    }
    public function import()
    {   
        $data = array();
        $this->template->admin('admin/event/guest/import',$data);
    }
    
    // action
    public function insert() 
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
                'created_at' => date('Y-m-d H:i:s')
            );

            // catat ke tabel app_guest
            $this->db->insert('app_guest', $data);
            $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/event/guest')];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function update()
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
                    'updated_at' => date('Y-m-d H:i:s')
                );
    
                // catat ke tabel app_guest
                $this->db->update('app_guest', $data, ['id' => $post['id']]);
                $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/event/guest')];
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function delete($id=null) 
    {
        $data = array(
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('app_guest', $data, array('id' => $id));
        $json['success'] = 0;
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function import_process()
    {
        $this->load->library('excel');
        $file = $_FILES['fileExcel']['tmp_name'];
        $object = PHPExcel_IOFactory::load($file);
        $data = array();
        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();
            for ($row=2; $row<=$highestRow; $row++) {
                $data[] = array(
                    'name' => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                    'email' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                    'phone' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                    'address' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                    'qrcode_path' => $this->Model_event_guest->_generate_qrcode($worksheet->getCellByColumnAndRow(0, $row)->getValue(),$this->Model_event_guest->_generate_data_qrcode()), // memanggil method _generate_qrcode dengan mengirimkan dua parameter yaitu data fullname dan data qrcode
                    'created_at' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id')
                );
            }
        }
        // insert data to database
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                // memanggil method _generate_data_qrcode untuk proses generate data qrcode
                $qrcode_data = $this->Model_event_guest->_generate_data_qrcode();
                // insert data
                $this->db->insert('app_guest', array_merge($value, ['qrcode_data' => $qrcode_data]));
                // $this->db->insert('app_guest', $value);
            }
            // redirect('admin/event/guest');
            $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/event/guest')];
        } else {
            // redirect('admin/event/guest/import');
            $json = ['error' => true, 'msg' => 'data not save', 'redirect' => base_url('admin/event/guest/import')];
        }

        // return json response
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}

// next step tambahkan validasi untuk file excel yang diupload, email dan no telepon sudah ada belum

?>