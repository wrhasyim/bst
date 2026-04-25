<?php
// app/Controllers/HonorController.php
require_once __DIR__ . '/../Models/Honor.php';
require_once __DIR__ . '/../Core/Database.php'; // Tambahan untuk koneksi DB langsung

class HonorController {
    private $honorModel;
    private $db; // Deklarasi properti $db

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->honorModel = new Honor();
        $this->db = Database::getInstance()->getConnection(); // Inisialisasi koneksi DB
    }

    public function index() {
        // Ambil data honor
        $data_honor = $this->honorModel->getHonorWaliKelas();
        
        // Pastikan $data_honor adalah array yang valid sebelum di-looping
        if (is_array($data_honor) && count($data_honor) > 0) {
            foreach ($data_honor as &$h) {
                // Keamanan: Jika array key tidak ditemukan, paksa jadi 0
                $user_id = $h['user_id'] ?? 0;
                $total_jatah = $h['total_jatah'] ?? 0;
                
                $h['sudah_cair'] = $this->honorModel->getSudahCair($user_id);
                $h['sisa_honor'] = $total_jatah - $h['sudah_cair'];
            }
        } else {
            $data_honor = []; // Kosongkan jika tidak ada data
        }

        $riwayat = $this->honorModel->getRiwayat();
        
        $title = "Pencairan Honor Wali Kelas";
        $content = __DIR__ . '/../../views/admin/honor/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function cairkan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi post
            $user_id = $_POST['user_id'] ?? 0;
            $jumlah = $_POST['jumlah'] ?? 0;
            $nama_kelas = $_POST['nama_kelas'] ?? 'Tidak Diketahui';

            if ($user_id > 0 && $jumlah > 0) {
                $data = [
                    'user_id' => $user_id,
                    'jumlah' => $jumlah,
                    'jenis' => 'walikelas',
                    'keterangan' => 'Pencairan honor Wali Kelas ' . $nama_kelas
                ];

                if ($this->honorModel->simpanPencairan($data)) {
                    $_SESSION['success'] = "Honor berhasil dicairkan sejumlah Rp" . number_format($jumlah, 0, ',', '.');
                } else {
                    $_SESSION['error'] = "Gagal mencatat riwayat pencairan ke database.";
                }
            } else {
                $_SESSION['error'] = "Data tidak valid untuk dicairkan.";
            }
            header('Location: ' . BASE_URL . '/honor');
            exit;
        }
    }

    // =================================================================
    // FITUR CETAK NOTA MASSAL HONOR (MANIFEST TANDA TERIMA)
    // =================================================================
    public function cetak_batch() {
        // Default mengambil tanggal hari ini, atau tanggal spesifik jika diakses via URL
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');

        $sql = "SELECT ph.*, u.nama 
                FROM pencairan_honor ph 
                JOIN users u ON ph.user_id = u.id 
                WHERE DATE(ph.tanggal_cair) = :tgl AND ph.jenis = 'walikelas'
                ORDER BY u.nama ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tgl' => $tanggal]);
        $data_honor = $stmt->fetchAll();

        // Kita tidak menggunakan layout admin, agar kertas bersih saat dicetak
        require_once __DIR__ . '/../../views/admin/honor/cetak_nota.php';
    }
}