<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Memuat User_model
		$this->load->model('Product_model');
		// Memuat library form validation
		$this->load->library('form_validation');
		// Memuat library session
		$this->load->library('session');
		// Set header agar bisa diakses dari luar

	}

	public function index()
	{
		$data = [
			'title' => 'Data Product',
			'content' => 'admin/product',
			'products' => $this->Product_model->get_all(), // Ambil semua data produk

		];
		$this->load->view('admin/index', $data);
	}
	public function store()
	{
		// Load form validation library
		$this->load->library('form_validation');

		// Aturan validasi form input
		$this->form_validation->set_rules('name', 'Nama Produk', 'required|trim');
		$this->form_validation->set_rules('description', 'Deskripsi', 'required|trim');
		$this->form_validation->set_rules('price', 'Harga', 'required|numeric');
		$this->form_validation->set_rules('discount', 'Diskon', 'numeric');
		$this->form_validation->set_rules('stock', 'Stok', 'required|integer');

		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, kembali ke halaman sebelumnya
			$this->session->set_flashdata('error', validation_errors());
			redirect('product/index');
			return;
		}

		// Setup konfigurasi upload
		$config['upload_path'] = './uploads/thumbnails/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = 2048; // dalam KB
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);

		// Validasi upload gambar
		if (!$this->upload->do_upload('thumbnail')) {
			$this->session->set_flashdata('error', $this->upload->display_errors());
			redirect('product/index');
			return;
		}

		// Jika berhasil upload
		$uploadData = $this->upload->data();
		$thumbnail = 'uploads/thumbnails/' . $uploadData['file_name'];

		// Ambil semua input
		$post = $this->input->post();
		$data = [
			'name' => $post['name'],
			'thumbnail' => $thumbnail,
			'description' => $post['description'],
			'price' => $post['price'],
			'discount' => $post['discount'] ?? 0,
			'stock' => $post['stock'],
			'is_populer' => isset($post['is_populer']) ? 1 : 0,
			'is_published' => isset($post['is_published']) ? 1 : 0,
		];

		// Simpan ke database
		$this->Product_model->save($data);

		// Redirect dengan pesan sukses
		$this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
		redirect('product/index');
	}



	public function update($id)
	{
		$this->load->library('form_validation');

		// Validasi input
		$this->form_validation->set_rules('name', 'Nama Produk', 'required|trim');
		$this->form_validation->set_rules('description', 'Deskripsi', 'required|trim');
		$this->form_validation->set_rules('price', 'Harga', 'required|numeric');
		$this->form_validation->set_rules('discount', 'Diskon', 'numeric');
		$this->form_validation->set_rules('stock', 'Stok', 'required|integer');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('product/index');
			return;
		}

		$post = $this->input->post();

		// Ambil data lama
		$oldProduct = $this->Product_model->find($id);
		$thumbnail = $oldProduct->thumbnail;

		// Konfigurasi upload thumbnail
		$config['upload_path'] = './uploads/thumbnails/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = 2048;
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);

		// Jika ada upload thumbnail
		if (!empty($_FILES['thumbnail']['name'])) {
			if ($this->upload->do_upload('thumbnail')) {
				$uploadData = $this->upload->data();
				$thumbnail = 'uploads/thumbnails/' . $uploadData['file_name'];

				// Hapus thumbnail lama jika ada
				if (file_exists($oldProduct->thumbnail)) {
					unlink($oldProduct->thumbnail);
				}
			} else {
				$this->session->set_flashdata('error', $this->upload->display_errors());
				redirect('product/index');
				return;
			}
		}

		// Data update
		$data = [
			'name' => $post['name'],
			'thumbnail' => $thumbnail,
			'description' => $post['description'],
			'price' => $post['price'],
			'discount' => $post['discount'] ?? 0,
			'stock' => $post['stock'],
			'is_populer' => isset($post['is_populer']) ? 1 : 0,
			'is_published' => isset($post['is_published']) ? 1 : 0,
		];

		$this->Product_model->save($data, $id);

		$this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
		redirect('product/index');
	}



	public function delete($id)
	{
		$this->Product_model->destroy($id); // Soft delete

		$this->session->set_flashdata('success', 'Produk berhasil dihapus.');
		redirect('product/index');
	}

}