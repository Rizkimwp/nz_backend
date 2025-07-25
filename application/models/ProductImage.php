<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_image_model extends CI_Model {

    protected $table = 'product_images';

    public function __construct()
    {
        parent::__construct();
    }

    // Menyimpan data gambar
    public function save($data, $id = null)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->update($this->table, $data);
        } else {
            return $this->db->insert($this->table, $data);
        }
    }

    // Ambil semua gambar untuk 1 produk
    public function get_by_product($product_id)
    {
        return $this->db->get_where($this->table, ['product_id' => $product_id])->result();
    }

    // Ambil 1 gambar by ID
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    // Soft delete (butuh kolom deleted_at di tabel)
    public function soft_delete($id)
    {
        return $this->db->where('id', $id)->update($this->table, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Hapus permanen
    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}