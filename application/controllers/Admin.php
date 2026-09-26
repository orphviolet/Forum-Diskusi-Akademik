<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Harus login sebagai admin
        if (!$this->session->userdata('admin_id')) {
            redirect('auth');
        }

        // Load Model yang Dibutuhkan
        $this->load->model('M_Admin');
        $this->load->model('M_Reward');
        $this->load->model('M_Notification');
    }

    public function index() {
        $data['admin'] = [
            'nama' => $this->session->userdata('admin_nama')
        ];

        $data['total_siswa']    = $this->M_Admin->countUsers();
        $data['total_kategori'] = $this->M_Admin->countKategori();
        $data['total_diskusi']  = $this->M_Admin->countDiskusi();
        $data['total_reward']   = $this->M_Admin->countReward();

        $this->load->view('admin/dashboard', $data);
    }

    // ===================== HALAMAN SISWA & GROWTH REPORT =====================

    public function students() {
        $data['admin'] = [
            'nama' => $this->session->userdata('admin_nama')
        ];

        $data['total_siswa'] = $this->M_Admin->countUsers();

        $growth = $this->M_Admin->getMonthlyGrowth(6);
        $data['growth'] = $growth;

        $bulan_ini  = !empty($growth) ? end($growth) : null;
        $bulan_lalu = (count($growth) >= 2) ? $growth[count($growth) - 2] : null;

        $data['siswa_baru_bulan_ini'] = $bulan_ini ? $bulan_ini['jumlah_baru'] : 0;

        if ($bulan_ini && $bulan_lalu && $bulan_lalu['jumlah_baru'] > 0) {
            $data['growth_persen'] = round((($bulan_ini['jumlah_baru'] - $bulan_lalu['jumlah_baru']) / $bulan_lalu['jumlah_baru']) * 100);
        } else {
            $data['growth_persen'] = null;
        }

        // TAMBAHAN: pengguna aktif (akses sistem) per bulan, proxy dari thread_views
        $bulan_indo = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
            '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu',
            '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];

        $monthly_raw = $this->M_Admin->getMonthlyActiveUsers(6);
        $monthly_active = [];
        foreach ($monthly_raw as $m) {
            list($tahun, $bln) = explode('-', $m['bulan']);
            $monthly_active[] = [
                'label'  => $bulan_indo[$bln] . ' ' . $tahun,
                'jumlah' => $m['jumlah_pengguna']
            ];
        }
        $data['monthly_active'] = $monthly_active;
        // TAMBAHAN: ambil angka bulan ini saja (elemen terakhir, karena array-nya urut lama -> baru)
        $data['pengguna_akses_bulan_ini'] = !empty($monthly_raw) ? end($monthly_raw)['jumlah_pengguna'] : 0;

        $this->load->view('admin/students', $data);
    }

    // ===================== KELOLA KATEGORI =====================

    public function category() {
        $data['admin'] = [
            'nama' => $this->session->userdata('admin_nama')
        ];

        $data['categories'] = $this->M_Admin->getAllKategori();

        $this->load->view('admin/category', $data);
    }

    public function add_category() {
        $nama_kategori = $this->input->post('nama_kategori', TRUE);

        if (!empty($nama_kategori)) {
            $this->M_Admin->insertKategori(['nama_kategori' => $nama_kategori]);
            $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan!');
        }

        redirect('admin/category');
    }

    public function edit_category($id) {
        $nama_kategori = $this->input->post('nama_kategori', TRUE);

        if (!empty($nama_kategori) && !empty($id)) {
            $this->M_Admin->updateKategori($id, ['nama_kategori' => $nama_kategori]);
            $this->session->set_flashdata('success', 'Kategori berhasil diperbarui!');
        }

        redirect('admin/category');
    }

    public function delete_category($id) {
        if (!empty($id)) {
            $this->M_Admin->deleteKategori($id);
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus!');
        }

        redirect('admin/category');
    }

    // ===================== KELOLA REWARD =====================

    public function reward() {
        $data['admin'] = [
            'nama' => $this->session->userdata('admin_nama')
        ];

        $data['rewards'] = $this->M_Admin->getAllReward();

        $this->load->view('admin/reward', $data);
    }

    public function add_reward() {
        $config['upload_path']   = './assets/uploads/rewards/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = 'reward_' . time();

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('icon')) {
            $uploadData = $this->upload->data();
            $icon_name  = $uploadData['file_name'];

            $data = [
                'title'       => $this->input->post('title', TRUE),
                'icon'        => $icon_name,
                'price'       => $this->input->post('price', TRUE),
                'description' => $this->input->post('description', TRUE),
                'benefits'    => $this->input->post('benefits', TRUE),
                'terms'       => $this->input->post('terms', TRUE),
            ];

            $this->M_Admin->insertReward($data);
            $this->session->set_flashdata('success', 'Reward berhasil ditambahkan!');
        } else {
            $error = $this->upload->display_errors('', '');
            $this->session->set_flashdata('error', 'Gagal upload gambar: ' . $error);
        }

        redirect('admin/reward');
    }

    public function edit_reward($id) {
        $reward_lama = $this->M_Admin->getRewardById($id);

        $data = [
            'title'       => $this->input->post('title', TRUE),
            'price'       => $this->input->post('price', TRUE),
            'description' => $this->input->post('description', TRUE),
            'benefits'    => $this->input->post('benefits', TRUE),
            'terms'       => $this->input->post('terms', TRUE),
        ];

        if (!empty($_FILES['icon']['name'])) {
            $config['upload_path']   = './assets/uploads/rewards/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
            $config['max_size']      = 2048;
            $config['file_name']     = 'reward_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('icon')) {
                if (!empty($reward_lama['icon']) && file_exists(FCPATH . 'assets/uploads/rewards/' . $reward_lama['icon'])) {
                    unlink(FCPATH . 'assets/uploads/rewards/' . $reward_lama['icon']);
                }

                $uploadData   = $this->upload->data();
                $data['icon'] = $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('error', 'Gagal upload gambar: ' . $this->upload->display_errors('', ''));
                redirect('admin/reward');
                return;
            }
        }

        $this->M_Admin->updateReward($id, $data);
        $this->session->set_flashdata('success', 'Reward berhasil diubah!');
        redirect('admin/reward');
    }

    public function delete_reward($id) {
        $reward = $this->M_Admin->getRewardById($id);

        if ($reward) {
            if (!empty($reward['icon']) && file_exists(FCPATH . 'assets/uploads/rewards/' . $reward['icon'])) {
                unlink(FCPATH . 'assets/uploads/rewards/' . $reward['icon']);
            }

            $this->M_Admin->deleteReward($id);
            $this->session->set_flashdata('success', 'Reward berhasil dihapus!');
        }

        redirect('admin/reward');
    }

    // ===================== VERIFIKASI JAWABAN (KHUSUS ADMIN) =====================
    // Semua endpoint di controller ini sudah dijaga oleh __construct(): hanya
    // request yang membawa session 'admin_id' yang bisa sampai ke sini, jadi
    // proses verifikasi di bawah ini otomatis "khusus admin".

    // 1. Halaman Daftar Seluruh Diskusi/Thread
    public function verification() {
        $data['admin'] = [
            'nama' => $this->session->userdata('admin_nama')
        ];

        $data['discussions'] = $this->M_Admin->getAllDiscussionsForVerification();
        $this->load->view('admin/verification', $data);
    }

    // 2. Halaman Detail Diskusi & Komentar
    public function verification_detail($discussion_id = NULL) {
        if (!$discussion_id) {
            redirect('admin/verification');
        }

        $data['admin'] = [
            'nama' => $this->session->userdata('admin_nama')
        ];

        $data['thread'] = $this->M_Admin->getDiscussionDetail($discussion_id);

        if (!$data['thread']) {
            $this->session->set_flashdata('error', 'Diskusi tidak ditemukan.');
            redirect('admin/verification');
        }

        $data['comments'] = $this->M_Admin->getAnswersByDiscussion($discussion_id);

        $this->load->view('admin/verification_detail', $data);
    }

    /**
     * 3. Verifikasi Komentar sebagai Jawaban Terbaik (Khusus Admin)
     */
    public function verify_comment($comment_id) {
        $comment = $this->M_Admin->getCommentById($comment_id);

        if (!$comment) {
            $this->session->set_flashdata('error_action', 'Komentar tidak ditemukan.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $thread_id = $comment['thread_id'];
        $thread    = $this->M_Admin->getThreadById($thread_id);

        // Cek apakah thread ini sudah punya jawaban terverifikasi sebelumnya
        $sudah_terverifikasi_sebelumnya = !empty($thread['verified_comment_id']);

        // Update thread + tambah poin + catat log poin (semua lewat model)
        $this->M_Admin->verifyAnswer($comment_id, $thread_id, $comment['user_id'], 50);

        // Reward poin & notifikasi hanya dikirim kalau ini verifikasi pertama untuk thread ini
        if (!$sudah_terverifikasi_sebelumnya) {
            $this->M_Notification->push(
                $comment['user_id'],                  // Penerima (pembuat komentar)
                $this->session->userdata('admin_id'), // Pengirim (Admin)
                'best_answer',
                $thread['slug'],
                $thread['title'],
                $comment_id
            );
        }

        $this->session->set_flashdata('success_comment', 'Jawaban berhasil diverifikasi sebagai jawaban terbaik oleh Admin!');
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * 4. Batal Verifikasi Komentar (Khusus Admin)
     */
    public function unverify_comment($thread_id) {
        $this->M_Admin->unverifyAnswer($thread_id);

        $this->session->set_flashdata('success_comment', 'Verifikasi jawaban terbaik telah dibatalkan.');
        redirect($_SERVER['HTTP_REFERER']);
    }
}