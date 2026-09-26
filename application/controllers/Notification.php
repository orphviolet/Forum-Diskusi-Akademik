<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
        $this->load->model('M_Notification');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['notifications'] = $this->M_Notification->get_user_notifications($user_id);
        $this->M_Notification->mark_all_as_read($user_id);
        $data['unread_notifications'] = 0; 
        
        $this->load->view('sidebar/notification', $data);
    }

    public function mark_all_read() {
        $user_id = $this->session->userdata('user_id');
        $this->M_Notification->mark_all_as_read($user_id);
        redirect('notification');
    }

    /**
     * Membaca string title (INITIALS|TYPE|SLUG|COMMENT_ID)
     * Mengubah status menjadi terbaca lalu mengarahkan langsung ke konten diskusi
     */
    public function read($id) {
        $notif = $this->M_Notification->get_notification_by_id($id);
        
        if ($notif) {
            $this->M_Notification->mark_as_read($id);
            
            $parts = explode('|', $notif['title']);
            $slug = isset($parts[2]) ? $parts[2] : '';
            $comment_id = isset($parts[3]) ? $parts[3] : '';

            if (!empty($slug)) {
                $target_url = 'thread/detail/' . $slug;
                
                if (!empty($comment_id) && $comment_id != '0') {
                    $target_url .= '#comment-' . $comment_id;
                }
                
                redirect($target_url);
            }
        }
        redirect('dashboard');
    }
}