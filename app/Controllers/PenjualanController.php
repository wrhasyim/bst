<?php
// app/Controllers/PenjualanController.php
require_once __DIR__ . '/../Core/Database.php';

class PenjualanController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // --- 1. TAMPILKAN STOK & RIWAYAT ---
    public function index() {
        // Ambil stok gudang: Sampah Valid yang BELUM DIJUAL (is_sold = 0)
        $sqlStock = "SELECT k.id as kategori_id, k.nama_sampah, k.harga_pengepul,
                            SUM(s.berat) as total_pcs,
                            SUM(s.total_pengepul) as estimasi_pendapatan
                     FROM kategori_sampah k
                     JOIN setoran s ON k.id = s.kategori_id
                     WHERE s.status = 'valid' AND s.is_sold = 0
                     GROUP BY k.id, k.nama_sampah, k.harga_pengepul
                     HAVING total_pcs > 0";
        $stok = $this->db->query($sqlStock)->fetchAll();

        // Ambil riwayat penjualan sebelumnya
        $sqlHistory = "SELECT p.*, k.nama_sampah 
                       FROM penjualan p 
                       JOIN kategori_sampah k ON p.kategori_id = k.id 
                       ORDER BY p.tanggal_jual DESC LIMIT 20";
        $riwayat = $this->db->query($sqlHistory)->fetchAll();

        $title = "Penjualan Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- 2. PROSES PENJUALAN ---
    public function jual() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kategori_id = $_POST['kategori_id'];

            try {
                // Kunci database untuk transaksi aman
                $this->db->beginTransaction();

                // 1. Hitung total Pcs dan Uang dari setoran yang ada
                $stmtCalc = $this->db->prepare("SELECT SUM(berat) as total_pcs, SUM(total_pengepul) as total_rp 
                                                FROM setoran 
                                                WHERE kategori_id = ? AND status = 'valid' AND is_sold = 0");
                $stmtCalc->execute([$kategori_id]);
                $data = $stmtCalc->fetch();

                if (!$data['total_pcs']) {
                    throw new Exception("Tidak ada stok valid untuk dijual pada kategori ini.");
                }

                // 2. Catat ke tabel Penjualan
                $stmtJual = $this->db->prepare("INSERT INTO penjualan (kategori_id, total_berat, total_pendapatan) VALUES (?, ?, ?)");
                $stmtJual->execute([$kategori_id, $data['total_pcs'], $data['total_rp']]);

                // 3. Update status Setoran menjadi "TERJUAL" (is_sold = 1)
                $stmtUpdate = $this->db->prepare("UPDATE setoran SET is_sold = 1 WHERE kategori_id = ? AND status = 'valid' AND is_sold = 0");
                $stmtUpdate->execute([$kategori_id]);

                // Eksekusi semua perintah
                $this->db->commit();
                $_SESSION['success'] = "Berhasil menjual " . number_format($data['total_pcs'], 0) . " Pcs ke Pengepul!";
                
            } catch (Exception $e) {
                // Jika gagal, batalkan semua perintah database
                $this->db->rollBack();
                $_SESSION['error'] = "Gagal menjual: " . $e->getMessage();
            }
            header('Location: ' . BASE_URL . '/penjualan');
            exit;
        }
    }
}