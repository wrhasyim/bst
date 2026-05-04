<?php
// app/Core/Logger.php
require_once __DIR__ . '/Database.php';

class Logger {
    public static function log($activity, $description = null) {
        $db = Database::getInstance()->getConnection();
        $user_id = $_SESSION['user_id'] ?? 0;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $sql = "INSERT INTO activity_logs (user_id, activity, description, ip_address) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id, $activity, $description, $ip]);
    }
}