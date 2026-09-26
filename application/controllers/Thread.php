<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Thread extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_Thread');
        $this->load->model('M_Comment'); 
        $this->load->model('M_Notification');
        $this->load->model('M_Reward'); // Load M_Reward untuk urusan pemberian poin terverifikasi
        $this->load->helper('url');

        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    public function create() {
        $this->form_validation->set_rules('title', 'Judul Pertanyaan', 'required|min_length[10]', [
            'required' => 'Judul pertanyaan wajib diisi!',
            'min_length' => 'Judul terlalu pendek, minimal harus 10 karakter!'
        ]);
        $this->form_validation->set_rules('category_id', 'Mata Pelajaran', 'required', [
            'required' => 'Silakan pilih mata pelajaran terkait!'
        ]);
        $this->form_validation->set_rules('body', 'Isi Pertanyaan', 'required|min_length[20]', [
            'required' => 'Detail isi pertanyaan tidak boleh kosong!',
            'min_length' => 'Berikan penjelasan yang lebih detail (minimal 20 karakter)!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $data['categories'] = $this->M_Thread->get_all_categories();
            $this->load->view('thread/create', $data);
        } else {
            $image_name = null;
            if (!empty($_FILES['image_lampiran']['name'])) {
                $config['upload_path'] = './assets/uploads/threads/';
                $config['allowed_types'] = 'jpg|jpeg|png|JPG|JPEG|PNG';
                $config['max_size'] = 5120; 
                $config['encrypt_name'] = TRUE;

                if (!is_dir($config['upload_path'])) { 
                    mkdir($config['upload_path'], 0777, TRUE); 
                }
                
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image_lampiran')) {
                    $upload_data = $this->upload->data();
                    $image_name = $upload_data['file_name'];
                } else {
                    $data['categories'] = $this->M_Thread->get_all_categories();
                    $data['error_upload'] = $this->upload->display_errors(", ");
                    return $this->load->view('thread/create', $data);
                }
            }

            $slug_title = url_title($this->input->post('title'), 'dash', TRUE);
            $unique_slug = $slug_title . '-' . time();
            $current_user_id = $this->session->userdata('user_id');
            
            $data_thread = [
                'user_id' => $current_user_id,
                'category_id' => $this->input->post('category_id', true),
                'title' => $this->input->post('title', true),
                'slug' => $unique_slug,
                'body' => $this->input->post('body', true),
                'image' => $image_name,
                'views_count' => 0,
                'is_verified' => 0,
                'is_hidden' => 0 // Dipertahankan karena diskusi kini bisa dilaporkan & disembunyikan
            ];

            $this->M_Thread->insert_thread($data_thread);

            // TAMBAHAN: KIRIM NOTIFIKASI DISKUSI BARU KE SEMUA USER LAIN
            $all_users = $this->db->select('id')->get('users')->result_array();
            foreach ($all_users as $u) {
                if ($u['id'] == $current_user_id) {
                    continue; 
                }
                $this->M_Notification->push(
                    $u['id'], 
                    $current_user_id, 
                    'new_thread', 
                    $unique_slug, 
                    $data_thread['title']
                );
            }

            $this->session->set_flashdata('success', 'Pertanyaan kamu berhasil diterbitkan!');
            redirect('thread/detail/' . $unique_slug);
        }
    }

    public function edit($id) {
        $thread = $this->M_Thread->get_thread_by_id($id);
        if (empty($thread)) {
            redirect('dashboard');
        }

        $pembuat_thread_id = (int) $thread['user_id'];
        $user_login_id = (int) $this->session->userdata('user_id');
        if ($pembuat_thread_id !== $user_login_id) {
            redirect('dashboard');
        }

        if ($this->input->post()) {
            $image_name = $thread['image'];
            if (!empty($_FILES['image_lampiran']['name'])) {
                $config['upload_path'] = './assets/uploads/threads/';
                $config['allowed_types'] = 'jpg|jpeg|png|JPG|JPEG|PNG';
                $config['max_size'] = 5120;
                $config['encrypt_name'] = TRUE;

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('image_lampiran')) {
                    if (!empty($thread['image']) && file_exists($config['upload_path'] . $thread['image'])) {
                        unlink($config['upload_path'] . $thread['image']);
                    }
                    $upload_data = $this->upload->data();
                    $image_name = $upload_data['file_name'];
                } else {
                    $data['error_upload'] = $this->upload->display_errors(", ");
                    $data['thread'] = $thread;
                    $data['categories'] = $this->M_Thread->get_all_categories();
                    return $this->load->view('thread/edit', $data);
                }
            }

            $update_data = [
                'title' => $this->input->post('title', true),
                'body' => $this->input->post('body', true),
                'category_id' => $this->input->post('category_id', true),
                'image' => $image_name
            ];

            $this->M_Thread->update_thread($id, $update_data);
            $this->session->set_flashdata('success', 'Diskusi berhasil diperbarui!');
            redirect('thread/detail/' . $thread['slug']);
            return;
        }

        $data['thread'] = $thread;
        $data['categories'] = $this->M_Thread->get_all_categories();
        $this->load->view('thread/edit', $data);
    }

    public function delete($id) {
        $thread = $this->M_Thread->get_thread_by_id($id);

        if ($thread['user_id'] == $this->session->userdata('user_id')) {
            $config_path = './assets/uploads/threads/';
            if (!empty($thread['image']) && file_exists($config_path . $thread['image'])) {
                unlink($config_path . $thread['image']);
            }

            $this->db->like('title', '|' . $thread['slug']);
            $this->db->delete('notifications');

            $this->M_Thread->delete_thread($id);
        }
        redirect('dashboard');
    }

    public function detail($slug) {
        $thread = $this->M_Thread->get_thread_by_slug($slug);
        
        // Cek jika thread tidak ditemukan atau disembunyikan karena report
        if (!$thread || $thread['is_hidden'] == 1) { 
            $this->session->set_flashdata('error_action', 'Diskusi tidak ditemukan atau telah dihapus karena laporan pelanggaran.');
            redirect('dashboard'); 
        }

        $current_user_id = $this->session->userdata('user_id');
        if ($current_user_id != $thread['user_id']) {
            $this->db->set('views_count', 'views_count+1', FALSE);
            $this->db->where('slug', $slug);
            $this->db->update('threads');

            // TAMBAHAN: catat riwayat view untuk sistem rekomendasi
            $this->M_Thread->record_view($current_user_id, $thread['id']);
        }

        $data['thread'] = $this->M_Thread->get_thread_by_slug($slug);
        $data['comments'] = $this->M_Comment->get_comments_by_thread($thread['id']);
        $this->load->view('thread/detail', $data);
    }

    public function report($slug) {
        $thread = $this->M_Thread->get_thread_by_slug($slug);

        if (!$thread || $thread['is_hidden'] == 1) {
            $this->session->set_flashdata('error_action', 'Diskusi tidak ditemukan atau telah dihapus karena laporan pelanggaran.');
            redirect('dashboard');
        }

        $data['ref'] = $this->input->get('ref'); // bawa origin dari detail
        $current_user_id = $this->session->userdata('user_id');
        $verified_comment_id = $thread['verified_comment_id'];

        // Ambil semua komentar aktif di thread ini beserta jumlah upvote-nya
        $this->db->select('comments.*, users.username, users.avatar,
            (SELECT COUNT(*) FROM votes WHERE votes.comment_id = comments.id AND votes.vote_type = "up") as total_upvote');
        $this->db->from('comments');
        $this->db->join('users', 'users.id = comments.user_id');
        $this->db->where('comments.thread_id', $thread['id']);
        $this->db->where('comments.is_hidden', 0);
        $this->db->order_by('total_upvote', 'DESC');
        $this->db->order_by('comments.created_at', 'ASC');
        $comments_ranked = $this->db->get()->result_array();

        // Susun ulang ranking: komentar terverifikasi selalu nomor 1, sisanya urut upvote terbanyak
        usort($comments_ranked, function ($a, $b) use ($verified_comment_id) {
            $a_verified = ($verified_comment_id && $a['id'] == $verified_comment_id) ? 1 : 0;
            $b_verified = ($verified_comment_id && $b['id'] == $verified_comment_id) ? 1 : 0;

            if ($a_verified !== $b_verified) {
                return $b_verified - $a_verified;
            }
            return $b['total_upvote'] - $a['total_upvote'];
        });

        // TAMBAHAN: ranking cuma tampilkan komentar yang layak (terverifikasi ATAU punya upvote),
        // supaya tidak ditambal komentar upvote 0 hanya demi menggenapi 5
        $comments_ranked = array_values(array_filter($comments_ranked, function ($c) use ($verified_comment_id) {
            $is_verified = ($verified_comment_id && $c['id'] == $verified_comment_id);
            return $is_verified || $c['total_upvote'] > 0;
        }));

        // TAMBAHAN: batasi maksimal 5 teratas
        $comments_ranked = array_slice($comments_ranked, 0, 5);

        // Filter khusus komentar milik user yang sedang login
        $my_comments = array_values(array_filter($comments_ranked, function ($c) use ($current_user_id) {
            return $c['user_id'] == $current_user_id;
        }));
        $total_komentar_saya = count($my_comments);
        $total_upvote_saya = array_sum(array_column($my_comments, 'total_upvote'));

        $data['thread'] = $thread;
        $data['comments_ranked'] = $comments_ranked;
        $data['my_comments'] = $my_comments;
        $data['total_komentar_saya'] = $total_komentar_saya;
        $data['total_upvote_saya'] = $total_upvote_saya;
        $data['current_user_id'] = $current_user_id;
        $data['verified_comment_id'] = $verified_comment_id;

        $this->load->view('thread/report', $data);
    }

    // Laporkan Thread / Diskusi + Auto Hide (Pesan Sukses Diseragamkan)
    public function report_thread($thread_id) {
        $thread = $this->db->get_where('threads', ['id' => $thread_id])->row_array();
        
        if (!$thread) {
            $this->session->set_flashdata('error_action', 'Diskusi yang ingin dilaporkan tidak ditemukan.');
            redirect('dashboard');
        }

        $current_user_id = $this->session->userdata('user_id');

        if ($thread['user_id'] == $current_user_id) {
            $this->session->set_flashdata('error_action', 'Anda tidak dapat melaporkan diskusi Anda sendiri.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $sudah_dilaporkan = $this->db->get_where('reports', [
            'user_id'   => $current_user_id,
            'thread_id' => $thread_id
        ])->num_rows();

        if ($sudah_dilaporkan > 0) {
            $this->session->set_flashdata('error_action', 'Anda sudah melaporkan diskusi ini sebelumnya.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $alasan = $this->input->post('alasan');
        if (empty($alasan)) {
            $alasan = 'Tidak ada alasan spesifik yang dipilih.';
        }

        $data_report = [
            'user_id'    => $current_user_id,
            'thread_id'  => $thread_id, 
            'comment_id' => null,
            'alasan'     => $alasan,
            'status'     => 'pending' 
        ];
        $this->db->insert('reports', $data_report);

        // LOGIKA AUTO HIDE UNTUK THREAD
        $total_report_sekarang = $this->db->where('thread_id', $thread_id)->count_all_results('reports');
        
        if ($total_report_sekarang >= 3) {
            $this->db->set('is_hidden', 1);
            $this->db->where('id', $thread_id);
            $this->db->update('threads');

            $this->db->set('status', 'reviewed');
            $this->db->where('thread_id', $thread_id);
            $this->db->update('reports');
            
            $this->session->set_flashdata('success', 'Diskusi berhasil dilaporkan.');
            redirect('dashboard');
        }

        $this->session->set_flashdata('success', 'Diskusi berhasil dilaporkan.');
        redirect($_SERVER['HTTP_REFERER']);
    }
}