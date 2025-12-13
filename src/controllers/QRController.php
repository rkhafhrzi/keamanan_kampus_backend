<?php

   
require_once dirname(__DIR__) . '/services/QRService.php';
require_once dirname(__DIR__) . '/utils/Respons.php';

class QRController
{
    private $service;

    public function __construct()
    {
        $this->service = new QRService();
    }

    public function tampilkanQR()
    {
        $kode = $_GET['kode'] ?? 'TIDAK ADA KODE';

        $gambar = $this->service->buat($kode);

        header('Content-Type: image/png');
        echo $gambar;
        exit;
    }
}
