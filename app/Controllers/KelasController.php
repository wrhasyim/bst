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

    // =================================================================
    // 1. TAMPILKAN DATA KELAS
    // =================================================================
    public function index() {
        // Query Cerdas: Ambil data kelas, nama wali kelas, dan hitung total siswa di kelas tersebut
        $sql = "SELECT k.*, u.nama as nama_walikelas, 
                (SELECT COUNT(id) FROM users WHERE kelas_id = k.id AND role = 'siswa') as total_siswa
                FROM kelas k 
                LEFT JOIN users u ON k.walikelas_id = u.id 
                ORDER BY k.nama_kelas ASC";
        $kelas = $this->db->query($sql)->fetchAll();
        
        // Ambil daftar guru untuk dropdown pilihan Wali Kelas
        $guru = $this->db->query("SELECT id, nama FROM users WHERE role IN ('guru', 'admin') AND is_active = 1 ORDER BY nama ASC")->fetchAll();

        $title = "Manajemen Data Kelas";
        $content = __DIR__ . '/../../views/admin/kelas/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. SIMPAN DATA KELAS BARU (DENGAN PROTEKSI 1 WALAS 1 KELAS)
    // =================================================================
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_kelas = htmlspecialchars(trim($_POST['nama_kelas']));
            $walikelas_id = !empty($_POST['walikelas_id']) ? $_POST['walikelas_id'] : null;

            // 🛡️ VALIDASI BACKEND: Pastikan guru yang ditunjuk belum menjadi Walas di kelas lain
            if ($walikelas_id) {
                $cek = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE walikelas_id = ? LIMIT 1");
                $cek->execute([$walikelas_id]);
                $konflik = $cek->fetch();
                
                if ($konflik) {
                    $_SESSION['error'] = "Gagal! Guru tersebut sudah menjabat sebagai wali kelas di kelas " . $konflik['nama_kelas'] . ".";
                    header('Location: ' . BASE_URL . '/kelas');
                    exit;
                }
            }

            try {
                $sql = "INSERT INTO kelas (nama_kelas, walikelas_id) VALUES (?, ?)";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute([$nama_kelas, $walikelas_id])) {
                    $_SESSION['success'] = "Kelas berhasil ditambahkan!";
                } else {
                    $_SESSION['error'] = "Gagal menambahkan data kelas.";
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Terjadi kesalahan database saat menambah kelas.";
            }
            header('Location: ' . BASE_URL . '/kelas');
            exit;
        }
    }

    // =================================================================
    // 3. UBAH DATA KELAS (DENGAN PROTEKSI MULTI-CLAIM WALAS)
    // =================================================================
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nama_kelas = htmlspecialchars(trim($_POST['nama_kelas']));
            $walikelas_id = !empty($_POST['walikelas_id']) ? $_POST['walikelas_id'] : null;

            // 🛡️ VALIDASI BACKEND: Pastikan guru tersebut tidak dipakai kelas lain (kecuali kelas ini sendiri)
            if ($walikelas_id) {
                $cek = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE walikelas_id = ? AND id != ? LIMIT 1");
                $cek->execute([$walikelas_id, $id]);
                $konflik = $cek->fetch();
                
                if ($konflik) {
                    $_SESSION['error'] = "Gagal! Guru tersebut sudah menjabat sebagai wali kelas di kelas " . $konflik['nama_kelas'] . ".";
                    header('Location: ' . BASE_URL . '/kelas');
                    exit;
                }
            }

            try {
                $sql = "UPDATE kelas SET nama_kelas=?, walikelas_id=? WHERE id=?";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute([$nama_kelas, $walikelas_id, $id])) {
                    $_SESSION['success'] = "Data Kelas berhasil diperbarui!";
                } else {
                    $_SESSION['error'] = "Gagal memperbarui data kelas.";
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Terjadi kesalahan database saat memperbarui kelas.";
            }
            header('Location: ' . BASE_URL . '/kelas');
            exit;
        }
    }

    // =================================================================
    // 4. HAPUS DATA KELAS (INTEGRITAS SINKRONISASI NASABAH)
    // =================================================================
    public function delete() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            try {
                // 🛡️ DATA INTEGRITY CHECK: Cek apakah masih ada siswa aktif di kelas ini
                $cekSiswa = $this->db->prepare("SELECT COUNT(*) FROM users WHERE kelas_id = ?");
                $cekSiswa->execute([$id]);
                
                if ($cekSiswa->fetchColumn() > 0) {
                    $_SESSION['error'] = "Gagal! Kelas ini tidak bisa dihapus karena masih ada siswa di dalamnya. Pindahkan atau luluskan siswa terlebih dahulu di menu Akademik.";
                    header('Location: ' . BASE_URL . '/kelas');
                    exit;
                }

                // Jika kelas sudah benar-benar kosong, lakukan penghapusan
                $stmt = $this->db->prepare("DELETE FROM kelas WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Kelas berhasil dihapus.";

            } catch (Exception $e) {
                $_SESSION['error'] = "Terjadi kesalahan sistem saat menghapus kelas.";
            }
        }
        header('Location: ' . BASE_URL . '/kelas');
        exit;
    }
}