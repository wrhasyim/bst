<?php
// app/Models/User.php
require_once __DIR__ . '/../Core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username AND is_active = 1 LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function getAll() {
        $sql = "SELECT u.*, k.nama_kelas 
                FROM users u 
                LEFT JOIN kelas k ON u.kelas_id = k.id 
                ORDER BY u.role ASC, u.nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO users (nama, username, password, role, kelas_id, angkatan, is_active) 
                VALUES (:nama, :username, :password, :role, :kelas_id, :angkatan, :is_active)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        // Query dinamis: Jika password diisi, update passwordnya. Jika kosong, biarkan yang lama.
        $sql = "UPDATE users SET nama = :nama, username = :username, role = :role, 
                kelas_id = :kelas_id, angkatan = :angkatan, is_active = :is_active ";
        
        if (!empty($data['password'])) {
            $sql .= ", password = :password ";
        } else {
            unset($data['password']); // Hapus dari array agar tidak error binding
        }
        
        $sql .= "WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}