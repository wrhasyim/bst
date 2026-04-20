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

    // =================================================================
    // 1. TAMPILAN HALAMAN PENARIKAN MASSAL PER KELAS
    // =================================================================
    public function index() {
        $kelas_id = $_GET['kelas_id'] ?? null;
        $all_kelas = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        $siswa_list = [];
        $total_saldo_kelas = 0; // Untuk summary kasir

        if ($kelas_id) {
            // Hitung Saldo Bersih per Siswa (Total Setoran Valid - Total Penarikan)
            $sql = "SELECT u.id, u.nama, u.username,
                    (SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE user_id = u.id AND status = 'valid') - 
                    (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE user_id = u.id) as saldo_tersedia
                    FROM users u 
                    WHERE u.kelas_id = :kid AND u.role = 'siswa' AND u.is_active = 1 
                    ORDER BY u.nama ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['kid' => $kelas_id]);
            $siswa_list = $stmt->fetchAll();

            // Hitung total uang yang harus disiapkan Admin untuk kelas ini
            foreach ($siswa_list as $s) {
                if ($s['saldo_tersedia'] > 0) {
                    $total_saldo_kelas += $s['saldo_tersedia'];
                }
            }
        }

        // Riwayat Penarikan Global (20 Terakhir)
        $riwayat = $this->db->query("SELECT p.*, u.nama, k.nama_kelas 
                                    FROM penarikan p 
                                    JOIN users u ON p.user_id = u.id 
                                    JOIN kelas k ON u.kelas_id = k.id 
                                    ORDER BY p.tanggal_tarik DESC LIMIT 20")->fetchAll();

        $title = "Kasir Pencairan Kelas";
        $content = __DIR__ . '/../../views/admin/penarikan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. PROSES PENARIKAN 1 KELAS FULL (AUTO-CALCULATE)
    // =================================================================
    public function batch_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kelas_id = $_POST['kelas_id'] ?? null;
            $keterangan_global = $_POST['keterangan'] ?? 'Pencairan Tabungan Kolektif';

            if (!$kelas_id) {
                $_SESSION['error'] = "Kelas belum dipilih!";
                header('Location: ' . BASE_URL . '/penarikan');
                exit;
            }

            try {
                $this->db->beginTransaction();

                // 1. Ambil seluruh siswa di kelas ini beserta saldonya
                $sqlSaldo = "SELECT u.id,
                             (SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE user_id = u.id AND status = 'valid') - 
                             (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE user_id = u.id) as saldo_aktif
                             FROM users u WHERE u.kelas_id = :kid AND u.role = 'siswa' AND u.is_active = 1";
                
                $stmtS = $this->db->prepare($sqlSaldo);
                $stmtS->execute(['kid' => $kelas_id]);
                $siswa_kelas = $stmtS->fetchAll();

                $count = 0;
                $total_keluar = 0;

                // 2. Tarik semua saldo yang > 0
                foreach ($siswa_kelas as $s) {
                    $saldo = (float)$s['saldo_aktif'];
                    
                    if ($saldo > 0) {
                        $stmtI = $this->db->prepare("INSERT INTO penarikan (user_id, jumlah, keterangan) VALUES (?, ?, ?)");
                        $stmtI->execute([$s['id'], $saldo, $keterangan_global]);
                        
                        $count++;
                        $total_keluar += $saldo;
                    }
                }

                // 3. Konfirmasi
                if ($count > 0) {
                    $this->db->commit();
                    $_SESSION['success'] = "Selesai! Berhasil mencairkan seluruh saldo untuk $count siswa. Uang yang harus diserahkan ke Wali Kelas: Rp" . number_format($total_keluar, 0, ',', '.');
                } else {
                    $this->db->rollBack();
                    $_SESSION['error'] = "Gagal! Semua siswa di kelas ini saldonya Rp0 (Kosong).";
                }

            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Terjadi Kesalahan Sistem: " . $e->getMessage();
            }
        }
        
        header('Location: ' . BASE_URL . '/penarikan?kelas_id=' . ($_POST['kelas_id'] ?? ''));
        exit;
    }
}