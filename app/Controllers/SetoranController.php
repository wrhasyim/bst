<?php
// app/Controllers/SetoranController.php
require_once __DIR__ . '/../Models/Setoran.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/KategoriSampah.php';
require_once __DIR__ . '/../Models/Kelas.php';
require_once __DIR__ . '/../Core/Logger.php';   // 🛡️ Load Audit Trail
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class SetoranController {
    private $setoranModel;
    private $userModel;
    private $sampahModel;
    private $kelasModel;
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'staff'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->setoranModel = new Setoran();
        $this->userModel = new User();
        $this->sampahModel = new KategoriSampah();
        $this->kelasModel = new Kelas();
        $this->db = Database::getInstance()->getConnection();
    }

    // =======================================================
    // 1. RIWAYAT TABUNGAN SISWA
    // =======================================================
    public function siswa() {
        $limit = 10; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page > 1) ? ($page * $limit) - $limit : 0;

        $total_data = $this->db->query("SELECT COUNT(*) FROM setoran s JOIN users u ON s.user_id = u.id WHERE u.role = 'siswa' AND s.status = 'valid'")->fetchColumn();
        $total_pages = ceil($total_data / $limit);

        $sql = "SELECT s.*, u.nama, k.nama_sampah, k.satuan, kl.nama_kelas
                FROM setoran s
                JOIN users u ON s.user_id = u.id
                JOIN kategori_sampah k ON s.kategori_id = k.id
                LEFT JOIN kelas kl ON u.kelas_id = kl.id
                WHERE u.role = 'siswa' AND s.status = 'valid'
                ORDER BY s.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $setoran = $stmt->fetchAll();

        $pagination = [
            'current_page' => $page,
            'total_pages'  => $total_pages,
            'total_data'   => $total_data
        ];

        $title = "Riwayat Tabungan";
        $content = __DIR__ . '/../../views/admin/setoran/siswa_index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =======================================================
    // 2. INPUT MASSAL PER KELAS (BATCH STORE)
    // =======================================================
    public function siswa_kelas() {
        $all_kelas = $this->db->query("SELECT * FROM kelas WHERE nama_kelas NOT LIKE '%KESISWAAN%' ORDER BY nama_kelas ASC")->fetchAll();
        $all_sampah = $this->db->query("SELECT * FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI' ORDER BY nama_sampah ASC")->fetchAll();
        
        $kelas_id = $_GET['kelas_id'] ?? null;
        $siswa_list = $kelas_id ? $this->db->query("SELECT id, nama FROM users WHERE kelas_id = $kelas_id AND role = 'siswa' AND is_active = 1 AND deleted_at IS NULL ORDER BY nama ASC")->fetchAll() : [];
        
        $title = "Setoran Per Kelas (Pcs)";
        $content = __DIR__ . '/../../views/admin/setoran/siswa_kelas.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function siswa_batch_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Keamanan CSRF

            try {
                $this->db->beginTransaction(); 

                $kat = $this->sampahModel->getById($_POST['kategori_id']);
                $harga_dasar = (float)($kat['harga_dasar'] ?? 0);
                $harga_pengepul = (float)($kat['harga_pengepul'] ?? 0);
                
                // PERBAIKAN: Ambil konversi KG, pastikan tidak 0 untuk menghindari error pembagian
                $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
                
                $total_pcs_masuk = 0;

                foreach ($_POST['berat'] as $uid => $jml) {
                    if ((float)$jml > 0) {
                        $u = $this->userModel->getById($uid);
                        $kls = ($u['kelas_id']) ? $this->kelasModel->getById($u['kelas_id']) : null;
                        
                        $this->setoranModel->create([
                            'user_id' => $uid, 
                            'kategori_id' => $_POST['kategori_id'], 
                            'berat' => $jml,
                            'total_harga' => $jml * $harga_dasar, 
                            // PERBAIKAN: Bagi jumlah PCS dengan nilai konversi KG terlebih dahulu
                            'total_pengepul' => ($jml / $konversi_kg) * $harga_pengepul,
                            'walikelas_id' => $kls['walikelas_id'] ?? null, 
                            'status' => 'pending'
                        ]);
                        $total_pcs_masuk += $jml;
                    }
                }

                $this->db->commit(); 
                Logger::log("Input Setoran Kelas", "Petugas menginput setoran batch sebanyak $total_pcs_masuk Pcs (Status: Pending)");
                $_SESSION['success'] = "Setoran batch berhasil disimpan. Menunggu validasi.";
            } catch (Exception $e) {
                $this->db->rollBack(); 
                $_SESSION['error'] = "Gagal simpan setoran: " . $e->getMessage();
            }

            header('Location: ' . BASE_URL . '/setoran/siswa');
            exit;
        }
    }

    // =======================================================
    // 3. BAGIAN GURU
    // =======================================================
    public function guru() {
        $limit = 10; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page > 1) ? ($page * $limit) - $limit : 0;

        $total_data = $this->db->query("SELECT COUNT(*) FROM setoran s JOIN users u ON s.user_id = u.id WHERE u.role != 'siswa'")->fetchColumn();
        $total_pages = ceil($total_data / $limit);

        $sql = "SELECT s.*, u.nama, k.nama_sampah, k.satuan
                FROM setoran s
                JOIN users u ON s.user_id = u.id
                JOIN kategori_sampah k ON s.kategori_id = k.id
                WHERE u.role != 'siswa'
                ORDER BY s.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $setoran = $stmt->fetchAll();

        $pagination = ['current_page' => $page, 'total_pages' => $total_pages, 'total_data' => $total_data];

        $users = $this->db->query("SELECT * FROM users WHERE role NOT IN ('siswa', 'admin') AND is_active = 1 AND deleted_at IS NULL ORDER BY nama ASC")->fetchAll();
        $all_sampah = $this->db->query("SELECT * FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI' ORDER BY nama_sampah ASC")->fetchAll();
        
        $guru_list = $users;
        $kategori_list = $all_sampah;

        $title = "Setoran Guru & Staf";
        $content = __DIR__ . '/../../views/admin/setoran/guru.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function guru_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Keamanan CSRF

            $kat = $this->sampahModel->getById($_POST['kategori_id']);
            $jml = (float)$_POST['berat'];
            
            $harga_satuan = (float)($kat['harga_dasar'] ?? 0);
            $harga_pengepul = (float)($kat['harga_pengepul'] ?? 0);
            
            // PERBAIKAN: Konversi PCS ke KG
            $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
            
            $this->setoranModel->create([
                'user_id' => $_POST['user_id'], 
                'kategori_id' => $_POST['kategori_id'], 
                'berat' => $jml,
                'total_harga' => $jml * $harga_satuan, 
                // PERBAIKAN: Bagi jumlah PCS dengan nilai konversi KG
                'total_pengepul' => ($jml / $konversi_kg) * $harga_pengepul,
                'walikelas_id' => null, 
                'status' => 'pending'
            ]);

            Logger::log("Input Setoran Guru", "Petugas menginput setoran guru sebesar $jml Pcs (Status: Pending)");
            $_SESSION['success'] = "Setoran guru ($jml Pcs) disimpan.";
            header('Location: ' . BASE_URL . '/setoran/guru');
            exit;
        }
    }

    // =======================================================
    // 4. VALIDASI SETORAN (DENGAN ARCHITECT SNAPSHOT PATTERN)
    // =======================================================
    public function validasi() {
        // 🛠️ AUTO-HEALING SYSTEM
        // PERBAIKAN: Sesuaikan query auto-healing agar membagi berat dengan konversi_kg menggunakan COALESCE NULLIF
        $sql_heal = "UPDATE setoran s 
                     JOIN kategori_sampah k ON s.kategori_id = k.id 
                     SET s.total_harga = (s.berat * k.harga_dasar), 
                         s.total_pengepul = ((s.berat / COALESCE(NULLIF(k.konversi_kg, 0), 1)) * k.harga_pengepul) 
                     WHERE s.status = 'pending' AND (s.total_harga = 0 OR s.total_pengepul = 0)";
        $this->db->query($sql_heal);

        $pending = $this->setoranModel->getPending();
        $title = "Validasi Setoran";
        $content = __DIR__ . '/../../views/admin/setoran/validasi.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function edit_pending($id) {
        $setoran = $this->setoranModel->getById($id);
        if (!$setoran || $setoran['status'] != 'pending') {
            header('Location: ' . BASE_URL . '/setoran/validasi');
            exit;
        }
        $sampah = $this->db->query("SELECT * FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI' ORDER BY nama_sampah ASC")->fetchAll();
        
        $title = "Koreksi Data (Pcs)";
        $content = __DIR__ . '/../../views/admin/setoran/edit_pending.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update_pending($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Keamanan CSRF

            $kat = $this->sampahModel->getById($_POST['kategori_id']);
            $jml = (float)$_POST['berat'];
            $harga_dasar = (float)($kat['harga_dasar'] ?? 0);
            $harga_pengepul = (float)($kat['harga_pengepul'] ?? 0);
            
            // PERBAIKAN: Konversi PCS ke KG
            $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
            
            $this->setoranModel->update($id, [
                'kategori_id' => $_POST['kategori_id'], 
                'berat' => $jml,
                'total_harga' => $jml * $harga_dasar, 
                // PERBAIKAN: Bagi jumlah PCS dengan nilai konversi KG
                'total_pengepul' => ($jml / $konversi_kg) * $harga_pengepul
            ]);
            
            Logger::log("Edit Pending", "Petugas mengoreksi data setoran pending ID #$id menjadi $jml Pcs");
            $_SESSION['success'] = "Data diperbarui.";
            header('Location: ' . BASE_URL . '/setoran/validasi');
            exit;
        }
    }

    public function proses_validasi($id) {
        $data = $this->setoranModel->getById($id); 
        if ($data) {
            // 🛠️ CRITICAL FIX: Ambil persentase honor walas SAAT INI (Detik validasi dilakukan)
            $stmtPersen = $this->db->query("SELECT nilai FROM pengaturan WHERE kunci = 'persen_honor_walikelas'");
            $persen_walas = ($stmtPersen->fetchColumn() ?? 0) / 100;

            $kat = $this->sampahModel->getById($data['kategori_id']);
            
            // PERBAIKAN: Konversi PCS ke KG saat melakukan snapshot
            $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
            
            $total_harga = $data['berat'] * (float)$kat['harga_dasar'];
            $total_pengepul = ($data['berat'] / $konversi_kg) * (float)$kat['harga_pengepul'];

            // 🛠️ CRITICAL FIX: Hitung dan kunci honor Walas (Hanya jika nasabah punya Walas)
            $honor_walas_rp = 0;
            if (!empty($data['walikelas_id'])) {
                // Laba Pengepul - Tabungan Nasabah = Margin. Kalikan persentase Walas.
                $honor_walas_rp = ($total_pengepul - $total_harga) * $persen_walas;
            }

            // Update harga riil, status valid, DAN KUNCI PERMANEN HONOR WALAS (Snapshot)
            $sql = "UPDATE setoran SET total_harga = ?, total_pengepul = ?, honor_walas_rp = ?, status = 'valid' WHERE id = ?";
            $this->db->prepare($sql)->execute([$total_harga, $total_pengepul, $honor_walas_rp, $id]);
            
            Logger::log("Validasi Setoran", "Petugas memvalidasi setoran ID #$id sebesar Rp " . number_format($total_harga, 0, ',', '.'));
            $_SESSION['success'] = "Data divalidasi dan saldo otomatis bertambah.";
        } else {
            $_SESSION['error'] = "Data tidak ditemukan.";
        }
        
        header('Location: ' . BASE_URL . '/setoran/validasi');
        exit;
    }

    public function hapus_pending($id) {
        $this->setoranModel->delete($id);
        Logger::log("Hapus Antrean", "Petugas menghapus setoran pending ID #$id");
        $_SESSION['success'] = "Data antrean dihapus.";
        header('Location: ' . BASE_URL . '/setoran/validasi');
        exit;
    }

    // =================================================================
    // 5. FITUR GAMIFIKASI: REWARD PRESTASI
    // =================================================================
    public function reward() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Keamanan CSRF

            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                $_SESSION['error'] = "Akses ditolak!";
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }

            $user_id = $_POST['user_id'];
            $nominal = (float) $_POST['nominal'];
            $nama_siswa = $_POST['nama_siswa'] ?? 'Siswa';

            if ($nominal <= 0) {
                $_SESSION['error'] = "Nominal hadiah tidak valid.";
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }

            try {
                $this->db->beginTransaction();

                $stmtCek = $this->db->query("SELECT id FROM kategori_sampah WHERE nama_sampah = '🌟 REWARD PRESTASI'");
                $kategori = $stmtCek->fetch();

                if (!$kategori) {
                    $this->db->query("INSERT INTO kategori_sampah (nama_sampah, harga_dasar, harga_pengepul, satuan) VALUES ('🌟 REWARD PRESTASI', 1, 0, 'Bonus')");
                    $kategori_id = $this->db->lastInsertId();
                } else {
                    $kategori_id = $kategori['id'];
                }

                $stmtWali = $this->db->prepare("SELECT k.walikelas_id FROM users u LEFT JOIN kelas k ON u.kelas_id = k.id WHERE u.id = ?");
                $stmtWali->execute([$user_id]);
                $wali_id = $stmtWali->fetchColumn() ?? 0;

                // Karena ini reward (bukan sampah riil), honor_walas_rp otomatis menggunakan nilai default 0 di database.
                $sql = "INSERT INTO setoran (user_id, walikelas_id, kategori_id, berat, total_harga, total_pengepul, status, is_sold) 
                        VALUES (?, ?, ?, 1, ?, 0, 'valid', 1)";
                $this->db->prepare($sql)->execute([$user_id, $wali_id, $kategori_id, $nominal]);

                $this->db->commit();
                
                Logger::log("Reward Prestasi", "Admin memberikan reward Rp " . number_format($nominal) . " kepada $nama_siswa");
                $_SESSION['success'] = "Reward Rp " . number_format($nominal, 0, ',', '.') . " berhasil dikirim ke " . htmlspecialchars($nama_siswa);
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }

            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    // =================================================================
    // 6. FITUR KHUSUS: KAS KESISWAAN (VIRTUAL ACCOUNT)
    // =================================================================
    public function create_kesiswaan() {
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $kategori = $this->db->query("SELECT * FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI' ORDER BY nama_sampah ASC")->fetchAll();
        $stmtCek = $this->db->query("SELECT id FROM users WHERE nama LIKE '%KESISWAAN%' AND role = 'siswa' LIMIT 1");
        $akun_kesiswaan = $stmtCek->fetch();

        if (!$akun_kesiswaan) {
            $_SESSION['error'] = "Akun virtual 'KAS KESISWAAN' belum ada.";
            header('Location: ' . BASE_URL . '/user');
            exit;
        }

        $title = "Input Botol Denda Kesiswaan";
        $content = __DIR__ . '/../../views/admin/setoran/kesiswaan_create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store_kesiswaan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff')) {
            Security::validate_csrf(); // 🛡️ Keamanan CSRF

            $user_id = $_POST['user_id'];
            $kategori_id = $_POST['kategori_id'];
            $berat = (float) $_POST['berat'];

            if ($berat <= 0) {
                $_SESSION['error'] = "Jumlah tidak valid.";
            } else {
                // PERBAIKAN: Tambahkan pengambilan kolom konversi_kg dari database
                $stmtKat = $this->db->prepare("SELECT harga_dasar, harga_pengepul, konversi_kg FROM kategori_sampah WHERE id = ?");
                $stmtKat->execute([$kategori_id]);
                $kat = $stmtKat->fetch();

                // PERBAIKAN: Konversi PCS ke KG
                $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
                $total_harga = $berat * (float)$kat['harga_dasar'];
                $total_pengepul = ($berat / $konversi_kg) * (float)$kat['harga_pengepul'];

                // walikelas_id NULL memastikan kas kesiswaan tidak dihitung sebagai honor walas siapapun
                $sql = "INSERT INTO setoran (user_id, walikelas_id, kategori_id, berat, total_harga, total_pengepul, status, is_sold) 
                        VALUES (?, NULL, ?, ?, ?, ?, 'valid', 0)";
                $this->db->prepare($sql)->execute([$user_id, $kategori_id, $berat, $total_harga, $total_pengepul]);
                
                Logger::log("Denda Kesiswaan", "Petugas mencatat denda pelanggaran sebesar $berat Pcs ke Kas Kesiswaan");
                $_SESSION['success'] = "Berhasil catat denda $berat Pcs ke Kas Kesiswaan.";
            }
            header('Location: ' . BASE_URL . '/setoran/create_kesiswaan');
            exit;
        }
    }
}