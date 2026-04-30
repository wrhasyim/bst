<?php
// app/Controllers/KasManualController.php
require_once __DIR__ . '/../Core/Database.php';

class KasManualController {
    private $db;

    public function __construct() {
        // Hanya Admin / Staff yang boleh akses
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // Tampilkan Halaman
    public function index() {
        $sql = "SELECT k.*, u.nama as admin_nama 
                FROM kas_manual k 
                JOIN users u ON k.user_id = u.id 
                ORDER BY k.tanggal DESC, k.created_at DESC";
        $data_kas = $this->db->query($sql)->fetchAll();

        $title = "Pencatatan Kas Manual";
        $content = __DIR__ . '/../../views/admin/kas_manual/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // Simpan Data Baru
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = $_POST['tanggal'];
            $jenis = $_POST['jenis'];
            $nominal = (float) $_POST['nominal'];
            $keterangan = $_POST['keterangan'];
            $user_id = $_SESSION['user_id'];

            if ($nominal <= 0) {
                $_SESSION['error'] = "Nominal tidak valid!";
            } else {
                $sql = "INSERT INTO kas_manual (user_id, tanggal, jenis, nominal, keterangan) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                if($stmt->execute([$user_id, $tanggal, $jenis, $nominal, $keterangan])) {
                    $_SESSION['success'] = "Data Kas Manual berhasil dicatat!";
                } else {
                    $_SESSION['error'] = "Gagal mencatat kas manual.";
                }
            }
            header('Location: ' . BASE_URL . '/kas_manual');
            exit;
        }
    }

    // Hapus Data (Membatalkan)
    public function delete() {
        if (isset($_GET['id'])) {
            $stmt = $this->db->prepare("DELETE FROM kas_manual WHERE id = ?");
            if($stmt->execute([$_GET['id']])) {
                $_SESSION['success'] = "Catatan Kas Manual dibatalkan/dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus data.";
            }
        }
        header('Location: ' . BASE_URL . '/kas_manual');
        exit;
    }
}