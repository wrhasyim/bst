<?php
// app/Controllers/DashboardController.php
require_once __DIR__ . '/../Core/Database.php';

class DashboardController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        // 1. Stok Gudang (Sampah Valid yang belum terjual)
        $stmtStok = $this->db->query("SELECT SUM(berat) FROM setoran WHERE status = 'valid' AND is_sold = 0");
        $stok_gudang = $stmtStok->fetchColumn() ?? 0;

        // 2. Total Tabungan (Kewajiban bayar ke Nasabah - Hanya yang Valid)
        $stmtTabungan = $this->db->query("SELECT SUM(total_harga) FROM setoran WHERE status = 'valid'");
        $total_tabungan = $stmtTabungan->fetchColumn() ?? 0;

        // 3. Kas Nyata (Total uang dari Pengepul)
        $stmtKas = $this->db->query("SELECT SUM(total_pendapatan) FROM penjualan");
        $kas_masuk = $stmtKas->fetchColumn() ?? 0;

        // 4. Margin Keuntungan Bersih (Selisih harga dari barang yang TERJUAL)
        $stmtMargin = $this->db->query("SELECT 
            IFNULL((SELECT SUM(total_pendapatan) FROM penjualan), 0) - 
            IFNULL((SELECT SUM(total_harga) FROM setoran WHERE is_sold = 1 AND status = 'valid'), 0) as profit");
        $keuntungan_bersih = $stmtMargin->fetchColumn() ?? 0;

        // 5. Statistik Anggota Aktif
        $jml_siswa = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'siswa' AND is_active = 1")->fetchColumn();
        $jml_guru = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'guru' AND is_active = 1")->fetchColumn();

        $title = "Dashboard BST System";
        $content = __DIR__ . '/../../views/admin/dashboard.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}