<?php
// app/Controllers/DashboardController.php
class DashboardController {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    public function index() {
        $title = "Dashboard Utama";
        $content = __DIR__ . '/../../views/admin/dashboard.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}