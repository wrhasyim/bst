<?php
// app/Controllers/ProfilController.php
require_once __DIR__ . '/../Core/Database.php';

class ProfilController {
    private $db;

    public function __construct() {
        // Bisa diakses oleh SEMUA ROLE yang sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // 1. TAMPILKAN HALAMAN PROFIL
    public function index() {
        $id = $_SESSION['user_id'];
        
        $stmt = $this->db->prepare("SELECT u.*, k.nama_kelas FROM users u LEFT JOIN kelas k ON u.kelas_id = k.id WHERE u.id = ?");
        $stmt->execute([$id]);
        $profil = $stmt->fetch();

        $title = "Profil & Keamanan Akun";
        $content = __DIR__ . '/../../views/admin/profil/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // 2. PROSES UPDATE DATA & PASSWORD
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'];
            $nama = trim($_POST['nama']);
            $username = strtolower(str_replace(' ', '', trim($_POST['username'])));
            $password_baru = $_POST['password'] ?? '';
            $konfirmasi = $_POST['konfirmasi_password'] ?? '';

            try {
                if (!empty($password_baru)) {
                    if ($password_baru !== $konfirmasi) {
                        $_SESSION['error'] = "Peringatan: Konfirmasi sandi baru tidak cocok!";
                        header('Location: ' . BASE_URL . '/profil');
                        exit;
                    }
                    // Jika ganti password
                    $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
                    $stmt = $this->db->prepare("UPDATE users SET nama = ?, username = ?, password = ? WHERE id = ?");
                    $stmt->execute([$nama, $username, $password_hash, $id]);
                } else {
                    // Jika password dikosongkan (hanya ganti nama/username)
                    $stmt = $this->db->prepare("UPDATE users SET nama = ?, username = ? WHERE id = ?");
                    $stmt->execute([$nama, $username, $id]);
                }

                // Perbarui Session Nama yang tampil di pojok kanan atas
                $_SESSION['nama'] = $nama;
                $_SESSION['success'] = "Data profil dan keamanan berhasil diperbarui!";
                
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal menyimpan. Username mungkin sudah digunakan oleh pengguna lain.";
            }

            header('Location: ' . BASE_URL . '/profil');
            exit;
        }
    }
}