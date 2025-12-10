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

        if (isset($hasil['error'])) {
            Respons::gagal($hasil['error'], 400);
        }

        Respons::sukses(['id' => $hasil], 201);
    }

    
    public function daftar()
    {
        Respons::sukses($this->service->semua());
    }

    
    public function cekKendaraan()
    {
        $plat = $_GET['plat'] ?? null;
        if (!$plat) Respons::gagal('Plat kendaraan wajib diisi');

        $hasil = $this->service->cariByPlat($plat);

        if (!$hasil) Respons::gagal("Kendaraan tidak ditemukan", 404);

        Respons::sukses($hasil);
    }

    
    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) Respons::gagal('ID wajib');

        $lama = $this->service->ambil($id);
        if (!$lama) Respons::gagal("Kendaraan tidak ditemukan", 404);

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) Respons::gagal('Data update tidak valid');

        $hasil = $this->service->update($id, $data);

        if (!$hasil) Respons::gagal("Gagal memperbarui kendaraan", 500);

        Respons::sukses(['pesan' => 'Kendaraan berhasil diperbarui']);
    }

    
    public function hapus()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) Respons::gagal('ID wajib');

        $lama = $this->service->ambil($id);
        if (!$lama) Respons::gagal("Kendaraan tidak ditemukan", 404);

        $this->service->hapus($id);

        Respons::sukses(['pesan' => 'Kendaraan dihapus']);
    }
}
