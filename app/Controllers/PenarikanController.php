<?php
// app/Controllers/PenarikanController.php
require_once __DIR__ . '/../Core/Database.php';

class PenarikanController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'staff'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // TAMPILKAN HALAMAN PENARIKAN MASSAL
    public function index() {
        $kelas_id = $_GET['kelas_id'] ?? null;
        $all_kelas = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $siswa_list = [];
        if ($kelas_id) {
            // Query Cerdas: Hitung Saldo Bersih per Siswa (Total Setoran Valid - Total Penarikan)
            $sql = "SELECT u.id, u.nama, 
                    (SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE user_id = u.id AND status = 'valid') - 
                    (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE user_id = u.id) as saldo_tersedia
                    FROM users u 
                    WHERE u.kelas_id = :kid AND u.role = 'siswa' AND u.is_active = 1 
                    ORDER BY u.nama ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['kid' => $kelas_id]);
            $siswa_list = $stmt->fetchAll();
        }

        // Riwayat Penarikan Global (20 Terakhir)
        $riwayat = $this->db->query("SELECT p.*, u.nama, k.nama_kelas 
                                    FROM penarikan p 
                                    JOIN users u ON p.user_id = u.id 
                                    JOIN kelas k ON u.kelas_id = k.id 
                                    ORDER BY p.tanggal_tarik DESC LIMIT 20")->fetchAll();

        $title = "Penarikan Saldo Massal";
        $content = __DIR__ . '/../../views/admin/penarikan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // PROSES SIMPAN PENARIKAN MASSAL (BATCH)
    public function batch_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $penarikan_data = $_POST['jumlah_tarik']; // Array [user_id => nominal]
            $keterangan_global = $_POST['keterangan'] ?? 'Penarikan Massal';

            try {
                $this->db->beginTransaction();
                $count = 0;

                foreach ($penarikan_data as $uid => $nominal) {
                    $nominal = (float)$nominal;
                    if ($nominal > 0) {
                        // 1. Validasi Saldo (Server Side)
                        $sqlSaldo = "SELECT (SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE user_id = :uid AND status = 'valid') - 
                                            (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE user_id = :uid) as saldo";
                        $stmtS = $this->db->prepare($sqlSaldo);
                        $stmtS->execute(['uid' => $uid]);
                        $saldo_aktif = $stmtS->fetchColumn();

                        if ($nominal > $saldo_aktif) {
                            throw new Exception("Saldo salah satu siswa tidak mencukupi!");
                        }

                        // 2. Insert Data Penarikan
                        $stmtI = $this->db->prepare("INSERT INTO penarikan (user_id, jumlah, keterangan) VALUES (?, ?, ?)");
                        $stmtI->execute([$uid, $nominal, $keterangan_global]);
                        $count++;
                    }
                }

                $this->db->commit();
                $_SESSION['success'] = "Berhasil memproses penarikan untuk $count nasabah.";
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Gagal memproses penarikan: " . $e->getMessage();
            }
        }
        header('Location: ' . BASE_URL . '/penarikan?kelas_id=' . ($_POST['kelas_id'] ?? ''));
        exit;
    }
}