<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('M_Thread'); 
        $this->load->helper('url');

        // Proteksi Keamanan
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        
        // Parameter sort (default: DESC / terbaru)
        $sort = $this->input->get('sort') == 'oldest' ? 'ASC' : 'DESC';

        $data['counts'] = $this->M_Thread->get_counts();
        $data['categories'] = $this->M_Thread->get_all_categories();

        // Konsisten menggunakan istilah history sesuai bawaan model
        $data['my_threads_history'] = $this->M_Thread->get_user_threads_history($user_id, $sort);
        $data['my_comments_history'] = $this->M_Thread->get_user_comments_history($user_id, $sort);
        
        $data['current_sort'] = ($sort == 'ASC') ? 'oldest' : 'newest';

        // Konsisten memanggil view bernama history.php di dalam folder sidebar
        $this->load->view('sidebar/history', $data);
    }
}