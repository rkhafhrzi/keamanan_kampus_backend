<?php

require_once dirname(__DIR__) . '/config/Database.php';

class Tamu {
    private $db;
    private $table = 'tamu';

    public function __construct() {
        $this->db = (new Database())->koneksi();
    }

    public function semua($limit = 200) {
        $stmt = $this->db->prepare("SELECT t.*, p.nama as petugas 
            FROM {$this->table} t 
            LEFT JOIN pengguna p ON t.petugas_id = p.id 
            ORDER BY jam_masuk DESC 
            LIMIT :lim");

        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ambil($id) {
        $stmt = $this->db->prepare("SELECT t.*, p.nama as petugas 
            FROM {$this->table} t 
            LEFT JOIN pengguna p ON t.petugas_id = p.id 
            WHERE t.id = ?");

        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function catatMasuk(array $data) {
        $sql = "INSERT INTO {$this->table} 
            (nama, instansi, tujuan, jam_masuk, petugas_id) 
            VALUES (:nama, :instansi, :tujuan, :jam_masuk, :petugas)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nama' => $data['nama'],
            ':instansi' => $data['instansi'] ?? null,
            ':tujuan' => $data['tujuan'] ?? null,
            ':jam_masuk' => $data['jam_masuk'] ?? date('Y-m-d H:i:s'),
            ':petugas' => $data['petugas_id'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        $sql = "UPDATE {$this->table}
                SET nama = :nama,
                    instansi = :instansi,
                    tujuan = :tujuan,
                    petugas_id = :petugas
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nama' => $data['nama'],
            ':instansi' => $data['instansi'],
            ':tujuan' => $data['tujuan'],
            ':petugas' => $data['petugas_id'],
            ':id' => $id
        ]);
    }

    public function catatKeluar($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} 
            SET jam_keluar = :jam 
            WHERE id = :id");

        return $stmt->execute([
            ':jam' => date('Y-m-d H:i:s'),
            ':id' => $id
        ]);
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
