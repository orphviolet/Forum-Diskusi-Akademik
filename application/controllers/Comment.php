<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('M_Thread');
        $this->load->model('M_Comment');
        $this->load->model('M_Notification');

        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    public function add_comment($slug) {
        $this->form_validation->set_rules('comment', 'Balasan', 'required|min_length[5]', [
            'required' => 'Kotak balasan tidak boleh kosong!',
            'min_length' => 'Isi balasan terlalu pendek (minimal 5 karakter)!'
        ]);
        
        $thread = $this->M_Thread->get_thread_by_slug($slug);

        if ($this->form_validation->run() == FALSE) {
            $data['thread'] = $thread;
            $data['comments'] = $this->M_Comment->get_comments_by_thread($thread['id']);
            return $this->load->view('thread/detail', $data);
        } else {
            $image_comment_name = null;
            if (!empty($_FILES['image_komentar']['name'])) {
                $config['upload_path'] = './assets/uploads/comments/';
                $config['allowed_types'] = 'jpg|jpeg|png|JPG|JPEG|PNG';
                $config['max_size'] = 5120;
                $config['encrypt_name'] = TRUE;

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image_komentar')) {
                    $upload_data = $this->upload->data();
                    $image_comment_name = $upload_data['file_name'];
                } else {
                    $data['thread'] = $thread;
                    $data['comments'] = $this->M_Comment->get_comments_by_thread($thread['id']);
                    $data['error_upload_comment'] = $this->upload->display_errors(", ");
                    return $this->load->view('thread/detail', $data);
                }
            }

            $current_user_id = $this->session->userdata('user_id');
            $comment_text = $this->input->post('comment', true);

            $data_comment = [
                'thread_id' => $thread['id'],
                'user_id'   => $current_user_id,
                'comment'   => $comment_text,
                'image'     => $image_comment_name,
                'parent_id' => null,
                'is_hidden' => 0
            ];

            $this->M_Comment->insert_comment($data_comment);
            $insert_comment_id = $this->db->insert_id();

            // DETEKSI REPLAI MENTION: Ambil string username di depan jika ada format @username
            $mention_user_id = null;
            if (preg_match('/^@([a-zA-Z0-9_]+)/', trim($comment_text), $matches)) {
                $mentioned_username = $matches[1];
                $user_target = $this->db->get_where('users', ['username' => $mentioned_username])->row_array();
                if ($user_target && $user_target['id'] != $current_user_id) {
                    $mention_user_id = $user_target['id'];
                }
            }

            $penerima_list = [];
            $penerima_list[] = $thread['user_id'];

            $existing_comments = $this->M_Comment->get_comments_by_thread($thread['id']);
            foreach ($existing_comments as $c) {
                $penerima_list[] = $c['user_id'];
            }

            $penerima_list = array_unique($penerima_list);

            foreach ($penerima_list as $receiver_id) {
                if ($receiver_id == $current_user_id) {
                    continue;
                }

                // Tentukan tipe notifikasi berdasarkan target penerima dan kondisi mention
                if ($mention_user_id && $receiver_id == $mention_user_id) {
                    // Kasus C membalas B -> B menerima notif "membalas komentarmu..."
                    $notif_type = 'reply_comment';
                } elseif ($receiver_id == $thread['user_id']) {
                    // Kasus B berkomentar di Thread A -> A menerima notif "berkomentar pada thread kamu..."
                    $notif_type = 'new_comment_owner';
                } else {
                    // Kasus pengguna umum yang terikut dalam daftar thread tersebut
                    $notif_type = 'new_comment_general';
                }

                $this->M_Notification->push(
                    $receiver_id, 
                    $current_user_id, 
                    $notif_type, 
                    $slug, 
                    $thread['title'], 
                    $insert_comment_id
                );
            }

            $this->session->set_flashdata('success_comment', 'Balasan kamu berhasil dikirim!');
            redirect('thread/detail/' . $slug);
        }
    }

    public function edit_comment($id) {
        $comment = $this->M_Comment->get_comment_by_id($id);
        $thread = $this->M_Thread->get_thread_by_id($comment['thread_id']);

        if ($comment['user_id'] != $this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        if ((time() - strtotime($comment['created_at'])) > 300) {
            $this->session->set_flashdata('error_action', 'Batas waktu mengedit komentar sudah habis!');
            redirect('thread/detail/' . $thread['slug']);
        }

        if ($this->input->post()) {
            $image_comment_name = $comment['image'];
            if (!empty($_FILES['image_komentar']['name'])) {
                $config['upload_path'] = './assets/uploads/comments/';
                $config['allowed_types'] = 'jpg|jpeg|png|JPG|JPEG|PNG';
                $config['max_size'] = 5120;
                $config['encrypt_name'] = TRUE;

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->load->library('upload');
                $this->upload->initialize($config);
                if ($this->upload->do_upload('image_komentar')) {
                    if (!empty($comment['image']) && file_exists($config['upload_path'] . $comment['image'])) {
                        unlink($config['upload_path'] . $comment['image']);
                    }
                    $upload_data = $this->upload->data();
                    $image_comment_name = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error_action', 'Gagal memuat gambar: ' . $this->upload->display_errors(", "));
                    redirect('thread/detail/' . $thread['slug']);
                }
            }

            $this->M_Comment->update_comment($id, $this->input->post('comment', true), $image_comment_name);
            $this->session->set_flashdata('success_comment', 'Komentar Anda berhasil diperbarui!');
            redirect('thread/detail/' . $thread['slug']);
        }
    }

    public function delete_comment($id) {
        $comment = $this->M_Comment->get_comment_by_id($id);
        $thread = $this->M_Thread->get_thread_by_id($comment['thread_id']);

        if ($comment['user_id'] == $this->session->userdata('user_id')) {
            $config_path = './assets/uploads/comments/';
            if (!empty($comment['image']) && file_exists($config_path . $comment['image'])) {
                unlink($config_path . $comment['image']);
            }
            
            $this->db->like('title', '|' . $thread['slug'] . '|' . $id);
            $this->db->delete('notifications');

            $this->M_Comment->delete_comment($id);
        }
        redirect('thread/detail/' . $thread['slug']);
    }

    public function report_comment($comment_id) {
        $comment = $this->db->get_where('comments', ['id' => $comment_id])->row_array();
        
        if (!$comment) {
            $this->session->set_flashdata('error_action', 'Komentar yang ingin dilaporkan tidak ditemukan.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $current_user_id = $this->session->userdata('user_id');

        if ($comment['user_id'] == $current_user_id) {
            $this->session->set_flashdata('error_action', 'Anda tidak dapat melaporkan komentar Anda sendiri.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $sudah_dilaporkan = $this->db->get_where('reports', [
            'user_id'    => $current_user_id,
            'comment_id' => $comment_id
        ])->num_rows();

        if ($sudah_dilaporkan > 0) {
            $this->session->set_flashdata('error_action', 'Anda sudah melaporkan komentar ini sebelumnya.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $alasan = $this->input->post('alasan');
        if (empty($alasan)) {
            $alasan = 'Tidak ada alasan spesifik yang dipilih.';
        }

        $data_report = [
            'user_id'    => $current_user_id,
            'thread_id'  => null, 
            'comment_id' => $comment_id,
            'alasan'     => $alasan,
            'status'     => 'pending' 
        ];
        $this->db->insert('reports', $data_report);
        
        $total_report_sekarang = $this->db->where('comment_id', $comment_id)->count_all_results('reports');
        
        if ($total_report_sekarang >= 3) {
            $this->db->set('is_hidden', 1);
            $this->db->where('id', $comment_id);
            $this->db->update('comments');

            $this->db->set('status', 'reviewed');
            $this->db->where('comment_id', $comment_id);
            $this->db->update('reports');
        }

        $this->session->set_flashdata('success_comment', 'Komentar berhasil dilaporkan.');
        redirect($_SERVER['HTTP_REFERER']);
    }

}