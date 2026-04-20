<?php
// app/Models/Setoran.php
require_once __DIR__ . '/../Core/Database.php';

class Setoran {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getSetoranSiswa($limit = 50) {
        $sql = "SELECT s.*, u.nama AS nama_siswa, k.nama_kelas, kat.nama_sampah 
                FROM setoran s 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN kelas k ON u.kelas_id = k.id 
                JOIN kategori_sampah kat ON s.kategori_id = kat.id 
                WHERE u.role = 'siswa' 
                ORDER BY s.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSetoranGuru($limit = 50) {
        $sql = "SELECT s.*, u.nama AS nama_guru, kat.nama_sampah 
                FROM setoran s 
                JOIN users u ON s.user_id = u.id 
                JOIN kategori_sampah kat ON s.kategori_id = kat.id 
                WHERE u.role = 'guru' 
                ORDER BY s.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Ambil data detail satu setoran
    public function getById($id) {
        $sql = "SELECT s.*, u.nama, u.role FROM setoran s JOIN users u ON s.user_id = u.id WHERE s.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getPending() {
        $sql = "SELECT s.*, u.nama AS nama_siswa, k.nama_kelas, kat.nama_sampah 
                FROM setoran s 
                JOIN users u ON s.user_id = u.id 
                LEFT JOIN kelas k ON u.kelas_id = k.id 
                JOIN kategori_sampah kat ON s.kategori_id = kat.id 
                WHERE s.status = 'pending' 
                ORDER BY s.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE setoran SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    // Update data setoran (koreksi)
    public function update($id, $data) {
        $sql = "UPDATE setoran SET 
                kategori_id = :kategori_id, 
                berat = :berat, 
                total_harga = :total_harga, 
                total_pengepul = :total_pengepul 
                WHERE id = :id AND status = 'pending'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array_merge($data, ['id' => $id]));
    }

    public function create($data) {
        $sql = "INSERT INTO setoran (user_id, kategori_id, berat, total_harga, total_pengepul, walikelas_id, status) 
                VALUES (:user_id, :kategori_id, :berat, :total_harga, :total_pengepul, :walikelas_id, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM setoran WHERE id = :id AND status = 'pending'");
        return $stmt->execute(['id' => $id]);
    }
}