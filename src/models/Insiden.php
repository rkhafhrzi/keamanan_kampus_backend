<?php

require_once dirname(__DIR__) . '/config/Database.php';

class Insiden {
    private $db;
    private $table = 'insiden';

    public function __construct() {
        $this->db = (new Database())->koneksi();
    }

    
    public function semua($limit = 200) {
        $sql = "SELECT i.*, p.nama AS pelapor
                FROM {$this->table} i
                LEFT JOIN pengguna p ON i.pelapor_id = p.id
                ORDER BY i.waktu_kejadian DESC
                LIMIT :lim";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function ambil($id) {
        $sql = "SELECT i.*, p.nama AS pelapor
                FROM {$this->table} i
                LEFT JOIN pengguna p ON i.pelapor_id = p.id
                WHERE i.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function buat(array $data) {
        $sql = "INSERT INTO {$this->table}
                (judul, deskripsi, kategori, lokasi, tingkat_keparahan, waktu_kejadian, pelapor_id, foto, status)
                VALUES (:judul, :deskripsi, :kategori, :lokasi, :tingkat, :waktu, :pelapor_id, :foto, :status)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':judul' => $data['judul'],
            ':deskripsi' => $data['deskripsi'] ?? null,
            ':kategori' => $data['kategori'] ?? null,
            ':lokasi' => $data['lokasi'] ?? null,
            ':tingkat' => $data['tingkat_keparahan'] ?? 'rendah',
            ':waktu' => $data['waktu_kejadian'] ?? date('Y-m-d H:i:s'),
            ':pelapor_id' => $data['pelapor_id'] ?? null,
            ':foto' => $data['foto'] ?? null,
            ':status' => $data['status'] ?? 'baru'
        ]);

        return $this->db->lastInsertId();
    }

    
    public function update($id, array $data) {
        $sql = "UPDATE {$this->table}
                SET judul=:judul, deskripsi=:deskripsi, kategori=:kategori,
                    lokasi=:lokasi, tingkat_keparahan=:tingkat, status=:status
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':judul' => $data['judul'],
            ':deskripsi' => $data['deskripsi'],
            ':kategori' => $data['kategori'],
            ':lokasi' => $data['lokasi'],
            ':tingkat' => $data['tingkat_keparahan'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    
    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
