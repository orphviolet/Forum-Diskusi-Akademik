<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Achievement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Memanggil model reward dengan format nama M_Reward
        $this->load->model('M_Reward');
        
        // Proteksi login (Sesuaikan dengan nama session user_id yang Anda pakai di Auth.php)
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login'); 
        }
    }

    // Menampilkan halaman utama achievement (Katalog + Riwayat Klaim)
    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        // Ambil profil user dan konversi ke Array murni agar seragam dengan template View
        $user_data = $this->M_Reward->get_user_profile($user_id);
        $data['user'] = json_decode(json_encode($user_data), true); 
        
        $data['rewards'] = $this->M_Reward->get_all_rewards();
        $data['my_claims'] = $this->M_Reward->get_user_claims($user_id);

        $this->load->view('sidebar/achievement', $data);
    }

    public function detail($id) {
        $user_id = $this->session->userdata('user_id');

        // Data user
        $user_data = $this->M_Reward->get_user_profile($user_id);
        $data['user'] = json_decode(json_encode($user_data), true);

        // Data reward
        $reward = $this->db->get_where('rewards', [
            'id' => $id
        ])->row_array();

        if (!$reward) {
            show_404();
        }

        $data['reward'] = $reward;

        $this->load->view('sidebar/achievement_detail', $data);
    }
    
    // Menangani aksi penukaran poin saat tombol diklik
    public function tukar() {
        $user_id = $this->session->userdata('user_id');
        $reward_id = $this->input->post('reward_id');

        // 1. Ambil info hadiah
        $reward = $this->db->get_where('rewards', ['id' => $reward_id])->row();
        if (!$reward) {
            $this->session->set_flashdata('error', 'Hadiah tidak ditemukan.');
            redirect('achievement');
        }

        // 2. Ambil poin terbaru user untuk validasi awal
        $user = $this->M_Reward->get_user_profile($user_id);
        if ($user->poin < $reward->price) {
            $this->session->set_flashdata('error', 'Poin Anda tidak cukup untuk menukarkan hadiah ini.');
            redirect('achievement');
        }

        // 3. Generate Kode Voucher Acak Unik (Contoh hasil: DUO-7AF92B)
        $prefix = strtoupper(substr(str_replace(' ', '', $reward->title), 0, 3)); 
        do {
            $voucher_code = $prefix . '-' . strtoupper(bin2hex(random_bytes(3))); 
        } while ($this->M_Reward->is_voucher_exists($voucher_code));

        // 4. Eksekusi transaksi database (Potong poin & simpan klaim)
        $proses = $this->M_Reward->proses_klaim_hadiah($user_id, $reward_id, $reward->price, $voucher_code);

        if ($proses) {
            // Flashdata untuk mentransfer data kode voucher yang baru saja berhasil diklaim ke Pop-up Modal
            $this->session->set_flashdata('claim_success', true);
            $this->session->set_flashdata('claimed_title', $reward->title);
            $this->session->set_flashdata('claimed_price', $reward->price);
            $this->session->set_flashdata('claimed_voucher', $voucher_code);
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem, silakan coba lagi.');
        }

        redirect('achievement');
    }
}