<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_lib
{
  /**
   * Constructor
   * 
   * @access public
   * @return void
   */
  public function __construct()
  {
    $this->ci = &get_instance();

    $this->ci->load->helper('form');
  }

  /**
   * Get user id
   * 
   * @access public
   * @return string
   */
  public function kode_barang()
  {
    $max_kode = $this->ci->db
      ->select('MAX(id) as kode_id')
      ->from('tb_product')
      ->where('date_deleted', NULL)
      ->get()
      ->row_array();

    $years = date('Y');
    $month = date('m');
    $d = date('d');
    $kode = $max_kode['kode_id'] + 1;

    $kode_barang = $years . $month . $d . $kode;

    return $kode_barang;
  }


  public function kode_transaksi()
  {

    $max_kode = $this->ci->db
      ->select('MAX(id) as kode_id')
      ->from('tb_penjualan')
      ->where('date_deleted', NULL)
      ->get()
      ->row_array();

    $years = date('Y');
    $month = date('m');
    $d = date('d');
    $kode = $max_kode['kode_id'] + 1;

    $kode_transaksi = 'T-' . $years . $month . $d . $kode;

    return $kode_transaksi;
  }

}
