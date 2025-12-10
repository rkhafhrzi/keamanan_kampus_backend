<?php

require_once dirname(__DIR__) . '/config/Database.php';

class LogAktivitas {
    private $db;
    private $table = 'log_aktivitas';

    public function __construct() {
        $this->db = (new Database())->koneksi();
    }

    public function semua($limit = 200) {
        $stmt = $this->db->prepare("SELECT la.*, p.nama as pengguna FROM {$this->table} la LEFT JOIN pengguna p ON la.pengguna_id = p.id ORDER BY created_at DESC LIMIT :lim");
        $stmt->bindValue(':lim',(int)$limit,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function catat(array $data) {
        $sql = "INSERT INTO {$this->table} (pengguna_id, aktivitas, meta, ip, created_at) VALUES (:pengguna_id,:aktivitas,:meta,:ip,NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':pengguna_id'=>$data['pengguna_id'] ?? null,
            ':aktivitas'=>$data['aktivitas'],
            ':meta'=>isset($data['meta']) ? json_encode($data['meta']) : null,
            ':ip'=>$data['ip'] ?? ($_SERVER['REMOTE_ADDR'] ?? null)
        ]);
        return $this->db->lastInsertId();
    }

    
    public function create(array $data) { return $this->catat($data); }
}
