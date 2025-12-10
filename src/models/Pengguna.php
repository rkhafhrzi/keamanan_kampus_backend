<?php

require_once dirname(__DIR__) . '/config/Database.php';

class Pengguna {
    private $db;

    public function __construct() {
        $this->db = (new Database())->koneksi();
    }

  
    public function semua($limit = 100) {
        $stmt = $this->db->prepare("SELECT id,nama,email,role,status,created_at FROM pengguna ORDER BY created_at DESC LIMIT :lim");
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ambil($id) {
        $stmt = $this->db->prepare("SELECT id,nama,email,role,status,created_at FROM pengguna WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buat(array $data) {
        $sql = "INSERT INTO pengguna (nama,email,password,role,status) VALUES (:nama,:email,:password,:role,:status)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nama' => $data['nama'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role'] ?? 'mahasiswa',
            ':status' => $data['status'] ?? 'aktif'
        ]);
        return $this->db->lastInsertId();
    }

    
    public function create(array $data) { return $this->buat($data); }
    public function catat(array $data) { return $this->buat($data); }

    public function update($id, array $data) {
        $sql = "UPDATE pengguna SET nama=:nama, role=:role, status=:status WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nama' => $data['nama'],
            ':role' => $data['role'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM pengguna WHERE id = ?");
        return $stmt->execute([$id]);
    }

  
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM pengguna WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function cariByEmail($email) { return $this->findByEmail($email); }

    
    public function generateToken($id) {
        $token = bin2hex(random_bytes(32));
        $stmt = $this->db->prepare("UPDATE pengguna SET token = :token, token_expired = DATE_ADD(NOW(), INTERVAL 1 DAY) WHERE id = :id");
        $stmt->execute([':token'=>$token, ':id'=>$id]);
        return $token;
    }

    public function validateToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM pengguna WHERE token = ? AND token_expired > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
