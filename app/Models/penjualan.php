<?php
// app/Models/Penjualan.php
require_once __DIR__ . '/../Core/Database.php';

class Penjualan {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Ambil daftar riwayat penjualan
    public function getAll() {
        $sql = "SELECT p.*, k.nama_sampah 
                FROM penjualan p 
                JOIN kategori_sampah k ON p.kategori_id = k.id 
                ORDER BY p.tanggal_jual DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Cek stok sampah yang siap jual (Valid & is_sold = 0)
    public function getReadyStock($kategori_id = null) {
        $sql = "SELECT kategori_id, SUM(berat) as total_stok 
                FROM setoran 
                WHERE status = 'valid' AND is_sold = 0";
        
        if ($kategori_id) {
            $sql .= " AND kategori_id = :kat_id GROUP BY kategori_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['kat_id' => $kategori_id]);
            return $stmt->fetch();
        }

        $sql .= " GROUP BY kategori_id";
        return $this->db->query($sql)->fetchAll();
    }

    // Simpan penjualan dan tandai setoran sebagai terjual
    public function create($data) {
        try {
            $this->db->beginTransaction();

            // 1. Simpan ke tabel penjualan
            $sql = "INSERT INTO penjualan (kategori_id, total_berat, harga_per_kg, total_pendapatan, keterangan) 
                    VALUES (:kategori_id, :total_berat, :harga_per_kg, :total_pendapatan, :keterangan)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);

            // 2. Update status setoran menjadi is_sold = 1 untuk kategori tersebut
            $update = "UPDATE setoran SET is_sold = 1 
                       WHERE kategori_id = :kategori_id AND status = 'valid' AND is_sold = 0";
            $stmtUpdate = $this->db->prepare($update);
            $stmtUpdate->execute(['kategori_id' => $data['kategori_id']]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}