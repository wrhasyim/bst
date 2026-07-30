<?php
// app/Controllers/PenarikanController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Logger.php';   // 🛡️ Load Audit Trail
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class PenarikanController {
    private $db;

    public function __construct() {
        // 🛡️ Satpam URL: Hanya Admin dan Staf yang bisa mencairkan uang
        Security::requireRole(['admin', 'staff']);
        $this->db = Database::getInstance()->getConnection();
    }

    // =================================================================
    // 1. TAMPILAN HALAMAN PENARIKAN MASSAL PER KELAS (SISWA)
    // =================================================================
    public function index() {
        $data = []; // Wadah untuk data View
        
        $kelas_id = $_GET['kelas_id'] ?? null;
        $data['kelas_id'] = $kelas_id;
        
        // 🛡️ FIX: Sembunyikan kelas Kesiswaan dari Dropdown
        $data['all_kelas'] = $this->db->query("SELECT * FROM kelas WHERE nama_kelas NOT LIKE '%KESISWAAN%' ORDER BY nama_kelas ASC")->fetchAll();
        
        $data['siswa_list'] = [];
        $data['total_saldo_kelas'] = 0; // Untuk summary kasir

        if ($kelas_id) {
            // 🛡️ FIX: Isolasi ganda, pastikan nama user kesiswaan juga tidak ikut terhitung
            $sql = "SELECT u.id, u.nama, u.username,
                    (SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE user_id = u.id AND status = 'valid') - 
                    (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE user_id = u.id) as saldo_tersedia
                    FROM users u 
                    WHERE u.kelas_id = :kid AND u.role = 'siswa' AND u.is_active = 1 AND u.nama NOT LIKE '%KESISWAAN%'
                    ORDER BY u.nama ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['kid' => $kelas_id]);
            $data['siswa_list'] = $stmt->fetchAll();

            // Hitung total uang yang harus disiapkan Admin untuk kelas ini
            foreach ($data['siswa_list'] as $s) {
                if ($s['saldo_tersedia'] > 0) {
                    $data['total_saldo_kelas'] += $s['saldo_tersedia'];
                }
            }
        }

        // Riwayat Penarikan Global (20 Terakhir)
        $data['riwayat'] = $this->db->query("SELECT p.*, u.nama, k.nama_kelas 
                                    FROM penarikan p 
                                    JOIN users u ON p.user_id = u.id 
                                    LEFT JOIN kelas k ON u.kelas_id = k.id 
                                    ORDER BY p.tanggal_tarik DESC LIMIT 20")->fetchAll();

        // RENDER TAMPILAN DENGAN LAYOUT UNIVERSAL
        extract($data);
        $title = "Kasir Pencairan Kelas";
        $content = __DIR__ . '/../../views/admin/penarikan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. PROSES PENARIKAN 1 KELAS FULL (AUTO-CALCULATE)
    // =================================================================
    public function batch_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Security::validate_csrf(); // 🛡️ Validasi Token Keamanan Form

            $kelas_id = $_POST['kelas_id'] ?? null;
            $keterangan_global = $_POST['keterangan'] ?? 'Pencairan Tabungan Kolektif';
            
            // Tangkap array input nominal yang diketik kasir di form
            $jumlah_tarik_array = $_POST['jumlah_tarik'] ?? [];

            if (!$kelas_id) {
                $_SESSION['error'] = "Kelas belum dipilih!";
                header('Location: ' . BASE_URL . '/penarikan');
                exit;
            }

            try {
                $this->db->beginTransaction();

                // 1. Ambil seluruh siswa di kelas ini beserta saldo maksimalnya untuk validasi keamanan
                $sqlSaldo = "SELECT u.id,
                             (SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE user_id = u.id AND status = 'valid') - 
                             (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE user_id = u.id) as saldo_aktif
                             FROM users u WHERE u.kelas_id = :kid AND u.role = 'siswa' AND u.is_active = 1 AND u.nama NOT LIKE '%KESISWAAN%'";
                
                $stmtS = $this->db->prepare($sqlSaldo);
                $stmtS->execute(['kid' => $kelas_id]);
                $siswa_kelas = $stmtS->fetchAll();

                $count = 0;
                $total_keluar = 0;

                // 2. Eksekusi penarikan berdasarkan input form (bukan asal tarik semua)
                foreach ($siswa_kelas as $s) {
                    $user_id = $s['id'];
                    $saldo_maksimal = (float)$s['saldo_aktif'];
                    
                    // Ambil nominal yang diisi di form, jika kosong anggap 0
                    $nominal_ditarik = isset($jumlah_tarik_array[$user_id]) ? (float)$jumlah_tarik_array[$user_id] : 0;
                    
                    // Validasi: Pastikan nominal ditarik lebih dari 0 dan tidak melebihi saldo maksimal
                    if ($nominal_ditarik > 0 && $nominal_ditarik <= $saldo_maksimal) {
                        $stmtI = $this->db->prepare("INSERT INTO penarikan (user_id, jumlah, keterangan) VALUES (?, ?, ?)");
                        $stmtI->execute([$user_id, $nominal_ditarik, $keterangan_global]);
                        
                        $count++;
                        $total_keluar += $nominal_ditarik;
                    }
                }

                // 3. Konfirmasi & Pencatatan Log
                if ($count > 0) {
                    $this->db->commit();

                    // Ambil nama kelas untuk kebutuhan pencatatan log
                    $stmtK = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE id = ?");
                    $stmtK->execute([$kelas_id]);
                    $nama_kelas = $stmtK->fetchColumn();

                    // 🛡️ Catat aktivitas pengeluaran uang ke Audit Trail
                    Logger::log("Penarikan Saldo", "Kasir mencairkan total dana kelas $nama_kelas sebesar Rp" . number_format($total_keluar, 0, ',', '.') . " untuk $count siswa.");

                    $_SESSION['success'] = "Selesai! Berhasil mencairkan saldo untuk $count siswa. Uang yang harus diserahkan ke Wali Kelas: Rp" . number_format($total_keluar, 0, ',', '.');
                } else {
                    $this->db->rollBack();
                    $_SESSION['error'] = "Gagal! Tidak ada nominal penarikan yang dimasukkan atau nominal tidak valid.";
                }

            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Terjadi Kesalahan Sistem: " . $e->getMessage();
            }
        }
        
        header('Location: ' . BASE_URL . '/penarikan?kelas_id=' . ($_POST['kelas_id'] ?? ''));
        exit;
    }

    // =================================================================
    // 3. PENARIKAN KHUSUS KAS KESISWAAN (DANA OSIS)
    // =================================================================
    public function kesiswaan_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Security::validate_csrf(); // 🛡️ Validasi Token

            $jumlah = (float) $_POST['jumlah'];
            $keterangan = $_POST['keterangan'] ?? 'Penarikan Dana OSIS';

            if ($jumlah <= 0) {
                $_SESSION['error'] = "Nominal penarikan tidak valid!";
                header('Location: ' . BASE_URL . '/laporan/kas_kesiswaan');
                exit;
            }

            try {
                // 1. Cari akun virtual KESISWAAN
                $stmtCek = $this->db->query("SELECT id FROM users WHERE nama LIKE '%KESISWAAN%' AND role = 'siswa' LIMIT 1");
                $akun_kesiswaan = $stmtCek->fetch();

                if (!$akun_kesiswaan) {
                    $_SESSION['error'] = "Gagal! Sistem tidak menemukan Akun Kas Kesiswaan.";
                    header('Location: ' . BASE_URL . '/laporan/kas_kesiswaan');
                    exit;
                }

                $user_id = $akun_kesiswaan['id'];

                // 2. Cek apakah saldo mencukupi
                $total_masuk = $this->db->query("SELECT IFNULL(SUM(total_harga),0) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn();
                $total_tarik = $this->db->query("SELECT IFNULL(SUM(jumlah),0) FROM penarikan WHERE user_id = $user_id")->fetchColumn();
                $saldo = $total_masuk - $total_tarik;

                if ($jumlah > $saldo) {
                    $_SESSION['error'] = "Gagal! Saldo tidak mencukupi. Maksimal penarikan: Rp " . number_format($saldo, 0, ',', '.');
                } else {
                    // 3. Proses input penarikan
                    $stmt = $this->db->prepare("INSERT INTO penarikan (user_id, jumlah, keterangan) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $jumlah, $keterangan]);
                    
                    // 4. Catat aktivitas ke Logger
                    Logger::log("Penarikan Kesiswaan", "Kasir menarik Dana OSIS/Kesiswaan sebesar Rp" . number_format($jumlah, 0, ',', '.'));
                    
                    $_SESSION['success'] = "Berhasil menarik Dana Kesiswaan sebesar Rp " . number_format($jumlah, 0, ',', '.');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Terjadi Kesalahan Sistem: " . $e->getMessage();
            }
        }
        
        // Kembali ke halaman dasbor kesiswaan
        header('Location: ' . BASE_URL . '/laporan/kas_kesiswaan');
        exit;
    }

    // =================================================================
    // 4. TAMPILAN HALAMAN PENARIKAN KHUSUS GURU/STAF
    // =================================================================
    public function guru() {
        $data = [];
        
        // 🛡️ FITUR BARU: Menambahkan klausa HAVING saldo_tersedia > 0 
        // agar hanya guru yang memiliki saldo tabungan yang muncul di form penarikan.
        $sql = "SELECT u.id, u.nama, u.username,
                (SELECT IFNULL(SUM(total_harga), 0) FROM setoran WHERE user_id = u.id AND status = 'valid') - 
                (SELECT IFNULL(SUM(jumlah), 0) FROM penarikan WHERE user_id = u.id) as saldo_tersedia
                FROM users u 
                WHERE u.role != 'siswa' AND u.role != 'admin' AND u.is_active = 1
                HAVING saldo_tersedia > 0
                ORDER BY u.nama ASC";
        
        $data['guru_list'] = $this->db->query($sql)->fetchAll();

        // 🛠️ PERUBAHAN: Ubah LIMIT menjadi 5 agar tabel riwayat tidak terlalu panjang
        $data['riwayat'] = $this->db->query("SELECT p.*, u.nama 
                                    FROM penarikan p 
                                    JOIN users u ON p.user_id = u.id 
                                    WHERE u.role != 'siswa'
                                    ORDER BY p.tanggal_tarik DESC LIMIT 5")->fetchAll();

        // RENDER TAMPILAN
        extract($data);
        $title = "Penarikan Tabungan Guru/Staf";
        $content = __DIR__ . '/../../views/admin/penarikan/guru.php'; 
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 5. PROSES PENARIKAN KHUSUS GURU/STAF
    // =================================================================
    public function guru_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            Security::validate_csrf(); // 🛡️ Validasi Token

            $user_id = $_POST['user_id'] ?? null;
            $jumlah = (float) $_POST['jumlah'];
            $keterangan = $_POST['keterangan'] ?? 'Pencairan Tabungan Guru/Staf';

            if (!$user_id || $jumlah <= 0) {
                $_SESSION['error'] = "Data tidak lengkap atau nominal tidak valid!";
                header('Location: ' . BASE_URL . '/penarikan/guru');
                exit;
            }

            try {
                // 1. Hitung sisa saldo guru tersebut
                $total_masuk = $this->db->query("SELECT IFNULL(SUM(total_harga),0) FROM setoran WHERE user_id = $user_id AND status = 'valid'")->fetchColumn();
                $total_tarik = $this->db->query("SELECT IFNULL(SUM(jumlah),0) FROM penarikan WHERE user_id = $user_id")->fetchColumn();
                $saldo = $total_masuk - $total_tarik;

                // 2. Cek apakah uang yang ditarik melebihi saldo
                if ($jumlah > $saldo) {
                    $_SESSION['error'] = "Gagal! Saldo tidak mencukupi. Saldo maksimal yang bisa ditarik: Rp " . number_format($saldo, 0, ',', '.');
                } else {
                    // 3. Proses input penarikan
                    $stmt = $this->db->prepare("INSERT INTO penarikan (user_id, jumlah, keterangan) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $jumlah, $keterangan]);
                    
                    // Ambil nama guru untuk pencatatan log (Audit Trail)
                    $stmtN = $this->db->prepare("SELECT nama FROM users WHERE id = ?");
                    $stmtN->execute([$user_id]);
                    $nama_guru = $stmtN->fetchColumn();

                    // 4. Catat aktivitas ke Logger
                    Logger::log("Penarikan Guru", "Kasir mencairkan tabungan guru a.n $nama_guru sebesar Rp" . number_format($jumlah, 0, ',', '.'));
                    
                    $_SESSION['success'] = "Berhasil mencairkan tabungan $nama_guru sebesar Rp " . number_format($jumlah, 0, ',', '.');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Terjadi Kesalahan Sistem: " . $e->getMessage();
            }
        }
        
        // Kembali ke halaman penarikan guru
        header('Location: ' . BASE_URL . '/penarikan/guru');
        exit;
    }
}
?>