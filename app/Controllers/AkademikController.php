<?php
// app/Controllers/AkademikController.php
require_once __DIR__ . '/../Core/Database.php';

class AkademikController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // =================================================================
    // 1. HALAMAN KENAIKAN KELAS
    // =================================================================
    public function kenaikan() {
        $kelas_list = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $title = "Kenaikan Kelas Massal";
        $content = __DIR__ . '/../../views/admin/akademik/kenaikan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function proses_kenaikan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dari_kelas = $_POST['dari_kelas'];
            $ke_kelas = $_POST['ke_kelas'];

            if ($dari_kelas === $ke_kelas) {
                $_SESSION['error'] = "Kelas asal dan tujuan tidak boleh sama!";
            } else {
                // Update seluruh siswa di kelas asal ke kelas tujuan
                $stmt = $this->db->prepare("UPDATE users SET kelas_id = ? WHERE kelas_id = ? AND role = 'siswa'");
                if ($stmt->execute([$ke_kelas, $dari_kelas])) {
                    $count = $stmt->rowCount();
                    $_SESSION['success'] = "Berhasil menaikkan $count siswa ke kelas baru.";
                } else {
                    $_SESSION['error'] = "Gagal memproses kenaikan kelas.";
                }
            }
            header('Location: ' . BASE_URL . '/akademik/kenaikan');
            exit;
        }
    }

    // =================================================================
    // 2. HALAMAN KELULUSAN (ALUMNI)
    // =================================================================
    public function kelulusan() {
        $kelas_list = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $title = "Kelulusan Alumni";
        $content = __DIR__ . '/../../views/admin/akademik/kelulusan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function proses_kelulusan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kelas_id = $_POST['kelas_id'];

            // Proses Kelulusan: 
            // 1. Set kelas_id menjadi NULL (Alumni tidak punya kelas)
            // 2. Set is_active menjadi 0 (Akun dinonaktifkan agar tidak muncul di dashboard/leaderboard)
            $stmt = $this->db->prepare("UPDATE users SET kelas_id = NULL, is_active = 0 WHERE kelas_id = ? AND role = 'siswa'");
            
            if ($stmt->execute([$kelas_id])) {
                $count = $stmt->rowCount();
                $_SESSION['success'] = "Berhasil meluluskan $count siswa. Status mereka kini menjadi Alumni (Non-aktif).";
            } else {
                $_SESSION['error'] = "Gagal memproses kelulusan.";
            }
            header('Location: ' . BASE_URL . '/akademik/kelulusan');
            exit;
        }
    }
}