<?php
// app/Models/Honor.php
require_once __DIR__ . '/../Core/Database.php';

class Honor {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Fungsi untuk Pencairan (Sisa Saldo)
    public function getHonorWaliKelas() {
        $sql = "SELECT 
                    s.walikelas_id as user_id, u.nama as nama_guru, k.nama_kelas,
                    SUM(s.honor_walas_rp) as total_jatah
                FROM setoran s
                JOIN users u ON s.walikelas_id = u.id
                JOIN kelas k ON u.id = k.walikelas_id
                WHERE s.status = 'valid' AND s.is_sold = 1
                GROUP BY s.walikelas_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // --- FITUR BARU: Method untuk Laporan Honor (Fix Error) ---
    public function getRekapHonorWaliKelas() {
        $sql = "SELECT 
                    u.nama AS nama_guru, k.nama_kelas,
                    SUM(CASE WHEN s.is_sold = 0 THEN s.honor_walas_rp ELSE 0 END) as total_potensi,
                    SUM(CASE WHEN s.is_sold = 1 THEN s.honor_walas_rp ELSE 0 END) as total_realisasi
                FROM setoran s
                JOIN users u ON s.walikelas_id = u.id
                JOIN kelas k ON u.id = k.walikelas_id
                JOIN kategori_sampah ks ON s.kategori_id = ks.id
                WHERE s.status = 'valid' AND ks.nama_sampah != '🌟 REWARD PRESTASI'
                GROUP BY u.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSudahCair($user_id) {
        $stmt = $this->db->prepare("SELECT SUM(jumlah) FROM pencairan_honor WHERE user_id = :uid");
        $stmt->execute(['uid' => $user_id]);
        return $stmt->fetchColumn() ?? 0;
    }

    public function simpanPencairan($data) {
        $sql = "INSERT INTO pencairan_honor (user_id, jumlah, jenis, keterangan) VALUES (:user_id, :jumlah, :jenis, :keterangan)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getRiwayat() {
        $sql = "SELECT ph.*, u.nama FROM pencairan_honor ph JOIN users u ON ph.user_id = u.id ORDER BY ph.tanggal_cair DESC LIMIT 50";
        return $this->db->query($sql)->fetchAll();
    }
}