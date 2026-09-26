<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Admin extends CI_Model {

    public function countUsers()
    {
        return $this->db->count_all('users');
    }

    public function countKategori()
    {
        return $this->db->count_all('categories');
    }

    public function countDiskusi()
    {
        return $this->db->count_all('threads');
    }

    public function countReward()
    {
        return $this->db->count_all('rewards');
    }

    // ===================== QUERY KATEGORI =====================

    public function getAllKategori()
    {
        return $this->db->get('categories')->result_array();
    }

    public function insertKategori($data)
    {
        return $this->db->insert('categories', $data);
    }

    public function updateKategori($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    public function deleteKategori($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }

    // ===================== QUERY REWARD =====================

    public function getAllReward()
    {
        $this->db->order_by('id', 'DESC');
        return $this->db->get('rewards')->result_array();
    }

    public function getRewardById($id)
    {
        return $this->db->get_where('rewards', ['id' => $id])->row_array();
    }

    public function insertReward($data)
    {
        return $this->db->insert('rewards', $data);
    }

    public function updateReward($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('rewards', $data);
    }

    public function deleteReward($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('rewards');
    }

    // ===================== QUERY VERIFIKASI JAWABAN =====================

    // Ambil seluruh thread/diskusi beserta jumlah komentar dan status verifikasinya
    public function getAllDiscussionsForVerification()
    {
        $this->db->select('
            threads.*, 
            categories.nama_kategori, 
            categories.nama_kategori as category_name, 
            users.username, 
            users.nama as nama_user, 
            users.nama as student_name,
            COUNT(comments.id) as total_answers,
            IF(threads.verified_comment_id IS NOT NULL, 1, 0) as is_verified
        ');
        $this->db->from('threads');
        $this->db->join('categories', 'categories.id = threads.category_id', 'left');
        $this->db->join('users', 'users.id = threads.user_id', 'left');
        $this->db->join('comments', 'comments.thread_id = threads.id', 'left');
        $this->db->group_by('threads.id');
        $this->db->order_by('threads.id', 'DESC');
        return $this->db->get()->result_array();
    }

    // Detail 1 diskusi/thread berdasarkan ID
    public function getDiscussionDetail($discussion_id)
    {
        $this->db->select('
            threads.*, 
            categories.nama_kategori, 
            categories.nama_kategori as category_name, 
            users.username, 
            users.nama as nama_user, 
            users.nama as student_name, 
            users.avatar
        ');
        $this->db->from('threads');
        $this->db->join('categories', 'categories.id = threads.category_id', 'left');
        $this->db->join('users', 'users.id = threads.user_id', 'left');
        $this->db->where('threads.id', $discussion_id);
        return $this->db->get()->row_array();
    }

    // Ambil seluruh komentar pada thread tertentu
    public function getAnswersByDiscussion($discussion_id)
    {
        $this->db->select('
            comments.*, 
            users.username, 
            users.nama as nama_user, 
            users.nama as responder_name, 
            users.avatar
        ');
        $this->db->from('comments');
        $this->db->join('users', 'users.id = comments.user_id', 'left');
        $this->db->where('comments.thread_id', $discussion_id);
        $this->db->order_by('comments.id', 'ASC');
        return $this->db->get()->result_array();
    }

    // Ambil 1 komentar berdasarkan ID (dipakai saat proses verifikasi)
    public function getCommentById($comment_id)
    {
        return $this->db->get_where('comments', ['id' => $comment_id])->row_array();
    }

    // Ambil 1 thread berdasarkan ID (dipakai saat proses verifikasi)
    public function getThreadById($thread_id)
    {
        return $this->db->get_where('threads', ['id' => $thread_id])->row_array();
    }

    // Proses Verifikasi Komentar (Jawaban): Update thread, tambah poin siswa, dan simpan log poin
    public function verifyAnswer($comment_id, $thread_id, $user_id, $points = 50)
    {
        $this->db->trans_start();

        // 1. Mark thread verified & simpan ID komentar yang diverifikasi
        $this->db->where('id', $thread_id);
        $this->db->update('threads', [
            'is_verified' => 1,
            'verified_comment_id' => $comment_id
        ]);

        // 2. Tambahkan poin ke tabel `users` (kolom: poin)
        $this->db->set('poin', "poin + $points", FALSE);
        $this->db->where('id', $user_id);
        $this->db->update('users');

        // 3. Catat riwayat ke tabel `point_logs`
        $this->db->insert('point_logs', [
            'user_id'  => $user_id,
            'amount'   => $points,
            'activity' => 'Jawaban Diverifikasi oleh Admin'
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Batal Verifikasi Komentar
    public function unverifyAnswer($thread_id)
    {
        $this->db->where('id', $thread_id);
        return $this->db->update('threads', [
            'is_verified' => 0,
            'verified_comment_id' => NULL
        ]);
    }

    // ===================== QUERY SISWA & GROWTH =====================

    // Ambil seluruh data siswa untuk ditampilkan di tabel
    // public function getAllStudents()
    // {
    //     $this->db->select('nama, kelas, username, poin, created_at');
    //     $this->db->order_by('nama', 'ASC');
    //     return $this->db->get('users')->result_array();
    // }

    // Hitung jumlah siswa baru per bulan + kumulatifnya, lalu ambil N bulan terakhir
    public function getMonthlyGrowth($months = 6)
    {
        $this->db->select("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as jumlah_baru", FALSE);
        $this->db->from('users');
        $this->db->group_by('bulan');
        $this->db->order_by('bulan', 'ASC');
        $rows = $this->db->get()->result_array();

        // Hitung kumulatif dari data paling awal, supaya angka kumulatif di bulan
        // yang ditampilkan tetap akurat (bukan cuma jumlah N bulan terakhir)
        $kumulatif = 0;
        $hasil = [];
        foreach ($rows as $r) {
            $kumulatif += (int) $r['jumlah_baru'];
            $hasil[] = [
                'bulan'       => $r['bulan'],
                'jumlah_baru' => (int) $r['jumlah_baru'],
                'kumulatif'   => $kumulatif
            ];
        }

        return array_slice($hasil, -$months);
    }

    // Hitung jumlah pengguna unik yang akses sistem (proxy: buka diskusi) per bulan,
    // semua bulan dalam rentang tetap tampil meskipun datanya 0
    public function getMonthlyActiveUsers($months = 6)
    {
        $this->db->select("DATE_FORMAT(viewed_at, '%Y-%m') as bulan, COUNT(DISTINCT user_id) as jumlah_pengguna", FALSE);
        $this->db->from('thread_views');
        $this->db->group_by('bulan');
        $rows = $this->db->get()->result_array();

        // Petakan hasil query ke key "YYYY-MM" biar gampang dicocokkan
        $map = [];
        foreach ($rows as $r) {
            $map[$r['bulan']] = (int) $r['jumlah_pengguna'];
        }

        // Bangun rentang bulan lengkap (dari [months-1] bulan lalu s.d. bulan ini),
        // isi 0 kalau bulan itu belum ada aktivitas sama sekali
        $hasil = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $key = date('Y-m', strtotime("-$i months"));
            $hasil[] = [
                'bulan'           => $key,
                'jumlah_pengguna' => isset($map[$key]) ? $map[$key] : 0
            ];
        }

        return $hasil;
    }
}