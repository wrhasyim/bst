<?php
// app/Controllers/KasController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class KasController {
    private $db;

    public function __construct() {
        Security::requireRole(['admin', 'staff']);
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $data = [];

        $sql = "SELECT k.*, u.nama as admin_nama 
                FROM kas_manual k 
                JOIN users u ON k.user_id = u.id 
                ORDER BY k.tanggal DESC, k.created_at DESC";
        $data['data_kas'] = $this->db->query($sql)->fetchAll();

        extract($data);
        $title = "Pencatatan Kas Manual";
        $content = __DIR__ . '/../../views/admin/kas_manual/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf();

            $tanggal = $_POST['tanggal'];
            $jenis = $_POST['jenis'];
            $sumber_kas = $_POST['sumber_kas'] ?? 'kas_besar';
            $nominal = (float) $_POST['nominal'];
            $keterangan = $_POST['keterangan'];
            $user_id = $_SESSION['user_id'];

            if ($nominal <= 0) {
                $_SESSION['error'] = "Nominal tidak valid!";
            } else {
                // ✨ LOGIKA VALIDASI SALDO (Hanya jika pengeluaran)
                if ($jenis === 'pengeluaran') {
                    // Hitung total masuk vs total keluar untuk sumber kas tersebut
                    $masuk = $this->db->query("SELECT IFNULL(SUM(nominal),0) FROM kas_manual WHERE jenis = 'pemasukan' AND sumber_kas = '$sumber_kas'")->fetchColumn();
                    $keluar = $this->db->query("SELECT IFNULL(SUM(nominal),0) FROM kas_manual WHERE jenis = 'pengeluaran' AND sumber_kas = '$sumber_kas'")->fetchColumn();
                    $saldo = $masuk - $keluar;

                    if ($nominal > $saldo) {
                        $_SESSION['error'] = "Gagal! Saldo " . strtoupper(str_replace('_', ' ', $sumber_kas)) . " tidak mencukupi. (Sisa: Rp " . number_format($saldo, 0, ',', '.') . ")";
                        header('Location: ' . BASE_URL . '/kas');
                        exit;
                    }
                }

                $sql = "INSERT INTO kas_manual (user_id, tanggal, jenis, sumber_kas, nominal, keterangan) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                if($stmt->execute([$user_id, $tanggal, $jenis, $sumber_kas, $nominal, $keterangan])) {
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