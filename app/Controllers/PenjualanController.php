<?php
// app/Controllers/PenjualanController.php
require_once __DIR__ . '/../Core/Database.php';

class PenjualanController {
    private $db;

    public function __construct() {
        // Proteksi route hanya untuk user yang sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // =================================================================
    // 1. TAMPILKAN DATA RIWAYAT PENJUALAN
    // =================================================================
    public function index() {
        try {
            // Coba jalankan query utama dengan kolom konversi_kg
            $sql = "SELECT p.*, k.nama_sampah, k.satuan, k.konversi_kg
                    FROM penjualan p
                    JOIN kategori_sampah k ON p.kategori_id = k.id
                    ORDER BY p.tanggal_jual DESC";
            $penjualan = $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            // 🛡️ ANTI-ERROR: Jika kolom konversi_kg belum di-tambah ke database, 
            // otomatis gunakan angka 1 agar halaman tidak crash!
            $sql = "SELECT p.*, k.nama_sampah, k.satuan, 1 as konversi_kg
                    FROM penjualan p
                    JOIN kategori_sampah k ON p.kategori_id = k.id
                    ORDER BY p.tanggal_jual DESC";
            $penjualan = $this->db->query($sql)->fetchAll();
        }

        $title = "Data Penjualan Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. FORM INPUT PENJUALAN (Filter Kategori Bersaldo Saja)
    // =================================================================
    public function create() {
        try {
            $sql = "SELECT k.id, k.nama_sampah, k.harga_pengepul, k.satuan, k.konversi_kg,
                           (SELECT IFNULL(SUM(berat), 0) 
                            FROM setoran s 
                            WHERE s.kategori_id = k.id 
                              AND s.status = 'valid' 
                              AND (s.is_sold = 0 OR s.is_sold IS NULL)
                           ) as stok_tersedia
                    FROM kategori_sampah k
                    WHERE k.nama_sampah != '🌟 REWARD PRESTASI'
                    HAVING stok_tersedia > 0";
                    
            $kategori_ready = $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            // 🛡️ ANTI-ERROR FALLBACK 
            $sql = "SELECT k.id, k.nama_sampah, k.harga_pengepul, k.satuan, 1 as konversi_kg,
                           (SELECT IFNULL(SUM(berat), 0) 
                            FROM setoran s 
                            WHERE s.kategori_id = k.id 
                              AND s.status = 'valid' 
                              AND (s.is_sold = 0 OR s.is_sold IS NULL)
                           ) as stok_tersedia
                    FROM kategori_sampah k
                    WHERE k.nama_sampah != '🌟 REWARD PRESTASI'
                    HAVING stok_tersedia > 0";
                    
            $kategori_ready = $this->db->query($sql)->fetchAll();
        }

        $title = "Input Penjualan Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 3. PROSES SIMPAN DENGAN IMMUTABLE SNAPSHOTTING (ACID COMPLIANCE)
    // =================================================================
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_SESSION['role'], ['admin', 'staff'])) {
            
            $kategori_id  = (int)($_POST['kategori_id'] ?? 0);
            $harga_per_kg = (float)($_POST['harga_per_kg'] ?? $_POST['harga_per_pcs'] ?? 0);
            
            // 🛠️ Menerima input volume jualan MANUAL dalam satuan Pcs
            $pcs_jual = (float)($_POST['total_pcs'] ?? 0); 
            $keterangan   = !empty($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : 'Penjualan Manual / Rutin';

            if ($kategori_id <= 0 || $harga_per_kg <= 0 || $pcs_jual <= 0) {
                $_SESSION['error'] = "Gagal! Pastikan kategori, volume, dan harga telah diisi (Tidak boleh Rp 0 atau 0 Pcs).";
                header('Location: ' . BASE_URL . '/penjualan');
                exit;
            }

            try {
                $this->db->beginTransaction();

                // 1. LOGIKA KONVERSI DINAMIS & PEMBULATAN KE KG BULAT
                try {
                    $stmtKat = $this->db->prepare("SELECT konversi_kg FROM kategori_sampah WHERE id = ?");
                    $stmtKat->execute([$kategori_id]);
                    $konversi = (int)$stmtKat->fetchColumn();
                } catch (Exception $e) {
                    $konversi = 1; // 🛡️ ANTI-ERROR Fallback
                }
                if ($konversi <= 0) $konversi = 1;

                $total_kg = round($pcs_jual / $konversi);
                if ($total_kg < 1) $total_kg = 1; // Pastikan minimal 1 KG

                // 2. FIFO INVENTORY DEDUCTION (Hitung HPP dan Walas sebagian)
                $stmtRows = $this->db->prepare("SELECT id, berat, total_harga, honor_walas_rp FROM setoran WHERE kategori_id = ? AND status = 'valid' AND (is_sold = 0 OR is_sold IS NULL) ORDER BY id ASC FOR UPDATE");
                $stmtRows->execute([$kategori_id]);
                $rows = $stmtRows->fetchAll();

                $sisa_diminta = $pcs_jual;
                $hpp_terpakai = 0;
                $walas_terpakai = 0;
                $ids_terjual = [];
                $total_tersedia = 0;

                // Hitung total stok murni yang ada
                foreach ($rows as $row) {
                    $total_tersedia += (float)$row['berat'];
                }

                if ($pcs_jual > $total_tersedia) {
                    throw new Exception("Stok gudang tidak cukup! Diminta: {$pcs_jual} Pcs. Tersedia: {$total_tersedia} Pcs.");
                }

                // Lakukan pemotongan baris demi baris (FIFO)
                foreach ($rows as $row) {
                    if ($sisa_diminta <= 0) break;
                    
                    $berat_row = (float)$row['berat'];
                    $ids_terjual[] = $row['id'];
                    
                    if ($berat_row <= $sisa_diminta) {
                        $hpp_terpakai += (float)$row['total_harga'];
                        $walas_terpakai += (float)$row['honor_walas_rp'];
                        $sisa_diminta -= $berat_row;
                    } else {
                        // Jika baris setoran ini lebih besar dari sisa yang mau dijual (Proporsional HPP)
                        $proporsi = $sisa_diminta / $berat_row;
                        $hpp_terpakai += $row['total_harga'] * $proporsi;
                        $walas_terpakai += $row['honor_walas_rp'] * $proporsi;
                        $sisa_diminta = 0;
                    }
                }

                $beban_nasabah = $hpp_terpakai;
                $total_walas_setoran = $walas_terpakai;

                // 3. Ambil Konfigurasi Persentase Terkini
                $stmtConfig = $this->db->query("SELECT kunci, nilai FROM pengaturan WHERE kunci LIKE 'persen_%'");
                $config = $stmtConfig->fetchAll(PDO::FETCH_KEY_PAIR);
                
                $p_pengelola = (float)($config['persen_honor_pengelola'] ?? 0) / 100;
                $p_sekolah   = (float)($config['persen_kas_sekolah'] ?? 0) / 100;
                $p_piket     = (float)($config['persen_honor_piket'] ?? 0) / 100;

                // 4. Kalkulasi Laba Berdasarkan KG yg Dibulatkan
                $total_pendapatan = $total_kg * $harga_per_kg;
                $margin_total     = $total_pendapatan - $beban_nasabah;
                
                $kas_sekolah_rp      = $margin_total * $p_sekolah;
                $honor_pengelola_rp  = $margin_total * $p_pengelola;
                $honor_piket_rp      = $margin_total * $p_piket;
                $kas_bst_rp = $margin_total - ($kas_sekolah_rp + $honor_pengelola_rp + $honor_piket_rp + $total_walas_setoran);

                // 5. Eksekusi Pertama: Catat di tabel Penjualan 
                $sqlInsert = "INSERT INTO penjualan (
                                kategori_id, total_pcs, harga_per_pcs, total_pendapatan, 
                                beban_nasabah_rp, margin_total_rp, kas_sekolah_rp, 
                                honor_pengelola_rp, honor_piket_rp, kas_bst_rp, 
                                tanggal_jual, keterangan
                              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
                
                $stmtInsert = $this->db->prepare($sqlInsert);
                $stmtInsert->execute([
                    $kategori_id, $pcs_jual, $harga_per_kg, $total_pendapatan,
                    $beban_nasabah, $margin_total, $kas_sekolah_rp,
                    $honor_pengelola_rp, $honor_piket_rp, $kas_bst_rp,
                    $keterangan
                ]);

                // 6. Eksekusi Kedua: Update is_sold HANYA untuk baris yang kita ambil
                if (!empty($ids_terjual)) {
                    $inQuery = implode(',', array_map('intval', $ids_terjual));
                    $this->db->query("UPDATE setoran SET is_sold = 1 WHERE id IN ($inQuery)");
                }

                $this->db->commit();
                $_SESSION['success'] = "Berhasil! Penjualan manual seberat " . number_format($total_kg, 0, ',', '.') . " KG tercatat dan laba berhasil dibagikan.";
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Gagal memproses penjualan: " . $e->getMessage();
            }

            header('Location: ' . BASE_URL . '/penjualan');
            exit;
        }
    }

    // =================================================================
    // 4. PROSES BATAL / HAPUS PENJUALAN (RESTORE STOK)
    // =================================================================
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
            $id = (int)$_POST['id'];

            try {
                $this->db->beginTransaction();

                $stmtCek = $this->db->prepare("SELECT kategori_id, tanggal_jual FROM penjualan WHERE id = ? FOR UPDATE");
                $stmtCek->execute([$id]);
                $penjualan = $stmtCek->fetch();

                if (!$penjualan) {
                    throw new Exception("Data penjualan tidak ditemukan.");
                }

                $stmtDel = $this->db->prepare("DELETE FROM penjualan WHERE id = ?");
                $stmtDel->execute([$id]);

                $stmtRestore = $this->db->prepare("UPDATE setoran SET is_sold = 0 WHERE kategori_id = ? AND is_sold = 1 AND created_at <= ?");
                $stmtRestore->execute([$penjualan['kategori_id'], $penjualan['tanggal_jual']]);

                $this->db->commit();
                $_SESSION['success'] = "Penjualan dibatalkan. Stok dikembalikan ke gudang.";

            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Gagal membatalkan penjualan: " . $e->getMessage();
            }
            
            header('Location: ' . BASE_URL . '/penjualan');
            exit;
        }
    }
}