<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{

	// Tabel utama
	protected $table = 'products';

	public function __construct()
	{
		parent::__construct();
	}

    public function decrease_stock($id, $qty) {
        $this->db->set('stock', 'stock - ' . (int)$qty, false);
        $this->db->where('id', $id);
        $this->db->update('products');
    }
	public function get_published_products()
	{
        return $this->db->get_where('products', ['is_published' => 1])->result();
    }

    public function get_product_by_slug($slug)
    {
        $this->db->where('slug', $slug);
        $this->db->where('is_published', 1);
        $product = $this->db->get('products')->row();

        if ($product) {
            $product->images = $this->db->get_where('product_images', ['product_id' => $product->id])->result();
        }

        return $product;
    }

	// Menambahkan atau memperbarui data (insert/update)
	public function save($data, $id = null)
	{
		// Set slug dari nama
		if (isset($data['name'])) {
			$data['slug'] = url_title($data['name'], '-', TRUE);
		}

		// Set default timestamps
		$now = date('Y-m-d H:i:s');
		if ($id) {
			$data['updated_at'] = $now;
			$this->db->where('id', $id);
			return $this->db->update($this->table, $data);
		} else {
			$data['created_at'] = $now;
			$data['updated_at'] = $now;
			return $this->db->insert($this->table, $data);
		}
	}


	// Controller
	public function destroy($id)
{
    $this->db->where('id', $id);
    $this->db->delete('products'); // Ganti 'products' dengan nama tabel yang kamu pakai

    
}


	// Mengambil semua produk
	public function get_all()
	{
		return $this->db->get($this->table)->result();
	}

	// Mengambil satu produk by ID
	public function get_by_id($id)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row();
	}

	// Soft delete: ubah `deleted_at` (asumsinya kamu punya kolom ini)
	public function soft_delete($id)
	{
		return $this->db->where('id', $id)->update($this->table, [
			'deleted_at' => date('Y-m-d H:i:s')
		]);
	}

	// Mengambil data relasi ke gambar produk
	public function get_images($product_id)
	{
		return $this->db->get_where('product_images', ['product_id' => $product_id])->result();
	}

	public function find($id)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row();
	}

	public function increase_stock($product_id, $qty)
	{
		$this->db->set('stock', 'stock + ' . (int)$qty, false);
		$this->db->where('id', $product_id);
		return $this->db->update('products');
	}
	
}