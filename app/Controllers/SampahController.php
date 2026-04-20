<?php
// app/Controllers/SampahController.php
require_once __DIR__ . '/../Core/Database.php';

class SampahController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // Tampilkan Data
    public function index() {
        $kategori = $this->db->query("SELECT * FROM kategori_sampah ORDER BY nama_sampah ASC")->fetchAll();
        
        $title = "Kategori & Harga Sampah";
        $content = __DIR__ . '/../../views/admin/sampah/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // Tambah Data Baru
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama_sampah'];
            $harga_dasar = $_POST['harga_dasar']; // Disinkronkan dengan DB
            $harga_pengepul = $_POST['harga_pengepul'];

            if ($harga_pengepul < $harga_dasar) {
                $_SESSION['error'] = "Gagal: Harga Jual (Pengepul) tidak boleh lebih kecil dari Harga Dasar (Nasabah).";
            } else {
                $sql = "INSERT INTO kategori_sampah (nama_sampah, harga_dasar, harga_pengepul, satuan) VALUES (?, ?, ?, 'Pcs')";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute([$nama, $harga_dasar, $harga_pengepul])) {
                    $_SESSION['success'] = "Kategori sampah baru berhasil ditambahkan.";
                }
            }
        }
        header('Location: ' . BASE_URL . '/sampah');
    }

    // Update Data
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nama = $_POST['nama_sampah'];
            $harga_dasar = $_POST['harga_dasar']; // Disinkronkan dengan DB
            $harga_pengepul = $_POST['harga_pengepul'];

            if ($harga_pengepul < $harga_dasar) {
                $_SESSION['error'] = "Update Gagal: Harga Pengepul harus lebih besar/sama dengan Harga Dasar.";
            } else {
                $sql = "UPDATE kategori_sampah SET nama_sampah=?, harga_dasar=?, harga_pengepul=? WHERE id=?";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute([$nama, $harga_dasar, $harga_pengepul, $id])) {
                    $_SESSION['success'] = "Data harga sampah berhasil diperbarui.";
                }
            }
        }
        header('Location: ' . BASE_URL . '/sampah');
    }

    // Hapus Data
  public function delete() {
        $id = $_GET['id'];
        
        try {
            // 1. Cek apakah sampah ini sudah ada di tabungan siswa
            $cekSetoran = $this->db->prepare("SELECT COUNT(*) FROM setoran WHERE kategori_id = ?");
            $cekSetoran->execute([$id]);
            if ($cekSetoran->fetchColumn() > 0) {
                $_SESSION['error'] = "Gagal! Kategori sampah ini tidak bisa dihapus karena sudah digunakan dalam Tabungan Nasabah.";
                header('Location: ' . BASE_URL . '/sampah');
                exit;
            }

            // 2. Cek apakah sampah ini sudah ada di riwayat penjualan
            $cekJual = $this->db->prepare("SELECT COUNT(*) FROM penjualan WHERE kategori_id = ?");
            $cekJual->execute([$id]);
            if ($cekJual->fetchColumn() > 0) {
                $_SESSION['error'] = "Gagal! Kategori sampah ini tidak bisa dihapus karena sudah ada di Riwayat Penjualan Pengepul.";
                header('Location: ' . BASE_URL . '/sampah');
                exit;
            }

            // 3. Jika aman, baru hapus
            $stmt = $this->db->prepare("DELETE FROM kategori_sampah WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success'] = "Kategori sampah berhasil dihapus.";
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Terjadi kesalahan sistem saat menghapus data.";
        }

        header('Location: ' . BASE_URL . '/sampah');
        exit;
    }
}