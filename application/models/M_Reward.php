<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Reward extends CI_Model {

    // Ambil profil user yang sedang login untuk mendapatkan poin & username
    public function get_user_profile($user_id) {
        return $this->db->get_where('users', ['id' => $user_id])->row();
    }

    // Ambil semua daftar hadiah di katalog
    public function get_all_rewards() {
        return $this->db->get('rewards')->result_array();
    }

    // Ambil riwayat klaim voucher milik user (Opsi A)
    public function get_user_claims($user_id) {
        $this->db->select('claims.*, rewards.title, rewards.icon');
        $this->db->from('claims');
        $this->db->join('rewards', 'claims.reward_id = rewards.id');
        $this->db->where('claims.user_id', $user_id);
        $this->db->order_by('claims.claimed_at', 'DESC');
        return $this->db->get()->result_array();
    }

    // Proses potong poin dan simpan voucher (Database Transaction)
    public function proses_klaim_hadiah($user_id, $reward_id, $price, $voucher_code) {
        $this->db->trans_start();

        // 1. Potong poin user di tabel users
        $this->db->set('poin', 'poin - ' . (int)$price, FALSE);
        $this->db->where('id', $user_id);
        $this->db->update('users');

        // 2. Masukkan data ke tabel claims
        $data_claim = [
            'user_id' => $user_id,
            'reward_id' => $reward_id,
            'voucher_code' => $voucher_code
        ];
        $this->db->insert('claims', $data_claim);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Cek duplikasi kode voucher
    public function is_voucher_exists($code) {
        $query = $this->db->get_where('claims', ['voucher_code' => $code]);
        return $query->num_rows() > 0;
    }

    // === FUNGSI TAMBAH POIN + OTOMATIS CATAT RIWAYAT KE LOG ===
    public function tambah_poin($user_id, $poin, $aktivitas) {
        $this->db->trans_start();

        // 1. Update saldo total poin di tabel users
        $this->db->set('poin', 'poin + ' . (int)$poin, FALSE);
        $this->db->where('id', $user_id);
        $this->db->update('users');

        // 2. Catat log aktivitas ke tabel point_logs
        $data_log = [
            'user_id'   => $user_id,
            'amount'    => $poin,
            'activity'  => $aktivitas
        ];
        $this->db->insert('point_logs', $data_log);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}