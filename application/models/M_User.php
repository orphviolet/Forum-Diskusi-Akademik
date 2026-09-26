<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_User extends CI_Model {

    public function get_user_by_id($user_id) {
        return $this->db->get_where('users', ['id' => $user_id])->row_array();
    }

    public function update_user($user_id, $data) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    public function check_username_except($username, $user_id)
{
    return $this->db
        ->where('username', $username)
        ->where('id !=', $user_id)
        ->get('users')
        ->row_array();
}

    // Hitung total diskusi yang pernah dibuat user
    public function count_user_threads($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('threads');
    }

    // Hitung total komentar/balasan yang pernah ditulis user
    public function count_user_comments($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('comments');
    }
}