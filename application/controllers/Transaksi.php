<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Memuat User_model
		$this->load->model('Product_transaction_detail_model');
		// Memuat library form validation
		$this->load->library('form_validation');
		// Memuat library session
		$this->load->library('session');
		// Set header agar bisa diakses dari luar

	}

	public function index()
	{
		$data = [
			'title' => 'Data Transaksi',
			'content' => 'admin/transaksi',
			'transactions' => $this->Product_transaction_detail_model->get_all(), // Ambil semua data transaksi
		];
		$this->load->view('admin/index', $data);
	}
}