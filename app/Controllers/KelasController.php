<?php
// app/Controllers/KelasController.php
require_once __DIR__ . '/../Core/Database.php';

class KelasController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // Tampilkan Data Kelas
    public function index() {
        // Query Cerdas: Ambil data kelas, nama wali kelas, dan hitung total siswa di kelas tersebut
        $sql = "SELECT k.*, u.nama as nama_walikelas, 
                (SELECT COUNT(id) FROM users WHERE kelas_id = k.id AND role = 'siswa') as total_siswa
                FROM kelas k 
                LEFT JOIN users u ON k.walikelas_id = u.id 
                ORDER BY k.nama_kelas ASC";
        $kelas = $this->db->query($sql)->fetchAll();
        
        // Ambil daftar guru untuk dropdown pilihan Wali Kelas
        $guru = $this->db->query("SELECT id, nama FROM users WHERE role IN ('guru', 'admin') ORDER BY nama ASC")->fetchAll();

        $title = "Manajemen Data Kelas";
        $content = __DIR__ . '/../../views/admin/kelas/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // Tambah Data Kelas Baru
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_kelas = $_POST['nama_kelas'];
            $walikelas_id = !empty($_POST['walikelas_id']) ? $_POST['walikelas_id'] : null;

            $stmt = $this->db->prepare("INSERT INTO kelas (nama_kelas, walikelas_id) VALUES (?, ?)");
            if ($stmt->execute([$nama_kelas, $walikelas_id])) {
                $_SESSION['success'] = "Data Kelas baru berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan data kelas.";
            }
        }
        header('Location: ' . BASE_URL . '/kelas');
    }

    // Update Data Kelas
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nama_kelas = $_POST['nama_kelas'];
            $walikelas_id = !empty($_POST['walikelas_id']) ? $_POST['walikelas_id'] : null;

            $stmt = $this->db->prepare("UPDATE kelas SET nama_kelas=?, walikelas_id=? WHERE id=?");
            if ($stmt->execute([$nama_kelas, $walikelas_id, $id])) {
                $_SESSION['success'] = "Data Kelas berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data kelas.";
            }
        }
        header('Location: ' . BASE_URL . '/kelas');
    }

    // Hapus Data Kelas
    public function delete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            try {
                $stmt = $this->db->prepare("DELETE FROM kelas WHERE id=?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Kelas berhasil dihapus!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Gagal: Kelas ini tidak bisa dihapus karena masih ada siswa di dalamnya.";
            }
        }
        header('Location: ' . BASE_URL . '/kelas');
    }
}