<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Badge extends CI_Model {

    // Fungsi mengambil semua lencana yang tersedia di forum
    public function get_all_badges() {
        return [
            1 => ['nama' => 'Anggota Resmi', 'deskripsi' => 'Telah melengkapi profil pendaftaran atau membuat thread pertama.', 'ikon' => 'bi-person-check-fill', 'warna' => 'text-success'],
            2 => ['nama' => 'Komentator Aktif', 'deskripsi' => 'Menulis minimal 10 komentar di forum.', 'ikon' => 'bi-chat-quote-fill', 'warna' => 'text-warning'],
            3 => ['nama' => 'Bintang Forum', 'deskripsi' => 'Salah satu thread atau komentar menembus 10 likes.', 'ikon' => 'bi-star-fill', 'warna' => 'text-primary'],
            4 => ['nama' => 'Juru Selamat', 'deskripsi' => 'Balasan Anda dipilih sebagai jawaban terbaik sebanyak 3 kali.', 'ikon' => 'bi-shield-check', 'warna' => 'text-danger']
        ];
    }

    // Fungsi mengecek lencana yang SUDAH DIMILIKI oleh user tertentu
    public function get_user_badges($user_id) {
        $query = $this->db->get_where('user_badges', ['user_id' => $user_id])->result_array();
        $owned = [];
        foreach ($query as $row) {
            $owned[] = $row['badge_id'];
        }
        return $owned;
    }

    /**
     * FUNGSI MASTER CEK BADGE OTOMATIS
     */
    public function check_and_grant_badges($user_id) {
        $owned_badges = $this->get_user_badges($user_id);
        $all_badges = $this->get_all_badges();

        // -------------------------------------------------------------------------
        // BADGE 1: ANGGOTA RESMI
        // -------------------------------------------------------------------------
        if (!in_array(1, $owned_badges)) {
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
            $total_thread = $this->db->where('user_id', $user_id)->count_all_results('threads');
            $has_custom_pic = (isset($user['avatar']) && $user['avatar'] != 'default.jpg' && !empty($user['avatar']));

            if ($has_custom_pic || $total_thread > 0) {
                $this->grant_badge($user_id, 1, $all_badges[1]['nama']);
            }
        }

        // -------------------------------------------------------------------------
        // BADGE 2: KOMENTATOR AKTIF
        // -------------------------------------------------------------------------
        if (!in_array(2, $owned_badges)) {
            $total_comments = $this->db->where('user_id', $user_id)->count_all_results('comments');
            if ($total_comments >= 10) {
                $this->grant_badge($user_id, 2, $all_badges[2]['nama']);
            }
        }

        // -------------------------------------------------------------------------
        // BADGE 3: BINTANG FORUM
        // -------------------------------------------------------------------------
        if (!in_array(3, $owned_badges)) {
            // 1. CEK LIKES PADA THREAD
            $thread_terpopuler = $this->db->query("
                SELECT v.thread_id, COUNT(v.id) as total_likes 
                FROM votes v
                JOIN threads t ON v.thread_id = t.id
                WHERE t.user_id = ? AND v.vote_type = 'up'
                GROUP BY v.thread_id 
                HAVING total_likes >= 10 
                LIMIT 1
            ", [$user_id])->row_array();

            // 2. CEK LIKES PADA KOMENTAR
            $komentar_terpopuler = $this->db->query("
                SELECT v.comment_id, COUNT(v.id) as total_likes 
                FROM votes v
                JOIN comments c ON v.comment_id = c.id
                WHERE c.user_id = ? AND v.vote_type = 'up'
                GROUP BY v.comment_id 
                HAVING total_likes >= 10 
                LIMIT 1
            ", [$user_id])->row_array();

            if (!empty($thread_terpopuler) || !empty($komentar_terpopuler)) {
                $this->grant_badge($user_id, 3, $all_badges[3]['nama']);
            }
        }

        // -------------------------------------------------------------------------
        // BADGE 4: JURU SELAMAT (SEKARANG SUDAH AKTIF BERDASARKAN VERIFIED COMMENT)
        // -------------------------------------------------------------------------
        if (!in_array(4, $owned_badges)) {
            // Menghitung berapa kali id komentar milik user ini masuk ke kolom verified_comment_id di tabel threads
            $total_best_answers = $this->db->query("
                SELECT COUNT(t.id) as total 
                FROM threads t
                JOIN comments c ON t.verified_comment_id = c.id
                WHERE c.user_id = ?
            ", [$user_id])->row_array();

            if (isset($total_best_answers['total']) && $total_best_answers['total'] >= 3) {
                $this->grant_badge($user_id, 4, $all_badges[4]['nama']);
            }
        }
    }

    // Fungsi internal untuk insert data ke database dan kirim notifikasi pencapaian
    private function grant_badge($user_id, $badge_id, $badge_name) {
        $cek_badge_duplikat = $this->db->get_where('user_badges', ['user_id' => $user_id, 'badge_id' => $badge_id])->row_array();
        if (!$cek_badge_duplikat) {
            $this->db->insert('user_badges', [
                'user_id'  => $user_id,
                'badge_id' => $badge_id
            ]);
        }

        $title_field = '💡|badge_achievement|achievement';
        $message = 'Selamat! Anda berhasil mendapatkan lencana baru: <strong>' . $badge_name . '</strong>. Cek di halaman Achievement Anda!';
        
        $this->db->set('user_id', $user_id);
        $this->db->set('title', $title_field);
        $this->db->set('message', $message);
        $this->db->set('is_read', 0);
        $this->db->set('created_at', 'NOW()', FALSE); 
        
        $this->db->insert('notifications');
    }

    public function cek_bintang_forum($author_user_id) {
        $this->check_and_grant_badges($author_user_id);
        return TRUE;
    }
}