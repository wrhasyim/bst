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
        $sql = "SELECT p.*, k.nama_sampah, k.satuan
                FROM penjualan p
                JOIN kategori_sampah k ON p.kategori_id = k.id
                ORDER BY p.tanggal_jual DESC";
        $penjualan = $this->db->query($sql)->fetchAll();

        $title = "Data Penjualan Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. FORM INPUT PENJUALAN (Filter Kategori Bersaldo Saja)
    // =================================================================
    public function create() {
        $sql = "SELECT k.id, k.nama_sampah, k.harga_pengepul, k.satuan,
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

        $title = "Input Penjualan Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 3. PROSES SIMPAN DENGAN IMMUTABLE SNAPSHOTTING (ACID COMPLIANCE)
    // =================================================================
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff')) {
            $kategori_id   = (int)$_POST['kategori_id'];
            $harga_per_pcs = (float)$_POST['harga_per_pcs'];
            $keterangan    = htmlspecialchars($_POST['keterangan'] ?? 'Penjualan ke Pengepul (Rutin)');

            try {
                $this->db->beginTransaction();

                // 1. Ambil Stok, HPP (Beban Nasabah), dan Snapshot Honor Walas dari tabel Setoran
                // Snapshot Walas yang sudah ada di setoran harus kita hitung agar jatah Kas BST akurat.
                $stmtStok = $this->db->prepare("
                    SELECT 
                        IFNULL(SUM(berat), 0) as total_pcs, 
                        IFNULL(SUM(total_harga), 0) as total_hpp,
                        IFNULL(SUM(honor_walas_rp), 0) as total_walas_snapshot
                    FROM setoran 
                    WHERE kategori_id = ? 
                      AND status = 'valid' 
                      AND (is_sold = 0 OR is_sold IS NULL) 
                    FOR UPDATE
                ");
                $stmtStok->execute([$kategori_id]);
                $stok = $stmtStok->fetch();
                
                $total_pcs           = (float)$stok['total_pcs'];
                $beban_nasabah       = (float)$stok['total_hpp'];
                $total_walas_setoran = (float)$stok['total_walas_snapshot'];

                if ($total_pcs <= 0) {
                    throw new Exception("Stok kosong atau sudah terjual oleh admin lain.");
                }

                // 2. Ambil Konfigurasi Persentase Terkini (Detik ini juga)
                $stmtConfig = $this->db->query("SELECT kunci, nilai FROM pengaturan WHERE kunci LIKE 'persen_%'");
                $config = $stmtConfig->fetchAll(PDO::FETCH_KEY_PAIR);
                
                $p_pengelola = (float)($config['persen_honor_pengelola'] ?? 0) / 100;
                $p_sekolah   = (float)($config['persen_kas_sekolah'] ?? 0) / 100;
                $p_piket     = (float)($config['persen_honor_piket'] ?? 0) / 100;

                // 3. Kalkulasi Laba & Distribusi Margin (Snapshotting Process)
                $total_pendapatan = $total_pcs * $harga_per_pcs;
                $margin_total     = $total_pendapatan - $beban_nasabah;
                
                $kas_sekolah_rp      = $margin_total * $p_sekolah;
                $honor_pengelola_rp  = $margin_total * $p_pengelola;
                $honor_piket_rp      = $margin_total * $p_piket;
                
                // Kas BST = Sisa Margin setelah dipotong semua jatah (termasuk Walas yang sudah dikunci di setoran)
                $kas_bst_rp = $margin_total - ($kas_sekolah_rp + $honor_pengelola_rp + $honor_piket_rp + $total_walas_setoran);

                // 4. Eksekusi Pertama: Catat di tabel Penjualan (KUNCI PERMANEN NOMINAL RUPIAH)
                $sqlInsert = "INSERT INTO penjualan (
                                kategori_id, total_pcs, harga_per_pcs, total_pendapatan, 
                                beban_nasabah_rp, margin_total_rp, kas_sekolah_rp, 
                                honor_pengelola_rp, honor_piket_rp, kas_bst_rp, 
                                tanggal_jual, keterangan
                              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
                
                $stmtInsert = $this->db->prepare($sqlInsert);
                $stmtInsert->execute([
                    $kategori_id, $total_pcs, $harga_per_pcs, $total_pendapatan,
                    $beban_nasabah, $margin_total, $kas_sekolah_rp,
                    $honor_pengelola_rp, $honor_piket_rp, $kas_bst_rp,
                    $keterangan
                ]);

                // 5. Eksekusi Kedua: Update status barang di tabel Setoran (Tandai Terjual)
                $stmtUpdate = $this->db->prepare("UPDATE setoran SET is_sold = 1 WHERE kategori_id = ? AND status = 'valid' AND (is_sold = 0 OR is_sold IS NULL)");
                $stmtUpdate->execute([$kategori_id]);

                $this->db->commit();
                $_SESSION['success'] = "Berhasil! Penjualan {$total_pcs} Pcs tercatat. Pembagian margin telah dikunci secara permanen.";
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