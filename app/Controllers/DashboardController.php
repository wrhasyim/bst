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

        // =======================================================
        // DATA LEADERBOARD: TOP 5 NASABAH (3 BULAN TERAKHIR)
        // =======================================================
        $stmtLb = $this->db->prepare("
            SELECT u.id, u.nama, k.nama_kelas, SUM(s.berat) as total_pcs 
            FROM setoran s 
            JOIN users u ON s.user_id = u.id 
            LEFT JOIN kelas k ON u.kelas_id = k.id
            WHERE s.created_at >= DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 2 MONTH), '%Y-%m-01')
            AND s.status = 'valid' 
            AND u.role = 'siswa'
            AND u.is_active = 1 -- FIX: Pastikan siswa masih aktif (bukan alumni)
            AND u.kelas_id IS NOT NULL -- FIX: Pastikan siswa memiliki kelas
            AND u.nama NOT LIKE '%KESISWAAN%'
            GROUP BY u.id 
            ORDER BY total_pcs DESC LIMIT 5
        ");
        $stmtLb->execute();
        $data['leaderboard'] = $stmtLb->fetchAll();

        // =======================================================
        // 1. DASHBOARD ADMIN & STAFF (METRIK SEKOLAH GLOBAL)
        // =======================================================
        if ($role === 'admin' || $role === 'staff') {
            $data['stok_gudang'] = $this->db->query("SELECT SUM(berat) FROM setoran WHERE status = 'valid' AND is_sold = 0")->fetchColumn() ?? 0;
            $data['total_tabungan'] = $this->db->query("SELECT SUM(total_harga) FROM setoran WHERE status = 'valid'")->fetchColumn() ?? 0;
            $data['kas_masuk'] = $this->db->query("SELECT SUM(total_pendapatan) FROM penjualan")->fetchColumn() ?? 0;
            
            $data['keuntungan_bersih'] = $this->db->query("
                SELECT IFNULL((SELECT SUM(total_pendapatan) FROM penjualan), 0) - 
                IFNULL((SELECT SUM(total_harga) FROM setoran WHERE is_sold = 1 AND status = 'valid' AND kategori_id NOT IN (SELECT id FROM kategori_sampah WHERE nama_sampah = '🌟 REWARD PRESTASI')), 0)
            ")->fetchColumn() ?? 0;
            
            $data['jml_siswa'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'siswa' AND is_active = 1")->fetchColumn() ?? 0;
            $data['jml_guru'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'guru' AND is_active = 1")->fetchColumn() ?? 0;

            // FITUR CHART.JS DATA
            $chart_labels = []; $chart_setoran = []; $chart_penjualan = [];
            for ($i = 5; $i >= 0; $i--) {
                $m = date('Y-m', strtotime("-$i months"));
                $chart_labels[] = date('M Y', strtotime("-$i months"));
                $chart_setoran[$m] = 0; $chart_penjualan[$m] = 0;
            }

            $start_date = date('Y-m-01', strtotime('-5 months'));

            $stmtSetoran = $this->db->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as bln, SUM(berat) as total FROM setoran WHERE status = 'valid' AND created_at >= ? GROUP BY bln");
            $stmtSetoran->execute([$start_date]);
            foreach ($stmtSetoran->fetchAll() as $row) { if (isset($chart_setoran[$row['bln']])) $chart_setoran[$row['bln']] = (float)$row['total']; }

            $stmtPenjualan = $this->db->prepare("SELECT DATE_FORMAT(tanggal_jual, '%Y-%m') as bln, SUM(total_pendapatan) as total FROM penjualan WHERE tanggal_jual >= ? GROUP BY bln");
            $stmtPenjualan->execute([$start_date]);
            foreach ($stmtPenjualan->fetchAll() as $row) { if (isset($chart_penjualan[$row['bln']])) $chart_penjualan[$row['bln']] = (float)$row['total']; }

            $data['chart'] = [
                'labels' => json_encode(array_values($chart_labels)),
                'setoran' => json_encode(array_values($chart_setoran)),
                'penjualan' => json_encode(array_values($chart_penjualan))
            ];
        } 
        
        // =======================================================
        // 2. DASHBOARD SISWA, GURU & WALI KELAS (METRIK PERSONAL)
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
            $total_jatah = $this->db->query("
                SELECT SUM(total_pengepul - total_harga) * $persen_wali 
                FROM setoran 
                WHERE walikelas_id = $user_id AND status = 'valid' AND is_sold = 1 
                AND kategori_id NOT IN (SELECT id FROM kategori_sampah WHERE nama_sampah = '🌟 REWARD PRESTASI')
            ")->fetchColumn() ?? 0;
            
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