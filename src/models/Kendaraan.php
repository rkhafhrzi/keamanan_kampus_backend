<?php

require_once dirname(__DIR__) . '/config/Database.php';

class Kendaraan {
    private $db;
    private $table = 'kendaraan';

    public function __construct() {
        $this->db = (new Database())->koneksi();
    }

    
    public function semua($limit = 200) {
        $sql = "SELECT 
                    k.*, 
                    p.nama as pemilik 
                FROM {$this->table} k 
                LEFT JOIN pengguna p ON k.pengguna_id = p.id 
                ORDER BY k.created_at DESC 
                LIMIT :lim";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function ambil($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function buat(array $data) {
        $sql = "INSERT INTO {$this->table} 
                (pengguna_id, plat_nomor, merek, warna, status) 
                VALUES (:pengguna_id, :plat_nomor, :merek, :warna, :status)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':pengguna_id' => $data['pengguna_id'] ?? null,
            ':plat_nomor'  => $data['plat_nomor'],
            ':merek'       => $data['merek'] ?? '',
            ':warna'       => $data['warna'] ?? '',
            ':status'      => $data['status'] ?? 'terdaftar'
        ]);

        return $this->db->lastInsertId();
    }


    public function create(array $data) { return $this->buat($data); }
    public function catat(array $data) { return $this->buat($data); }

    
    public function update($id, array $data) {
        // Ambil data lama
        $lama = $this->ambil($id);
        if (!$lama) return false;

        
        $plat   = $data['plat_nomor'] ?? $lama['plat_nomor'];
        $merek  = $data['merek']      ?? $lama['merek'];
        $warna  = $data['warna']      ?? $lama['warna'];
        $status = $data['status']     ?? $lama['status'];

        $sql = "UPDATE {$this->table} SET 
                    plat_nomor = :plat_nomor, 
                    merek = :merek, 
                    warna = :warna, 
                    status = :status 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':plat_nomor' => $plat,
            ':merek'      => $merek,
            ':warna'      => $warna,
            ':status'     => $status,
            ':id'         => $id
        ]);
    }

    
    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    
    public function cariByPlat($plat) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE plat_nomor = ? LIMIT 1");
        $stmt->execute([$plat]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function findByPlat($plat) { 
        return $this->cariByPlat($plat); 
    }
}
