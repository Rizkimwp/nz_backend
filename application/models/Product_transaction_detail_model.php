<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_transaction_detail_model extends CI_Model
{

	protected $table = 'product_transaction_details';

	public function __construct()
	{
		parent::__construct();
	}

	// Simpan atau update detail transaksi
	public function save($data, $id = null)
	{
		if ($id) {
			$this->db->where('id', $id);
			return $this->db->update($this->table, $data);
		} else {
			return $this->db->insert($this->table, $data);
		}
	}

	// Ambil semua detail transaksi
	public function get_all()
	{
		$this->db->select('
        product_transaction_details.*,
        products.name AS product_name,
        products.thumbnail AS product_thumbnail,
        product_transactions.name AS customer_name,
        product_transactions.phone,
        product_transactions.email,
        product_transactions.booking_trx_id,
        product_transactions.address,
        product_transactions.sub_total_amount,
        product_transactions.grand_total_amount,
        product_transactions.is_paid,
        product_transactions.created_at AS transaction_date
    ');
		$this->db->from('product_transaction_details');
		$this->db->join('products', 'products.id = product_transaction_details.product_id');
		$this->db->join('product_transactions', 'product_transactions.id = product_transaction_details.product_transaction_id');
		$this->db->where('product_transactions.deleted_at IS NULL');
		$this->db->order_by('product_transaction_details.created_at', 'DESC');

		return $this->db->get()->result();
	}


	// Ambil detail by ID
	public function get_by_id($id)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row();
	}

	// Ambil detail berdasarkan ID transaksi
	public function get_by_transaction($transaction_id)
	{
		return $this->db->get_where($this->table, ['product_transaction_id' => $transaction_id])->result();
	}

	// Ambil data produk dari relasi (butuh model Product_model)
	public function get_product($product_id)
	{
		$this->load->model('Product_model');
		return $this->Product_model->get_by_id($product_id);
	}

	// Ambil data transaksi dari relasi (butuh model Product_transaction_model)
	public function get_transaction($transaction_id)
	{
		$this->load->model('Product_transaction_model');
		return $this->Product_transaction_model->get_by_id($transaction_id);
	}

	// Soft delete (butuh kolom deleted_at)
	public function soft_delete($id)
	{
		return $this->db->where('id', $id)->update($this->table, [
			'deleted_at' => date('Y-m-d H:i:s')
		]);
	}

	public function insert($data)
	{
		return $this->db->insert('product_transaction_details', $data);
	}

	public function get_by_transaction_id($transaction_id)
	{
		return $this->db->get_where('product_transaction_details', ['product_transaction_id' => $transaction_id])->result();
	}

	public function delete_by_transaction_id($transaction_id)
	{
		return $this->db->delete('product_transaction_details', ['product_transaction_id' => $transaction_id]);
	}

}