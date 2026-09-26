<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Thread extends CI_Model {

    public function get_all_categories() {
        return $this->db->get('categories')->result_array();
    }

    public function insert_thread($data) {
        return $this->db->insert('threads', $data);
    }

    public function get_all_threads() {
        $this->db->select('threads.*, users.username, users.nama, users.kelas, users.avatar, categories.nama_kategori');
        $this->db->from('threads');
        $this->db->join('users', 'users.id = threads.user_id');
        $this->db->join('categories', 'categories.id = threads.category_id');
        $this->db->where('threads.is_hidden', 0); // Sembunyikan yang dilaporkan
        $this->db->order_by('threads.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_counts() {
        return [
            'total_users' => $this->db->count_all('users'),
            'total_threads' => $this->db->where('is_hidden', 0)->count_all_results('threads'), // Hanya hitung yang aktif
            'total_categories' => $this->db->count_all('categories')
        ];
    }

    public function get_thread_by_slug($slug) {
        $this->db->select('threads.*, users.username, users.nama, users.kelas, users.avatar, categories.nama_kategori');
        $this->db->from('threads');
        $this->db->join('users', 'users.id = threads.user_id');
        $this->db->join('categories', 'categories.id = threads.category_id');
        $this->db->where('threads.slug', $slug);
        return $this->db->get()->row_array();
    }

    public function get_recent_threads($limit, $filter = null, $search = null) {
        $this->db->select('threads.*, users.username, users.nama, users.kelas, users.avatar, categories.nama_kategori, COUNT(comments.id) as total_balasan');
        $this->db->from('threads');
        $this->db->join('users', 'users.id = threads.user_id');
        $this->db->join('categories', 'categories.id = threads.category_id');
        $this->db->join('comments', 'comments.thread_id = threads.id', 'left');
        $this->db->where('threads.is_hidden', 0); // Sembunyikan yang dilaporkan dari dashboard
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('threads.title', $search);
            $this->db->or_like('threads.body', $search);
            $this->db->group_end();
        }
        
        if ($filter == 'unanswered') {
            $this->db->where('threads.is_verified', 0);
        }
        
        $this->db->group_by('threads.id');
        $this->db->order_by('threads.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }

    public function get_category_threads($category_id, $filter = 'all', $sort = 'latest', $limit = 20, $offset = 0, $search = null) {
        $this->db->select('threads.*, users.username, users.nama, users.kelas, users.avatar, categories.nama_kategori, COUNT(comments.id) as total_balasan');
        $this->db->from('threads');
        $this->db->join('users', 'users.id = threads.user_id');
        $this->db->join('categories', 'categories.id = threads.category_id');
        $this->db->join('comments', 'comments.thread_id = threads.id', 'left');
        $this->db->where('threads.category_id', $category_id);
        $this->db->where('threads.is_hidden', 0); // Sembunyikan yang dilaporkan

        if (!empty($search)) {
            $this->db->like('threads.title', $search);
        }
        
        if ($filter == 'unanswered') {
            $this->db->having('total_balasan', 0);
        }
        
        $this->db->group_by('threads.id');
        
        if ($sort == 'oldest') {
            $this->db->order_by('threads.created_at', 'ASC');
        } else {
            $this->db->order_by('threads.created_at', 'DESC');
        }
        
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function count_category_threads($category_id, $filter = 'all', $search = null) {
        if ($filter == 'unanswered') {
            $search_condition = !empty($search) ? "AND threads.title LIKE '%".$this->db->escape_like_str($search)."%'" : "";
            $subquery = "SELECT threads.id FROM threads LEFT JOIN comments ON comments.thread_id = threads.id WHERE threads.category_id = $category_id AND threads.is_hidden = 0 $search_condition GROUP BY threads.id HAVING COUNT(comments.id) = 0";
            return $this->db->query($subquery)->num_rows();
        }
        
        $this->db->where('category_id', $category_id);
        $this->db->where('is_hidden', 0); // Pastikan yang dihitung hanya yang tidak di-hide
        if (!empty($search)) {
            $this->db->like('title', $search);
        }
        return $this->db->count_all_results('threads');
    }

    public function get_thread_by_id($id) {
        return $this->db->get_where('threads', ['id' => $id])->row_array();
    }

    public function update_thread($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('threads', $data);
    }

    public function delete_thread($id) {
        $this->db->where('thread_id', $id)->delete('comments');
        $this->db->where('id', $id);
        return $this->db->delete('threads');
    }

    public function get_user_threads_history($user_id, $order = 'DESC') {
        $this->db->select('threads.*, categories.nama_kategori');
        $this->db->from('threads');
        $this->db->join('categories', 'categories.id = threads.category_id');
        $this->db->where('threads.user_id', $user_id);
        $this->db->where('threads.is_hidden', 0); // Hanya tampilkan yang aktif di riwayat terbuka
        $this->db->order_by('threads.created_at', $order);
        return $this->db->get()->result_array();
    }

    public function get_user_comments_history($user_id, $order = 'DESC') {
        $this->db->select('comments.*, threads.title as thread_title, threads.slug as thread_slug');
        $this->db->from('comments');
        $this->db->join('threads', 'threads.id = comments.thread_id');
        $this->db->where('comments.user_id', $user_id);
        $this->db->order_by('comments.created_at', $order);
        return $this->db->get()->result_array();
    }

    //(revisi)
    // ambil id kategori dari thread terakhir yang dibuka user
    public function get_last_viewed_category($user_id) {
        $this->db->select('threads.category_id');
        $this->db->from('thread_views');
        $this->db->join('threads', 'threads.id = thread_views.thread_id');
        $this->db->where('thread_views.user_id', $user_id);
        $this->db->order_by('thread_views.viewed_at', 'DESC');
        $this->db->limit(1);
        $row = $this->db->get()->row_array();
        return $row ? $row['category_id'] : null;
    }

    // ambil beberapa thread_id terakhir yang sudah dilihat user (untuk di-exclude dari rekomendasi)
    public function get_recently_viewed_thread_ids($user_id, $limit = 10) {
        $this->db->select('thread_id');
        $this->db->from('thread_views');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('viewed_at', 'DESC');
        $this->db->limit($limit);
        $rows = $this->db->get()->result_array();
        return array_column($rows, 'thread_id');
    }

    // rekomendasi berdasarkan kategori tertentu
    public function get_threads_by_category($category_id, $limit = 5, $exclude_ids = []) {
        $this->db->select('threads.*, categories.nama_kategori, users.username, users.avatar, COUNT(comments.id) as total_balasan');
        $this->db->from('threads');
        $this->db->join('categories', 'categories.id = threads.category_id');
        $this->db->join('users', 'users.id = threads.user_id');
        $this->db->join('comments', 'comments.thread_id = threads.id', 'left');
        $this->db->where('threads.category_id', $category_id);

        if (!empty($exclude_ids)) {
            $this->db->where_not_in('threads.id', $exclude_ids);
        }

        $this->db->group_by('threads.id');
        $this->db->order_by('threads.created_at', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    // diskusi populer, dihitung dari total views (thread_views) + total balasan
    public function get_popular_threads($limit = 5, $exclude_ids = []) {
        $this->db->select('threads.*, categories.nama_kategori, users.username, users.avatar,
            COUNT(DISTINCT comments.id) as total_balasan,
            COUNT(DISTINCT thread_views.id) as total_views');
        $this->db->from('threads');
        $this->db->join('categories', 'categories.id = threads.category_id');
        $this->db->join('users', 'users.id = threads.user_id');
        $this->db->join('comments', 'comments.thread_id = threads.id', 'left');
        $this->db->join('thread_views', 'thread_views.thread_id = threads.id', 'left');

        if (!empty($exclude_ids)) {
            $this->db->where_not_in('threads.id', $exclude_ids);
        }

        $this->db->group_by('threads.id');
        $this->db->order_by('total_views', 'DESC');
        $this->db->order_by('total_balasan', 'DESC');
        $this->db->order_by('threads.created_at', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    // catat riwayat view untuk sistem rekomendasi
    public function record_view($user_id, $thread_id) {
        $this->db->insert('thread_views', [
            'user_id'   => $user_id,
            'thread_id' => $thread_id,
            'viewed_at' => date('Y-m-d H:i:s')
        ]);
    }
}