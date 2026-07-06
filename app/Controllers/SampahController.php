<?php
// app/Controllers/SampahController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class SampahController {
    private $db;

    public function __construct() {
        // 🛡️ Satpam URL: Manajemen Kategori Sampah hanya boleh diakses oleh Admin
        Security::requireRole(['admin']);
        $this->db = Database::getInstance()->getConnection();
    }

    // Tampilkan Data
    public function index() {
        $data = []; // Wadah untuk data View
        
        $data['kategori'] = $this->db->query("
            SELECT * FROM kategori_sampah 
            WHERE nama_sampah != '🌟 REWARD PRESTASI' 
            ORDER BY nama_sampah ASC
        ")->fetchAll();
        
        // RENDER TAMPILAN DENGAN LAYOUT UNIVERSAL
        extract($data);
        $title = "Kategori & Harga Sampah";
        $content = __DIR__ . '/../../views/admin/sampah/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // Tambah Data Baru
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Proteksi Keamanan Form

            $nama = $_POST['nama_sampah'];
            $harga_dasar = $_POST['harga_dasar']; 
            
            // FIX: Ambil nilai harga_guru, jika kosong maka otomatis samakan dengan harga dasar
            $harga_guru = !empty($_POST['harga_guru']) ? $_POST['harga_guru'] : $harga_dasar; 
            
            $harga_pengepul = $_POST['harga_pengepul'];
            
            // 🛠️ FIX: Tangkap input konversi_kg dari form (Default 1 jika dikosongkan)
            $konversi_kg = !empty($_POST['konversi_kg']) ? (int)$_POST['konversi_kg'] : 1;

            // Validasi: Harga pengepul tidak boleh lebih kecil dari harga terdiversifikasi yang tertinggi
            $max_harga_beli = max($harga_dasar, $harga_guru);

            if ($harga_pengepul < $max_harga_beli) {
                $_SESSION['error'] = "Gagal: Harga Jual (Pengepul) harus lebih besar/sama dengan Harga Beli (Siswa & Guru).";
            } else {
                // 🛠️ FIX: Masukkan parameter konversi_kg ke query INSERT
                $sql = "INSERT INTO kategori_sampah (nama_sampah, harga_dasar, harga_guru, harga_pengepul, satuan, konversi_kg) VALUES (?, ?, ?, ?, 'Pcs', ?)";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute([$nama, $harga_dasar, $harga_guru, $harga_pengepul, $konversi_kg])) {
                    $_SESSION['success'] = "Kategori sampah baru berhasil ditambahkan.";
                }
            }
        }
        header('Location: ' . BASE_URL . '/sampah');
        exit;
    }

    // Update Data
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Proteksi Keamanan Form

            $id = $_POST['id'];
            $nama = $_POST['nama_sampah'];
            $harga_dasar = $_POST['harga_dasar']; 
            
            // FIX: Ambil nilai harga_guru
            $harga_guru = !empty($_POST['harga_guru']) ? $_POST['harga_guru'] : $harga_dasar;
            
            $harga_pengepul = $_POST['harga_pengepul'];
            
            // 🛠️ FIX: Tangkap input konversi_kg untuk proses update
            $konversi_kg = !empty($_POST['konversi_kg']) ? (int)$_POST['konversi_kg'] : 1;

            $max_harga_beli = max($harga_dasar, $harga_guru);

            if ($harga_pengepul < $max_harga_beli) {
                $_SESSION['error'] = "Update Gagal: Harga Jual (Pengepul) harus lebih besar/sama dengan Harga Beli tertinggi.";
            } else {
                // 🛠️ FIX: Masukkan parameter konversi_kg ke query UPDATE
                $sql = "UPDATE kategori_sampah SET nama_sampah=?, harga_dasar=?, harga_guru=?, harga_pengepul=?, konversi_kg=? WHERE id=?";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute([$nama, $harga_dasar, $harga_guru, $harga_pengepul, $konversi_kg, $id])) {
                    $_SESSION['success'] = "Data harga sampah berhasil diperbarui.";
                }
            }
        }
        header('Location: ' . BASE_URL . '/sampah');
        exit;
    }

    // Hapus Data
    public function delete() {
        $id = $_GET['id'];
        
        try {
            // 1. Cek apakah sampah ini sudah ada di tabungan siswa/guru
            $cekSetoran = $this->db->prepare("SELECT COUNT(*) FROM setoran WHERE kategori_id = ?");
            $cekSetoran->execute([$id]);
            if ($cekSetoran->fetchColumn() > 0) {
                $_SESSION['error'] = "Gagal! Kategori sampah ini tidak bisa dihapus karena sudah digunakan dalam Transaksi Setoran.";
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
?>