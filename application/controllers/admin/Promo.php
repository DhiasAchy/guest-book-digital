<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promo extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('logged_in') != 1) {
			redirect('admin');
		}

		$this->load->library('form_validation');

		$this->load->model('Model_promo');
	}
	public function index()
	{
		$promo = $this->Model_promo->getall();

		$data['promo'] =  $promo;
		$this->template->admin('admin/promo/view',$data);

		// $this->load
		// 	->layout(false)
		// 	->view('admin/promo/view', $data);
	}

	// View action
	public function add()
	{
		$this->template->admin('admin/promo/add');	
		// $this->load
		// 	->layout(false)
		// 	->view('admin/promo/add');
	}

	public function edit($id = null)
	{
		if (!empty($id)) {
			$data['promo'] = $this->Model_promo->getbyid($id);
			$this->template->admin('admin/promo/edit',$data);
			// $this->load
			// 	->layout(false)
			// 	->view('admin/promo/edit', $data);
		}
	}

	// Action


	public function insert()
	{
		$post = $this->input->post(null, false);
		$this->form_validation
			->set_rules('title', 'Title', 'trim|required')
			->set_error_delimiters('', '');

		if ($this->form_validation->run() == FALSE) {
			$json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
		} else {
			if (!empty($_FILES['image']['name'])) {
				_mkdir();
				$config['upload_path']          = './storage/promo';
				$config['allowed_types']        = 'gif|jpg|png';
				$config['encrypt_name'] = TRUE;
				// $config['max_size']             = 100;
				// $config['max_width']            = 1024;
				// $config['max_height']           = 768;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('image')) {
					$json = ['error' => true, 'msg' => $this->upload->display_errors()];
				} else {
					$file_name = $this->upload->data()['file_name'];
					$data = array(
						'title' => $post['title'],
						'image' => 'storage/promo/' . $file_name,
						'created_at' => date('Y-m-d H:i:s')
					);

					$this->db->insert('promo', $data);
					$json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/promo')];
				}
			} else {
				$json = ['error' => true, 'msg' => 'Please upload file!'];
			}
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
	public function update()
	{
		$post = $this->input->post(null, false);
		$this->form_validation
			->set_rules('title', 'Title', 'trim|required')
			->set_error_delimiters('', '');

		if ($this->form_validation->run() == FALSE) {
			$json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
		} else {
			if (!empty($_FILES['image']['name'])) {
				_mkdir();
				$config['upload_path']          = './storage/promo';
				$config['allowed_types']        = 'gif|jpg|png';
				$config['encrypt_name'] = TRUE;
				// $config['max_size']             = 100;
				// $config['max_width']            = 1024;
				// $config['max_height']           = 768;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('image')) {
					$json = ['error' => true, 'msg' => $this->upload->display_errors()];
				} else {
					$file_name = $this->upload->data()['file_name'];
					$data = array(
						'title' => $post['title'],
						'image' => 'storage/promo/' . $file_name,
						'created_at' => date('Y-m-d H:i:s')
					);

					$this->db->update('promo', $data, ['promo_id' => $post['promo_id']]);
					$json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/promo')];
				}
			} else {
				$data = array(
					'title' => $post['title'],
					'created_at' => date('Y-m-d H:i:s')
				);

				$this->db->update('promo', $data, ['promo_id' => $post['promo_id']]);
				$json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/promo')];
			}
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function delete($id = null)
	{
		$data = array(
			'updated_at' => date('Y-m-d H:i:s'),
			'deleted_at' => date('Y-m-d H:i:s')
		);
		$this->db->update('promo', $data, array('promo_id' => $id));
		$json['success'] = 1;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
}
