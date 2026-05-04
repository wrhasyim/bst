<?php
// app/Controllers/SetoranController.php
require_once __DIR__ . '/../Models/Setoran.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/KategoriSampah.php';
require_once __DIR__ . '/../Models/Kelas.php';

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
    // 1. RIWAYAT TABUNGAN SISWA (OPTIMIZED WITH PAGINATION)
    // =======================================================
    public function siswa() {
        // --- LOGIKA PAGINATION ---
        $limit = 10; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page > 1) ? ($page * $limit) - $limit : 0;

        // Hitung Total Data (Cek status valid saja)
        $total_data = $this->db->query("SELECT COUNT(*) FROM setoran s JOIN users u ON s.user_id = u.id WHERE u.role = 'siswa' AND s.status = 'valid'")->fetchColumn();
        $total_pages = ceil($total_data / $limit);

        // Query Ambil Data Terbatas (Limit & Offset)
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
            try {
                $this->db->beginTransaction(); // 🔐 PROTEKSI: Pastikan semua masuk atau tidak sama sekali

                $kat = $this->sampahModel->getById($_POST['kategori_id']);
                foreach ($_POST['berat'] as $uid => $jml) {
                    if ((float)$jml > 0) {
                        $u = $this->userModel->getById($uid);
                        $kls = ($u['kelas_id']) ? $this->kelasModel->getById($u['kelas_id']) : null;
                        
                        $this->setoranModel->create([
                            'user_id' => $uid, 
                            'kategori_id' => $_POST['kategori_id'], 
                            'berat' => $jml,
                            'total_harga' => $jml * $kat['harga_dasar'], 
                            'total_pengepul' => $jml * $kat['harga_pengepul'],
                            'walikelas_id' => $kls['walikelas_id'] ?? null, 
                            'status' => 'pending'
                        ]);
                    }
                }

                $this->db->commit(); // ✅ SIMPAN PERMANEN
                $_SESSION['success'] = "Setoran batch berhasil disimpan. Menunggu validasi.";
            } catch (Exception $e) {
                $this->db->rollBack(); // ❌ BATALKAN JIKA ADA ERROR
                $_SESSION['error'] = "Gagal simpan setoran: " . $e->getMessage();
            }

            header('Location: ' . BASE_URL . '/setoran/siswa');
            exit;
        }
    }

    // =======================================================
    // 3. BAGIAN GURU (WITH PAGINATION)
    // =======================================================
    public function guru() {
        $limit = 10; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page > 1) ? ($page * $limit) - $limit : 0;

        $total_data = $this->db->query("SELECT COUNT(*) FROM setoran s JOIN users u ON s.user_id = u.id WHERE u.role != 'siswa' AND s.status = 'valid'")->fetchColumn();
        $total_pages = ceil($total_data / $limit);

        $sql = "SELECT s.*, u.nama, k.nama_sampah, k.satuan
                FROM setoran s
                JOIN users u ON s.user_id = u.id
                JOIN kategori_sampah k ON s.kategori_id = k.id
                WHERE u.role != 'siswa' AND s.status = 'valid'
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
            $kat = $this->sampahModel->getById($_POST['kategori_id']);
            $jml = (float)$_POST['berat'];
            $harga_satuan = isset($kat['harga_guru']) ? $kat['harga_guru'] : ($kat['harga_dasar'] ?? 0);
            
            $this->setoranModel->create([
                'user_id' => $_POST['user_id'], 
                'kategori_id' => $_POST['kategori_id'], 
                'berat' => $jml,
                'total_harga' => $jml * $harga_satuan, 
                'total_pengepul' => $jml * $kat['harga_pengepul'],
                'walikelas_id' => null, 
                'status' => 'pending'
            ]);
            $_SESSION['success'] = "Setoran guru ($jml Pcs) disimpan.";
            header('Location: ' . BASE_URL . '/setoran/guru');
            exit;
        }
    }

    // =======================================================
    // 4. VALIDASI SETORAN
    // =======================================================
    public function validasi() {
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
            $kat = $this->sampahModel->getById($_POST['kategori_id']);
            $jml = (float)$_POST['berat'];
            
            $this->setoranModel->update($id, [
                'kategori_id' => $_POST['kategori_id'], 
                'berat' => $jml,
                'total_harga' => $jml * $kat['harga_dasar'], 
                'total_pengepul' => $jml * $kat['harga_pengepul']
            ]);
            
            $_SESSION['success'] = "Data diperbarui.";
            header('Location: ' . BASE_URL . '/setoran/validasi');
            exit;
        }
    }

    public function proses_validasi($id) {
        $this->setoranModel->updateStatus($id, 'valid');
        $_SESSION['success'] = "Data divalidasi.";
        header('Location: ' . BASE_URL . '/setoran/validasi');
        exit;
    }

    public function hapus_pending($id) {
        $this->setoranModel->delete($id);
        $_SESSION['success'] = "Data dihapus.";
        header('Location: ' . BASE_URL . '/setoran/validasi');
        exit;
    }

    // =================================================================
    // FITUR GAMIFIKASI: REWARD PRESTASI
    // =================================================================
    public function reward() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

                $sql = "INSERT INTO setoran (user_id, walikelas_id, kategori_id, berat, total_harga, total_pengepul, status, is_sold) 
                        VALUES (?, ?, ?, 1, ?, 0, 'valid', 1)";
                $this->db->prepare($sql)->execute([$user_id, $wali_id, $kategori_id, $nominal]);

                $this->db->commit();
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
    // FITUR KHUSUS: KAS KESISWAAN (VIRTUAL ACCOUNT)
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
            $user_id = $_POST['user_id'];
            $kategori_id = $_POST['kategori_id'];
            $berat = (float) $_POST['berat'];

            if ($berat <= 0) {
                $_SESSION['error'] = "Jumlah tidak valid.";
            } else {
                $stmtKat = $this->db->prepare("SELECT harga_dasar, harga_pengepul FROM kategori_sampah WHERE id = ?");
                $stmtKat->execute([$kategori_id]);
                $kat = $stmtKat->fetch();

                $sql = "INSERT INTO setoran (user_id, walikelas_id, kategori_id, berat, total_harga, total_pengepul, status, is_sold) 
                        VALUES (?, NULL, ?, ?, ?, ?, 'valid', 0)";
                $this->db->prepare($sql)->execute([$user_id, $kategori_id, $berat, $berat * $kat['harga_dasar'], $berat * $kat['harga_pengepul']]);
                $_SESSION['success'] = "Berhasil catat denda $berat Pcs ke Kas Kesiswaan.";
            }
            header('Location: ' . BASE_URL . '/setoran/create_kesiswaan');
            exit;
        }
    }
    public function proses_validasi($id) {
        $data = $this->setoranModel->getById($id); // Ambil info sebelum validasi
        $this->setoranModel->updateStatus($id, 'valid');
        
        // CATAT LOG
        Logger::log("Validasi Setoran", "Petugas memvalidasi setoran ID #$id sebesar Rp " . number_format($data['total_harga']));

        $_SESSION['success'] = "Data divalidasi.";
        header('Location: ' . BASE_URL . '/setoran/validasi');
        exit;
    }

    public function hapus_pending($id) {
        $data = $this->setoranModel->getById($id);
        $this->setoranModel->delete($id);

        // CATAT LOG
        Logger::log("Hapus Antrean", "Petugas menghapus setoran pending ID #$id");

        $_SESSION['success'] = "Data dihapus.";
        header('Location: ' . BASE_URL . '/setoran/validasi');
        exit;
    }
}