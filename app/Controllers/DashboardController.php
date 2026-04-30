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
        $role = $_SESSION['role'];
        $user_id = $_SESSION['user_id'];
        $data = [];

        /// =======================================================
        // DATA LEADERBOARD: TOP 5 NASABAH BULAN INI (GLOBAL)
        // =======================================================
        $bulan_ini = date('Y-m');
        $stmtLb = $this->db->prepare("
            SELECT u.id, u.nama, k.nama_kelas, SUM(s.berat) as total_pcs 
            FROM setoran s 
            JOIN users u ON s.user_id = u.id 
            LEFT JOIN kelas k ON u.kelas_id = k.id
            WHERE DATE_FORMAT(s.created_at, '%Y-%m') = :bulan_ini 
            AND s.status = 'valid' AND u.role = 'siswa'
            GROUP BY u.id 
            ORDER BY total_pcs DESC LIMIT 5
        ");
        $stmtLb->execute(['bulan_ini' => $bulan_ini]);
        $data['leaderboard'] = $stmtLb->fetchAll();

        // =======================================================
        // 1. DASHBOARD ADMIN & STAFF (METRIK SEKOLAH)
        // =======================================================
        if ($role === 'admin' || $role === 'staff') {
            $data['stok_gudang'] = $this->db->query("SELECT SUM(berat) FROM setoran WHERE status = 'valid' AND is_sold = 0")->fetchColumn() ?? 0;
            $data['total_tabungan'] = $this->db->query("SELECT SUM(total_harga) FROM setoran WHERE status = 'valid'")->fetchColumn() ?? 0;
            $data['kas_masuk'] = $this->db->query("SELECT SUM(total_pendapatan) FROM penjualan")->fetchColumn() ?? 0;
            $data['keuntungan_bersih'] = $this->db->query("SELECT IFNULL((SELECT SUM(total_pendapatan) FROM penjualan), 0) - IFNULL((SELECT SUM(total_harga) FROM setoran WHERE is_sold = 1 AND status = 'valid'), 0)")->fetchColumn() ?? 0;
            $data['jml_siswa'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'siswa' AND is_active = 1")->fetchColumn() ?? 0;
            $data['jml_guru'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'guru' AND is_active = 1")->fetchColumn() ?? 0;
        } 
        
        // =======================================================
        // 2. DASHBOARD SISWA, GURU & WALI KELAS
        // =======================================================
        else {
            $setoran = $this->db->query("SELECT SUM(total_harga) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn() ?? 0;
            $tarik = $this->db->query("SELECT SUM(jumlah) FROM penarikan WHERE user_id = $user_id")->fetchColumn() ?? 0;
            $data['saldo_pribadi'] = $setoran - $tarik;
            
            $data['total_pcs'] = $this->db->query("SELECT SUM(berat) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn() ?? 0;
            $data['riwayat_pribadi'] = $this->db->query("SELECT s.*, k.nama_sampah FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id WHERE s.user_id = $user_id ORDER BY s.created_at DESC LIMIT 5")->fetchAll();

            $cek_wali = $this->db->query("SELECT * FROM kelas WHERE walikelas_id = $user_id LIMIT 1")->fetch();
            $data['is_walikelas_aktif'] = $cek_wali ? true : false;
            $data['kelas_dikelola'] = $cek_wali;

            $persen_wali = ($this->db->query("SELECT nilai FROM pengaturan WHERE kunci = 'persen_honor_walikelas'")->fetchColumn() ?? 0) / 100;
            $total_jatah = $this->db->query("SELECT SUM(total_pengepul - total_harga) * $persen_wali FROM setoran WHERE walikelas_id = $user_id AND status = 'valid' AND is_sold = 1")->fetchColumn() ?? 0;
            $total_cair = $this->db->query("SELECT SUM(jumlah) FROM pencairan_honor WHERE user_id = $user_id")->fetchColumn() ?? 0;
            
            $data['honor_belum_cair'] = $total_jatah - $total_cair;
            $data['history_honor'] = $this->db->query("SELECT * FROM pencairan_honor WHERE user_id = $user_id ORDER BY tanggal_cair DESC LIMIT 5")->fetchAll();

            $data['ranking_siswa'] = [];
            if($data['is_walikelas_aktif']) {
                $kid = $data['kelas_dikelola']['id'];
                $data['ranking_siswa'] = $this->db->query("
                    SELECT u.nama, SUM(s.berat) as total_pcs 
                    FROM users u 
                    LEFT JOIN setoran s ON u.id = s.user_id AND s.status = 'valid'
                    WHERE u.kelas_id = $kid AND u.role = 'siswa' AND u.is_active = 1
                    GROUP BY u.id ORDER BY total_pcs DESC LIMIT 5
                ")->fetchAll();
            }
        }

        $title = "Dashboard BST System";
        $content = __DIR__ . '/../../views/admin/dashboard.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}