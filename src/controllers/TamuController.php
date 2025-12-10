<?php


require_once dirname(__DIR__) . '/services/TamuService.php';
require_once dirname(__DIR__) . '/utils/Respons.php';

class TamuController
{
    private $service;

    public function __construct()
    {
        $this->service = new TamuService();
    }

    public function tambahTamu()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) Respons::gagal('Data tidak valid');

        $hasil = $this->service->catat($data);

        Respons::sukses($hasil);
    }

    public function keluarTamu()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) Respons::gagal('ID wajib');

        $hasil = $this->service->keluar($id);

        Respons::sukses($hasil);
    }

    public function daftar()
    {
        Respons::sukses($this->service->semua());
    }
}
