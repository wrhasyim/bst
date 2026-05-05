<?php
// app/Controllers/HonorController.php
require_once __DIR__ . '/../Models/Honor.php';
require_once __DIR__ . '/../Core/Database.php'; 
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security untuk proteksi CSRF

class HonorController {
    private $honorModel;
    private $db; 

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->honorModel = new Honor();
        $this->db = Database::getInstance()->getConnection(); 
    }

    // =================================================================
    // 1. TAMPILKAN HALAMAN HONOR WALI KELAS
    // =================================================================
    public function index() {
        // Ambil data alokasi jatah honor
        $data_honor = $this->honorModel->getHonorWaliKelas();
        
        if (is_array($data_honor) && count($data_honor) > 0) {
            foreach ($data_honor as &$h) {
                $user_id = $h['user_id'] ?? 0;
                $total_jatah = $h['total_jatah'] ?? 0;
                
                // 🛠️ BUG FIX: Override Query Model agar HANYA menghitung pencairan Walas
                // Mencegah uang Pengelola/Piket ikut terhitung jika Admin merangkap sbg Wali Kelas
                $stmtCair = $this->db->prepare("SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor WHERE user_id = ? AND jenis = 'walikelas'");
                $stmtCair->execute([$user_id]);
                $h['sudah_cair'] = $stmtCair->fetchColumn() ?? 0;
                
                $h['sisa_honor'] = $total_jatah - $h['sudah_cair'];
            }
        } else {
            $data_honor = []; 
        }

        // 🛠️ BUG FIX: Override Query untuk Sidebar Riwayat
        // Memaksa tabel hanya menampilkan histori dengan tag 'walikelas'
        $sqlRiwayat = "SELECT ph.*, u.nama 
                       FROM pencairan_honor ph 
                       JOIN users u ON ph.user_id = u.id 
                       WHERE ph.jenis = 'walikelas' 
                       ORDER BY ph.tanggal_cair DESC LIMIT 20";
        $riwayat = $this->db->query($sqlRiwayat)->fetchAll();
        
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
    // 3. FITUR CETAK NOTA MASSAL HONOR (MANIFEST)
    // =================================================================
    public function cetak_batch() {
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');

        // Ini sudah benar karena memiliki filter jenis = 'walikelas'
        $sql = "SELECT ph.*, u.nama 
                FROM pencairan_honor ph 
                JOIN users u ON ph.user_id = u.id 
                WHERE DATE(ph.tanggal_cair) = :tgl AND ph.jenis = 'walikelas'
                ORDER BY u.nama ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tgl' => $tanggal]);
        $data_honor = $stmt->fetchAll();

        require_once __DIR__ . '/../../views/admin/honor/cetak_nota.php';
    }
}