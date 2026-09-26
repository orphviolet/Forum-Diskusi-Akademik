<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * KONFIGURASI SMTP EMAIL (DIGUNAKAN SAAT SUDAH ONLINE/HOSTING)
 * Jika sudah dihosting, ganti 'email_anda@gmail.com' dan 'password_aplikasi_anda'
 * dengan akun Gmail yang valid agar email verifikasi benar-benar terkirim ke siswa.
 */
$config = [
    'protocol'  => 'smtp',
    'smtp_host' => 'ssl://smtp.googlemail.com',
    'smtp_user' => 'email_anda@gmail.com',     // <-- Ganti dengan Gmail Anda nanti
    'smtp_pass' => 'password_aplikasi_anda', // <-- Ganti dengan Password Aplikasi Google Anda nanti
    'smtp_port' => 465,
    'mailtype'  => 'html',
    'charset'   => 'utf-8',
    'newline'   => "\r\n"
];