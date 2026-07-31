<?php
// app/Controllers/KasController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class KasController {
    private $db;

    public function __construct() {
        Security::requireRole(['admin', 'staff']);
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $data = [];

        $sql = "SELECT k.*, u.nama as admin_nama 
                FROM kas_manual k 
                JOIN users u ON k.user_id = u.id 
                ORDER BY k.tanggal DESC, k.created_at DESC";
        $data['data_kas'] = $this->db->query($sql)->fetchAll();

        extract($data);
        $title = "Pencatatan Kas Manual";
        $content = __DIR__ . '/../../views/admin/kas_manual/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Matikan sementara keamanan CSRF agar form tidak mudah kedaluwarsa
            // Security::validate_csrf();

            $tanggal = $_POST['tanggal'];
            $jenis = $_POST['jenis'];
            $sumber_kas = $_POST['sumber_kas'] ?? 'kas_besar';
            $nominal = (float) $_POST['nominal'];
            $keterangan = $_POST['keterangan'];
            $user_id = $_SESSION['user_id'];

            // 🛡️ FITUR PENGUNCI REWARD TRIWULAN
            // Mencegah pencairan reward jika bukan di bulan Maret (3), Juni (6), September (9), atau Desember (12)
            if (stripos($keterangan, 'reward') !== false && $jenis === 'pengeluaran') {
                $bulan_ini = (int) date('n'); 
                $bulan_triwulan = [3, 6, 9, 12]; 

                if (!in_array($bulan_ini, $bulan_triwulan)) {
                    $_SESSION['error'] = "Akses Ditolak! Reward hanya bisa dikeluarkan pada akhir Triwulan (Maret, Juni, September, Desember) untuk mencegah salah klik.";
                    header('Location: ' . BASE_URL . '/kas');
                    exit;
                }
            }

            if ($nominal <= 0) {
                $_SESSION['error'] = "Nominal tidak valid!";
            } else {
                // ✨ LOGIKA VALIDASI SALDO (Diperbarui agar sinkron dengan Buku Kas)
                if ($jenis === 'pengeluaran') {
                    $saldo = 0;

                    if ($sumber_kas === 'kas_tutup_botol') {
                        // 1. Kalkulasi Saldo Kas Tutup Botol (Pemasukan Penjualan + Manual - Pengeluaran Manual)
                        $in_penjualan = $this->db->query("SELECT IFNULL(SUM(kas_tutup_botol_rp), 0) FROM penjualan")->fetchColumn();
                        $in_manual = $this->db->query("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pemasukan' AND sumber_kas = 'kas_tutup_botol'")->fetchColumn();
                        $out_manual = $this->db->query("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pengeluaran' AND sumber_kas = 'kas_tutup_botol'")->fetchColumn();
                        
                        $saldo = ($in_penjualan + $in_manual) - $out_manual;
                    } else {
                        // 2. Kalkulasi Saldo Kas Besar
                        $in_penjualan = $this->db->query("SELECT IFNULL(SUM(total_pendapatan - kas_tutup_botol_rp), 0) FROM penjualan")->fetchColumn();
                        $in_manual = $this->db->query("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pemasukan' AND sumber_kas = 'kas_besar'")->fetchColumn();
                        
                        $out_tarik = $this->db->query("SELECT IFNULL(SUM(jumlah), 0) FROM penarikan")->fetchColumn();
                        $out_honor = $this->db->query("SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor")->fetchColumn();
                        $out_reward = $this->db->query("SELECT IFNULL(SUM(s.total_harga), 0) FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id WHERE k.nama_sampah = '🌟 REWARD PRESTASI'")->fetchColumn();
                        $out_manual = $this->db->query("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pengeluaran' AND sumber_kas = 'kas_besar'")->fetchColumn();
                        
                        $saldo = ($in_penjualan + $in_manual) - ($out_tarik + $out_honor + $out_reward + $out_manual);
                    }

                    // Cek apakah saldo mencukupi
                    if ($nominal > $saldo) {
                        $_SESSION['error'] = "Gagal! Saldo " . strtoupper(str_replace('_', ' ', $sumber_kas)) . " tidak mencukupi. (Sisa Saldo Ril: Rp " . number_format($saldo, 0, ',', '.') . ")";
                        header('Location: ' . BASE_URL . '/kas');
                        exit;
                    }
                }

                $sql = "INSERT INTO kas_manual (user_id, tanggal, jenis, sumber_kas, nominal, keterangan) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                if($stmt->execute([$user_id, $tanggal, $jenis, $sumber_kas, $nominal, $keterangan])) {
                    $_SESSION['success'] = "Data Kas Manual berhasil dicatat!";
                } else {
                    $_SESSION['error'] = "Gagal mencatat kas manual.";
                }
            }
            header('Location: ' . BASE_URL . '/kas');
            exit;
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $stmt = $this->db->prepare("DELETE FROM kas_manual WHERE id = ?");
            if($stmt->execute([$_GET['id']])) {
                $_SESSION['success'] = "Catatan Kas Manual dibatalkan/dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus data.";
            }
        }
        header('Location: ' . BASE_URL . '/kas');
        exit;
    }
}
?>