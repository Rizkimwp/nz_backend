<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    // Simpan data user (insert/update)
    public function save($data, $id = null)
    {
        // Hash password jika ada
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if ($id) {
            $this->db->where('id', $id);
            return $this->db->update($this->table, $data);
        } else {
            return $this->db->insert($this->table, $data);
        }
    }

    // Ambil semua user
    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    // Ambil user berdasarkan ID
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    // Ambil user berdasarkan email
    public function get_by_email($email)
    {
        return $this->db->get_where($this->table, ['email' => $email])->row();
    }

    // Validasi login manual
    public function validate_login($email, $password)
    {
        $user = $this->get_by_email($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    // Soft delete atau hapus permanen (jika tidak pakai soft delete)
    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }


}