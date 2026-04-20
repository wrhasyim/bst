<?php
// app/Models/KategoriSampah.php
require_once __DIR__ . '/../Core/Database.php';

class KategoriSampah {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Ambil semua data
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM kategori_sampah ORDER BY nama_sampah ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Ambil 1 data berdasarkan ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kategori_sampah WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Tambah data baru
    public function create($data) {
        $sql = "INSERT INTO kategori_sampah (nama_sampah, harga_siswa, harga_guru, harga_pengepul) 
                VALUES (:nama_sampah, :harga_siswa, :harga_guru, :harga_pengepul)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // Update data
    public function update($id, $data) {
        $sql = "UPDATE kategori_sampah 
                SET nama_sampah = :nama_sampah, 
                    harga_siswa = :harga_siswa, 
                    harga_guru = :harga_guru, 
                    harga_pengepul = :harga_pengepul 
                WHERE id = :id";
        
        $data['id'] = $id; // Gabungkan ID ke array data
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // Hapus data
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM kategori_sampah WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}