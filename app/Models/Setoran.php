<?php
// app/Models/Setoran.php
require_once __DIR__ . '/../Core/Database.php';

class Setoran {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Mengambil riwayat setoran khusus Siswa
    public function getSetoranSiswa() {
        $sql = "SELECT s.*, u.nama AS nama_siswa, u.angkatan, k.nama_kelas, kat.nama_sampah 
                FROM setoran s 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN kelas k ON u.kelas_id = k.id 
                JOIN kategori_sampah kat ON s.kategori_id = kat.id 
                WHERE u.role = 'siswa' 
                ORDER BY s.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menyimpan transaksi baru
    public function create($data) {
        $sql = "INSERT INTO setoran (user_id, kategori_id, berat, total_harga, total_pengepul, walikelas_id, status) 
                VALUES (:user_id, :kategori_id, :berat, :total_harga, :total_pengepul, :walikelas_id, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}