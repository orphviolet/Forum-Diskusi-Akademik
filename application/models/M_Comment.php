<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Comment extends CI_Model {

    public function __construct() {
        parent::__construct();
        // BERSIH: Pemanggilan M_Badge yang memicu error telah dihapus total dari sini
    }

    public function get_comments_by_thread($thread_id) {
        // REVISI: Menambahkan users.avatar ke dalam select komentar
        $this->db->select('comments.*, users.username, users.nama, users.kelas, users.avatar');
        $this->db->from('comments');
        $this->db->join('users', 'users.id = comments.user_id');
        $this->db->where('comments.thread_id', $thread_id);
        
        // FIXED: Memastikan komentar yang sudah otomatis di-hide/disembunyikan oleh report sistem tidak ikut dipanggil
        $this->db->where('comments.is_hidden', 0);
        
        $this->db->order_by('comments.created_at', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_comment_by_id($id) {
        return $this->db->get_where('comments', ['id' => $id])->row_array();
    }

    public function insert_comment($data) {
        // BERSIH: Langsung lakukan insert data komentar biasa tanpa memicu pengecekan badge lagi
        return $this->db->insert('comments', $data);
    }

    public function update_comment($id, $content, $image = null) {
        $this->db->where('id', $id);
        return $this->db->update('comments', [
            'comment' => $content,
            'image' => $image
        ]);
    }

    public function delete_comment($id) {
        $this->db->where('id', $id);
        return $this->db->delete('comments');
    }
}