<?php

class Database {
    private $host;
    private $nama_db;
    private $user;
    private $pass;
    private $port;
    private $conn;

    public function __construct() {
        $this->host    = getenv('DB_HOST') ?: 'localhost'; 
        $this->nama_db = getenv('DB_NAME') ?: 'keamanan_kampus';
        $this->user    = getenv('DB_USER') ?: 'root';
        $this->pass    = getenv('DB_PASS') ?: '';
        $this->port    = getenv('DB_PORT') ?: 3306;
        $this->conn    = null;
    }

    public function koneksi() {
        if ($this->conn instanceof PDO) {
            return $this->conn;
        }

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->nama_db};port={$this->port};charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

        } catch (PDOException $e) {

            http_response_code(500);
            echo json_encode([
                'status' => 'gagal',
                'pesan' => 'Koneksi database gagal: ' . $e->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}
