<?php
// app/Core/Security.php

class Security {
    
    /**
     * Generate CSRF token jika belum ada di session
     */
    public static function csrf_token() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Cetak input hidden HTML berisi token untuk form
     */
    public static function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . self::csrf_token() . '">';
    }

    /**
     * Validasi token saat ada request POST
     * Cegah serangan Cross-Site Request Forgery
     */
    public static function validate_csrf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                
                // Jika token tidak valid/hilang, tolak akses dan kembalikan ke halaman sebelumnya
                $_SESSION['error'] = "Security Alert: Invalid CSRF Token. Form kedaluwarsa atau permintaan ditolak demi keamanan.";
                
                // Gunakan HTTP_REFERER jika ada, atau kembalikan ke dashboard
                $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : BASE_URL . '/dashboard';
                header('Location: ' . $redirect_url);
                exit;
                
            }
        }
    }
}