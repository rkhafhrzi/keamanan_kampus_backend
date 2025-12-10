<?php


require_once dirname(__DIR__) . '/services/KendaraanService.php';
require_once dirname(__DIR__) . '/utils/Respons.php';

class KendaraanController
{
    private $service;

    public function __construct()
    {
        $this->service = new KendaraanService();
    }

    public function tambahKendaraan()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) Respons::gagal('Data tidak valid');

        $hasil = $this->service->catat($data);

        Respons::sukses($hasil);
    }

    public function cekKendaraan()
    {
        $plat = $_GET['plat'] ?? null;

        if (!$plat) Respons::gagal('Plat kendaraan wajib diisi');

        $hasil = $this->service->cariByPlat($plat);

        Respons::sukses($hasil);
    }

    public function daftar()
    {
        Respons::sukses($this->service->semua());
    }

    public function hapus()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) Respons::gagal('ID wajib');

        $this->service->hapus($id);

        Respons::sukses(['pesan' => 'Kendaraan dihapus']);
    }
}
