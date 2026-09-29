<?php
// app/Models/Kelas.php
require_once __DIR__ . '/../Core/Database.php';

class Kelas {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Ambil semua data kelas beserta nama Wali Kelas dan Total Siswa
    public function getAll() {
        $sql = "SELECT k.*, u.nama AS nama_wali, 
               (SELECT COUNT(id) FROM users WHERE kelas_id = k.id AND role = 'siswa') AS total_siswa 
               FROM kelas k 
               LEFT JOIN users u ON k.walikelas_id = u.id 
               ORDER BY k.nama_kelas ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kelas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO kelas (nama_kelas, walikelas_id) VALUES (:nama_kelas, :walikelas_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE kelas SET nama_kelas = :nama_kelas, walikelas_id = :walikelas_id WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM kelas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}