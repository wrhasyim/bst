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
        $stmtConfig = $this->db->query("SELECT kunci, nilai FROM pengaturan WHERE kunci LIKE 'persen_%'");
        $config = $stmtConfig->fetchAll(PDO::FETCH_KEY_PAIR);
        $persen_wali = ($config['persen_honor_walikelas'] ?? 0) / 100;
        
        $total_kotor = $this->db->query("SELECT SUM(total_pendapatan) FROM penjualan")->fetchColumn() ?? 0;

        $sqlBebanNasabah = "SELECT SUM(s.total_harga) 
                            FROM setoran s 
                            JOIN kategori_sampah k ON s.kategori_id = k.id 
                            WHERE s.status = 'valid' AND s.is_sold = 1 AND k.nama_sampah != '🌟 REWARD PRESTASI'";
        $beban_nasabah = $this->db->query($sqlBebanNasabah)->fetchColumn() ?? 0;

        $margin_total = $total_kotor - $beban_nasabah;

        $sql_honor_wali = "SELECT SUM((s.total_pengepul - s.total_harga) * :persen)
                           FROM setoran s
                           JOIN users u ON s.walikelas_id = u.id
                           JOIN kategori_sampah k ON s.kategori_id = k.id
                           WHERE s.status = 'valid' AND s.is_sold = 1 AND k.nama_sampah != '🌟 REWARD PRESTASI'";
        $stmtWali = $this->db->prepare($sql_honor_wali);
        $stmtWali->execute(['persen' => $persen_wali]);
        $honor_walikelas = $stmtWali->fetchColumn() ?? 0;

        $sqlMarginEstimasi = "SELECT SUM(s.total_pengepul - s.total_harga) 
                              FROM setoran s 
                              JOIN kategori_sampah k ON s.kategori_id = k.id 
                              WHERE s.status = 'valid' AND s.is_sold = 1 AND k.nama_sampah != '🌟 REWARD PRESTASI'";
        $margin_estimasi = $this->db->query($sqlMarginEstimasi)->fetchColumn() ?? 0;

        $honor_pengelola = $margin_estimasi * (($config['persen_honor_pengelola'] ?? 0) / 100);
        $kas_sekolah     = $margin_estimasi * (($config['persen_kas_sekolah'] ?? 0) / 100);
        
        $sqlBebanReward = "SELECT SUM(s.total_harga) 
                           FROM setoran s 
                           JOIN kategori_sampah k ON s.kategori_id = k.id 
                           WHERE k.nama_sampah = '🌟 REWARD PRESTASI'";
        $beban_reward = $this->db->query($sqlBebanReward)->fetchColumn() ?? 0;

        $kas_bst = $margin_total - ($honor_walikelas + $honor_pengelola + $kas_sekolah) - $beban_reward;

        $laporan = [
            'total_kotor'     => $total_kotor,
            'beban_nasabah'   => $beban_nasabah,
            'margin_total'    => $margin_total,
            'beban_reward'    => $beban_reward, 
            'kas_bst'         => $kas_bst,
            'kas_sekolah'     => $kas_sekolah,
            'honor_pengelola' => $honor_pengelola,
            'honor_walikelas' => $honor_walikelas
        ];

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

        $qRekap = "SELECT 
                        u.nama AS nama_guru, k.nama_kelas, 
                        SUM(CASE WHEN s.is_sold = 0 THEN (s.total_pengepul - s.total_harga) * :p1 ELSE 0 END) as total_potensi,
                        SUM(CASE WHEN s.is_sold = 1 THEN (s.total_pengepul - s.total_harga) * :p2 ELSE 0 END) as total_realisasi
                    FROM setoran s
                    JOIN users u ON s.walikelas_id = u.id
                    JOIN kelas k ON u.id = k.walikelas_id
                    JOIN kategori_sampah ks ON s.kategori_id = ks.id
                    WHERE s.status = 'valid' AND ks.nama_sampah != '🌟 REWARD PRESTASI'
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
            $data_rekap = $stmt->fetchAll();
        }

        $title = "Rekap Tabungan Per Kelas";
        $content = __DIR__ . '/../../views/admin/laporan/setoran.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 4. BUKU TABUNGAN (DUAL MODE: INDIVIDU & KOLEKTIF KELAS)
    // =================================================================
    public function nasabah() {
        $user_id = $_GET['user_id'] ?? null;
        $kelas_id = $_GET['kelas_id'] ?? null;
        
        $siswa_list = $this->db->query("SELECT u.id, u.nama, k.nama_kelas 
                                        FROM users u 
                                        LEFT JOIN kelas k ON u.kelas_id = k.id 
                                        WHERE u.role = 'siswa' 
                                        ORDER BY u.nama ASC")->fetchAll();
                                        
        $kelas_list = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        // Mode Individu
        $detail_siswa = null; 
        $mutasi = []; 
        $total_saldo = 0;
        
        // Mode Kelas
        $detail_kelas = null; 
        $rekap_kelas = []; 
        $total_kelas_saldo = 0;

        if ($user_id) {
            // LOGIKA INDIVIDU (MUTASI)
            $stmtUser = $this->db->prepare("SELECT u.*, k.nama_kelas 
                                            FROM users u 
                                            LEFT JOIN kelas k ON u.kelas_id = k.id 
                                            WHERE u.id = ?");
            $stmtUser->execute([$user_id]);
            $detail_siswa = $stmtUser->fetch();

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
            $stmtMutasi->execute([
                'uid1' => $user_id, 
                'uid2' => $user_id
            ]);
            $mutasi = $stmtMutasi->fetchAll();

            $total_setoran = $this->db->query("SELECT IFNULL(SUM(total_harga),0) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn();
            $total_tarik = $this->db->query("SELECT IFNULL(SUM(jumlah),0) FROM penarikan WHERE user_id = $user_id")->fetchColumn();
            $total_saldo = $total_setoran - $total_tarik;

        } elseif ($kelas_id) {
            // LOGIKA KOLEKTIF KELAS
            $stmtKelas = $this->db->prepare("SELECT k.*, u.nama as nama_wali 
                                             FROM kelas k 
                                             LEFT JOIN users u ON k.walikelas_id = u.id 
                                             WHERE k.id = ?");
            $stmtKelas->execute([$kelas_id]);
            $detail_kelas = $stmtKelas->fetch();

            $sqlRekap = "SELECT u.id, u.nama,
                            (SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE user_id = u.id AND status = 'valid') AS total_masuk,
                            (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE user_id = u.id) AS total_keluar
                         FROM users u
                         WHERE u.kelas_id = ? AND u.role = 'siswa' AND u.nama NOT LIKE '%KESISWAAN%'
                         ORDER BY u.nama ASC";
            $stmtRekap = $this->db->prepare($sqlRekap);
            $stmtRekap->execute([$kelas_id]);
            $rekap_kelas = $stmtRekap->fetchAll();
        }

        $title = "Buku Tabungan Nasabah";
        $content = __DIR__ . '/../../views/admin/laporan/nasabah.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 5. BUKU KAS UMUM (ARUS KAS RIL / FISIK) - 7 IN 1 QUERY
    // =================================================================
    public function buku_kas() {
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        
        $periode = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        $sql = "
            SELECT tanggal_jual AS waktu, 'Penjualan Pengepul' AS uraian, keterangan AS detail, total_pendapatan AS debit, 0 AS kredit, 'masuk' as jenis
            FROM penjualan WHERE DATE_FORMAT(tanggal_jual, '%Y-%m') = :p1
            
            UNION ALL
            
            SELECT tanggal AS waktu, 'Pemasukan Kas (Manual)' AS uraian, keterangan AS detail, nominal AS debit, 0 AS kredit, 'masuk_manual' as jenis
            FROM kas_manual WHERE jenis = 'pemasukan' AND DATE_FORMAT(tanggal, '%Y-%m') = :p2

            UNION ALL
            
            -- BLOK A: PENARIKAN KOLEKTIF (HANYA SISWA REGULER)
            SELECT 
                MAX(p.tanggal_tarik) AS waktu, 
                CONCAT('Penarikan Kolektif: Kelas ', k.nama_kelas) AS uraian, 
                CONCAT(COUNT(p.id), ' Transaksi Siswa') AS detail, 
                0 AS debit, 
                SUM(p.jumlah) AS kredit, 
                'keluar_nasabah' as jenis
            FROM penarikan p 
            JOIN users u ON p.user_id = u.id 
            JOIN kelas k ON u.kelas_id = k.id 
            WHERE DATE_FORMAT(p.tanggal_tarik, '%Y-%m') = :p3a
              AND u.role = 'siswa' 
              AND u.nama NOT LIKE '%KESISWAAN%'
            GROUP BY DATE(p.tanggal_tarik), k.id, k.nama_kelas
            
            UNION ALL

            -- BLOK B: PENARIKAN INDIVIDUAL (GURU, KESISWAAN, ATAU TANPA KELAS)
            SELECT 
                p.tanggal_tarik AS waktu, 
                CONCAT('Penarikan Tunai: ', u.nama) AS uraian, 
                p.keterangan AS detail, 
                0 AS debit, 
                p.jumlah AS kredit, 
                'keluar_nasabah' as jenis
            FROM penarikan p 
            JOIN users u ON p.user_id = u.id 
            WHERE DATE_FORMAT(p.tanggal_tarik, '%Y-%m') = :p3b
              AND (u.role != 'siswa' OR u.kelas_id IS NULL OR u.nama LIKE '%KESISWAAN%')

            UNION ALL
            
            SELECT h.tanggal_cair AS waktu, CONCAT('Pencairan Honor: ', u.nama) AS uraian, h.keterangan AS detail, 0 AS debit, h.jumlah AS kredit, 'keluar_honor' as jenis
            FROM pencairan_honor h JOIN users u ON h.user_id = u.id WHERE DATE_FORMAT(h.tanggal_cair, '%Y-%m') = :p4
            
            UNION ALL
            
            SELECT s.created_at AS waktu, CONCAT('Reward Prestasi: ', u.nama) AS uraian, 'Pemberian Hadiah Saldo' AS detail, 0 AS debit, s.total_harga AS kredit, 'keluar_reward' as jenis
            FROM setoran s JOIN users u ON s.user_id = u.id JOIN kategori_sampah k ON s.kategori_id = k.id 
            WHERE k.nama_sampah = '🌟 REWARD PRESTASI' AND DATE_FORMAT(s.created_at, '%Y-%m') = :p5

            UNION ALL
            
            SELECT tanggal AS waktu, 'Pengeluaran Kas (Manual)' AS uraian, keterangan AS detail, 0 AS debit, nominal AS kredit, 'keluar_manual' as jenis
            FROM kas_manual WHERE jenis = 'pengeluaran' AND DATE_FORMAT(tanggal, '%Y-%m') = :p6
            
            ORDER BY waktu ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'p1' => $periode, 
            'p2' => $periode, 
            'p3a' => $periode, 
            'p3b' => $periode, 
            'p4' => $periode, 
            'p5' => $periode, 
            'p6' => $periode
        ]);
        $buku_kas = $stmt->fetchAll();

        $sqlSaldoAwal = "
            SELECT 
                (SELECT IFNULL(SUM(total_pendapatan), 0) FROM penjualan WHERE DATE_FORMAT(tanggal_jual, '%Y-%m') < :s1) 
                + (SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pemasukan' AND DATE_FORMAT(tanggal, '%Y-%m') < :s2)
                - (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE DATE_FORMAT(tanggal_tarik, '%Y-%m') < :s3) 
                - (SELECT IFNULL(SUM(jumlah), 0) FROM pencairan_honor WHERE DATE_FORMAT(tanggal_cair, '%Y-%m') < :s4) 
                - (SELECT IFNULL(SUM(s.total_harga), 0) FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id WHERE k.nama_sampah = '🌟 REWARD PRESTASI' AND DATE_FORMAT(s.created_at, '%Y-%m') < :s5) 
                - (SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pengeluaran' AND DATE_FORMAT(tanggal, '%Y-%m') < :s6)
            AS saldo_awal
        ";
        $stmtAwal = $this->db->prepare($sqlSaldoAwal);
        $stmtAwal->execute([
            's1' => $periode, 
            's2' => $periode, 
            's3' => $periode, 
            's4' => $periode, 
            's5' => $periode, 
            's6' => $periode
        ]);
        $saldo_awal = $stmtAwal->fetchColumn() ?? 0;

        $title = "Buku Kas Umum (Kas Ril)";
        $content = __DIR__ . '/../../views/admin/laporan/buku_kas.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 6. FITUR BARU: LAPORAN KHUSUS KAS KESISWAAN (DENDA)
    // =================================================================
    public function kas_kesiswaan() {
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        // Cari akun virtual kesiswaan di tabel users (mencari substring 'KESISWAAN')
        $stmtCek = $this->db->query("SELECT id, nama FROM users WHERE nama LIKE '%KESISWAAN%' AND role = 'siswa' LIMIT 1");
        $akun_kesiswaan = $stmtCek->fetch();

        if (!$akun_kesiswaan) {
            $_SESSION['error'] = "Akun virtual 'KAS KESISWAAN' belum terdeteksi di Master Data Pengguna. Silakan buat terlebih dahulu!";
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

        $title = "Dashboard Kas Kesiswaan (Denda)";
        $content = __DIR__ . '/../../views/admin/laporan/kas_kesiswaan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}