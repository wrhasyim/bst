<?php
// app/Controllers/HonorController.php
require_once __DIR__ . '/../Models/Honor.php';
require_once __DIR__ . '/../Core/Database.php'; 
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security untuk proteksi CSRF

class HonorController {
    private $honorModel;
    private $db; 

    public function __construct() {
        // 🛡️ Satpam URL: Pencairan honor hanya boleh dilakukan oleh Admin
        Security::requireRole(['admin']);
        
        $this->honorModel = new Honor();
        $this->db = Database::getInstance()->getConnection(); 
    }

    // =================================================================
    // 1. TAMPILKAN HALAMAN HONOR WALI KELAS
    // =================================================================
    public function index() {
        $data = []; // Wadah untuk data View

        // 🚀 CRITICAL RULE APPLIED: ARCHITECT SNAPSHOT PATTERN
        $sql = "SELECT s.walikelas_id as user_id, u.nama as nama_guru, k.nama_kelas, SUM(s.honor_walas_rp) as total_jatah
                FROM setoran s
                JOIN users u ON s.walikelas_id = u.id
                JOIN kelas k ON u.id = k.walikelas_id
                WHERE s.status = 'valid' AND s.is_sold = 1
                GROUP BY s.walikelas_id";
                
        $data_honor = $this->db->query($sql)->fetchAll();
        
        if (is_array($data_honor) && count($data_honor) > 0) {
            foreach ($data_honor as &$h) {
                $user_id = $h['user_id'] ?? 0;
                $total_jatah = $h['total_jatah'] ?? 0;
                
                // 🛠️ Mencegah uang Pengelola/Piket ikut terhitung jika Admin merangkap sbg Wali Kelas
                $stmtCair = $this->db->prepare("SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor WHERE user_id = ? AND jenis = 'walikelas'");
                $stmtCair->execute([$user_id]);
                $h['sudah_cair'] = $stmtCair->fetchColumn() ?? 0;
                
                // Sisa honor murni kalkulasi (Jatah Permanen - Pencairan Historis)
                $h['sisa_honor'] = $total_jatah - $h['sudah_cair'];
            }
        } else {
            $data_honor = []; 
        }
        
        $data['data_honor'] = $data_honor;

        // 🛠️ BUG FIX: Override Query untuk Sidebar Riwayat
        $sqlRiwayat = "SELECT ph.*, u.nama 
                       FROM pencairan_honor ph 
                       JOIN users u ON ph.user_id = u.id 
                       WHERE ph.jenis = 'walikelas' 
                       ORDER BY ph.tanggal_cair DESC LIMIT 20";
        $data['riwayat'] = $this->db->query($sqlRiwayat)->fetchAll();
        
        // RENDER TAMPILAN DENGAN LAYOUT UNIVERSAL
        extract($data);
        $title = "Pencairan Honor Wali Kelas";
        $content = __DIR__ . '/../../views/admin/honor/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. PROSES PENCAIRAN HONOR WALI KELAS
    // =================================================================
    public function cairkan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Validasi Keamanan Form
            
            $user_id = (int)($_POST['user_id'] ?? 0);
            $jumlah = (float)($_POST['jumlah'] ?? 0);
            $nama_kelas = htmlspecialchars($_POST['nama_kelas'] ?? 'Tidak Diketahui');

            if ($user_id > 0 && $jumlah > 0) {
                $data = [
                    'user_id' => $user_id,
                    'jumlah' => $jumlah,
                    'jenis' => 'walikelas', // 🔐 KUNCI UTAMA: Tagging eksklusif walikelas
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
    // 3. FITUR CETAK NOTA MASSAL HONOR (MANIFEST DAFTAR TUNGGU)
    // =================================================================
    public function cetak_batch() {
        // 🛠️ REFACTORING ULTIMATE: Mengubah Laporan Histori menjadi Manifest Daftar Tunggu!
        
        $sql = "SELECT s.walikelas_id as user_id, u.nama as nama_guru, SUM(s.honor_walas_rp) as total_jatah
                FROM setoran s
                JOIN users u ON s.walikelas_id = u.id
                WHERE s.status = 'valid' AND s.is_sold = 1
                GROUP BY s.walikelas_id
                ORDER BY u.nama ASC";
                
        $potensi_honor = $this->db->query($sql)->fetchAll();
        $data_honor = [];

        if (is_array($potensi_honor) && count($potensi_honor) > 0) {
            foreach ($potensi_honor as $h) {
                $user_id = $h['user_id'] ?? 0;
                $total_jatah = $h['total_jatah'] ?? 0;
                
                // Cari total yang sudah pernah dicairkan
                $stmtCair = $this->db->prepare("SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor WHERE user_id = ? AND jenis = 'walikelas'");
                $stmtCair->execute([$user_id]);
                $sudah_cair = $stmtCair->fetchColumn() ?? 0;
                
                $sisa_honor = $total_jatah - $sudah_cair;

                // 🎯 KUNCI UTAMA: Hanya daftarkan ke array cetak JIKA SISA SALDO > 0
                if ($sisa_honor > 0) {
                    $data_honor[] = [
                        'nama'   => $h['nama_guru'],
                        'jumlah' => $sisa_honor 
                    ];
                }
            }
        }

        // Jika tidak ada data yang bisa dicairkan
        if (empty($data_honor)) {
            echo "<script>alert('Semua honor wali kelas sudah lunas! Tidak ada manifest tagihan untuk dicetak.'); window.close();</script>";
            exit;
        }

        // 📝 Catatan: Tidak menggunakan layout admin karena ini halaman cetak (print/kertas)
        require_once __DIR__ . '/../../views/admin/honor/cetak_nota.php';
    }
}
?>