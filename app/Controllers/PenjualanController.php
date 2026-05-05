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
        // 🛠️ BUG FIX ULTIMATE: Menggunakan Sub-Query (SELECT di dalam SELECT)
        // Solusi ampuh menembus blokir 'ONLY_FULL_GROUP_BY' Strict Mode MySQL.
        // Memastikan barang valid bernilai '0' atau 'NULL' terbaca dengan sempurna.
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
    // 3. PROSES SIMPAN DENGAN DATABASE TRANSACTIONS (ACID COMPLIANCE)
    // =================================================================
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff')) {
            $kategori_id   = (int)$_POST['kategori_id'];
            
            // 🛠️ CRITICAL RULE APPLIED: Ganti dari harga_per_kg menjadi harga_per_pcs
            $harga_per_pcs = (float)$_POST['harga_per_pcs'];
            $keterangan    = htmlspecialchars($_POST['keterangan'] ?? 'Penjualan ke Pengepul (Rutin)');

            try {
                // 🔐 START TRANSACTION: Kunci database untuk operasi relasional ini
                $this->db->beginTransaction();

                // 1. Lock & Hitung Stok Tersedia (FOR UPDATE mencegah Race Condition dari klik ganda)
                // 🛠️ CRITICAL RULE APPLIED: Alias SUM(berat) menjadi total_pcs
                $stmtStok = $this->db->prepare("SELECT IFNULL(SUM(berat), 0) as total_pcs FROM setoran WHERE kategori_id = ? AND status = 'valid' AND (is_sold = 0 OR is_sold IS NULL) FOR UPDATE");
                $stmtStok->execute([$kategori_id]);
                $stok = $stmtStok->fetch();
                $total_pcs = $stok['total_pcs'];

                if ($total_pcs <= 0) {
                    throw new Exception("Stok untuk kategori sampah ini kosong atau baru saja terjual oleh admin lain.");
                }

                // 🛠️ CRITICAL RULE APPLIED: Kalkulasi menggunakan pcs
                $total_pendapatan = $total_pcs * $harga_per_pcs;

                // 2. Eksekusi Pertama: Catat di tabel Penjualan
                // 🛠️ CRITICAL RULE APPLIED: Insert menggunakan kolom tabel yang baru (total_pcs, harga_per_pcs)
                $stmtInsert = $this->db->prepare("INSERT INTO penjualan (kategori_id, total_pcs, harga_per_pcs, total_pendapatan, tanggal_jual, keterangan) VALUES (?, ?, ?, ?, NOW(), ?)");
                $stmtInsert->execute([$kategori_id, $total_pcs, $harga_per_pcs, $total_pendapatan, $keterangan]);

                // 3. Eksekusi Kedua: Update status barang di tabel Setoran (Tandai Terjual)
                $stmtUpdate = $this->db->prepare("UPDATE setoran SET is_sold = 1 WHERE kategori_id = ? AND status = 'valid' AND (is_sold = 0 OR is_sold IS NULL)");
                $stmtUpdate->execute([$kategori_id]);

                // ✅ COMMIT TRANSACTION: Jika kedua eksekusi di atas sukses, simpan data permanen ke database
                $this->db->commit();

                // 🛠️ CRITICAL RULE APPLIED: Pesan sukses menggunakan Pcs
                $_SESSION['success'] = "Berhasil! Stok {$total_pcs} Pcs terjual dengan pendapatan Rp " . number_format($total_pendapatan, 0, ',', '.');
            } catch (Exception $e) {
                // ❌ ROLLBACK TRANSACTION: Jika ada SATU SAJA yang gagal, batalkan semuanya! (Uang & Barang aman)
                $this->db->rollBack();
                $_SESSION['error'] = "Transaksi Dibatalkan Otomatis oleh Sistem: " . $e->getMessage();
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
                // 🔐 START TRANSACTION
                $this->db->beginTransaction();

                // 1. Ambil info penjualan yang akan dibatalkan
                $stmtCek = $this->db->prepare("SELECT kategori_id, tanggal_jual FROM penjualan WHERE id = ? FOR UPDATE");
                $stmtCek->execute([$id]);
                $penjualan = $stmtCek->fetch();

                if (!$penjualan) {
                    throw new Exception("Data penjualan tidak ditemukan.");
                }

                // 2. Eksekusi Pertama: Hapus histori uang masuk dari tabel penjualan
                $stmtDel = $this->db->prepare("DELETE FROM penjualan WHERE id = ?");
                $stmtDel->execute([$id]);

                // 3. Eksekusi Kedua: RESTORE stok setoran menjadi is_sold = 0 (Dikembalikan ke gudang)
                // Logika aman: Hanya pulihkan setoran yang tanggal masuknya sebelum/sama dengan waktu penjualan
                $stmtRestore = $this->db->prepare("UPDATE setoran SET is_sold = 0 WHERE kategori_id = ? AND is_sold = 1 AND created_at <= ?");
                $stmtRestore->execute([$penjualan['kategori_id'], $penjualan['tanggal_jual']]);

                // ✅ COMMIT
                $this->db->commit();
                $_SESSION['success'] = "Penjualan berhasil dibatalkan. Stok barang telah dikembalikan ke gudang.";

            } catch (Exception $e) {
                // ❌ ROLLBACK
                $this->db->rollBack();
                $_SESSION['error'] = "Gagal membatalkan penjualan: " . $e->getMessage();
            }
            
            header('Location: ' . BASE_URL . '/penjualan');
            exit;
        }
    }
}