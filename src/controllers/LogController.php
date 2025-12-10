<?php


require_once dirname(__DIR__) . '/services/LogService.php';
require_once dirname(__DIR__) . '/utils/Respons.php';

class LogController
{
    private $service;

    public function __construct()
    {
        $this->service = new LogService();
    }

    public function catatAktivitas()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) Respons::gagal('Data tidak valid');

        $hasil = $this->service->catat($data);

        Respons::sukses($hasil);
    }

    public function semua()
    {
        Respons::sukses($this->service->semua());
    }
}
