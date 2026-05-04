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

        // JOIN users tetap dilakukan tanpa filter deleted_at agar histori honor walas lama tidak hilang
        $sql_honor_wali = "SELECT SUM((s.total_pengepul - s.total_harga) * :persen) 
                           FROM setoran s 
                           JOIN users u ON s.walikelas_id = u.id 
                           JOIN kategori_sampah k ON s.kategori_id = k.id 
                           WHERE s.status = 'valid' AND s.is_sold = 1 AND k.nama_sampah != '🌟 REWARD PRESTASI'";
        $stmtWali = $this->db->prepare($sql_honor_wali);
        $stmtWali->execute(['persen' => $persen_wali]);
        $honor_walikelas = $stmtWali->fetchColumn() ?? 0;

        $honor_pengelola = $margin_total * (($config['persen_honor_pengelola'] ?? 0) / 100);
        $kas_sekolah     = $margin_total * (($config['persen_kas_sekolah'] ?? 0) / 100);
        
        $sqlBebanReward = "SELECT SUM(s.total_harga) FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id WHERE k.nama_sampah = '🌟 REWARD PRESTASI'";
        $beban_reward = $this->db->query($sqlBebanReward)->fetchColumn() ?? 0;

        $kas_bst = $margin_total - ($honor_walikelas + $honor_pengelola + $kas_sekolah) - $beban_reward;

        // TRACING STATUS PEMBAYARAN & REFUND
        $cair_pengelola_out = $this->db->query("SELECT IFNULL(SUM(ph.jumlah), 0) FROM pencairan_honor ph JOIN users u ON ph.user_id = u.id WHERE u.role IN ('admin', 'staff')")->fetchColumn();
        $refund_pengelola = $this->db->query("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pemasukan' AND keterangan LIKE '%Refund Honor Pengelola%'")->fetchColumn();
        $cair_pengelola = $cair_pengelola_out - $refund_pengelola;
        $sisa_pengelola = $honor_pengelola - $cair_pengelola;

        $cair_wali_out = $this->db->query("SELECT IFNULL(SUM(ph.jumlah), 0) FROM pencairan_honor ph JOIN users u ON ph.user_id = u.id WHERE u.role NOT IN ('admin', 'staff', 'siswa')")->fetchColumn();
        $refund_wali = $this->db->query("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pemasukan' AND keterangan LIKE '%Refund Honor Wali Kelas%'")->fetchColumn();
        $cair_wali = $cair_wali_out - $refund_wali;
        $sisa_wali = $honor_walikelas - $cair_wali;

        $cair_sekolah_out = $this->db->query("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pengeluaran' AND keterangan LIKE '%Sumbangan Kas Sekolah%'")->fetchColumn();
        $refund_sekolah = $this->db->query("SELECT IFNULL(SUM(nominal), 0) FROM kas_manual WHERE jenis = 'pemasukan' AND keterangan LIKE '%Refund Kas Sekolah%'")->fetchColumn();
        $cair_sekolah = $cair_sekolah_out - $refund_sekolah;
        $sisa_sekolah = $kas_sekolah - $cair_sekolah;

        $persen_sekolah = $config['persen_kas_sekolah'] ?? 0;
        $persen_pengelola = $config['persen_honor_pengelola'] ?? 0;
        $persen_wali = $config['persen_honor_walikelas'] ?? 0;
        $persen_bst = 100 - ($persen_sekolah + $persen_pengelola + $persen_wali);

        $laporan = [
            'total_kotor'      => $total_kotor,
            'beban_nasabah'    => $beban_nasabah,
            'margin_total'     => $margin_total,
            'beban_reward'     => $beban_reward, 
            'kas_bst'          => $kas_bst,
            'kas_sekolah'      => $kas_sekolah,
            'honor_pengelola'  => $honor_pengelola,
            'honor_walikelas'  => $honor_walikelas,
            'cair_pengelola'   => $cair_pengelola,
            'sisa_pengelola'   => $sisa_pengelola,
            'cair_wali'        => $cair_wali,
            'sisa_wali'        => $sisa_wali,
            'cair_sekolah'     => $cair_sekolah,
            'sisa_sekolah'     => $sisa_sekolah,
            'persen_bst'       => $persen_bst,
            'persen_sekolah'   => $persen_sekolah,
            'persen_pengelola' => $persen_pengelola,
            'persen_wali'      => $persen_wali
        ];

        $history = $this->db->query("SELECT p.*, k.nama_sampah FROM penjualan p JOIN kategori_sampah k ON p.kategori_id = k.id ORDER BY p.tanggal_jual DESC LIMIT 10")->fetchAll();

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

        // Tampilkan rekap honor walikelas meskipun gurunya sudah soft-delete agar histori tetap sinkron
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

        $total_margin_potensi = 0;
        $total_margin_realisasi = 0;
        foreach ($rekap_honor as $rh) {
            $total_margin_potensi += $rh['total_potensi'];
            $total_margin_realisasi += $rh['total_realisasi'];
        }

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

            // Tampilkan seluruh siswa (aktif maupun soft-delete) di rekap sejarah kelas
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
    // 4. BUKU TABUNGAN NASABAH
    // =================================================================
    public function nasabah() {
        $user_id = $_GET['user_id'] ?? null;
        $kelas_id = $_GET['kelas_id'] ?? null;
        
        // Filter: Hanya tampilkan siswa yang MASIH AKTIF di dropdown pemilihan nasabah
        $siswa_list = $this->db->query("SELECT u.id, u.nama, k.nama_kelas 
                                        FROM users u 
                                        LEFT JOIN kelas k ON u.kelas_id = k.id 
                                        WHERE u.role = 'siswa' AND u.deleted_at IS NULL 
                                        ORDER BY u.nama ASC")->fetchAll();
                                        
        $kelas_list = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $detail_siswa = null; 
        $mutasi = []; 
        $total_saldo = 0;
        
        $detail_kelas = null; 
        $rekap_kelas = []; 
        $total_kelas_saldo = 0;

        if ($user_id) {
            $stmtUser = $this->db->prepare("SELECT u.*, k.nama_kelas FROM users u LEFT JOIN kelas k ON u.kelas_id = k.id WHERE u.id = ?");
            $stmtUser->execute([$user_id]);
            $detail_siswa = $stmtUser->fetch();

            $sqlMutasi = "SELECT created_at as tanggal, 'setoran' as tipe, nama_sampah as ket, berat as qty, total_harga as jumlah
                          FROM setoran s JOIN kategori_sampah k ON s.kategori_id = k.id 
                          WHERE s.user_id = :uid1 AND s.status = 'valid'
                          UNION ALL
                          SELECT tanggal_tarik as tanggal, 'penarikan' as tipe, keterangan as ket, 0 as qty, jumlah
                          FROM penarikan WHERE user_id = :uid2 
                          ORDER BY tanggal DESC";
            
            $stmtMutasi = $this->db->prepare($sqlMutasi);
            $stmtMutasi->execute(['uid1' => $user_id, 'uid2' => $user_id]);
            $mutasi = $stmtMutasi->fetchAll();

            $total_setoran = $this->db->query("SELECT IFNULL(SUM(total_harga),0) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn();
            $total_tarik = $this->db->query("SELECT IFNULL(SUM(jumlah),0) FROM penarikan WHERE user_id = $user_id")->fetchColumn();
            $total_saldo = $total_setoran - $total_tarik;

        } elseif ($kelas_id) {
            $stmtKelas = $this->db->prepare("SELECT k.*, u.nama as nama_wali FROM kelas k LEFT JOIN users u ON k.walikelas_id = u.id WHERE k.id = ?");
            $stmtKelas->execute([$kelas_id]);
            $detail_kelas = $stmtKelas->fetch();

            // Tetap hitung saldo meskipun ada siswa alumni/soft-delete
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
    // 5. BUKU KAS UMUM (ARUS KAS RIL)
    // =================================================================
    public function buku_kas() {
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        
        $periode = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        $sql = "
            SELECT tanggal_jual AS waktu, 'Penjualan Pengepul' AS uraian, keterangan AS detail, total_pendapatan AS debit, 0 AS kredit, 'masuk' as jenis
            FROM penjualan WHERE DATE_FORMAT(tanggal_jual, '%Y-%m') = :p1
            
            UNION ALL
            
            SELECT tanggal AS waktu, 
                   CASE WHEN keterangan LIKE '%Refund%' THEN 'Koreksi Kelebihan Bayar' ELSE 'Pemasukan Kas (Manual)' END AS uraian, 
                   keterangan AS detail, nominal AS debit, 0 AS kredit, 'masuk_manual' as jenis
            FROM kas_manual WHERE jenis = 'pemasukan' AND DATE_FORMAT(tanggal, '%Y-%m') = :p2

            UNION ALL
            
            -- Penarikan: JOIN u tanpa filter deleted_at agar nama tetap muncul di Buku Kas sejarah
            SELECT MAX(p.tanggal_tarik) AS waktu, CONCAT('Penarikan Kolektif: Kelas ', k.nama_kelas) AS uraian, CONCAT(COUNT(p.id), ' Transaksi Siswa') AS detail, 0 AS debit, SUM(p.jumlah) AS kredit, 'keluar_nasabah' as jenis
            FROM penarikan p JOIN users u ON p.user_id = u.id JOIN kelas k ON u.kelas_id = k.id 
            WHERE DATE_FORMAT(p.tanggal_tarik, '%Y-%m') = :p3a AND u.role = 'siswa' AND u.nama NOT LIKE '%KESISWAAN%'
            GROUP BY DATE(p.tanggal_tarik), k.id, k.nama_kelas
            
            UNION ALL

            SELECT p.tanggal_tarik AS waktu, CONCAT('Penarikan Tunai: ', u.nama) AS uraian, p.keterangan AS detail, 0 AS debit, p.jumlah AS kredit, 'keluar_nasabah' as jenis
            FROM penarikan p JOIN users u ON p.user_id = u.id 
            WHERE DATE_FORMAT(p.tanggal_tarik, '%Y-%m') = :p3b AND (u.role != 'siswa' OR u.kelas_id IS NULL OR u.nama LIKE '%KESISWAAN%')

            UNION ALL
            
            SELECT h.tanggal_cair AS waktu, 
                   CASE WHEN h.keterangan LIKE '%Pengelola%' THEN 'Pencairan Honor Pengelola' ELSE CONCAT('Pencairan Honor: ', u.nama) END AS uraian, 
                   h.keterangan AS detail, 0 AS debit, h.jumlah AS kredit, 'keluar_honor' as jenis
            FROM pencairan_honor h JOIN users u ON h.user_id = u.id WHERE DATE_FORMAT(h.tanggal_cair, '%Y-%m') = :p4
            
            UNION ALL
            
            SELECT s.created_at AS waktu, CONCAT('Reward Prestasi: ', u.nama) AS uraian, 'Pemberian Hadiah Saldo' AS detail, 0 AS debit, s.total_harga AS kredit, 'keluar_reward' as jenis
            FROM setoran s JOIN users u ON s.user_id = u.id JOIN kategori_sampah k ON s.kategori_id = k.id 
            WHERE k.nama_sampah = '🌟 REWARD PRESTASI' AND DATE_FORMAT(s.created_at, '%Y-%m') = :p5

            UNION ALL
            
            SELECT tanggal AS waktu, 
                   CASE WHEN keterangan LIKE '%Sumbangan Kas Sekolah%' THEN 'Penyerahan Kas Sekolah' ELSE 'Pengeluaran Kas (Manual)' END AS uraian, 
                   keterangan AS detail, 0 AS debit, nominal AS kredit, 'keluar_manual' as jenis
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
    // 6. DASHBOARD KAS KESISWAAN (DENDA)
    // =================================================================
    public function kas_kesiswaan() {
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

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

        $title = "Dashboard Kas Kesiswaan (Denda)";
        $content = __DIR__ . '/../../views/admin/laporan/kas_kesiswaan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 7. FITUR AKUNTANSI: PENCAIRAN DAN REFUND DANA
    // =================================================================
    public function cairkan_kas_sekolah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff')) {
            $nominal = (float) $_POST['nominal'];
            if ($nominal > 0) {
                try {
                    $ket = "Sumbangan Kas Sekolah (Berdasarkan Margin Pembagian)";
                    // NOW() digunakan untuk ketelitian sorting riwayat buku kas
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff')) {
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

    public function refund_lebih_bayar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff')) {
            $nominal = (float) $_POST['nominal'];
            $jenis = $_POST['jenis_refund'];

            if ($nominal > 0) {
                $ket = "";
                if ($jenis === 'sekolah') $ket = "Refund Kas Sekolah (Koreksi)";
                if ($jenis === 'pengelola') $ket = "Refund Honor Pengelola (Koreksi)";
                if ($jenis === 'wali') $ket = "Refund Honor Walas (Koreksi)";

                if ($ket !== "") {
                    try {
                        $sql = "INSERT INTO kas_manual (user_id, tanggal, jenis, nominal, keterangan, created_at) VALUES (?, NOW(), 'pemasukan', ?, ?, NOW())";
                        $this->db->prepare($sql)->execute([$_SESSION['user_id'], $nominal, $ket,]);
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
}