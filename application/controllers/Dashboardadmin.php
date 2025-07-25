<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboardadmin extends MY_Controller {

  public function index()
  {
    $data = [
      'title' => 'Dashboard Admin',
      'content' => 'admin/dashboard'
    ];
    $this->load->view('admin/index', $data);
  }

}