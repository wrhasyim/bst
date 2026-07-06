<?php
// app/Controllers/KasController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class KasController {
    private $db;

    public function __construct() {
        // 🛡️ Satpam URL: Hanya Admin dan Staff yang boleh mengakses menu Kas
        Security::requireRole(['admin', 'staff']);
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $data = []; // Wadah untuk data View

        $sql = "SELECT k.*, u.nama as admin_nama 
                FROM kas_manual k 
                JOIN users u ON k.user_id = u.id 
                ORDER BY k.tanggal DESC, k.created_at DESC";
        $data['data_kas'] = $this->db->query($sql)->fetchAll();

        // RENDER TAMPILAN DENGAN LAYOUT UNIVERSAL
        extract($data);
        $title = "Pencatatan Kas Manual";
        $content = __DIR__ . '/../../views/admin/kas_manual/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Proteksi Keamanan Form

            $tanggal = $_POST['tanggal'];
            $jenis = $_POST['jenis'];
            $nominal = (float) $_POST['nominal'];
            $keterangan = $_POST['keterangan'];
            $user_id = $_SESSION['user_id'];

            if ($nominal <= 0) {
                $_SESSION['error'] = "Nominal tidak valid!";
            } else {
                $sql = "INSERT INTO kas_manual (user_id, tanggal, jenis, nominal, keterangan) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                if($stmt->execute([$user_id, $tanggal, $jenis, $nominal, $keterangan])) {
                    $_SESSION['success'] = "Data Kas Manual berhasil dicatat!";
                } else {
                    $_SESSION['error'] = "Gagal mencatat kas manual.";
                }
            }
            
            header('Location: ' . BASE_URL . '/kas');
            exit;
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $stmt = $this->db->prepare("DELETE FROM kas_manual WHERE id = ?");
            if($stmt->execute([$_GET['id']])) {
                $_SESSION['success'] = "Catatan Kas Manual dibatalkan/dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus data.";
            }
        }
        
        header('Location: ' . BASE_URL . '/kas');
        exit;
    }
}
?>