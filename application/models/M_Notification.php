<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Notification extends CI_Model {

    public function get_user_notifications($user_id) {
        $this->db->query("DELETE FROM notifications WHERE created_at < NOW() - INTERVAL 30 DAY");

        $this->db->select('notifications.*, 
                           sender.avatar as sender_avatar,
                           sender.username as sender_username,
                          (CASE 
                            WHEN DATEDIFF(NOW(), notifications.created_at) = 0 THEN "HARI INI" 
                            WHEN DATEDIFF(NOW(), notifications.created_at) = 1 THEN "KEMARIN" 
                            ELSE DATE_FORMAT(notifications.created_at, "%d %b %Y") 
                           END) as day_group');
        $this->db->from('notifications');
        $this->db->join('users as sender', 'sender.username = SUBSTRING_INDEX(notifications.title, "|", 1)', 'left');
        $this->db->where('notifications.user_id', $user_id);
        $this->db->order_by('notifications.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function mark_all_as_read($user_id) {
        $this->db->set('is_read', 1);
        $this->db->where('user_id', $user_id);
        return $this->db->update('notifications');
    }

    public function get_notification_by_id($id) {
        return $this->db->get_where('notifications', ['id' => $id])->row_array();
    }

    public function mark_as_read($id) {
        $this->db->set('is_read', 1);
        $this->db->where('id', $id);
        return $this->db->update('notifications');
    }

    public function push($receiver_id, $sender_id, $type, $slug, $thread_title, $comment_id = null) {
        if ($receiver_id == $sender_id) return;

        $sender = $this->db->get_where('users', ['id' => $sender_id])->row_array();
        $sender_user = isset($sender['username']) ? $sender['username'] : 'pengguna';
        
        $title_field = $sender_user . '|' . $type . '|' . $slug;
        if ($comment_id) {
            $title_field .= '|' . $comment_id;
        }

        switch ($type) {
            case 'new_thread':
                $email_subject = 'Diskusi Baru: ' . $thread_title;
                $message = '<strong style="color:#2C5EAD; font-weight:700;">@' . $sender_user . '</strong> membuat diskusi baru — <span style="color:#1591DC; font-weight:500;">' . $thread_title . '</span>';
                break;

            case 'new_comment_owner':
                $email_subject = 'Balasan Baru di Thread Anda';
                $message = '<strong style="color:#2C5EAD; font-weight:700;">@' . $sender_user . '</strong> berkomentar pada thread kamu — <span style="color:#1591DC; font-weight:500;">' . $thread_title . '</span>';
                break;

            case 'reply_comment':
                // KHUSUS UNTUK BALASAN YANG MENYEBUT TARGET SPESIFIK (C membalas B)
                $email_subject = 'Seseorang membalas komentar Anda';
                $message = '<strong style="color:#2C5EAD; font-weight:700;">@' . $sender_user . '</strong> membalas komentarmu pada thread — <span style="color:#1591DC; font-weight:500;">' . $thread_title . '</span>';
                break;

            case 'new_comment_general':
                $email_subject = 'Aktivitas Baru di Diskusi yang Anda Ikuti';
                $message = '<strong style="color:#2C5EAD; font-weight:700;">@' . $sender_user . '</strong> berkomentar pada thread — <span style="color:#1591DC; font-weight:500;">' . $thread_title . '</span>';
                break;

            case 'upvote_thread':
                $email_subject = 'Seseorang Menyukai Diskusi Anda';
                $message = '<strong style="color:#2C5EAD; font-weight:700;">@' . $sender_user . '</strong> menyukai diskusi yang kamu buat — <span style="color:#1591DC; font-weight:500;">' . $thread_title . '</span>';
                break;

            case 'upvote_comment':
                $email_subject = 'Seseorang Menyukai Balasan Anda';
                $message = '<strong style="color:#2C5EAD; font-weight:700;">@' . $sender_user . '</strong> menyukai balasanmu di thread <span style="color:#1591DC; font-weight:500;">' . $thread_title . '</span>';
                break;

            case 'best_answer':
                $email_subject = '🏆 Jawaban Anda Terpilih Sebagai Jawaban Terbaik!';
                $message = 'Balasanmu dipilih sebagai <strong style="color:#2C5EAD; font-weight:700;">jawaban terbaik</strong> di thread <span style="color:#1591DC; font-weight:500;">' . $thread_title . '</span>';
                break;

            default:
                $email_subject = 'Aktivitas Baru di Forum';
                $message = 'Ada interaksi baru di thread ' . $thread_title;
        }

        $this->db->insert('notifications', [
            'user_id'    => $receiver_id,
            'title'      => $title_field,
            'message'    => $message,
            'is_read'    => 0
        ]);

        try {
            $receiver = $this->db->get_where('users', ['id' => $receiver_id])->row_array();
            if ($receiver && !empty($receiver['email'])) {
                $this->load->library('email');

                $link_diskusi = base_url('thread/detail/' . $slug);
                if ($comment_id) {
                    $link_diskusi .= '#comment-' . $comment_id;
                }

                $email_html = '
                <div style="font-family: \'Segoe UI\', Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e9ecef; border-radius: 12px; background-color: #ffffff;">
                    <div style="text-align: center; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
                        <h2 style="color: #2C5EAD; margin: 0; font-size: 22px; font-weight: 700;">FORUM SMAN 3 NABIRE</h2>
                    </div>
                    <div style="padding: 20px 0; color: #334155; line-height: 1.6;">
                        <p style="font-size: 16px; margin-top: 0;">Halo <strong>@' . (isset($receiver['username']) ? $receiver['username'] : 'Siswa') . '</strong>,</p>
                        <p style="font-size: 15px;">Ada aktivitas terbaru di akun Anda:</p>
                        
                        <div style="background-color: #f8fafc; padding: 15px; border-left: 4px solid #2C5EAD; border-radius: 4px; margin: 20px 0; font-size: 15px;">
                            ' . $message . '
                        </div>
                        
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="' . $link_diskusi . '" style="background-color: #2C5EAD; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 50px; font-weight: 600; font-size: 14px; display: inline-block;">
                                Lihat Diskusi Lengkap
                            </a>
                        </div>
                    </div>
                </div>';

                $this->email->to($receiver['email']);
                $this->email->subject($email_subject);
                $this->email->message($email_html);
                $this->email->send();
            }
        } catch (Exception $e) {
            // Mengabaikan error email di localhost
        }
    }

    public function remove($receiver_id, $type, $slug, $comment_id = null) {
        $like_string = '|' . $type . '|' . $slug;
        if ($comment_id) {
            $like_string .= '|' . $comment_id;
        }
        
        $this->db->like('title', $like_string);
        $this->db->where('user_id', $receiver_id);
        $this->db->delete('notifications');
    }

    public function get_unread_count($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->count_all_results('notifications');
    }
}