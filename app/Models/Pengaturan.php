<?php
// app/Models/Pengaturan.php
require_once __DIR__ . '/../Core/Database.php';

class Pengaturan {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllSettings() {
        $stmt = $this->db->query("SELECT kunci, nilai FROM pengaturan");
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[$row['kunci']] = $row['nilai'];
        }
        return $result;
    }

    public function updateSettings($data) {
        $sql = "UPDATE pengaturan SET nilai = :nilai WHERE kunci = :kunci";
        $stmt = $this->db->prepare($sql);
        
        try {
            $this->db->beginTransaction();
            foreach ($data as $kunci => $nilai) {
                $stmt->execute([
                    'nilai' => $nilai,
                    'kunci' => $kunci
                ]);
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}