<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vote extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
        $this->load->model('M_Notification');
        $this->load->model('M_Reward'); // Load M_Reward untuk memproses poin & log aktivitas
    }

    public function thread($thread_id) {
        $user_id = $this->session->userdata('user_id');

        $thread = $this->db->get_where('threads', ['id' => $thread_id])->row_array();
        if (!$thread) redirect('dashboard');

        $cek = $this->db->get_where('votes', [
            'user_id' => $user_id, 
            'thread_id' => $thread_id,
            'vote_type' => 'up'
        ])->row_array();

        if ($cek) {
            // JIKA UNLIKE THREAD: Hapus data vote asli
            $this->db->delete('votes', ['id' => $cek['id']]);

            // Hapus menggunakan parameter SLUG agar tidak merusak notifikasi milik user lain
            $this->M_Notification->remove($thread['user_id'], 'upvote_thread', $thread['slug']);

            // Poin dikurangi kembali & dicatat di tabel log
            $this->M_Reward->tambah_poin($thread['user_id'], -10, 'Pembatalan Upvote pada Topik');
        } else {
            // JIKA LIKE THREAD: Masukkan data & buat notifikasi baru
            $this->db->insert('votes', [
                'user_id' => $user_id,
                'thread_id' => $thread_id,
                'vote_type' => 'up'
            ]);

            $this->M_Notification->push($thread['user_id'], $user_id, 'upvote_thread', $thread['slug'], $thread['title']);

            // Berikan hadiah +10 poin kepada pemilik thread & dicatat di log
            $this->M_Reward->tambah_poin($thread['user_id'], 10, 'Menerima Upvote pada Topik');
        }

        redirect('thread/detail/' . $thread['slug']);
    }

    public function comment($comment_id) {
        $user_id = $this->session->userdata('user_id');

        $comment = $this->db->get_where('comments', ['id' => $comment_id])->row_array();
        if (!$comment) redirect('dashboard');
        
        $thread = $this->db->get_where('threads', ['id' => $comment['thread_id']])->row_array();

        $cek = $this->db->get_where('votes', [
            'user_id' => $user_id, 
            'comment_id' => $comment_id,
            'vote_type' => 'up'
        ])->row_array();

        if ($cek) {
            // JIKA UNLIKE KOMENTAR: Hapus data vote asli
            $this->db->delete('votes', ['id' => $cek['id']]);

            // Hapus menggunakan parameter SLUG agar aman dari bug salah hapus massal
            $this->M_Notification->remove($comment['user_id'], 'upvote_comment', $thread['slug']);

            // Poin dikurangi kembali & dicatat di tabel log
            $this->M_Reward->tambah_poin($comment['user_id'], -10, 'Pembatalan Upvote pada Balasan');
        } else {
            // JIKA LIKE KOMENTAR: Masukkan data & buat notifikasi + lampirkan anchor id komentar
            $this->db->insert('votes', [
                'user_id' => $user_id,
                'comment_id' => $comment_id,
                'vote_type' => 'up'
            ]);

            $this->M_Notification->push($comment['user_id'], $user_id, 'upvote_comment', $thread['slug'], $thread['title'], $comment_id);

            // Berikan hadiah +10 poin kepada pemilik komentar & dicatat di log
            $this->M_Reward->tambah_poin($comment['user_id'], 10, 'Menerima Upvote pada Balasan');
        }

        redirect('thread/detail/' . $thread['slug'] . '#comment-' . $comment_id);
    }
}