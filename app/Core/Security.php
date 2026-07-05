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

    /**
     * 🛡️ Satpam URL: Validasi Hak Akses (Role-Based Access Control)
     * Mencegah user mengakses halaman yang bukan haknya melalui URL browser.
     * 
     * @param array $allowed_roles Daftar peran yang diizinkan masuk (misal: ['admin', 'staff'])
     */
    public static function requireRole($allowed_roles = []) {
        // Pastikan sesi sudah dimulai sebelum kita membaca $_SESSION
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Jika pengguna belum login sama sekali, lempar ke halaman login
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Akses ditolak! Silakan login terlebih dahulu.";
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        // 2. Ambil peran pengguna saat ini (ubah ke huruf kecil agar aman dari salah ketik)
        $user_role = strtolower($_SESSION['role'] ?? '');

        // 3. Cek apakah peran pengguna ADA di dalam daftar yang diizinkan ($allowed_roles)
        if (!in_array($user_role, $allowed_roles)) {
            
            // Jika tidak diizinkan, berikan pesan error
            $_SESSION['error'] = "Akses ditolak! Anda tidak memiliki izin untuk membuka halaman ini.";
            
            // Tendang kembali ke dashboard sesuai dengan role mereka masing-masing
            if ($user_role === 'admin' || $user_role === 'staff') {
                header('Location: ' . BASE_URL . '/admin/dashboard');
            } elseif ($user_role === 'walas') {
                header('Location: ' . BASE_URL . '/walas/dashboard');
            } elseif ($user_role === 'siswa') {
                header('Location: ' . BASE_URL . '/siswa/dashboard');
            } else {
                header('Location: ' . BASE_URL . '/dashboard');
            }
            exit; // Hentikan eksekusi kode sepenuhnya
        }
    }
}
?>