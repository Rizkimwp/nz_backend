<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo_code_model extends CI_Model {

    protected $table = 'promo_codes';

    public function __construct()
    {
        parent::__construct();
    }

    // Simpan atau update data promo
    public function save($data, $id = null)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->update($this->table, $data);
        } else {
            return $this->db->insert($this->table, $data);
        }
    }

    // Ambil semua promo
    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    // Ambil promo berdasarkan ID
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    // Ambil promo berdasarkan kode
    public function get_by_code($code)
    {
        return $this->db->get_where($this->table, ['code' => $code])->row();
    }

    // Soft delete promo (butuh kolom deleted_at)
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