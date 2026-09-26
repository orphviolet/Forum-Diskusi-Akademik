<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('M_Thread');
        $this->load->model('M_Comment'); 
        $this->load->model('M_User'); // Memuat model user untuk mengambil data profil ter-update
        $this->load->model('M_Notification'); // Memuat model notifikasi untuk menghitung data unread

        // Proteksi Keamanan: Jika belum login, tendang ke halaman login utama
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    // 1. HALAMAN UTAMA FORUM (TIMELINE) DENGAN FITUR PENCARIAN
    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        // Ambil data user & categories untuk sidebar
        $data['user'] = $this->M_User->get_user_by_id($user_id);
        $data['categories'] = $this->M_Thread->get_all_categories();
        $data['unread_notifications'] = $this->M_Notification->get_unread_count($user_id);

        // Ambil keyword pencarian dari URL
        $search = $this->input->get('search');
        $filter = $this->input->get('type');
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        if ($page < 1) $page = 1;

        $limit_thread = 10; // <-- atur di sini mau 5 atau 10

        $data['search_keyword'] = $search;
        $data['current_filter'] = $filter ? $filter : 'all';
        $data['current_page'] = $page;

        // Query database timeline utama (SUNTIKAN REVISI: users.avatar ikut diambil untuk merender foto profil timeline)
        $this->db->select('threads.*, categories.nama_kategori, users.nama, users.kelas, users.username, users.avatar, COUNT(comments.id) as total_balasan');
        $this->db->from('threads'); // Keamanan tambahan: Memastikan referensi join dari basis tabel threads secara eksplisit
        $this->db->join('categories', 'categories.id = threads.category_id');
        $this->db->join('users', 'users.id = threads.user_id');
        $this->db->join('comments', 'comments.thread_id = threads.id', 'left');
        $this->db->group_by('threads.id');

        // PERBAIKAN: Hanya mencari berdasarkan judul (threads.title) untuk menghindari error Unknown Column description
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('threads.title', $search);
            $this->db->group_end();
        }

        // PERBAIKAN LOGIKA: Sekarang memfilter diskusi yang benar-benar tidak memiliki balasan komentar (total_balasan = 0)
        if ($filter == 'unanswered') {
            $this->db->having('total_balasan', 0);
        }

        $data['threads'] = $this->db->order_by('threads.created_at', 'DESC')
                                     ->limit(10)
                                     ->get()->result_array();
        
        // Load data counter statistik
        $data['counts'] = [
            'total_users' => $this->db->count_all('users'),
            'total_threads' => $this->db->count_all('threads'),
            'total_categories' => $this->db->count_all('categories')
        ];

        // ===== SISTEM REKOMENDASI DISKUSI =====
        $limit_recommend = 5;

        $last_category_id = $this->M_Thread->get_last_viewed_category($user_id);

        if ($last_category_id) {
            $recently_viewed = $this->M_Thread->get_recently_viewed_thread_ids($user_id);

            $recommended = $this->M_Thread->get_threads_by_category($last_category_id, $limit_recommend, $recently_viewed);

            if (count($recommended) < $limit_recommend) {
                $exclude_fill = array_merge($recently_viewed, array_column($recommended, 'id'));
                $fill = $this->M_Thread->get_popular_threads($limit_recommend - count($recommended), $exclude_fill);
                $recommended = array_merge($recommended, $fill);
            }

            $data['recommendation_type'] = 'category';

            $this->db->where('id', $last_category_id);
            $cat = $this->db->get('categories')->row_array();
            $data['recommended_category_name'] = $cat ? $cat['nama_kategori'] : '';
        } else {
            // User baru / belum ada riwayat -> tampilkan diskusi populer (tanpa exclude timeline utama)
            $recommended = $this->M_Thread->get_popular_threads($limit_recommend, []);
            $data['recommendation_type'] = 'popular';
        }

        $data['recommended_threads'] = $recommended;
        // ===== END SISTEM REKOMENDASI =====

        // Memanggil folder view sidebar/dashboard.php sesuai struktur asli Anda
        $this->load->view('sidebar/dashboard', $data);
    }

    // 2. HALAMAN DAFTAR GRID KOTAK SEMUA MAPEL (views/sidebar/category.php)
    public function categories_list() {
        $user_id = $this->session->userdata('user_id');
        
        $data['user'] = $this->M_User->get_user_by_id($user_id);
        $data['unread_notifications'] = $this->M_Notification->get_unread_count($user_id);
        
        // Menggunakan library database manual untuk menarik data categories mentah (menyesuaikan struktur views)
        $data['categories'] = $this->db->get('categories')->result_array();

        // Diarahkan ke views/sidebar/category.php (Sesuai keinginan Anda untuk Grid Mapel)
        $this->load->view('sidebar/category', $data);
    }

    // 3. HALAMAN DAFTAR DISKUSI PER MAPEL YANG DIPILIH (views/category.php)
    public function category() {
        $user_id = $this->session->userdata('user_id');
        $category_id = $this->input->get('category');
        if (empty($category_id)) {
            redirect('dashboard');
        }

        $filter = $this->input->get('type');
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'latest';
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $search = $this->input->get('search');

        if ($page < 1) $page = 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $data['user'] = $this->M_User->get_user_by_id($user_id); 
        $data['counts'] = $this->M_Thread->get_counts();
        $data['categories'] = $this->M_Thread->get_all_categories();
        $data['unread_notifications'] = $this->M_Notification->get_unread_count($user_id);
        $data['threads'] = $this->M_Thread->get_category_threads($category_id, $filter, $sort, $limit, $offset, $search);
        
        $total_rows = $this->M_Thread->count_category_threads($category_id, $filter, $search);
        $data['total_pages'] = ceil($total_rows / $limit);
        $data['current_page'] = $page;
        $data['current_sort'] = $sort;
        $data['search_keyword'] = $search;
        $data['current_filter'] = ($filter == 'unanswered') ? 'unanswered' : 'all';
        $data['current_category'] = $category_id;

        $this->db->where('id', $category_id);
        $cat_info = $this->db->get('categories')->row_array();
        
        // Penyesuaian nama array: Mendukung field 'nama_kategori' atau 'name' agar terhindar dari bug undifined index
        if ($cat_info) {
            $data['category_name'] = isset($cat_info['nama_kategori']) ? $cat_info['nama_kategori'] : $cat_info['name'];
        } else {
            $data['category_name'] = 'Kategori';
        }

        // PERBAIKAN RUTE: Diarahkan ke views/category.php tanpa subfolder sidebar (Daftar diskusi per mapel full-width)
        $this->load->view('category', $data);
    }

    // 4. HALAMAN RIWAYAT AKTIVITAS SISWA
    public function history() {
        $user_id = $this->session->userdata('user_id');
        $sort = $this->input->get('sort') == 'oldest' ? 'ASC' : 'DESC';

        $data['user'] = $this->M_User->get_user_by_id($user_id);
        $data['categories'] = $this->M_Thread->get_all_categories(); 
        $data['unread_notifications'] = $this->M_Notification->get_unread_count($user_id);
        
        $data['my_threads_history'] = $this->M_Thread->get_user_threads_history($user_id, $sort);
        $data['my_comments_history'] = $this->M_Thread->get_user_comments_history($user_id, $sort);
        
        $data['current_sort'] = ($sort == 'ASC') ? 'oldest' : 'newest';

        $this->load->view('sidebar/history', $data);
    }

    // 5. HALAMAN ACHIEVEMENT (MARKETPLACE HADIAH & VOUCHER SISWA)
    public function achievement() {
        // PERBAIKAN: Memuat model baru M_Reward menggantikan M_Badge yang sudah dihapus
        $this->load->model('M_Reward');
        $user_id = $this->session->userdata('user_id');

        // Mengambil profil user ter-update untuk poin dan kategori pendukung sidebar
        $data['user'] = $this->M_User->get_user_by_id($user_id);
        $data['categories'] = $this->M_Thread->get_all_categories();
        $data['unread_notifications'] = $this->M_Notification->get_unread_count($user_id);

        // AMBIL DATA REAL MARKETPLACE DARI DATABASE
        $data['rewards'] = $this->M_Reward->get_all_rewards();
        $data['my_claims'] = $this->M_Reward->get_user_claims($user_id);

        // Memanggil view reward Anda
        $this->load->view('sidebar/achievement', $data);
    }
}