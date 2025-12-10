<?php

require_once dirname(__DIR__) . '/services/InsidenService.php';
require_once dirname(__DIR__) . '/utils/Respons.php';

class InsidenController
{
    private $service;

    public function __construct()
    {
        $this->service = new InsidenService();
    }

   
    public function laporkanInsiden()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) Respons::gagal("Data tidak valid");

        $hasil = $this->service->buat($data);

        if (isset($hasil['error'])) Respons::gagal($hasil['error']);

        Respons::sukses(['id' => $hasil], 201);
    }

    
    public function daftar()
    {
        Respons::sukses($this->service->semua());
    }

    
    public function ambil()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) Respons::gagal("ID wajib");

        $data = $this->service->ambil($id);
        if (!$data) Respons::gagal("Insiden tidak ditemukan", 404);

        Respons::sukses($data);
    }

    public function ubah()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) Respons::gagal("ID wajib");

        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) Respons::gagal("Data update tidak valid");

        $this->service->update($id, $data);

        Respons::sukses(['pesan' => "Insiden diperbarui"]);
    }

    
    public function hapus()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) Respons::gagal("ID wajib");

        $this->service->hapus($id);
        Respons::sukses(['pesan' => "Insiden dihapus"]);
    }
}
