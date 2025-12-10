<?php

require_once dirname(__DIR__) . '/config/Database.php';

class Ruangan {
    private $db;
    private $table = 'ruangan';

    public function __construct() {
        $this->db = (new Database())->koneksi();
    }

    public function semua($limit = 200) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY nama_ruangan LIMIT :lim");
        $stmt->bindValue(':lim',(int)$limit,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ambil($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ambilByKode($kode) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE kode_ruangan = ? LIMIT 1");
        $stmt->execute([$kode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buat(array $data) {
        $sql = "INSERT INTO {$this->table} (kode_ruangan, nama_ruangan, latitude, longitude, akses_roles) VALUES (:kode,:nama,:lat,:lng,:akses)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':kode'=>$data['kode_ruangan'],
            ':nama'=>$data['nama_ruangan'],
            ':lat'=>$data['latitude'] ?? null,
            ':lng'=>$data['longitude'] ?? null,
            ':akses'=>$data['akses_roles'] ?? 'dosen,petugas,mahasiswa'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, array $data) {
        $sql = "UPDATE {$this->table} SET kode_ruangan=:kode,nama_ruangan=:nama,latitude=:lat,longitude=:lng,akses_roles=:akses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':kode'=>$data['kode_ruangan'],
            ':nama'=>$data['nama_ruangan'],
            ':lat'=>$data['latitude'],
            ':lng'=>$data['longitude'],
            ':akses'=>$data['akses_roles'],
            ':id'=>$id
        ]);
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
