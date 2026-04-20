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

    // Tampilkan Riwayat Penjualan
    public function index() {
        $sql = "SELECT p.*, k.nama_sampah 
                FROM penjualan p 
                JOIN kategori_sampah k ON p.kategori_id = k.id 
                ORDER BY p.tanggal_jual DESC";
        $riwayat = $this->db->query($sql)->fetchAll();

        $title = "Riwayat Penjualan Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // Form Jual Dinamis (Nego & Jumlah)
    public function create() {
        // Ambil stok gudang per kategori yang valid dan belum dijual
        $sql = "SELECT k.id, k.nama_sampah, k.harga_pengepul as harga_estimasi,
                       IFNULL(SUM(s.berat), 0) as stok_pcs
                FROM kategori_sampah k
                LEFT JOIN setoran s ON k.id = s.kategori_id AND s.status = 'valid' AND s.is_sold = 0
                GROUP BY k.id HAVING stok_pcs > 0";
        $kategori = $this->db->query($sql)->fetchAll();

        $title = "Proses Jual ke Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // Proses Simpan Penjualan Dinamis
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kategori_id = $_POST['kategori_id'];
            $jumlah_jual = (float)$_POST['total_berat']; // Pcs yang mau dijual
            $harga_nego = (float)$_POST['harga_per_kg'];  // Harga hasil nego
            $keterangan = $_POST['keterangan'] ?? '';

            try {
                $this->db->beginTransaction();

                // 1. Catat ke Tabel Penjualan
                $total_pendapatan = $jumlah_jual * $harga_nego;
                $sqlJual = "INSERT INTO penjualan (kategori_id, total_berat, harga_per_kg, total_pendapatan, keterangan) 
                            VALUES (?, ?, ?, ?, ?)";
                $this->db->prepare($sqlJual)->execute([$kategori_id, $jumlah_jual, $harga_nego, $total_pendapatan, $keterangan]);

                // 2. Logika Update Setoran (FIFO)
                // Ambil semua setoran belum terjual untuk kategori ini, dari yang paling lama
                $stmtSetoran = $this->db->prepare("SELECT * FROM setoran WHERE kategori_id = ? AND status = 'valid' AND is_sold = 0 ORDER BY created_at ASC");
                $stmtSetoran->execute([$kategori_id]);
                $list_setoran = $stmtSetoran->fetchAll();

                $remaining = $jumlah_jual;
                foreach ($list_setoran as $s) {
                    if ($remaining <= 0) break;

                    if ($s['berat'] <= $remaining) {
                        // Jika seluruh setoran ini terjual
                        $this->db->prepare("UPDATE setoran SET is_sold = 1 WHERE id = ?")->execute([$s['id']]);
                        $remaining -= $s['berat'];
                    } else {
                        // Jika hanya sebagian setoran ini yang terjual (Split Record)
                        $berat_sisa = $s['berat'] - $remaining;
                        
                        // Update record lama jadi sisa stok
                        $proporsi_harga = ($berat_sisa / $s['berat']) * $s['total_harga'];
                        $proporsi_pengepul = ($berat_sisa / $s['berat']) * $s['total_pengepul'];
                        
                        $this->db->prepare("UPDATE setoran SET berat = ?, total_harga = ?, total_pengepul = ? WHERE id = ?")
                                 ->execute([$berat_sisa, $proporsi_harga, $proporsi_pengepul, $s['id']]);

                        // Buat record baru untuk bagian yang sudah terjual
                        $berat_jual = $remaining;
                        $proporsi_harga_jual = ($berat_jual / $s['berat']) * $s['total_harga'];
                        $proporsi_pengepul_jual = ($berat_jual / $s['berat']) * $s['total_pengepul'];

                        $sqlSplit = "INSERT INTO setoran (user_id, walikelas_id, kategori_id, berat, total_harga, total_pengepul, status, is_sold, created_at) 
                                     VALUES (?, ?, ?, ?, ?, ?, 'valid', 1, ?)";
                        $this->db->prepare($sqlSplit)->execute([
                            $s['user_id'], $s['walikelas_id'], $s['kategori_id'], 
                            $berat_jual, $proporsi_harga_jual, $proporsi_pengepul_jual, $s['created_at']
                        ]);

                        $remaining = 0;
                    }
                }

                $this->db->commit();
                $_SESSION['success'] = "Penjualan berhasil dicatat! Pendapatan: Rp " . number_format($total_pendapatan, 0, ',', '.');
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Gagal memproses penjualan: " . $e->getMessage();
            }
            header('Location: ' . BASE_URL . '/penjualan');
            exit;
        }
    }
}