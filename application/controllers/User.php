<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Memuat User_model
		$this->load->model('User_model');
		// Memuat library form validation
		$this->load->library('form_validation');
		// Memuat library session
		$this->load->library('session');
	}

	// --- Halaman Registrasi ---
	public function register()
	{
		// Jika sudah login, redirect ke dashboard
		if ($this->session->userdata('logged_in')) {
			redirect('dashboard');
		}

		// Aturan validasi form
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[4]|is_unique[users.username]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		$this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|matches[password]');

		if ($this->form_validation->run() === FALSE) {
			// Tampilkan form registrasi dengan error validasi
			$this->load->view('templates/header'); // Asumsi ada header
			$this->load->view('user/register');
			$this->load->view('templates/footer'); // Asumsi ada footer
		} else {
			// Data valid, simpan user baru
			$data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => $this->input->post('password') // Password akan di-hash di model
			);

			if ($this->User_model->save($data)) {
				$this->session->unset_flashdata('success', 'Registrasi berhasil! Silakan login.');
				redirect('user/login');
			} else {
				$this->session->unset_flashdata('error', 'Registrasi gagal. Mohon coba lagi.');
				redirect('user/register');
			}
		}
	}

	// --- Halaman Login ---
	

	// --- Daftar Pengguna (Perlu Otentikasi & Otorisasi) ---
	public function index() // Atau bisa dinamai `list_users`
	{
		// // Contoh proteksi: hanya bisa diakses jika sudah login
		// if (!$this->session->userdata('logged_in')) {
		//     $this->session->unset_flashdata('error', 'Anda harus login untuk mengakses halaman ini.');
		//     redirect('user/login');
		// }
		$data = [
			'title' => 'Dashboard Admin',
			'content' => 'admin/user',
			'users' => $this->User_model->get_all()
		];


		$this->load->view('admin/index', $data); // Tampilkan daftar user

	}

	// --- Halaman Profil Pengguna ---
	public function profile($id = null)
	{
		// Pastikan user sudah login
		if (!$this->session->userdata('logged_in')) {
			$this->session->unset_flashdata('error', 'Anda harus login untuk melihat profil.');
			redirect('user/login');
		}

		// Jika ID tidak diberikan, gunakan ID user yang sedang login
		if (empty($id)) {
			$id = $this->session->userdata('user_id');
		}

		$data['user'] = $this->User_model->get_by_id($id);

		if (empty($data['user'])) {
			show_404(); // Tampilkan halaman 404 jika user tidak ditemukan
		}

		$this->load->view('templates/header');
		$this->load->view('user/profile', $data);
		$this->load->view('templates/footer');
	}

	// --- Edit Profil Pengguna ---
	public function edit_profile()
	{
		// Pastikan user sudah login
		if (!$this->session->userdata('logged_in')) {
			$this->session->unset_flashdata('error', 'Anda harus login untuk mengedit profil.');
			redirect('user/login');
		}

		$user_id = $this->session->userdata('user_id');
		$data['user'] = $this->User_model->get_by_id($user_id);

		// Set rules untuk validasi (abaikan validasi is_unique untuk email saat update)
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[4]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		// Hanya validasi password jika diisi
		if ($this->input->post('password')) {
			$this->form_validation->set_rules('password', 'Password Baru', 'min_length[6]');
			$this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'matches[password]');
		}

		if ($this->form_validation->run() === FALSE) {
			// Tampilkan form edit dengan error validasi
			$this->load->view('templates/header');
			$this->load->view('user/edit_profile', $data);
			$this->load->view('templates/footer');
		} else {
			$update_data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email')
			);

			// Tambahkan password jika diisi
			if ($this->input->post('password')) {
				$update_data['password'] = $this->input->post('password');
			}

			if ($this->User_model->save($update_data, $user_id)) {
				// Perbarui session jika username/email berubah
				$this->session->set_userdata('username', $this->input->post('username'));
				$this->session->set_userdata('email', $this->input->post('email'));

				$this->session->unset_flashdata('success', 'Profil berhasil diperbarui.');
				redirect('user/profile');
			} else {
				$this->session->unset_flashdata('error', 'Gagal memperbarui profil. Mohon coba lagi.');
				redirect('user/edit_profile');
			}
		}
	}

	public function add()
	{
		$data = [
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'password' => $this->input->post('password'), // optional
		];

		$this->User_model->save($data);

		redirect('user');
	}

	public function update($id)
	{
		// Validasi input
		$this->form_validation->set_rules('name', 'Nama', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		// Jika ada field password, boleh kosong
		if ($this->input->post('password')) {
			$this->form_validation->set_rules('password', 'Password', 'min_length[6]');
		}

		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, kembalikan ke halaman sebelumnya
			$this->session->unset_flashdata('error', validation_errors('<div>', '</div>'));
			redirect('user'); // atau redirect ke halaman edit
		}

		// Ambil data dari form
		$data = [
			'name' => $this->input->post('name', TRUE),
			'email' => $this->input->post('email', TRUE),
		];

		// Tambahkan password jika diisi
		$password = $this->input->post('password');
		if (!empty($password)) {
			$data['password'] = $password;
		}

		// Simpan ke model
		$this->load->model('User_model');
		$this->User_model->save($data, $id);

		$this->session->unset_flashdata('success', 'Data user berhasil diperbarui.');
		redirect('user');
	}


	// --- Hapus Pengguna (Admin Only, perlu otorisasi lebih lanjut) ---
	public function delete($id)
	{
		// // Proteksi: Hanya admin yang bisa menghapus user lain
		// // Anda perlu implementasi logika otorisasi peran di sini
		// if (!$this->session->userdata('logged_in') /* || !$this->session->userdata('is_admin') */) {
		//     $this->session->unset_flashdata('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
		//     redirect('user/login');
		// }

		if ($this->User_model->delete($id)) {
			$this->session->unset_flashdata('success', 'Pengguna berhasil dihapus.');
			redirect('user'); // Redirect ke daftar pengguna
		} else {
			$this->session->unset_flashdata('error', 'Gagal menghapus pengguna.');
			redirect('user');
		}
	}
}