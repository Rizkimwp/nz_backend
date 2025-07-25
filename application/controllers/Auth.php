<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session'); // ✅ WAJIB
		$this->load->helper(['form', 'url']); // ✅ Untuk form & redirect
	}

	public function login()
	{
		$this->load->view('auth/login');
	}

	public function loginProcess()
	{
		// Jika sudah login, redirect ke dashboard
		if ($this->session->userdata('logged_in')) {
			redirect('dashboard');
		}

		// Aturan validasi form
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() === FALSE) {
			// Tampilkan kembali form login dengan error
			$this->load->view('auth/login'); // Ganti sesuai view kamu
		} else {
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$user = $this->User_model->validate_login($email, $password);

			if ($user) {
				// Login berhasil, set session
				$user_data = array(
					'user_id' => $user->id,
					'name' => $user->name,
					'email' => $user->email,
					'logged_in' => TRUE
				);
				$this->session->set_userdata($user_data);

				// Set flash message sukses
				$this->session->set_flashdata('success', 'Login berhasil! Selamat datang.');

				redirect('dashboard');
			} else {
				// Login gagal
				$this->session->set_flashdata('error', 'Email atau password salah.');
				redirect('masuk');
			}
		}
	}


	// --- Logout ---
	public function logout()
	{
		// Hapus semua data session terkait user
		$this->session->unset_userdata(array('user_id', 'name', 'email', 'logged_in'));

		// Set flashdata pesan sukses
		$this->session->set_flashdata('success', 'Anda telah logout.');

		// Redirect kembali ke halaman login
		redirect('masuk'); // atau 'auth/login' sesuai routing kamu
	}

}