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
        // 2. DASHBOARD SISWA & GURU & WALI KELAS (PERSONAL)
        // =======================================================
        else {
            // A. DATA DASAR TABUNGAN PRIBADI
            $setoran = $this->db->query("SELECT SUM(total_harga) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn() ?? 0;
            $tarik = $this->db->query("SELECT SUM(jumlah) FROM penarikan WHERE user_id = $user_id")->fetchColumn() ?? 0;
            $data['saldo_pribadi'] = $setoran - $tarik;
            
            $data['total_pcs'] = $this->db->query("SELECT SUM(berat) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn() ?? 0;
            $data['riwayat_pribadi'] = $this->db->query("SELECT s.*, k.nama_sampah FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id WHERE s.user_id = $user_id ORDER BY s.created_at DESC LIMIT 5")->fetchAll();

            // B. CEK JABATAN WALI KELAS AKTIF
            $cek_wali = $this->db->query("SELECT * FROM kelas WHERE walikelas_id = $user_id LIMIT 1")->fetch();
            $data['is_walikelas_aktif'] = $cek_wali ? true : false;
            $data['kelas_dikelola'] = $cek_wali;

            // C. LOGIKA HONOR (UNTUK MANTAN & WALI KELAS AKTIF)
            // 1. Ambil persentase honor dari database (contoh: 15% berarti 0.15)
            $persen_wali = ($this->db->query("SELECT nilai FROM pengaturan WHERE kunci = 'persen_honor_walikelas'")->fetchColumn() ?? 0) / 100;
            
            // 2. Hitung Total Jatah (Dari barang yg valid, terjual, dan ID walikelas-nya cocok)
            $total_jatah = $this->db->query("SELECT SUM(total_pengepul - total_harga) * $persen_wali FROM setoran WHERE walikelas_id = $user_id AND status = 'valid' AND is_sold = 1")->fetchColumn() ?? 0;
            
            // 3. Hitung Total Honor yg Sudah Diambil dari tabel pencairan_honor
            $total_cair = $this->db->query("SELECT SUM(jumlah) FROM pencairan_honor WHERE user_id = $user_id")->fetchColumn() ?? 0;
            
            // 4. Sisa Belum Cair (Jatah dikurangi Cair)
            $data['honor_belum_cair'] = $total_jatah - $total_cair;
            
            // 5. Ambil 5 Riwayat Pencairan Terakhir
            $data['history_honor'] = $this->db->query("SELECT * FROM pencairan_honor WHERE user_id = $user_id ORDER BY tanggal_cair DESC LIMIT 5")->fetchAll();

            // D. RANKING SISWA (Hanya muncul jika sedang menjabat Wali Kelas)
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