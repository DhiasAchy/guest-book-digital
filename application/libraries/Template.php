<?php
class Template
{
    protected $_ci;

    function __construct()
    {
        $this->_ci = &get_instance();
    }

    function admin($content, $data = NULL)
    {
        $data['content'] = $this->_ci->load->layout(false)->view($content, $data, TRUE);
        $this->_ci->load->layout(false)->view('layouts/admin', $data);
    }
  
}
