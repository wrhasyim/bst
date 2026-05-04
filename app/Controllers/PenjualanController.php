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
        // Query pintar: Hanya memunculkan kategori sampah yang memiliki stok 'is_sold = 0'
        $sql = "SELECT k.id, k.nama_sampah, k.harga_pengepul, k.satuan, IFNULL(SUM(s.berat), 0) as stok_tersedia
                FROM kategori_sampah k
                LEFT JOIN setoran s ON k.id = s.kategori_id AND s.status = 'valid' AND s.is_sold = 0
                WHERE k.nama_sampah != '🌟 REWARD PRESTASI'
                GROUP BY k.id
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
            $kategori_id  = (int)$_POST['kategori_id'];
            $harga_per_kg = (float)$_POST['harga_per_kg'];
            $keterangan   = htmlspecialchars($_POST['keterangan'] ?? 'Penjualan ke Pengepul (Rutin)');

            try {
                // 🔐 START TRANSACTION: Kunci database untuk operasi relasional ini
                $this->db->beginTransaction();

                // 1. Lock & Hitung Stok Tersedia (FOR UPDATE mencegah Race Condition dari klik ganda)
                $stmtStok = $this->db->prepare("SELECT IFNULL(SUM(berat), 0) as total_berat FROM setoran WHERE kategori_id = ? AND status = 'valid' AND is_sold = 0 FOR UPDATE");
                $stmtStok->execute([$kategori_id]);
                $stok = $stmtStok->fetch();
                $total_berat = $stok['total_berat'];

                if ($total_berat <= 0) {
                    throw new Exception("Stok untuk kategori sampah ini kosong atau baru saja terjual oleh admin lain.");
                }

                $total_pendapatan = $total_berat * $harga_per_kg;

                // 2. Eksekusi Pertama: Catat di tabel Penjualan
                $stmtInsert = $this->db->prepare("INSERT INTO penjualan (kategori_id, total_berat, harga_per_kg, total_pendapatan, tanggal_jual, keterangan) VALUES (?, ?, ?, ?, NOW(), ?)");
                $stmtInsert->execute([$kategori_id, $total_berat, $harga_per_kg, $total_pendapatan, $keterangan]);

                // 3. Eksekusi Kedua: Update status barang di tabel Setoran (Tandai Terjual)
                $stmtUpdate = $this->db->prepare("UPDATE setoran SET is_sold = 1 WHERE kategori_id = ? AND status = 'valid' AND is_sold = 0");
                $stmtUpdate->execute([$kategori_id]);

                // ✅ COMMIT TRANSACTION: Jika kedua eksekusi di atas sukses, simpan data permanen ke database
                $this->db->commit();

                $_SESSION['success'] = "Berhasil! Stok {$total_berat} terjual dengan pendapatan Rp " . number_format($total_pendapatan, 0, ',', '.');
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