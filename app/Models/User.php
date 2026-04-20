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
}