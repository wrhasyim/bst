<?php
// app/Controllers/LaporanController.php
require_once __DIR__ . '/../Core/Database.php';

class LaporanController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // =================================================================
    // 1. LAPORAN KEUANGAN GLOBAL
    // =================================================================
    public function keuangan() {
        // Ambil Konfigurasi Persentase
        $stmtConfig = $this->db->query("SELECT kunci, nilai FROM pengaturan WHERE kunci LIKE 'persen_%'");
        $config = $stmtConfig->fetchAll(PDO::FETCH_KEY_PAIR);

        // Ambil Data Finansial dari Penjualan yang VALID dan SUDAH TERJUAL
        $sqlMargin = "SELECT 
                        SUM(total_pengepul) as kotor, 
                        SUM(total_harga) as beban_nasabah,
                        SUM(total_pengepul - total_harga) as margin_total
                      FROM setoran 
                      WHERE status = 'valid' AND is_sold = 1";
        $data = $this->db->query($sqlMargin)->fetch();

        $margin_total = $data['margin_total'] ?? 0;

        // Bangun Array Laporan (Kunci-kunci ini harus ada untuk View)
        $laporan = [
            'total_kotor'     => $data['kotor'] ?? 0,
            'beban_nasabah'   => $data['beban_nasabah'] ?? 0,
            'margin_total'    => $margin_total,
            'kas_bst'         => (($config['persen_kas_bst'] ?? 0) / 100) * $margin_total,
            'kas_sekolah'     => (($config['persen_kas_sekolah'] ?? 0) / 100) * $margin_total,
            'honor_pengelola' => (($config['persen_honor_pengelola'] ?? 0) / 100) * $margin_total,
            'honor_walikelas' => (($config['persen_honor_walikelas'] ?? 0) / 100) * $margin_total
        ];

        // Histori Penjualan Terbaru
        $history = $this->db->query("SELECT p.*, k.nama_sampah 
                                    FROM penjualan p 
                                    JOIN kategori_sampah k ON p.kategori_id = k.id 
                                    ORDER BY p.tanggal_jual DESC LIMIT 10")->fetchAll();

        $title = "Laporan Keuangan Global";
        $content = __DIR__ . '/../../views/admin/laporan/keuangan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. LAPORAN HONOR & INSENTIF
    // =================================================================
    public function honor() {
        $stmtConfig = $this->db->query("SELECT kunci, nilai FROM pengaturan WHERE kunci LIKE 'persen_%'");
        $config = $stmtConfig->fetchAll(PDO::FETCH_KEY_PAIR);
        $persen_wali = ($config['persen_honor_walikelas'] ?? 0) / 100;

        // Hitung Margin
        $total_margin_potensi = $this->db->query("SELECT SUM(total_pengepul - total_harga) FROM setoran WHERE status = 'valid'")->fetchColumn() ?? 0;
        $total_margin_realisasi = $this->db->query("SELECT SUM(total_pengepul - total_harga) FROM setoran WHERE status = 'valid' AND is_sold = 1")->fetchColumn() ?? 0;

        // Rekap Honor Per Wali Kelas
        $qRekap = "SELECT 
                        u.nama AS nama_guru, k.nama_kelas, 
                        SUM(CASE WHEN s.is_sold = 0 THEN (s.total_pengepul - s.total_harga) * :p1 ELSE 0 END) as total_potensi,
                        SUM(CASE WHEN s.is_sold = 1 THEN (s.total_pengepul - s.total_harga) * :p2 ELSE 0 END) as total_realisasi
                    FROM setoran s
                    JOIN users u ON s.walikelas_id = u.id
                    JOIN kelas k ON u.id = k.walikelas_id
                    WHERE s.status = 'valid'
                    GROUP BY u.id";
        $stmt = $this->db->prepare($qRekap);
        $stmt->execute(['p1' => $persen_wali, 'p2' => $persen_wali]);
        $rekap_honor = $stmt->fetchAll();

        $title = "Laporan Honor & Insentif";
        $content = __DIR__ . '/../../views/admin/laporan/honor.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 3. REKAP SETORAN PER KELAS
    // =================================================================
    public function setoran() {
        $kelas_id = $_GET['kelas_id'] ?? null;
        $kelas_list = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $data_rekap = [];
        $nama_kelas_aktif = "";

        if ($kelas_id) {
            $stmtKelas = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE id = :id");
            $stmtKelas->execute(['id' => $kelas_id]);
            $nama_kelas_aktif = $stmtKelas->fetchColumn();

            $sql = "SELECT u.nama, IFNULL(SUM(s.berat), 0) as total_pcs, IFNULL(SUM(s.total_harga), 0) as total_rp
                    FROM users u
                    LEFT JOIN setoran s ON u.id = s.user_id AND s.status = 'valid'
                    WHERE u.kelas_id = :kid AND u.role = 'siswa'
                    GROUP BY u.id, u.nama ORDER BY u.nama ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['kid' => $kelas_id]);
            $data_rekap = $stmt->fetchAll();
        }

        $title = "Rekap Tabungan Per Kelas";
        $content = __DIR__ . '/../../views/admin/laporan/setoran.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 4. BUKU TABUNGAN PER NASABAH (FITUR BARU - LENGKAP)
    // =================================================================
    public function nasabah() {
        $user_id = $_GET['user_id'] ?? null;
        // Ambil daftar siswa untuk dropdown filter
        $siswa_list = $this->db->query("SELECT u.id, u.nama, k.nama_kelas FROM users u LEFT JOIN kelas k ON u.kelas_id = k.id WHERE u.role = 'siswa' ORDER BY u.nama ASC")->fetchAll();
        
        $detail_siswa = null;
        $riwayat = [];
        $total_saldo = 0;

        if ($user_id) {
            // Ambil Detail Profil Siswa
            $stmtUser = $this->db->prepare("SELECT u.*, k.nama_kelas FROM users u LEFT JOIN kelas k ON u.kelas_id = k.id WHERE u.id = ?");
            $stmtUser->execute([$user_id]);
            $detail_siswa = $stmtUser->fetch();

            // Ambil Riwayat Setoran yang sudah VALID (Kredit)
            $stmtRiwayat = $this->db->prepare("SELECT s.*, kat.nama_sampah 
                                              FROM setoran s 
                                              JOIN kategori_sampah kat ON s.kategori_id = kat.id 
                                              WHERE s.user_id = ? AND s.status = 'valid' 
                                              ORDER BY s.created_at DESC");
            $stmtRiwayat->execute([$user_id]);
            $riwayat = $stmtRiwayat->fetchAll();

            // Hitung Total Saldo (Hanya dari setoran valid)
            $stmtSaldo = $this->db->prepare("SELECT SUM(total_harga) FROM setoran WHERE user_id = ? AND status = 'valid'");
            $stmtSaldo->execute([$user_id]);
            $total_saldo = $stmtSaldo->fetchColumn() ?? 0;
        }

        $title = "Buku Tabungan Nasabah";
        $content = __DIR__ . '/../../views/admin/laporan/nasabah.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}