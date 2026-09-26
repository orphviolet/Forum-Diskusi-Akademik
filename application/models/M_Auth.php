<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Auth extends CI_Model {

    // Fungsi untuk mengambil data user berdasarkan username (Tanpa cek is_active)
    public function check_login($username) {
        $this->db->where('username', $username);
        return $this->db->get('users')->row_array();
    }

    public function check_admin($username) {
        return $this->db
                    ->get_where('admins', ['username' => $username])
                    ->row_array();
    }

    public function register_user($data) {
        return $this->db->insert('users', $data);
    }
}