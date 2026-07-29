<?php
// app/Controllers/SetoranController.php
require_once __DIR__ . '/../Models/Setoran.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/KategoriSampah.php';
require_once __DIR__ . '/../Models/Kelas.php';
require_once __DIR__ . '/../Core/Logger.php';   
require_once __DIR__ . '/../Core/Security.php'; 

class SetoranController {
    private $setoranModel;
    private $userModel;
    private $sampahModel;
    private $kelasModel;
    private $db;

    public function __construct() {
        Security::requireRole(['admin', 'staff']);
        
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
        $data = []; 

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
        
        $data['setoran'] = $stmt->fetchAll();
        $data['pagination'] = [
            'current_page' => $page,
            'total_pages'  => $total_pages,
            'total_data'   => $total_data
        ];

        extract($data);
        $title = "Riwayat Tabungan";
        $content = __DIR__ . '/../../views/admin/setoran/siswa_index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =======================================================
    // 2. INPUT MASSAL PER KELAS (BATCH STORE)
    // =======================================================
    public function siswa_kelas() {
        $data = [];
        $data['all_kelas'] = $this->db->query("SELECT * FROM kelas WHERE nama_kelas NOT LIKE '%KESISWAAN%' ORDER BY nama_kelas ASC")->fetchAll();
        $data['all_sampah'] = $this->db->query("SELECT * FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI' ORDER BY nama_sampah ASC")->fetchAll();
        
        $kelas_id = $_GET['kelas_id'] ?? null;
        $data['kelas_id'] = $kelas_id;
        $data['siswa_list'] = $kelas_id ? $this->db->query("SELECT id, nama FROM users WHERE kelas_id = $kelas_id AND role = 'siswa' AND is_active = 1 AND deleted_at IS NULL ORDER BY nama ASC")->fetchAll() : [];
        
        extract($data);
        $title = "Setoran Per Kelas (Pcs)";
        $content = __DIR__ . '/../../views/admin/setoran/siswa_kelas.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function siswa_batch_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); 

            try {
                $this->db->beginTransaction(); 

                $kat = $this->sampahModel->getById($_POST['kategori_id']);
                $harga_dasar = (float)($kat['harga_dasar'] ?? 0);
                $harga_pengepul = (float)($kat['harga_pengepul'] ?? 0);
                
                $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
                
                $total_pcs_masuk = 0;
                $tanpa_walas = $_POST['tanpa_walas'] ?? '0';

                foreach ($_POST['berat'] as $uid => $jml) {
                    if ((float)$jml > 0) {
                        $u = $this->userModel->getById($uid);
                        $kls = ($u['kelas_id']) ? $this->kelasModel->getById($u['kelas_id']) : null;
                        
                        $walikelas_id = ($tanpa_walas === '1') ? null : ($kls['walikelas_id'] ?? null);
                        
                        $this->setoranModel->create([
                            'user_id' => $uid, 
                            'kategori_id' => $_POST['kategori_id'], 
                            'berat' => $jml,
                            'total_harga' => $jml * $harga_dasar, 
                            'total_pengepul' => ($jml / $konversi_kg) * $harga_pengepul,
                            'walikelas_id' => $walikelas_id, 
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
    // 2B. FITUR EXPORT TEMPLATE & IMPORT CSV (MULTIKATEGORI)
    // =======================================================
    public function download_template_csv() {
        $kelas_id = $_GET['kelas_id'] ?? null;
        if (!$kelas_id) exit("Pilih kelas terlebih dahulu.");
        
        $stmtKelas = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE id = ?");
        $stmtKelas->execute([$kelas_id]);
        $nama_kelas = $stmtKelas->fetchColumn();
        
        $siswa_list = $this->db->query("SELECT id, nama FROM users WHERE kelas_id = $kelas_id AND role = 'siswa' AND is_active = 1 AND deleted_at IS NULL ORDER BY nama ASC")->fetchAll();
        $all_sampah = $this->db->query("SELECT * FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI' ORDER BY id ASC")->fetchAll();

        $filename = "Template_Import_" . str_replace(' ', '_', $nama_kelas) . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        $header = ['ID_Siswa', 'Nama_Siswa'];
        foreach ($all_sampah as $kat) {
            $header[] = "[ID:" . $kat['id'] . "] " . $kat['nama_sampah']; 
        }
        fputcsv($output, $header); 
        
        foreach ($siswa_list as $s) {
            $row = [$s['id'], $s['nama']];
            foreach ($all_sampah as $kat) {
                $row[] = 0; 
            }
            fputcsv($output, $row); 
        }
        fclose($output);
        exit;
    }

    public function import_csv_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); 

            $kelas_id = $_POST['kelas_id'];
            $tanpa_walas = $_POST['tanpa_walas'] ?? '0';
            
            if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
                $file = $_FILES['file_csv']['tmp_name'];
                $handle = fopen($file, "r");
                
                $headers = fgetcsv($handle, 1000, ","); 
                
                $col_to_cat = [];
                for ($i = 2; $i < count($headers); $i++) {
                    if (preg_match('/\[ID:(\d+)\]/', $headers[$i], $matches)) {
                        $col_to_cat[$i] = (int)$matches[1];
                    }
                }

                if (empty($col_to_cat)) {
                    $_SESSION['error'] = "Format template CSV tidak valid atau tidak memiliki kolom kategori sampah.";
                    header('Location: ' . BASE_URL . '/setoran/siswa_kelas?kelas_id=' . $kelas_id);
                    exit;
                }

                try {
                    $this->db->beginTransaction(); 
                    
                    $stmtSampah = $this->db->query("SELECT * FROM kategori_sampah");
                    $sampahData = [];
                    while ($s = $stmtSampah->fetch()) {
                        $sampahData[$s['id']] = $s;
                    }
                    
                    $stmtKelas = $this->db->prepare("SELECT walikelas_id FROM kelas WHERE id = ?");
                    $stmtKelas->execute([$kelas_id]);
                    $walikelas_id_db = $stmtKelas->fetchColumn();
                    
                    $walikelas_id = ($tanpa_walas === '1') ? null : $walikelas_id_db;
                    $total_pcs_masuk = 0;
                    
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $uid = $data[0]; 
                        
                        $stmtCek = $this->db->prepare("SELECT id FROM users WHERE id = ? AND kelas_id = ? AND role = 'siswa'");
                        $stmtCek->execute([$uid, $kelas_id]);
                        
                        if ($stmtCek->fetch()) {
                            foreach ($col_to_cat as $col_index => $kategori_id) {
                                $jml = (float)($data[$col_index] ?? 0);
                                
                                if ($jml > 0 && isset($sampahData[$kategori_id])) {
                                    $kat = $sampahData[$kategori_id];
                                    $harga_dasar = (float)$kat['harga_dasar'];
                                    $harga_pengepul = (float)$kat['harga_pengepul'];
                                    $konversi_kg = ((float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;

                                    $this->setoranModel->create([
                                        'user_id' => $uid, 
                                        'kategori_id' => $kategori_id, 
                                        'berat' => $jml,
                                        'total_harga' => $jml * $harga_dasar, 
                                        'total_pengepul' => ($jml / $konversi_kg) * $harga_pengepul,
                                        'walikelas_id' => $walikelas_id, 
                                        'status' => 'pending'
                                    ]);
                                    $total_pcs_masuk += $jml;
                                }
                            }
                        }
                    }
                    fclose($handle);
                    
                    $this->db->commit(); 
                    Logger::log("Import Setoran CSV", "Petugas mengimport setoran massal multikategori sebanyak $total_pcs_masuk Pcs (Status: Pending)");
                    $_SESSION['success'] = "Data multikategori CSV berhasil di-import ($total_pcs_masuk Pcs). Menunggu validasi kasir.";
                } catch (Exception $e) {
                    $this->db->rollBack(); 
                    $_SESSION['error'] = "Gagal memproses file CSV: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "File CSV tidak ditemukan atau format tidak valid.";
            }
            header('Location: ' . BASE_URL . '/setoran/siswa_kelas?kelas_id=' . $kelas_id);
            exit;
        }
    }

    // =======================================================
    // 3. BAGIAN GURU
    // =======================================================
    public function guru() {
        $data = [];
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
        
        $data['setoran'] = $stmt->fetchAll();
        $data['pagination'] = ['current_page' => $page, 'total_pages' => $total_pages, 'total_data' => $total_data];
        $data['guru_list'] = $this->db->query("SELECT * FROM users WHERE role NOT IN ('siswa', 'admin') AND is_active = 1 AND deleted_at IS NULL ORDER BY nama ASC")->fetchAll();
        $data['all_sampah'] = $this->db->query("SELECT * FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI' ORDER BY nama_sampah ASC")->fetchAll();

        extract($data);
        $title = "Setoran Guru & Staf";
        $content = __DIR__ . '/../../views/admin/setoran/guru.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function guru_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); 

            $kat = $this->sampahModel->getById($_POST['kategori_id']);
            $jml = (float)$_POST['berat'];
            
            // Menggunakan harga_pengepul langsung untuk guru
            $harga_satuan = (float)($kat['harga_pengepul'] ?? 0);
            $harga_pengepul = (float)($kat['harga_pengepul'] ?? 0);
            $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
            
            $this->setoranModel->create([
                'user_id' => $_POST['user_id'], 
                'kategori_id' => $_POST['kategori_id'], 
                'berat' => $jml,
                'total_harga' => $jml * $harga_satuan, 
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
    // 4. VALIDASI SETORAN
    // =======================================================
    public function validasi() {
        $data = [];
        // Heal data yang nol (Opsional - menyesuaikan role juga jika diperlukan, tapi fungsi di bawah sudah melindunginya)
        $data['pending'] = $this->setoranModel->getPending();
        
        extract($data);
        $title = "Validasi Setoran";
        $content = __DIR__ . '/../../views/admin/setoran/validasi.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // 🛠️ FUNGSI BARU: VALIDASI MASSAL SEMUA ANTREAN (DIPERBARUI DENGAN ROLE)
    public function proses_validasi_semua() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf();
            
            try {
                $this->db->beginTransaction();
                
                $stmtPersen = $this->db->query("SELECT nilai FROM pengaturan WHERE kunci = 'persen_honor_walikelas'");
                $persen_walas = ($stmtPersen->fetchColumn() ?? 0) / 100;
                
                // JOIN tabel users untuk mendapatkan role secara massal
                $stmt = $this->db->query("
                    SELECT s.*, k.harga_dasar, k.harga_pengepul, k.konversi_kg, u.role
                    FROM setoran s 
                    JOIN kategori_sampah k ON s.kategori_id = k.id 
                    JOIN users u ON s.user_id = u.id
                    WHERE s.status = 'pending'
                ");
                $pending_list = $stmt->fetchAll();
                
                if (count($pending_list) === 0) {
                    $this->db->rollBack();
                    $_SESSION['error'] = "Tidak ada data antrean yang perlu divalidasi.";
                    header('Location: ' . BASE_URL . '/setoran/validasi');
                    exit;
                }
                
                $count = 0;
                foreach ($pending_list as $data) {
                    $konversi_kg = (isset($data['konversi_kg']) && (float)$data['konversi_kg'] > 0) ? (float)$data['konversi_kg'] : 1;
                    
                    // Logika Dinamis Harga Berdasarkan Role
                    if ($data['role'] === 'siswa') {
                        $harga_satuan = (float)$data['harga_dasar'];
                    } else {
                        $harga_satuan = (float)$data['harga_pengepul']; // Harga penuh untuk guru
                    }

                    $total_harga = $data['berat'] * $harga_satuan;
                    $total_pengepul = ($data['berat'] / $konversi_kg) * (float)$data['harga_pengepul'];
                    
                    $honor_walas_rp = 0;
                    if (!empty($data['walikelas_id']) && $data['role'] === 'siswa') {
                        $honor_walas_rp = ($total_pengepul - $total_harga) * $persen_walas;
                    }
                    
                    $sql = "UPDATE setoran SET total_harga = ?, total_pengepul = ?, honor_walas_rp = ?, status = 'valid' WHERE id = ?";
                    $this->db->prepare($sql)->execute([$total_harga, $total_pengepul, $honor_walas_rp, $data['id']]);
                    $count++;
                }
                
                $this->db->commit();
                Logger::log("Validasi Setoran Massal", "Kasir memvalidasi seluruh antrean setoran sebanyak $count transaksi sekaligus.");
                $_SESSION['success'] = "Selesai! Berhasil memvalidasi $count data setoran sekaligus ke dalam saldo tabungan utama.";
                
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Terjadi kesalahan sistem: " . $e->getMessage();
            }
            
            header('Location: ' . BASE_URL . '/setoran/validasi');
            exit;
        }
    }

    public function edit_pending($id) {
        $data = [];
        $data['setoran'] = $this->setoranModel->getById($id);
        if (!$data['setoran'] || $data['setoran']['status'] != 'pending') {
            header('Location: ' . BASE_URL . '/setoran/validasi');
            exit;
        }
        $data['sampah'] = $this->db->query("SELECT * FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI' ORDER BY nama_sampah ASC")->fetchAll();
        
        extract($data);
        $title = "Koreksi Data (Pcs)";
        $content = __DIR__ . '/../../views/admin/setoran/edit_pending.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // 🛠️ FUNGSI DIPERBARUI: UPDATE PENDING (DIPERBARUI DENGAN ROLE)
    public function update_pending($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); 

            // 1. Ambil data setoran saat ini untuk mengetahui ID Pengguna (user_id)
            $setoran_lama = $this->setoranModel->getById($id);
            if (!$setoran_lama) {
                $_SESSION['error'] = "Data setoran tidak ditemukan.";
                header('Location: ' . BASE_URL . '/setoran/validasi');
                exit;
            }

            // 2. Ambil informasi role/jabatan pengguna (Siswa atau Guru)
            $user_id = $setoran_lama['user_id'];
            $stmtUser = $this->db->prepare("SELECT role FROM users WHERE id = ?");
            $stmtUser->execute([$user_id]);
            $role_pengguna = $stmtUser->fetchColumn();

            // 3. Ambil data kategori sampah yang baru dipilih
            $kat = $this->sampahModel->getById($_POST['kategori_id']);
            $jml = (float)$_POST['berat'];
            
            // 4. LOGIKA HARGA DINAMIS BERDASARKAN ROLE
            if ($role_pengguna === 'siswa') {
                $harga_satuan = (float)($kat['harga_dasar'] ?? 0);
            } else {
                $harga_satuan = (float)($kat['harga_pengepul'] ?? 0);
            }
            
            $harga_pengepul = (float)($kat['harga_pengepul'] ?? 0);
            $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
            
            // 5. Simpan pembaruan dengan harga yang sudah benar
            $this->setoranModel->update($id, [
                'kategori_id' => $_POST['kategori_id'], 
                'berat' => $jml,
                'total_harga' => $jml * $harga_satuan, 
                'total_pengepul' => ($jml / $konversi_kg) * $harga_pengepul
            ]);
            
            Logger::log("Edit Pending", "Petugas mengoreksi data setoran pending ID #$id menjadi $jml Pcs (Role: $role_pengguna)");
            $_SESSION['success'] = "Data diperbarui dengan harga yang disesuaikan.";
            header('Location: ' . BASE_URL . '/setoran/validasi');
            exit;
        }
    }

    // 🛠️ FUNGSI DIPERBARUI: PROSES VALIDASI (DIPERBARUI DENGAN ROLE)
    public function proses_validasi($id) {
        $data = $this->setoranModel->getById($id); 
        if ($data) {
            $stmtPersen = $this->db->query("SELECT nilai FROM pengaturan WHERE kunci = 'persen_honor_walikelas'");
            $persen_walas = ($stmtPersen->fetchColumn() ?? 0) / 100;

            $kat = $this->sampahModel->getById($data['kategori_id']);
            $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
            
            // CEK ROLE PENGGUNA UNTUK HARGA SATUAN YANG TEPAT
            $stmtUser = $this->db->prepare("SELECT role FROM users WHERE id = ?");
            $stmtUser->execute([$data['user_id']]);
            $role_pengguna = $stmtUser->fetchColumn();

            if ($role_pengguna === 'siswa') {
                $harga_satuan = (float)$kat['harga_dasar'];
            } else {
                $harga_satuan = (float)$kat['harga_pengepul']; // Harga penuh untuk guru
            }

            $total_harga = $data['berat'] * $harga_satuan;
            $total_pengepul = ($data['berat'] / $konversi_kg) * (float)$kat['harga_pengepul'];

            $honor_walas_rp = 0;
            // Guru tidak memiliki walikelas, pastikan perhitungan insentif hanya berlaku untuk siswa
            if (!empty($data['walikelas_id']) && $role_pengguna === 'siswa') {
                $honor_walas_rp = ($total_pengepul - $total_harga) * $persen_walas;
            }

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
        Security::requireRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); 

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
        $data = [];
        $data['kategori'] = $this->db->query("SELECT * FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI' ORDER BY nama_sampah ASC")->fetchAll();
        
        $stmtCek = $this->db->query("SELECT id FROM users WHERE nama LIKE '%KESISWAAN%' AND role = 'siswa' LIMIT 1");
        $data['akun_kesiswaan'] = $stmtCek->fetch();

        if (!$data['akun_kesiswaan']) {
            $_SESSION['error'] = "Akun virtual 'KAS KESISWAAN' belum ada.";
            header('Location: ' . BASE_URL . '/user');
            exit;
        }

        extract($data);
        $title = "Input Botol Denda Kesiswaan";
        $content = __DIR__ . '/../../views/admin/setoran/kesiswaan_create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store_kesiswaan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); 

            $user_id = $_POST['user_id'];
            $kategori_id = $_POST['kategori_id'];
            $berat = (float) $_POST['berat'];

            if ($berat <= 0) {
                $_SESSION['error'] = "Jumlah tidak valid.";
            } else {
                $stmtKat = $this->db->prepare("SELECT harga_dasar, harga_pengepul, konversi_kg FROM kategori_sampah WHERE id = ?");
                $stmtKat->execute([$kategori_id]);
                $kat = $stmtKat->fetch();

                $konversi_kg = (isset($kat['konversi_kg']) && (float)$kat['konversi_kg'] > 0) ? (float)$kat['konversi_kg'] : 1;
                $total_harga = $berat * (float)$kat['harga_dasar'];
                $total_pengepul = ($berat / $konversi_kg) * (float)$kat['harga_pengepul'];

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
?>