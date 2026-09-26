<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('M_Auth');
    }

    public function index() {

        // Jika sudah login sebagai user
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        // Jika sudah login sebagai admin
        if ($this->session->userdata('admin_id')) {
            redirect('admin');
        }

        $this->form_validation->set_rules('username', 'Username', 'required', [
            'required' => 'Kolom Username tidak boleh kosong!'
        ]);

        $this->form_validation->set_rules('password', 'Password', 'required', [
            'required' => 'Kolom Password tidak boleh kosong!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login');
        } else {
            $this->_proses_login();
        }
    }

    public function register() {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        // Aturan Validasi Registrasi
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required', [
            'required' => 'Nama Lengkap wajib diisi!'
        ]);
        $this->form_validation->set_rules('kelas', 'Kelas', 'required', [
            'required' => 'Silakan isi Kelas Anda!'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email', [
            'required' => 'Alamat email wajib diisi!',
            'valid_email' => 'Format penulisan alamat email tidak valid!'
        ]);
        
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]|callback_cek_nama_dalam_username', [
            'required' => 'Username wajib diisi!',
            'is_unique' => 'Username ini sudah terdaftar, silakan cari nama lain!'
        ]);
        
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|callback_cek_kekuatan_password', [
            'required' => 'Password wajib diisi!',
            'min_length' => 'Password minimal harus 8 karakter!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/register');
        } else {
            // REVISI: Token dihapus, 'is_active' langsung diset ke 1 (aktif), dan fungsi kirim email dihapus
            $data_siswa = [
                'nama'       => $this->input->post('nama', true),
                'kelas'      => strtoupper($this->input->post('kelas', true)),
                'email'      => $this->input->post('email', true),
                'username'   => $this->input->post('username', true),
                'password'   => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'is_active'  => 1, // Langsung aktif tanpa verifikasi email
            ];

            $this->M_Auth->register_user($data_siswa);

            // REVISI: Pesan sukses diganti karena tidak perlu cek email lagi
            $this->session->set_flashdata('success', 'Registrasi berhasil! Akun Anda sudah aktif, silakan login.');
            redirect('auth');
        }
    }

    /**
     * Fungsi Callback Kustom untuk Validasi Keamanan Username
     */
    public function cek_nama_dalam_username($username) {
        $nama_lengkap = strtolower($this->input->post('nama', true));
        $username_low = strtolower($username);

        if (empty($nama_lengkap)) {
            return TRUE;
        }

        $kata_nama = explode(' ', $nama_lengkap);

        foreach ($kata_nama as $kata) {
            if (strlen($kata) < 3) continue; 

            if (strpos($username_low, $kata) !== FALSE) {
                $this->form_validation->set_message('cek_nama_dalam_username', 'Username tidak boleh mengandung bagian dari nama lengkap Anda demi keamanan!');
                return FALSE;
            }
        }

        return TRUE;
    }

    /*Fungsi Callback Kustom untuk Memastikan Password Mengandung Angka atau Karakter Spesial*/
    public function cek_kekuatan_password($password) {
        // Regex ini memeriksa apakah ada minimal satu angka ATAU satu karakter spesial
        // jika hanya berisi huruf saja (tanpa angka/simbol), maka akan bernilai FALSE
        if (preg_match('/[0-9]/', $password) || preg_match('/[^a-zA-Z0-9]/', $password)) {
            return TRUE;
        }

        $this->form_validation->set_message('cek_kekuatan_password', 'Password tidak boleh hanya berisi huruf! Harus mengandung angka atau karakter spesial.');
        return FALSE;
    }

    private function _proses_login() {

        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        // ==================== CEK ADMIN ====================
        $admin = $this->M_Auth->check_admin($username);

        if ($admin) {

            if (password_verify($password, $admin['password'])) {

                $session_admin = [
                    'admin_id'       => $admin['id'],
                    'admin_nama'     => $admin['nama'],
                    'admin_username' => $admin['username'],
                    'is_admin'       => TRUE
                ];

                $this->session->set_userdata($session_admin);

                redirect('admin');
                return;
            }

            $this->session->set_flashdata('error', 'Username atau Password Anda salah!');
            redirect('auth');
            return;
        }

        // ==================== CEK USER ====================
        $user = $this->M_Auth->check_login($username);

        if ($user) {

            if (password_verify($password, $user['password'])) {

                $session_data = [
                    'user_id'   => $user['id'],
                    'nama'      => $user['nama'],
                    'kelas'     => $user['kelas'],
                    'username'  => $user['username']
                ];

                $this->session->set_userdata($session_data);

                redirect('dashboard');
                return;
            }

            $this->session->set_flashdata('error', 'Username atau Password Anda salah!');
            redirect('auth');
            return;
        }

        // ==================== TIDAK DITEMUKAN ====================
        $this->session->set_flashdata('error', 'Username atau Password Anda salah!');
        redirect('auth');
    }

    public function logout() {

        // Hapus session user
        $this->session->unset_userdata([
            'user_id',
            'nama',
            'kelas',
            'username'
        ]);

        // Hapus session admin
        $this->session->unset_userdata([
            'admin_id',
            'admin_nama',
            'admin_username',
            'is_admin'
        ]);

        // Atau bisa juga:
        // $this->session->sess_destroy();

        $this->session->set_flashdata('success', 'Anda telah berhasil logout.');
        redirect('auth');
    }
}