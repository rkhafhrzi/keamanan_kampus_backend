<?php

require_once dirname(__DIR__) . '/models/Pengguna.php';
require_once dirname(__DIR__) . '/services/QRGenerator.php';
require_once dirname(__DIR__) . '/services/LogService.php';

class QRService
{
    private $penggunaModel;
    private $qrGen;
    private $log;

    public function __construct()
    {
        $this->penggunaModel = new Pengguna();
        $this->qrGen = new QRGenerator();
        $this->log = new LogService();
    }

    // method utama (tetap)
    public function buatQRBase64($id_pengguna)
    {
        $user = $this->penggunaModel->ambil($id_pengguna);
        if (!$user) return null;

        $payload = json_encode([
            'id' => $user['id'],
            'nama' => $user['nama'],
            'role' => $user['role']
        ]);

        $base64 = $this->qrGen->buatBase64($payload);
        $this->log->catat($id_pengguna, "QR dibuat");
        return $base64;
    }

    // 🔥 ADAPTER agar controller tidak error
    public function buat($id_pengguna)
    {
        return base64_decode($this->buatQRBase64($id_pengguna));
    }

    public function validasiQR($qr_data)
    {
        $decoded = json_decode($qr_data, true);
        if (!$decoded) return ['valid' => false];

        $user = $this->penggunaModel->ambil($decoded['id']);
        if (!$user) return ['valid' => false];

        return [
            'valid' => true,
            'user' => [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'role' => $user['role']
            ]
        ];
    }
}
