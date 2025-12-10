<?php

require_once dirname(__DIR__) . '/config/Database.php';

class Insiden {
    private $db;
    private $table = 'insiden';

    public function __construct() {
        $this->db = (new Database())->koneksi();
    }

    public function semua($limit = 200) {
        $stmt = $this->db->prepare("SELECT i.*, p.nama as pelapor FROM {$this->table} i LEFT JOIN pengguna p ON i.pengguna_id = p.id ORDER BY incident_date DESC LIMIT :lim");
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ambil($id) {
        $stmt = $this->db->prepare("SELECT i.*, p.nama as pelapor FROM {$this->table} i LEFT JOIN pengguna p ON i.pengguna_id = p.id WHERE i.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buat(array $data) {
        $sql = "INSERT INTO {$this->table} (pengguna_id, judul, deskripsi, latitude, longitude, kategori, incident_date, status) 
                VALUES (:pengguna_id,:judul,:deskripsi,:latitude,:longitude,:kategori,:incident_date,:status)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':pengguna_id' => $data['pengguna_id'] ?? null,
            ':judul' => $data['judul'] ?? null,
            ':deskripsi' => $data['deskripsi'] ?? null,
            ':latitude' => $data['latitude'] ?? null,
            ':longitude' => $data['longitude'] ?? null,
            ':kategori' => $data['kategori'] ?? 'umum',
            ':incident_date' => $data['incident_date'] ?? date('Y-m-d H:i:s'),
            ':status' => $data['status'] ?? 'lapor'
        ]);
        return $this->db->lastInsertId();
    }


    public function create(array $data) { return $this->buat($data); }
    public function catat(array $data) { return $this->buat($data); }
    public function kirim(array $data) { return $this->buat($data); }

    public function update($id, array $data) {
        $sql = "UPDATE {$this->table} SET judul=:judul, deskripsi=:deskripsi, latitude=:latitude, longitude=:longitude, kategori=:kategori, status=:status, incident_date=:incident_date WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute([
            ':judul'=>$data['judul'],
            ':deskripsi'=>$data['deskripsi'],
            ':latitude'=>$data['latitude'],
            ':longitude'=>$data['longitude'],
            ':kategori'=>$data['kategori'],
            ':status'=>$data['status'],
            ':incident_date'=>$data['incident_date'],
            ':id'=>$id
        ]);
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Statistik
    public function frekuensiPerBulan($tahun = null) {
        $tahun = $tahun ?? date('Y');
        $sql = "SELECT DATE_FORMAT(incident_date, '%Y-%m') as bulan, COUNT(*) as jumlah FROM {$this->table} WHERE YEAR(incident_date)=:tahun GROUP BY bulan ORDER BY bulan";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tahun'=>$tahun]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
