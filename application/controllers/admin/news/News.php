<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('logged_in') != 1) {
			redirect('admin');
		}

		$this->load->library('form_validation');
	}
	public function index()
	{
		$data['news'] = $this->Model_news->getall_join_with_category('all');
		$this->template->admin('admin/news/news/view',$data);
		// $this->load
		// 	->layout(false)
		// 	->view('admin/news/news/view', $data);
	}

	// View action
	public function add()
	{
		$data['category'] = $this->db->get_where('category_news', ['deleted_at' => null])->result_array();
		$this->template->admin('admin/news/news/add',$data);
		// $this->load
		// 	->layout(false)
		// 	->view('admin/news/news/add', $data);
	}

	public function edit($id = null)
	{
		if (!empty($id)) {
			$data['category'] = $this->db->get_where('category_news', ['deleted_at' => null])->result_array();
			$data['news'] = $this->db->get_where('news', ['news_id' => $id])->row_array();
			// echo json_encode($data);
			// return;
			$this->template->admin('admin/news/news/edit',$data);
			// $this->load
			// 	->layout(false)
			// 	->view('admin/news/news/edit', $data);
		}
	}
	public function detail($id = null)
	{
		if (!empty($id)) {
			// $data['category'] = $this->db->get_where('category_news', ['deleted_at' => null])->result_array();
			$this->db->select('news.*,category_news.category');
			$this->db->join('category_news','news.category_news_id = category_news.category_news_id');
			$data['news'] = $this->db->get_where('news', ['news_id' => $id])->row_array();
			$this->template->admin('admin/news/news/detail',$data);
			// $this->load
			// 	->layout(false)
			// 	->view('admin/news/news/detail', $data);
		}
	}

	// Action


	public function insert()
	{
		$post = $this->input->post(null, false);
		$this->form_validation
			->set_rules('title', 'Title', 'trim|required')
			->set_rules('category_news_id', 'Category', 'trim|required')
			->set_rules('content', 'Content', 'trim|required')
			->set_error_delimiters('', '');

		if ($this->form_validation->run() == FALSE) {
			$json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
		} else {
			$slug = function ($title) {
				$check = $this->db->get('brand')->result_array();
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

			if (!empty($_FILES['image']['name'])) {
				_mkdir();
				$config['upload_path']          = './storage/news';
				$config['allowed_types']        = 'gif|jpg|png';
				$config['encrypt_name'] 		= TRUE;
				// $config['max_size']             = 100;
				// $config['max_width']            = 1024;
				// $config['max_height']           = 768;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('image')) {
					$json = ['error' => true, 'msg' => $this->upload->display_errors()];
				} else {
					$file_name = $this->upload->data()['file_name'];
					$this->db->trans_begin();
					$data = array(
						'slug' => $slug(str_replace(array('&','-','/','#','_','*','%','@','^','~'),'',$post['title'])),
						'title' => $post['title'],
						'status' => $post['status'],
						'category_news_id' => $post['category_news_id'],
						'content' => $post['content'],
						'meta_title' => $post['meta_title'],
						'meta_keywords' => $post['meta_keywords'],
						'meta_description' => $post['meta_description'],
						'image' => 'storage/news/' . $file_name,
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $this->session->userdata('user_id'),
					);
					$this->db->insert('news', $data);
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$json = ['error' => false, 'msg' => 'Something went wrong :('];
					} else {
						$this->db->trans_commit();
						$json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/news/news')];
					}
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
			$slug = function ($title) {
				$toLowerTitle = url_title($title, 'dash', true);
				$slug = $toLowerTitle;
				$changed = $this->db->get_where('news', ['slug' => $toLowerTitle]);
				if ($changed->num_rows() < 1) :
					$check = $this->db->get('news')->result_array();
					$get_all_slug = array_column($check, 'slug');
					$x = 0;
					if (in_array($toLowerTitle, $get_all_slug)) :
						$x += 1;
					endif;
					if ($x > 0) :
						$slug = $toLowerTitle . '-' . $x;
					endif;
				endif;
				return $slug;
			};
			if (!empty($_FILES['image']['name'])) {
				_mkdir();
				$config['upload_path']          = './storage/news';
				$config['allowed_types']        = 'gif|jpg|png';
				$config['encrypt_name'] 		= TRUE;
				// $config['max_size']             = 100;
				// $config['max_width']            = 1024;
				// $config['max_height']           = 768;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('image')) {
					$json = ['error' => true, 'msg' => $this->upload->display_errors()];
				} else {
					$file_name = $this->upload->data()['file_name'];
					$this->db->trans_begin();
					$data = array(
						'slug' => $slug(str_replace(array('&','-','/','#','_','*','%','@','^','~'),'',$post['title'])),
						'title' => $post['title'],
						'status' => $post['status'],
						'category_news_id' => $post['category_news_id'],
						'content' => $post['content'],
						'image' => 'storage/news/' . $file_name,
						'meta_title' => $post['meta_title'],
						'meta_keywords' => $post['meta_keywords'],
						'meta_description' => $post['meta_description'],
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $this->session->userdata('user_id'),
					);
					$this->db->update('news', $data, ['news_id' => $post['news_id']]);
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$json = ['error' => false, 'msg' => 'Something went wrong :('];
					} else {
						$this->db->trans_commit();
						$json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/news/news')];
					}
				}
			} else {
				$this->db->trans_begin();
				$data = array(
					'slug' => $slug(str_replace(array('&','-','/','#','_','*','%','@','^','~'),'',$post['title'])),
					'title' => $post['title'],
					'status' => $post['status'],
					'category_news_id' => $post['category_news_id'],
					'content' => $post['content'],
					'meta_title' => $post['meta_title'],
					'meta_keywords' => $post['meta_keywords'],
					'meta_description' => $post['meta_description'],
					'created_at' => date('Y-m-d H:i:s'),
					'created_by' => $this->session->userdata('user_id'),
				);
				$this->db->update('news', $data, ['news_id' => $post['news_id']]);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$json = ['error' => false, 'msg' => 'Something went wrong :('];
				} else {
					$this->db->trans_commit();
					$json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/news/news')];
				}
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
		$this->db->update('news', $data, array('news_id' => $id));
		$json['success'] = 1;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
}
