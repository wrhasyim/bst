<?php
// app/Controllers/DashboardController.php
require_once __DIR__ . '/../Core/Database.php';

class DashboardController {
    private $db;

    public function __construct() {
        // 🛡️ Mencegah error jika session sudah berjalan
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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

        $data['tgl_mulai_reward'] = $this->db->query("SELECT nilai FROM pengaturan WHERE kunci = 'tanggal_mulai_reward'")->fetchColumn() ?: date('Y-m-01', strtotime('-2 months'));
        $start_reward_dt = $data['tgl_mulai_reward'] . ' 00:00:00';

        // =======================================================
        // DATA LEADERBOARD: TOP 5 NASABAH 
        // 🛠️ FIX: Pengecualian KESISWAAN, SABTU CERIA, dan KAS KELAS
        // =======================================================
        $stmtLb = $this->db->prepare("
            SELECT u.id, u.nama, k.nama_kelas, SUM(s.berat) as total_pcs 
            FROM setoran s 
            JOIN users u ON s.user_id = u.id 
            LEFT JOIN kelas k ON u.kelas_id = k.id
            WHERE s.created_at >= :tgl_mulai 
            AND s.status = 'valid' 
            AND u.role = 'siswa'
            AND u.is_active = 1 
            AND u.kelas_id IS NOT NULL 
            AND u.nama NOT LIKE '%KESISWAAN%'
            AND u.nama NOT LIKE '%SABTU CERIA%'
            AND u.nama NOT LIKE 'KAS KELAS - %'
            GROUP BY u.id 
            ORDER BY total_pcs DESC LIMIT 5
        ");
        $stmtLb->execute(['tgl_mulai' => $start_reward_dt]);
        $data['leaderboard'] = $stmtLb->fetchAll();

        // =======================================================
        // 1. DASHBOARD ADMIN & STAFF (METRIK SEKOLAH GLOBAL)
        // =======================================================
        if ($role === 'admin' || $role === 'staff') {
            $data['stok_gudang'] = $this->db->query("SELECT SUM(berat) FROM setoran WHERE status = 'valid' AND is_sold = 0")->fetchColumn() ?? 0;
            
            $total_setoran_global = $this->db->query("SELECT SUM(total_harga) FROM setoran WHERE status = 'valid'")->fetchColumn() ?? 0;
            $total_penarikan_global = $this->db->query("SELECT SUM(jumlah) FROM penarikan")->fetchColumn() ?? 0;
            $data['total_tabungan'] = $total_setoran_global - $total_penarikan_global;
            
            $data['kas_masuk'] = $this->db->query("SELECT SUM(total_pendapatan) FROM penjualan")->fetchColumn() ?? 0;
            
            $data['keuntungan_bersih'] = $this->db->query("
                SELECT IFNULL((SELECT SUM(total_pendapatan) FROM penjualan), 0) - 
                IFNULL((SELECT SUM(total_harga) FROM setoran WHERE is_sold = 1 AND status = 'valid' AND kategori_id NOT IN (SELECT id FROM kategori_sampah WHERE nama_sampah = '🌟 REWARD PRESTASI')), 0)
            ")->fetchColumn() ?? 0;
            
            // 🛠️ FIX: Mengecualikan Akun Kesiswaan, Sabtu Ceria, dan Kas Kelas dari hitungan Total Siswa
            $data['jml_siswa'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'siswa' AND is_active = 1 AND nama NOT LIKE '%KESISWAAN%' AND nama NOT LIKE '%SABTU CERIA%' AND nama NOT LIKE 'KAS KELAS - %'")->fetchColumn() ?? 0;
            $data['jml_guru'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'guru' AND is_active = 1")->fetchColumn() ?? 0;

            $chart_labels = []; 
            $chart_setoran = []; 
            $chart_penarikan = [];

            for ($i = 5; $i >= 0; $i--) {
                $bulan_sql = date('Y-m', strtotime("-$i months"));
                $chart_labels[] = date('M Y', strtotime("-$i months"));

                $stmtMasuk = $this->db->prepare("SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE status = 'valid' AND DATE_FORMAT(created_at, '%Y-%m') = ?");
                $stmtMasuk->execute([$bulan_sql]);
                $chart_setoran[] = (float) $stmtMasuk->fetchColumn();

                $stmtKeluar = $this->db->prepare("SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE DATE_FORMAT(tanggal_tarik, '%Y-%m') = ?");
                $stmtKeluar->execute([$bulan_sql]);
                $chart_penarikan[] = (float) $stmtKeluar->fetchColumn();
            }

            $data['json_labels'] = json_encode($chart_labels);
            $data['json_setoran'] = json_encode($chart_setoran);
            $data['json_penarikan'] = json_encode($chart_penarikan);
        } 
        
        // =======================================================
        // 2. DASHBOARD SISWA, GURU & WALI KELAS (METRIK PERSONAL)
        // =======================================================
        else {
            $stmtSetoran = $this->db->prepare("SELECT SUM(total_harga) FROM setoran WHERE user_id = ? AND status = 'valid'");
            $stmtSetoran->execute([$user_id]);
            $setoran = $stmtSetoran->fetchColumn() ?? 0;

            $stmtTarik = $this->db->prepare("SELECT SUM(jumlah) FROM penarikan WHERE user_id = ?");
            $stmtTarik->execute([$user_id]);
            $tarik = $stmtTarik->fetchColumn() ?? 0;
            $data['saldo_pribadi'] = $setoran - $tarik;
            
            $stmtPcs = $this->db->prepare("SELECT SUM(berat) FROM setoran WHERE user_id = ? AND status = 'valid'");
            $stmtPcs->execute([$user_id]);
            $data['total_pcs'] = $stmtPcs->fetchColumn() ?? 0;

            $stmtRiwayat = $this->db->prepare("SELECT s.*, k.nama_sampah FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id WHERE s.user_id = ? ORDER BY s.created_at DESC LIMIT 5");
            $stmtRiwayat->execute([$user_id]);
            $data['riwayat_pribadi'] = $stmtRiwayat->fetchAll();

            $stmtWali = $this->db->prepare("SELECT * FROM kelas WHERE walikelas_id = ? LIMIT 1");
            $stmtWali->execute([$user_id]);
            $cek_wali = $stmtWali->fetch();
            $data['is_walikelas_aktif'] = $cek_wali ? true : false;
            $data['kelas_dikelola'] = $cek_wali;
            $data['saldo_kas_kelas_walas'] = 0; // Default

            $persen_wali = ($this->db->query("SELECT nilai FROM pengaturan WHERE kunci = 'persen_honor_walikelas'")->fetchColumn() ?? 0) / 100;
            $stmtJatah = $this->db->prepare("
                SELECT SUM(total_pengepul - total_harga) * $persen_wali 
                FROM setoran 
                WHERE walikelas_id = ? AND status = 'valid' AND is_sold = 1
                AND kategori_id NOT IN (SELECT id FROM kategori_sampah WHERE nama_sampah = '🌟 REWARD PRESTASI')
            ");
            $stmtJatah->execute([$user_id]);
            $total_jatah = $stmtJatah->fetchColumn() ?? 0;
            
            $stmtCair = $this->db->prepare("SELECT SUM(jumlah) FROM pencairan_honor WHERE user_id = ?");
            $stmtCair->execute([$user_id]);
            $total_cair = $stmtCair->fetchColumn() ?? 0;
            
            $data['honor_belum_cair'] = $total_jatah - $total_cair;

            $stmtHistory = $this->db->prepare("SELECT * FROM pencairan_honor WHERE user_id = ? ORDER BY tanggal_cair DESC LIMIT 5");
            $stmtHistory->execute([$user_id]);
            $data['history_honor'] = $stmtHistory->fetchAll();

            $data['ranking_siswa'] = [];
            if($data['is_walikelas_aktif']) {
                $kid = $data['kelas_dikelola']['id'];
                // 🛠️ FIX: Mengecualikan Akun Virtual dari Ranking Internal Kelas Wali
                $stmtRank = $this->db->prepare("
                    SELECT u.nama, SUM(s.berat) as total_pcs 
                    FROM users u 
                    LEFT JOIN setoran s ON u.id = s.user_id AND s.status = 'valid' AND s.created_at >= :tgl_mulai
                    WHERE u.kelas_id = :kid AND u.role = 'siswa' AND u.is_active = 1 
                    AND u.nama NOT LIKE '%KESISWAAN%' AND u.nama NOT LIKE '%SABTU CERIA%' AND u.nama NOT LIKE 'KAS KELAS - %'
                    GROUP BY u.id ORDER BY total_pcs DESC LIMIT 5
                ");
                $stmtRank->execute(['tgl_mulai' => $start_reward_dt, 'kid' => $kid]);
                $data['ranking_siswa'] = $stmtRank->fetchAll();

                // 🌟 FIX POIN 12: Menghitung Total Saldo Kas Kelas (Sabtu Ceria) khusus untuk kelas ini
                $nama_akun_kelas = "KAS KELAS - " . strtoupper($data['kelas_dikelola']['nama_kelas']);
                $stmtCariKas = $this->db->prepare("SELECT id FROM users WHERE nama = ? AND role = 'siswa' LIMIT 1");
                $stmtCariKas->execute([$nama_akun_kelas]);
                $akun_kas = $stmtCariKas->fetch();

                if ($akun_kas) {
                    $uid_kas = $akun_kas['id'];
                    $masuk_kas = $this->db->query("SELECT IFNULL(SUM(total_harga),0) FROM setoran WHERE user_id = $uid_kas AND status = 'valid'")->fetchColumn();
                    $keluar_kas = $this->db->query("SELECT IFNULL(SUM(jumlah),0) FROM penarikan WHERE user_id = $uid_kas")->fetchColumn();
                    $data['saldo_kas_kelas_walas'] = $masuk_kas - $keluar_kas;
                }
            }
        }

        $title = "Dashboard BST System";
        
        extract($data); 
        $content = __DIR__ . '/../../views/admin/dashboard.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}
?>