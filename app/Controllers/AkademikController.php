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

    // ==========================================================
    // 1. HALAMAN KENAIKAN KELAS
    // ==========================================================
    public function kenaikan() {
        // Ambil daftar kelas untuk dropdown asal dan tujuan
        $kelas = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $title = "Proses Kenaikan Kelas";
        $content = __DIR__ . '/../../views/admin/akademik/kenaikan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function proses_kenaikan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kelas_asal = $_POST['kelas_asal'];
            $kelas_tujuan = $_POST['kelas_tujuan'];

            if ($kelas_asal == $kelas_tujuan) {
                $_SESSION['error'] = "Gagal! Kelas asal dan tujuan tidak boleh sama.";
            } else {
                try {
                    $sql = "UPDATE users SET kelas_id = ? WHERE kelas_id = ? AND role = 'siswa'";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$kelas_tujuan, $kelas_asal]);
                    
                    $jumlah = $stmt->rowCount();
                    $_SESSION['success'] = "Berhasil! $jumlah siswa telah dipindahkan ke kelas tujuan.";
                } catch (Exception $e) {
                    $_SESSION['error'] = "Terjadi kesalahan sistem.";
                }
            }
        }
        header('Location: ' . BASE_URL . '/akademik/kenaikan');
        exit;
    }

    // ==========================================================
    // 2. HALAMAN KELULUSAN
    // ==========================================================
    public function kelulusan() {
        $kelas = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $title = "Proses Kelulusan Alumni";
        $content = __DIR__ . '/../../views/admin/akademik/kelulusan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function proses_kelulusan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kelas_id = $_POST['kelas_id'];

            try {
                // Proses Kelulusan: Role jadi 'alumni', status non-aktif, kelas_id dikosongkan
                $sql = "UPDATE users SET role = 'alumni', is_active = 0, kelas_id = NULL 
                        WHERE kelas_id = ? AND role = 'siswa'";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$kelas_id]);

                $jumlah = $stmt->rowCount();
                $_SESSION['success'] = "Selamat! $jumlah siswa di kelas tersebut telah dinyatakan Lulus (Alumni) dan dinonaktifkan.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Gagal memproses kelulusan.";
            }
        }
        header('Location: ' . BASE_URL . '/akademik/kelulusan');
        exit;
    }
}