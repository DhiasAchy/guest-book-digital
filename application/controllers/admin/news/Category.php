<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
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
    $data['category'] = $this->db->get_where('category_news', ['deleted_at' => null])->result_array();
    $this->template->admin('admin/news/category/view',$data);
    // $this->load
    //   ->layout(false)
    //   ->view('admin/news/category/view', $data);
  }

  // View action
  public function add()
  {
    $this->template->admin('admin/news/category/add');
    // $this->load
    //   ->layout(false)
    //   ->view('admin/news/category/add');
  }

  public function edit($id = null)
  {
    if (!empty($id)) {
      $data['category'] = $this->db->get_where('category_news', ['category_news_id' => $id])->row_array();
      $this->template->admin('admin/news/category/edit',$data);
      // $this->load
      //   ->layout(false)
      //   ->view('admin/news/category/edit', $data);
    }
  }

  // Action


  public function insert()
  {
    $post = $this->input->post(null, false);
    $this->form_validation
      ->set_rules('category', 'Category', 'trim|required')
      ->set_error_delimiters('', '');

    if ($this->form_validation->run() == FALSE) {
      $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
    } else {
      $list_ctg = function () {
        $category = $this->db->get_where('category_news', ['deleted_at' => null])->result_array();
        return array_map('strtolower', array_column($category, 'category'));
      };
      if (in_array(strtolower($post['category']), $list_ctg())) {
        $json = ['error' => true, 'msg' => 'category already exists ', 'redirect' => base_url('admin/news/category')];
      } else {
        $data = [
          'category' => $post['category'],
        ];
        $this->db->insert('category_news', $data);
        $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/news/category')];
      }
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($json));
  }
  public function update()
  {
    $post = $this->input->post(null, false);
    $this->form_validation
      ->set_rules('category_news_id', 'CategoryID', 'trim|required')
      ->set_rules('category', 'Category', 'trim|required')
      ->set_error_delimiters('', '');

    if ($this->form_validation->run() == FALSE) {
      $json = ['error' => true, 'msg' => $this->form_validation->get_errors(), 'form_validation' => true];
    } else {
      $changes = function($category,$id){
        $ctg = $this->db->get_where('category_news', ['category_news_id' => $id])->row_array();
        $return = false;
        if(strtolower($ctg['category']) != strtolower($category)){
          $return = true;
        }
        return $return;
      };
      $list_ctg = function () {
        $category = $this->db->get_where('category_news', ['deleted_at' => null])->result_array();
        return array_map('strtolower', array_column($category, 'category'));
      };

      if($changes($post['category'],$post['category_news_id'])):
        if (in_array(strtolower($post['category']), $list_ctg())) {
          $json = ['error' => true, 'msg' => 'category already exists ', 'redirect' => base_url('admin/news/category')];
        } else {
          $data = [
            'category' => $post['category'],
          ];
          $this->db->update('category_news', $data,['category_news_id' => $post['category_news_id']]);
          $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/news/category')];
        }
      else:
        $json = ['error' => false, 'msg' => 'data saved successfully', 'redirect' => base_url('admin/news/category')];
      endif;
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
