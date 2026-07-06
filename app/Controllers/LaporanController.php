<?php
// app/Controllers/LaporanController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class LaporanController {
    private $db;

    public function __construct() {
        // 🛡️ Satpam URL: Laporan hanya dapat diakses oleh Admin dan Staff
        Security::requireRole(['admin', 'staff']);
        $this->db = Database::getInstance()->getConnection();
    }

    // =================================================================
    // 1. LAPORAN KEUANGAN GLOBAL (IMMUTABLE SNAPSHOT + DATE FILTER)
    // =================================================================
    public function keuangan() {
        $data = []; // Wadah untuk data View

        // Ambil data persentase saat ini hanya untuk ditampilkan di label UI %
        $stmtConfig = $this->db->query("SELECT kunci, nilai FROM pengaturan WHERE kunci LIKE 'persen_%'");
        $config = $stmtConfig->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // 🚀 Tangkap Filter Tanggal dari Request (GET)
        $start_date = $_GET['start_date'] ?? '';
        $end_date = $_GET['end_date'] ?? '';
        
        // 🛡️ Siapkan Parameter dan Kondisi WHERE Dinamis
        $params = [];
        $wherePenjualan = "";
        $whereSetoran = "s.status = 'valid' AND k.nama_sampah != '🌟 REWARD PRESTASI'";
        $whereReward = "k.nama_sampah = '🌟 REWARD PRESTASI'";
        
        $whereCairP = "keterangan LIKE '%Pengelola%'";
        $whereCairW = "jenis = 'walikelas'";
        $whereCairK = "keterangan LIKE '%Piket%'";
        
        $whereKasOut = "jenis = 'pengeluaran' AND keterangan LIKE '%Sumbangan Kas Sekolah%'";
        $whereKasInP = "jenis = 'pemasukan' AND keterangan LIKE '%Refund Honor Pengelola%'";
        $whereKasInW = "jenis = 'pemasukan' AND keterangan LIKE '%Refund Honor Wali Kelas%'";
        $whereKasInS = "jenis = 'pemasukan' AND keterangan LIKE '%Refund Kas Sekolah%'";
        $whereKasInK = "jenis = 'pemasukan' AND keterangan LIKE '%Refund Honor Piket%'";
        
        // ✨ Filter dinamis khusus Tutup Botol Keluar
        $whereKasTbOut = "jenis = 'pengeluaran' AND sumber_kas = 'kas_tutup_botol'";

        if (!empty($start_date) && !empty($end_date)) {
            $sd = $start_date . ' 00:00:00';
            $ed = $end_date . ' 23:59:59';
            $params = ['start' => $sd, 'end' => $ed];
            
            $wherePenjualan = "WHERE tanggal_jual BETWEEN :start AND :end";
            $whereSetoran .= " AND s.created_at BETWEEN :start AND :end";
            $whereReward .= " AND s.created_at BETWEEN :start AND :end";
            
            $whereCairP .= " AND tanggal_cair BETWEEN :start AND :end";
            $whereCairW .= " AND ph.tanggal_cair BETWEEN :start AND :end";
            $whereCairK .= " AND tanggal_cair BETWEEN :start AND :end";
            
            $whereKasOut .= " AND tanggal BETWEEN :start AND :end";
            
            $whereKasInP .= " AND tanggal BETWEEN :start AND :end";
            $whereKasInW .= " AND tanggal BETWEEN :start AND :end";
            $whereKasInS .= " AND tanggal BETWEEN :start AND :end";
            $whereKasInK .= " AND tanggal BETWEEN :start AND :end";
            
            $whereKasTbOut .= " AND tanggal BETWEEN :start AND :end"; // Filter tgl tutup botol
        }
        
        // ✨ Tambahkan SUM(kas_tutup_botol_rp) di pencarian Penjualan
        $sqlSnapshot = "SELECT 
                            SUM(total_pendapatan) as total_kotor,
                            SUM(beban_nasabah_rp) as beban_nasabah,
                            SUM(margin_total_rp) as margin_total,
                            SUM(kas_sekolah_rp) as kas_sekolah,
                            SUM(honor_pengelola_rp) as honor_pengelola,
                            SUM(honor_piket_rp) as honor_piket,
                            SUM(kas_bst_rp) as kas_bst,
                            SUM(kas_tutup_botol_rp) as total_tutup_botol_in
                        FROM penjualan $wherePenjualan";
        
        $stmtSnap = $this->db->prepare($sqlSnapshot);
        $stmtSnap->execute($params);
        $data_snap = $stmtSnap->fetch();

        $total_kotor     = (float)($data_snap['total_kotor'] ?? 0);
        $beban_nasabah   = (float)($data_snap['beban_nasabah'] ?? 0);
        $margin_total    = (float)($data_snap['margin_total'] ?? 0);
        $kas_sekolah     = (float)($data_snap['kas_sekolah'] ?? 0);
        $honor_pengelola = (float)($data_snap['honor_pengelola'] ?? 0);
        $honor_piket     = (float)($data_snap['honor_piket'] ?? 0);
        $kas_bst         = (float)($data_snap['kas_bst'] ?? 0);
        $tutup_botol_in  = (float)($data_snap['total_tutup_botol_in'] ?? 0);

        // 2. DATA HONOR WALAS (Dari Setoran)
        $sql_honor_wali = "SELECT SUM(s.honor_walas_rp) FROM setoran s JOIN users u ON s.walikelas_id = u.id JOIN kategori_sampah k ON s.kategori_id = k.id WHERE s.is_sold = 1 AND $whereSetoran";
        $stmtWalas = $this->db->prepare($sql_honor_wali);
        $stmtWalas->execute($params);
        $honor_walikelas = $stmtWalas->fetchColumn() ?? 0;

        // 3. BEBAN REWARD PRESTASI
        $sqlBebanReward = "SELECT SUM(s.total_harga) FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id WHERE $whereReward";
        $stmtReward = $this->db->prepare($sqlBebanReward);
        $stmtReward->execute($params);
        $beban_reward = $stmtReward->fetchColumn() ?? 0;

        // =================================================================
        // 🛠️ TRACING STATUS PEMBAYARAN (DENGAN FILTER)
        // =================================================================

        $stmt = $this->db->prepare("SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor WHERE $whereCairP"); $stmt->execute($params); $cair_pengelola_out = $stmt->fetchColumn();
        $stmt = $this->db->prepare("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE $whereKasInP"); $stmt->execute($params); $refund_pengelola = $stmt->fetchColumn();
        $cair_pengelola = (float)$cair_pengelola_out - (float)$refund_pengelola;
        $sisa_pengelola = $honor_pengelola - $cair_pengelola;

        $stmt = $this->db->prepare("SELECT IFNULL(SUM(ph.jumlah), 0) FROM pencairan_honor ph JOIN users u ON ph.user_id = u.id WHERE $whereCairW"); $stmt->execute($params); $cair_wali_out = $stmt->fetchColumn();
        $stmt = $this->db->prepare("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE $whereKasInW"); $stmt->execute($params); $refund_wali = $stmt->fetchColumn();
        $cair_wali = (float)$cair_wali_out - (float)$refund_wali;
        $sisa_wali = $honor_walikelas - $cair_wali;

        $stmt = $this->db->prepare("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE $whereKasOut"); $stmt->execute($params); $cair_sekolah_out = $stmt->fetchColumn();
        $stmt = $this->db->prepare("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE $whereKasInS"); $stmt->execute($params); $refund_sekolah = $stmt->fetchColumn();
        $cair_sekolah = (float)$cair_sekolah_out - (float)$refund_sekolah;
        $sisa_sekolah = $kas_sekolah - $cair_sekolah;

        $stmt = $this->db->prepare("SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor WHERE $whereCairK"); $stmt->execute($params); $cair_piket_out = $stmt->fetchColumn();
        $stmt = $this->db->prepare("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE $whereKasInK"); $stmt->execute($params); $refund_piket = $stmt->fetchColumn();
        $cair_piket = (float)$cair_piket_out - (float)$refund_piket;
        $sisa_piket = $honor_piket - $cair_piket;

        // ✨ TRACING PENGELUARAN KAS TUTUP BOTOL
        $stmtTbOut = $this->db->prepare("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE $whereKasTbOut");
        $stmtTbOut->execute($params);
        $tutup_botol_out = (float)$stmtTbOut->fetchColumn();
        $sisa_tutup_botol = $tutup_botol_in - $tutup_botol_out;

        $data['laporan'] = [
            'total_kotor'      => $total_kotor,
            'beban_nasabah'    => $beban_nasabah,
            'margin_total'     => $margin_total,
            'beban_reward'     => $beban_reward, 
            'kas_bst'          => $kas_bst,
            'kas_sekolah'      => $kas_sekolah,
            'honor_pengelola'  => $honor_pengelola,
            'honor_walikelas'  => $honor_walikelas,
            'honor_piket'      => $honor_piket,
            'cair_pengelola'   => $cair_pengelola,
            'sisa_pengelola'   => $sisa_pengelola,
            'cair_wali'        => $cair_wali,
            'sisa_wali'        => $sisa_wali,
            'cair_sekolah'     => $cair_sekolah,
            'sisa_sekolah'     => $sisa_sekolah,
            'cair_piket'       => $cair_piket,
            'sisa_piket'       => $sisa_piket,
            
            // ✨ Data untuk dirender di file HTML Laporan Keuangan
            'tutup_botol_in'   => $tutup_botol_in,
            'tutup_botol_out'  => $tutup_botol_out,
            'sisa_tutup_botol' => $sisa_tutup_botol,

            'persen_bst'       => $config['persen_kas_bst'] ?? 0,
            'persen_sekolah'   => $config['persen_kas_sekolah'] ?? 0,
            'persen_pengelola' => $config['persen_honor_pengelola'] ?? 0,
            'persen_wali'      => $config['persen_honor_walikelas'] ?? 0,
            'persen_piket'     => $config['persen_honor_piket'] ?? 0
        ];

        // HISTORY PENJUALAN
        $stmtHist = $this->db->prepare("SELECT p.*, k.nama_sampah FROM penjualan p JOIN kategori_sampah k ON p.kategori_id = k.id $wherePenjualan ORDER BY p.tanggal_jual DESC LIMIT 10");
        $stmtHist->execute($params);
        $data['history'] = $stmtHist->fetchAll();
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // RENDER TAMPILAN
        extract($data);
        $title = "Laporan Keuangan Global";
        $content = __DIR__ . '/../../views/admin/laporan/keuangan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. LAPORAN HONOR & INSENTIF
    // =================================================================
    public function honor() {
        $data = [];
        // Membaca jatah yang sudah terkunci di kolom honor_walas_rp di tabel setoran
        $qRekap = "SELECT 
                        u.nama AS nama_guru, k.nama_kelas, 
                        SUM(CASE WHEN s.is_sold = 0 THEN s.honor_walas_rp ELSE 0 END) as total_potensi,
                        SUM(CASE WHEN s.is_sold = 1 THEN s.honor_walas_rp ELSE 0 END) as total_realisasi
                    FROM setoran s
                    JOIN users u ON s.walikelas_id = u.id
                    JOIN kelas k ON u.id = k.walikelas_id
                    JOIN kategori_sampah ks ON s.kategori_id = ks.id
                    WHERE s.status = 'valid' AND ks.nama_sampah != '🌟 REWARD PRESTASI'
                    GROUP BY u.id";
                    
        $stmt = $this->db->query($qRekap);
        $data['rekap_honor'] = $stmt->fetchAll();

        $data['total_margin_potensi'] = 0;
        $data['total_margin_realisasi'] = 0;
        foreach ($data['rekap_honor'] as $rh) {
            $data['total_margin_potensi'] += (float)$rh['total_potensi'];
            $data['total_margin_realisasi'] += (float)$rh['total_realisasi'];
        }

        extract($data);
        $title = "Laporan Honor & Insentif";
        $content = __DIR__ . '/../../views/admin/laporan/honor.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 3. REKAP SETORAN PER KELAS
    // =================================================================
    public function setoran() {
        $data = [];
        $data['kelas_id'] = $_GET['kelas_id'] ?? null;
        $data['kelas_list'] = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $data['data_rekap'] = [];
        $data['nama_kelas_aktif'] = "";

        if ($data['kelas_id']) {
            $stmtKelas = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE id = :id");
            $stmtKelas->execute(['id' => $data['kelas_id']]);
            $data['nama_kelas_aktif'] = $stmtKelas->fetchColumn();

            $sql = "SELECT u.nama, 
                           IFNULL(SUM(CASE WHEN ks.nama_sampah != '🌟 REWARD PRESTASI' THEN s.berat ELSE 0 END), 0) as total_pcs, 
                           IFNULL(SUM(s.total_harga), 0) as total_rp
                    FROM users u
                    LEFT JOIN setoran s ON u.id = s.user_id AND s.status = 'valid'
                    LEFT JOIN kategori_sampah ks ON s.kategori_id = ks.id
                    WHERE u.kelas_id = :kid AND u.role = 'siswa'
                    GROUP BY u.id, u.nama 
                    ORDER BY u.nama ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['kid' => $data['kelas_id']]);
            $data['data_rekap'] = $stmt->fetchAll();
        }

        extract($data);
        $title = "Rekap Tabungan Per Kelas";
        $content = __DIR__ . '/../../views/admin/laporan/setoran.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 4. BUKU TABUNGAN NASABAH
    // =================================================================
    public function nasabah() {
        $data = [];
        $data['user_id'] = $_GET['user_id'] ?? null;
        $data['kelas_id'] = $_GET['kelas_id'] ?? null;
        
        $data['siswa_list'] = $this->db->query("SELECT u.id, u.nama, k.nama_kelas 
                                        FROM users u 
                                        LEFT JOIN kelas k ON u.kelas_id = k.id 
                                        WHERE u.role = 'siswa' AND u.deleted_at IS NULL 
                                        ORDER BY u.nama ASC")->fetchAll();
                                        
        $data['kelas_list'] = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $data['detail_siswa'] = null;
        $data['mutasi'] = [];
        $data['total_saldo'] = 0;
        $data['detail_kelas'] = null;
        $data['rekap_kelas'] = [];
        
        if ($data['user_id']) {
            $stmtUser = $this->db->prepare("SELECT u.*, k.nama_kelas FROM users u LEFT JOIN kelas k ON u.kelas_id = k.id WHERE u.id = ?");
            $stmtUser->execute([$data['user_id']]);
            $data['detail_siswa'] = $stmtUser->fetch();

            $sqlMutasi = "SELECT created_at as tanggal, 'setoran' as tipe, nama_sampah as ket, berat as qty, total_harga as jumlah
                          FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id 
                          WHERE s.user_id = :uid1 AND s.status = 'valid'
                          UNION ALL
                          SELECT tanggal_tarik as tanggal, 'penarikan' as tipe, keterangan as ket, 0 as qty, jumlah
                          FROM penarikan WHERE user_id = :uid2 
                          ORDER BY tanggal DESC";
            
            $stmtMutasi = $this->db->prepare($sqlMutasi);
            $stmtMutasi->execute(['uid1' => $data['user_id'], 'uid2' => $data['user_id']]);
            $data['mutasi'] = $stmtMutasi->fetchAll();

            $total_setoran = (float)$this->db->query("SELECT IFNULL(SUM(total_harga),0) FROM setoran WHERE user_id = {$data['user_id']} AND status = 'valid'")->fetchColumn();
            $total_tarik = (float)$this->db->query("SELECT IFNULL(SUM(jumlah),0) FROM penarikan WHERE user_id = {$data['user_id']}")->fetchColumn();
            $data['total_saldo'] = $total_setoran - $total_tarik;

        } elseif ($data['kelas_id']) {
            $stmtKelas = $this->db->prepare("SELECT k.*, u.nama as nama_wali FROM kelas k LEFT JOIN users u ON k.walikelas_id = u.id WHERE k.id = ?");
            $stmtKelas->execute([$data['kelas_id']]);
            $data['detail_kelas'] = $stmtKelas->fetch();

            $sqlRekap = "SELECT u.id, u.nama,
                             (SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE user_id = u.id AND status = 'valid') AS total_masuk,
                             (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE user_id = u.id) AS total_keluar
                         FROM users u
                         WHERE u.kelas_id = ? AND u.role = 'siswa' AND u.nama NOT LIKE '%KESISWAAN%'
                         ORDER BY u.nama ASC";
            $stmtRekap = $this->db->prepare($sqlRekap);
            $stmtRekap->execute([$data['kelas_id']]);
            $data['rekap_kelas'] = $stmtRekap->fetchAll();
        }

        extract($data);
        $title = "Buku Tabungan Nasabah";
        $content = __DIR__ . '/../../views/admin/laporan/nasabah.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 5. BUKU KAS UMUM (DENGAN FILTER RENTANG TANGGAL)
    // =================================================================
    public function buku_kas() {
        $data = [];
        $data['start_date'] = $_GET['start_date'] ?? date('Y-m-01');
        $data['end_date'] = $_GET['end_date'] ?? date('Y-m-t');
        
        $start_dt = $data['start_date'] . ' 00:00:00';
        $end_dt = $data['end_date'] . ' 23:59:59';

        $sql = "
            SELECT waktu, uraian, detail, debit, kredit, sumber_kas FROM (
                SELECT tanggal_jual AS waktu, 'Penjualan Pengepul' AS uraian, keterangan AS detail, total_pendapatan AS debit, 0 AS kredit, 'kas_besar' as sumber_kas
                FROM penjualan WHERE tanggal_jual BETWEEN :p1a AND :p1b
                UNION ALL
                SELECT tanggal AS waktu, CASE WHEN keterangan LIKE '%Refund%' THEN 'Koreksi' ELSE 'Pemasukan Kas' END AS uraian, keterangan AS detail, nominal AS debit, 0 AS kredit, sumber_kas
                FROM kas_manual WHERE jenis = 'pemasukan' AND tanggal BETWEEN :p2a AND :p2b
                UNION ALL
                SELECT MAX(p.tanggal_tarik) AS waktu, CONCAT('Penarikan: ', k.nama_kelas) AS uraian, 'Mutasi Siswa' AS detail, 0 AS debit, SUM(p.jumlah) AS kredit, 'kas_besar' as sumber_kas
                FROM penarikan p JOIN users u ON p.user_id = u.id JOIN kelas k ON u.kelas_id = k.id 
                WHERE p.tanggal_tarik BETWEEN :p3a AND :p3b 
                GROUP BY DATE(p.tanggal_tarik), k.id
                UNION ALL
                SELECT h.tanggal_cair AS waktu, 'Pencairan Honor' AS uraian, h.keterangan AS detail, 0 AS debit, h.jumlah AS kredit, 'kas_besar' as sumber_kas
                FROM pencairan_honor h WHERE h.tanggal_cair BETWEEN :p4a AND :p4b
                UNION ALL
                SELECT tanggal AS waktu, 'Pengeluaran Kas' AS uraian, keterangan AS detail, 0 AS debit, nominal AS kredit, sumber_kas
                FROM kas_manual WHERE jenis = 'pengeluaran' AND tanggal BETWEEN :p5a AND :p5b
            ) as mutasi ORDER BY waktu ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'p1a'=>$start_dt, 'p1b'=>$end_dt, 
            'p2a'=>$start_dt, 'p2b'=>$end_dt, 
            'p3a'=>$start_dt, 'p3b'=>$end_dt, 
            'p4a'=>$start_dt, 'p4b'=>$end_dt, 
            'p5a'=>$start_dt, 'p5b'=>$end_dt, 
            'p6a'=>$start_dt, 'p6b'=>$end_dt, 
            'p7a'=>$start_dt, 'p7b'=>$end_dt
        ]);
        $data['buku_kas'] = $stmt->fetchAll();

        // Hitung Saldo Awal berdasarkan waktu sebelum start_date
        $sqlSaldoAwal = "
            SELECT 
                (SELECT IFNULL(SUM(total_pendapatan), 0) FROM penjualan WHERE tanggal_jual < :s1) 
                + (SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pemasukan' AND tanggal < :s2)
                - (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE tanggal_tarik < :s3) 
                - (SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor WHERE tanggal_cair < :s4) 
                - (SELECT IFNULL(SUM(s.total_harga), 0) FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id WHERE k.nama_sampah = '🌟 REWARD PRESTASI' AND s.created_at < :s5) 
                - (SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pengeluaran' AND tanggal < :s6)
            AS saldo_awal
        ";
        $stmtAwal = $this->db->prepare($sqlSaldoAwal);
        $stmtAwal->execute([
            's1' => $start_dt, 's2' => $start_dt, 's3' => $start_dt, 
            's4' => $start_dt, 's5' => $start_dt, 's6' => $start_dt
        ]);
        $data['saldo_awal'] = $stmtAwal->fetchColumn() ?? 0;

        extract($data);
        $title = "Buku Kas Umum (Kas Ril)";
        $content = __DIR__ . '/../../views/admin/laporan/buku_kas.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 6. DASHBOARD KAS KESISWAAN (DENDA)
    // =================================================================
    public function kas_kesiswaan() {
        $data = [];

        $stmtCek = $this->db->query("SELECT id, nama FROM users WHERE nama LIKE '%KESISWAAN%' AND role = 'siswa' LIMIT 1");
        $akun_kesiswaan = $stmtCek->fetch();

        if (!$akun_kesiswaan) {
            $_SESSION['error'] = "Akun virtual 'KAS KESISWAAN' belum terdeteksi. Silakan buat terlebih dahulu!";
            header('Location: ' . BASE_URL . '/user');
            exit;
        }

        $user_id = $akun_kesiswaan['id'];

        $sqlMutasi = "SELECT s.created_at as tanggal, 'setoran' as tipe, k.nama_sampah as jenis_botol, 'Denda Kedisiplinan' as ket, s.berat as qty, s.total_harga as jumlah
                      FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id 
                      WHERE s.user_id = :uid1 AND s.status = 'valid'
                      UNION ALL
                      SELECT tanggal_tarik as tanggal, 'penarikan' as tipe, '-' as jenis_botol, keterangan as ket, 0 as qty, jumlah
                      FROM penarikan WHERE user_id = :uid2
                      ORDER BY tanggal DESC";
        $stmtMutasi = $this->db->prepare($sqlMutasi);
        $stmtMutasi->execute(['uid1' => $user_id, 'uid2' => $user_id]);
        $data['mutasi'] = $stmtMutasi->fetchAll();

        $data['total_uang_masuk'] = $this->db->query("SELECT IFNULL(SUM(total_harga),0) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn();
        $data['total_uang_ditarik'] = $this->db->query("SELECT IFNULL(SUM(jumlah),0) FROM penarikan WHERE user_id = $user_id")->fetchColumn();
        $data['saldo_aktif'] = $data['total_uang_masuk'] - $data['total_uang_ditarik'];
        $data['total_botol_pcs'] = $this->db->query("SELECT IFNULL(SUM(berat),0) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn();

        extract($data);
        $title = "Dashboard Kas Kesiswaan (Denda)";
        $content = __DIR__ . '/../../views/admin/laporan/kas_kesiswaan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 7. FITUR AKUNTANSI: PENCAIRAN DAN REFUND DANA
    // =================================================================
    public function cairkan_kas_sekolah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf();
            $nominal = (float) $_POST['nominal'];
            if ($nominal > 0) {
                try {
                    $ket = "Sumbangan Kas Sekolah (Berdasarkan Margin Pembagian)";
                    $sql = "INSERT INTO kas_manual (user_id, tanggal, jenis, nominal, keterangan, created_at) VALUES (?, NOW(), 'pengeluaran', ?, ?, NOW())";
                    $this->db->prepare($sql)->execute([$_SESSION['user_id'], $nominal, $ket]);
                    $_SESSION['success'] = "Berhasil mencatat penyerahan dana ke Sekolah.";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal memproses data: " . $e->getMessage();
                }
            }
        }
        header('Location: ' . BASE_URL . '/laporan/keuangan');
        exit;
    }

    public function cairkan_honor_pengelola() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf();
            $nominal = (float) $_POST['nominal'];
            $user_id = $_SESSION['user_id'];
            if ($nominal > 0) {
                try {
                    $ket = "Pencairan Honor Pengelola (Berdasarkan Margin Pembagian)";
                    $sql = "INSERT INTO pencairan_honor (user_id, jumlah, jenis, keterangan, tanggal_cair) VALUES (?, ?, 'pengelola', ?, NOW())";
                    $this->db->prepare($sql)->execute([$user_id, $nominal, $ket]);
                    $_SESSION['success'] = "Berhasil mencairkan Honor Pengelola.";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal memproses data: " . $e->getMessage();
                }
            }
        }
        header('Location: ' . BASE_URL . '/laporan/keuangan');
        exit;
    }

    public function cairkan_honor_piket() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf();
            $nominal = (float) $_POST['nominal'];
            if ($nominal > 0) {
                try {
                    $ket = "Pencairan Honor Siswa Piket (Berdasarkan Margin Pembagian)";
                    $sql = "INSERT INTO pencairan_honor (user_id, jumlah, jenis, keterangan, tanggal_cair) VALUES (?, ?, 'piket', ?, NOW())";
                    $this->db->prepare($sql)->execute([$_SESSION['user_id'], $nominal, $ket]);
                    $_SESSION['success'] = "Berhasil mencairkan Honor Siswa Piket.";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal memproses data: " . $e->getMessage();
                }
            }
        }
        header('Location: ' . BASE_URL . '/laporan/keuangan');
        exit;
    }

    public function refund_lebih_bayar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf();
            $nominal = (float) $_POST['nominal'];
            $jenis = $_POST['jenis_refund'];

            if ($nominal > 0) {
                $ket = "";
                if ($jenis === 'sekolah') $ket = "Refund Kas Sekolah (Koreksi)";
                if ($jenis === 'pengelola') $ket = "Refund Honor Pengelola (Koreksi)";
                if ($jenis === 'wali') $ket = "Refund Honor Walas (Koreksi)";
                if ($jenis === 'piket') $ket = "Refund Honor Piket (Koreksi)";

                if ($ket !== "") {
                    try {
                        $sql = "INSERT INTO kas_manual (user_id, tanggal, jenis, nominal, keterangan, created_at) VALUES (?, NOW(), 'pemasukan', ?, ?, NOW())";
                        $this->db->prepare($sql)->execute([$_SESSION['user_id'], $nominal, $ket]);
                        $_SESSION['success'] = "Berhasil menarik kembali kelebihan dana Rp " . number_format($nominal, 0, ',', '.') . " ke Kas Utama.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal melakukan refund: " . $e->getMessage();
                    }
                }
            }
        }
        header('Location: ' . BASE_URL . '/laporan/keuangan');
        exit;
    }

    // =================================================================
    // 8. FITUR EXPORT EXCEL (CSV)
    // =================================================================
    public function export_setoran() {
        $kelas_id = $_GET['kelas_id'] ?? null;
        if (!$kelas_id) exit("Pilih kelas terlebih dahulu.");

        $stmtKelas = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE id = ?");
        $stmtKelas->execute([$kelas_id]);
        $nama_kelas = $stmtKelas->fetchColumn();

        $filename = "Rekap_Tabungan_" . str_replace(" ", "_", $nama_kelas) . "_" . date('Ymd') . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Nama Nasabah', 'Total Sampah (Pcs)', 'Total Tabungan (Rp)']);

        $sql = "SELECT u.nama, 
                       IFNULL(SUM(CASE WHEN ks.nama_sampah != '🌟 REWARD PRESTASI' THEN s.berat ELSE 0 END), 0) as total_pcs, 
                       IFNULL(SUM(s.total_harga), 0) as total_rp
                FROM users u
                LEFT JOIN setoran s ON u.id = s.user_id AND s.status = 'valid'
                LEFT JOIN kategori_sampah ks ON s.kategori_id = ks.id
                WHERE u.kelas_id = :kid AND u.role = 'siswa'
                GROUP BY u.id, u.nama 
                ORDER BY u.nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['kid' => $kelas_id]);
        
        $i = 1;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [$i++, $row['nama'], $row['total_pcs'], $row['total_rp']]);
        }
        fclose($output);
        exit;
    }

    public function export_buku_kas() {
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');
        $sd = $start_date . ' 00:00:00'; 
        $ed = $end_date . ' 23:59:59';
        
        $filename = "Buku_Kas_BST_" . $start_date . "_to_" . $end_date . ".csv"; 
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Waktu', 'Uraian Transaksi', 'Detail/Keterangan', 'Debit (Masuk)', 'Kredit (Keluar)']);

        $sql = "
            SELECT waktu, uraian, detail, debit, kredit FROM (
                SELECT tanggal_jual AS waktu, 'Penjualan Pengepul' AS uraian, keterangan AS detail, total_pendapatan AS debit, 0 AS kredit 
                FROM penjualan WHERE tanggal_jual BETWEEN :p1a AND :p1b 
                UNION ALL 
                SELECT tanggal AS waktu, 'Pemasukan Kas' AS uraian, keterangan AS detail, nominal AS debit, 0 AS kredit 
                FROM kas_manual WHERE jenis = 'pemasukan' AND tanggal BETWEEN :p2a AND :p2b 
                UNION ALL 
                SELECT MAX(p.tanggal_tarik) AS waktu, CONCAT('Penarikan Kolektif: ', k.nama_kelas) AS uraian, 'Mutasi Siswa' AS detail, 0 AS debit, SUM(p.jumlah) AS kredit 
                FROM penarikan p JOIN users u ON p.user_id = u.id JOIN kelas k ON u.kelas_id = k.id 
                WHERE p.tanggal_tarik BETWEEN :p3a AND :p3b AND u.role = 'siswa' 
                GROUP BY DATE(p.tanggal_tarik), k.id, k.nama_kelas 
                UNION ALL 
                SELECT h.tanggal_cair AS waktu, 'Pencairan Honor' AS uraian, h.keterangan AS detail, 0 AS debit, h.jumlah AS kredit 
                FROM pencairan_honor h WHERE h.tanggal_cair BETWEEN :p4a AND :p4b 
                UNION ALL 
                SELECT tanggal AS waktu, 'Pengeluaran Kas' AS uraian, keterangan AS detail, 0 AS debit, nominal AS kredit 
                FROM kas_manual WHERE jenis = 'pengeluaran' AND tanggal BETWEEN :p5a AND :p5b
            ) as mutasi ORDER BY waktu ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['p1a'=>$sd, 'p1b'=>$ed, 'p2a'=>$sd, 'p2b'=>$ed, 'p3a'=>$sd, 'p3b'=>$ed, 'p4a'=>$sd, 'p4b'=>$ed, 'p5a'=>$sd, 'p5b'=>$ed]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
}
?>