<?php
// app/Core/Logger.php
require_once __DIR__ . '/Database.php';

class Logger {
    public static function log($activity, $description = null) {
        $db = Database::getInstance()->getConnection();
        $user_id = $_SESSION['user_id'] ?? 0;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // 1. Eksekusi INSERT untuk menyimpan log baru
        $sqlInsert = "INSERT INTO activity_logs (user_id, activity, description, ip_address) VALUES (?, ?, ?, ?)";
        $stmtInsert = $db->prepare($sqlInsert);
        $stmtInsert->execute([$user_id, $activity, $description, $ip]);

        // 2. Eksekusi DELETE (Lazy Cleanup) untuk membuang log usang
        // Mengamankan 500 data terbaru dan menghapus sisanya
        $sqlDelete = "DELETE FROM activity_logs 
                      WHERE id NOT IN (
                          SELECT id FROM (
                              SELECT id FROM activity_logs ORDER BY id DESC LIMIT 500
                          ) temp_table
                      )";
        $stmtDelete = $db->prepare($sqlDelete);
        $stmtDelete->execute();
    }
}