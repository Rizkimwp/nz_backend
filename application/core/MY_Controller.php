<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $auth_user;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');

        // Middleware login check
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        // Set data user global ke semua view
        $this->auth_user = array(
            'user_id' => $this->session->userdata('user_id'),
            'name' => $this->session->userdata('name'),
            'email' => $this->session->userdata('email'),
        );
        $this->load->vars(['auth_user' => $this->auth_user]);
    }
}