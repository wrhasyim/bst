<?php
// app/Controllers/AuthController.php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Core/Logger.php';   // 🛡️ Load Logger Global
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        // 0. Pastikan Sesi sudah dimulai sebelum menggunakan $_SESSION
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. PROSES LOGIKA LOGIN (Jika form disubmit via POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 🛡️ Validasi CSRF Token agar form login tidak bisa di-bypass oleh bot
            Security::validate_csrf();

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            // ✅ Memanggil data user dari Model
            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                
                // Cegah user yang sudah di-soft delete atau dinonaktifkan untuk login
                if (isset($user['is_active']) && $user['is_active'] == 0) {
                    $_SESSION['error'] = "Akun Anda telah dinonaktifkan!";
                    header('Location: ' . BASE_URL . '/auth/login');
                    exit;
                }

                // Set Session Utama (Identitas Digital)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role']; // Ini penentu hak akses kita
                $_SESSION['nama'] = $user['nama'];

                // 🛡️ Buat token CSRF baru khusus untuk sesi login ini
                Security::csrf_token();

                // 🛡️ Catat aktivitas login ke Audit Trail
                Logger::log("Login", "User berhasil masuk ke dalam sistem");

                // 🚀 PERBAIKAN BUG 404: 
                // Semua pengguna (Admin, Staf, Walas, Siswa) kini diarahkan ke satu rute utama
                header('Location: ' . BASE_URL . '/dashboard');
                exit;

            } else {
                $_SESSION['error'] = "Username atau password salah!";
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }
        }

        // 2. TAMPILKAN HALAMAN LOGIN (Jika request GET)
        $title = "Login - BST SYSTEM";
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 🛡️ Catat log SEBELUM session dihancurkan (agar nama/user_id masih terbaca oleh Logger)
        if (isset($_SESSION['user_id'])) {
            Logger::log("Logout", "User keluar dari sistem");
        }

        // Hancurkan semua sesi keamanan dan identitas
        unset($_SESSION['csrf_token']);
        session_unset();
        session_destroy();
        
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}
?>