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
    // 4. BUKU TABUNGAN PER NASABAH (INDIVIDUAL MUTATION)
    // =================================================================
    public function nasabah() {
        $user_id = $_GET['user_id'] ?? null;
        // Ambil daftar siswa untuk dropdown filter
        $siswa_list = $this->db->query("SELECT u.id, u.nama, k.nama_kelas FROM users u LEFT JOIN kelas k ON u.kelas_id = k.id WHERE u.role = 'siswa' ORDER BY u.nama ASC")->fetchAll();
        
        $detail_siswa = null;
        $mutasi = [];
        $total_saldo = 0;

        if ($user_id) {
            // Ambil Detail Profil Siswa
            $stmtUser = $this->db->prepare("SELECT u.*, k.nama_kelas FROM users u LEFT JOIN kelas k ON u.kelas_id = k.id WHERE u.id = ?");
            $stmtUser->execute([$user_id]);
            $detail_siswa = $stmtUser->fetch();

            /** * QUERY MUTASI (UNION)
             * PERBAIKAN: Menggunakan alias parameter :uid1 dan :uid2 
             * untuk mencegah error "Invalid parameter number" pada mode strict PDO
             */
            $sqlMutasi = "SELECT created_at as tanggal, 'setoran' as tipe, nama_sampah as ket, berat as qty, total_harga as jumlah
                          FROM setoran s 
                          JOIN kategori_sampah k ON s.kategori_id = k.id 
                          WHERE s.user_id = :uid1 AND s.status = 'valid'
                          UNION ALL
                          SELECT tanggal_tarik as tanggal, 'penarikan' as tipe, keterangan as ket, 0 as qty, jumlah
                          FROM penarikan 
                          WHERE user_id = :uid2
                          ORDER BY tanggal DESC";
            
            $stmtMutasi = $this->db->prepare($sqlMutasi);
            // Binding parameter secara terpisah
            $stmtMutasi->execute([
                'uid1' => $user_id, 
                'uid2' => $user_id
            ]);
            $mutasi = $stmtMutasi->fetchAll();

            // Hitung Saldo Bersih (Kredit - Debit)
            $stmtSetoran = $this->db->prepare("SELECT SUM(total_harga) FROM setoran WHERE user_id = ? AND status = 'valid'");
            $stmtSetoran->execute([$user_id]);
            $total_setoran = $stmtSetoran->fetchColumn() ?? 0;

            $stmtTarik = $this->db->prepare("SELECT SUM(jumlah) FROM penarikan WHERE user_id = ?");
            $stmtTarik->execute([$user_id]);
            $total_tarik = $stmtTarik->fetchColumn() ?? 0;

            $total_saldo = $total_setoran - $total_tarik;
        }

        $title = "Buku Tabungan Nasabah";
        $content = __DIR__ . '/../../views/admin/laporan/nasabah.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
    // =================================================================
    // 5. BUKU KAS UMUM (ARUS KAS RIL / FISIK) - FITUR BARU
    // =================================================================
    public function buku_kas() {
        // Filter Tutup Buku (Bulan & Tahun)
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        
        $periode = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        // QUERY SAKTI: Menyatukan 3 Tabel Transaksi (Uang Masuk & Uang Keluar)
        // 1. Penjualan Pengepul (KAS MASUK / DEBIT)
        // 2. Penarikan Nasabah (KAS KELUAR / KREDIT)
        // 3. Pencairan Honor (KAS KELUAR / KREDIT)
        $sql = "
            SELECT 
                tanggal_jual AS waktu, 
                'Penjualan Pengepul' AS uraian, 
                keterangan AS detail,
                total_pendapatan AS debit, 
                0 AS kredit,
                'masuk' as jenis
            FROM penjualan
            WHERE DATE_FORMAT(tanggal_jual, '%Y-%m') = :periode1

            UNION ALL

            SELECT 
                p.tanggal_tarik AS waktu, 
                CONCAT('Penarikan Tunai: ', u.nama) AS uraian, 
                p.keterangan AS detail,
                0 AS debit, 
                p.jumlah AS kredit,
                'keluar_nasabah' as jenis
            FROM penarikan p
            JOIN users u ON p.user_id = u.id
            WHERE DATE_FORMAT(p.tanggal_tarik, '%Y-%m') = :periode2

            UNION ALL

            SELECT 
                h.tanggal_cair AS waktu, 
                CONCAT('Pencairan Honor: ', u.nama) AS uraian, 
                h.keterangan AS detail,
                0 AS debit, 
                h.jumlah AS kredit,
                'keluar_honor' as jenis
            FROM pencairan_honor h
            JOIN users u ON h.user_id = u.id
            WHERE DATE_FORMAT(h.tanggal_cair, '%Y-%m') = :periode3

            ORDER BY waktu ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'periode1' => $periode,
            'periode2' => $periode,
            'periode3' => $periode
        ]);
        $buku_kas = $stmt->fetchAll();

        // Hitung Saldo Bulan Lalu (Untuk Saldo Awal Bulan Ini)
        // Ini memastikan uang dari bulan lalu tidak hilang dari hitungan laci
        $sqlSaldoAwal = "
            SELECT 
                (SELECT IFNULL(SUM(total_pendapatan), 0) FROM penjualan WHERE DATE_FORMAT(tanggal_jual, '%Y-%m') < :p1) -
                (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE DATE_FORMAT(tanggal_tarik, '%Y-%m') < :p2) -
                (SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor WHERE DATE_FORMAT(tanggal_cair, '%Y-%m') < :p3) 
            AS saldo_awal
        ";
        $stmtAwal = $this->db->prepare($sqlSaldoAwal);
        $stmtAwal->execute(['p1' => $periode, 'p2' => $periode, 'p3' => $periode]);
        $saldo_awal = $stmtAwal->fetchColumn() ?? 0;

        $title = "Buku Kas Umum (Kas Ril)";
        $content = __DIR__ . '/../../views/admin/laporan/buku_kas.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}