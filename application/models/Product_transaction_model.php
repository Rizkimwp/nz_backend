<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_transaction_model extends CI_Model {

    protected $table = 'product_transactions';

    public function __construct()
    {
        parent::__construct();
    }
	public function updateIsPaid($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('product_transactions', [
            'is_paid' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    // Simpan atau update transaksi
	public function insert($data) {
        $this->db->insert('product_transactions', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('product_transactions', $data);
    }
    public function save($data, $id = null)
    {
        if (!isset($data['booking_trx_id'])) {
            $data['booking_trx_id'] = $this->generate_unique_trx_id();
        }

        if ($id) {
            $this->db->where('id', $id);
            return $this->db->update($this->table, $data);
        } else {
            return $this->db->insert($this->table, $data);
        }
    }

    // Generate kode transaksi unik
    public function generate_unique_trx_id()
    {
        $prefix = 'NS';
        do {
            $randomString = $prefix . mt_rand(100, 9999);
            $exists = $this->db->get_where($this->table, ['booking_trx_id' => $randomString])->num_rows() > 0;
        } while ($exists);

        return $randomString;
    }

    // Ambil semua transaksi
    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    // Ambil 1 transaksi berdasarkan ID
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    // Ambil detail produk untuk transaksi tertentu
    public function get_details($transaction_id)
    {
        return $this->db->get_where('product_transaction_details', ['product_transaction_id' => $transaction_id])->result();
    }

    // Soft delete (butuh kolom deleted_at)
    public function soft_delete($id)
    {
        return $this->db->where('id', $id)->update($this->table, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

	public function delete($id)
{
    return $this->db->delete('product_transactions', ['id' => $id]);
}
}