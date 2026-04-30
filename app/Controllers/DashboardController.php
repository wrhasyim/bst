<?php
// app/Controllers/DashboardController.php
require_once __DIR__ . '/../Core/Database.php';

class DashboardController {
    private $db;

    public function __construct() {
        // Proteksi route: Hanya user yang sudah login yang bisa mengakses Dashboard
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
        // DATA LEADERBOARD: TOP 5 NASABAH (3 BULAN TERAKHIR / TRIWULAN)
        // =======================================================
        // Menggunakan DATE_SUB INTERVAL 2 MONTH untuk menarik data dari tanggal 1 dua bulan lalu hingga hari ini.
        // Ini memastikan kompetisi berjalan adil selama 1 kuartal (3 bulan).
        $stmtLb = $this->db->prepare("
            SELECT u.id, u.nama, k.nama_kelas, SUM(s.berat) as total_pcs 
            FROM setoran s 
            JOIN users u ON s.user_id = u.id 
            LEFT JOIN kelas k ON u.kelas_id = k.id
            WHERE s.created_at >= DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 2 MONTH), '%Y-%m-01')
            AND s.status = 'valid' AND u.role = 'siswa'
            GROUP BY u.id 
            ORDER BY total_pcs DESC LIMIT 5
        ");
        $stmtLb->execute();
        $data['leaderboard'] = $stmtLb->fetchAll();

        // =======================================================
        // 1. DASHBOARD ADMIN & STAFF (METRIK SEKOLAH GLOBAL)
        // =======================================================
        if ($role === 'admin' || $role === 'staff') {
            // Sisa Pcs botol yang masih ada di gudang dan belum dijual
            $data['stok_gudang'] = $this->db->query("SELECT SUM(berat) FROM setoran WHERE status = 'valid' AND is_sold = 0")->fetchColumn() ?? 0;
            
            // Total Rupiah yang ada di dalam tabungan seluruh siswa
            $data['total_tabungan'] = $this->db->query("SELECT SUM(total_harga) FROM setoran WHERE status = 'valid'")->fetchColumn() ?? 0;
            
            // Total Kas Riil (Hanya dari uang pengepul)
            $data['kas_masuk'] = $this->db->query("SELECT SUM(total_pendapatan) FROM penjualan")->fetchColumn() ?? 0;
            
            // Keuntungan Bersih = Total Pendapatan Pengepul DIKURANGI Kewajiban HPP ke Siswa.
            // PENTING: Mengecualikan Kategori '🌟 REWARD PRESTASI' agar laba sekolah tidak terdistorsi oleh uang hadiah/promosi.
            $data['keuntungan_bersih'] = $this->db->query("
                SELECT IFNULL((SELECT SUM(total_pendapatan) FROM penjualan), 0) - 
                IFNULL((SELECT SUM(total_harga) FROM setoran WHERE is_sold = 1 AND status = 'valid' AND kategori_id NOT IN (SELECT id FROM kategori_sampah WHERE nama_sampah = '🌟 REWARD PRESTASI')), 0)
            ")->fetchColumn() ?? 0;
            
            // Metrik Anggota Aktif
            $data['jml_siswa'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'siswa' AND is_active = 1")->fetchColumn() ?? 0;
            $data['jml_guru'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'guru' AND is_active = 1")->fetchColumn() ?? 0;
        } 
        
        // =======================================================
        // 2. DASHBOARD SISWA, GURU & WALI KELAS (METRIK PERSONAL)
        // =======================================================
        else {
            // Hitung Saldo Pribadi (Total Setor - Total Tarik)
            $setoran = $this->db->query("SELECT SUM(total_harga) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn() ?? 0;
            $tarik = $this->db->query("SELECT SUM(jumlah) FROM penarikan WHERE user_id = $user_id")->fetchColumn() ?? 0;
            $data['saldo_pribadi'] = $setoran - $tarik;
            
            // Total Pcs Keseluruhan milik pengguna ini
            $data['total_pcs'] = $this->db->query("SELECT SUM(berat) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn() ?? 0;
            
            // Riwayat transaksi terbaru (Limit 5)
            $data['riwayat_pribadi'] = $this->db->query("SELECT s.*, k.nama_sampah FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id WHERE s.user_id = $user_id ORDER BY s.created_at DESC LIMIT 5")->fetchAll();

            // Cek apakah User ini menjabat sebagai Wali Kelas
            $cek_wali = $this->db->query("SELECT * FROM kelas WHERE walikelas_id = $user_id LIMIT 1")->fetch();
            $data['is_walikelas_aktif'] = $cek_wali ? true : false;
            $data['kelas_dikelola'] = $cek_wali;

            // Jika punya jabatan (Wali Kelas / Guru), hitung Jatah Honor
            // PENTING: Menghitung honor dari selisih margin, namun mengecualikan setoran fiktif '🌟 REWARD PRESTASI'
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

            // Ranking Internal khusus untuk kelas yang dikelola oleh Wali Kelas
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

        // Render Tampilan
        $title = "Dashboard BST System";
        $content = __DIR__ . '/../../views/admin/dashboard.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}