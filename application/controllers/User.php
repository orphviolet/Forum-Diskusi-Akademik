<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login'); 
        }
        $this->load->model('M_User');
    }

    // 1. HALAMAN TAMPILAN PROFIL UTAMA
    public function profile() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->M_User->get_user_by_id($user_id);
        $data['total_threads']  = $this->M_User->count_user_threads($user_id);
        $data['total_comments'] = $this->M_User->count_user_comments($user_id);
        $this->load->view('user/profile', $data);
    }

    // 2. HALAMAN KHUSUS EDIT PROFIL
    public function edit() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->M_User->get_user_by_id($user_id);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim', ['required' => 'Nama Lengkap wajib diisi!']);
        $this->form_validation->set_rules('kelas', 'Kelas', 'required|trim', ['required' => 'Kelas wajib diisi!']);
        $this->form_validation->set_rules('username', 'Username', 'required|trim|callback_cek_username_edit', ['required' => 'Username wajib diisi!']);
        
        if ($this->input->post('password_baru')) {
            $this->form_validation->set_rules('password_lama', 'Password Lama', 'required', ['required' => 'Password lama wajib diisi!']);
            $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[8]|callback_cek_kekuatan_password', [
                'required'   => 'Password baru wajib diisi!',
                'min_length' => 'Password minimal harus 8 karakter!'
            ]);
        }

        if ($this->form_validation->run() == FALSE) {
            $error = validation_errors('', '<br>');
            if (!empty($error)) {
                $this->session->set_flashdata('error_edit', $error);
            }
            $this->load->view('user/edit_profile', $data);
        } else {
            if ($this->input->post('password_baru')) {
                $pwd_lama = $this->input->post('password_lama');
                if (!password_verify($pwd_lama, $data['user']['password'])) {
                    $this->session->set_flashdata('error_edit', 'Password lama yang Anda masukkan salah!');
                    redirect('user/edit');
                    return;
                }
            }

            $update_data = [
                'nama'     => $this->input->post('nama', true),
                'kelas'    => strtoupper($this->input->post('kelas', true)),
                'username' => $this->input->post('username', true),
            ];

            if ($this->input->post('password_baru')) {
                $update_data['password'] = password_hash($this->input->post('password_baru'), PASSWORD_DEFAULT);
            }

            // === PERBAIKAN SEKTOR UPLOAD AVATAR ===
            if (!empty($_FILES['avatar']['name'])) {
                $config['upload_path']   = './assets/uploads/avatars/';
                $config['allowed_types'] = 'jpg|jpeg|png|JPG|JPEG|PNG';
                $config['max_size']      = 2048; 
                $config['encrypt_name']  = TRUE;

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->load->library('upload');
                $this->upload->initialize($config);

                if ($this->upload->do_upload('avatar')) {
                    if (!empty($data['user']['avatar']) && file_exists($config['upload_path'] . $data['user']['avatar'])) {
                        unlink($config['upload_path'] . $data['user']['avatar']);
                    }
                    $upload_data = $this->upload->data();
                    $update_data['avatar'] = $upload_data['file_name'];
                } else {
                    $error_msg = $this->upload->display_errors('','');
                    $this->session->set_flashdata('error_edit', 'Gagal upload foto: ' . $error_msg);
                    redirect('user/edit');
                    return;
                }
            }

            $this->M_User->update_user($user_id, $update_data);
            
            $this->session->set_userdata([
                'nama'     => $update_data['nama'],
                'kelas'    => $update_data['kelas'],
                'username' => $update_data['username']
            ]);
            
            $this->session->set_flashdata('success_profile', 'Profil berhasil diperbarui!');
            redirect('user/profile');
        }
    }

    // 3. CALLBACK VALIDASI USERNAME KUSTOM
    public function cek_username_edit($username) {
        $user_id = $this->session->userdata('user_id');
        $nama_lengkap = strtolower($this->input->post('nama', true));
        $username_low = strtolower($username);

        if (!empty($nama_lengkap)) {
            $kata_nama = explode(' ', $nama_lengkap);
            foreach ($kata_nama as $kata) {
                if (strlen($kata) < 3) continue;
                if (strpos($username_low, $kata) !== FALSE) {
                    $this->form_validation->set_message('cek_username_edit', 'Username tidak boleh mengandung bagian dari nama lengkap Anda demi keamanan!');
                    return FALSE;
                }
            }
        }

        if ($this->M_User->check_username_except($username, $user_id)) {
            $this->form_validation->set_message('cek_username_edit', 'Username ini sudah digunakan oleh pengguna lain!');
            return FALSE;
        }
        return TRUE;
    }

    // 4. CALLBACK VALIDASI KEKUATAN PASSWORD KUSTOM
    public function cek_kekuatan_password($password) {
        if (preg_match('/[0-9]/', $password) || preg_match('/[^a-zA-Z0-9]/', $password)) {
            return TRUE;
        }
        $this->form_validation->set_message('cek_kekuatan_password', 'Password tidak boleh hanya berisi huruf! Harus mengandung angka atau karakter spesial.');
        return FALSE;
    }
}