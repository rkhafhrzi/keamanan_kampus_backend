<?php

require_once dirname(__DIR__) . '/services/RuanganService.php';
require_once dirname(__DIR__) . '/utils/Respons.php';

class RuanganController
{
    private $service;

    public function __construct()
    {
        $this->service = new RuanganService();
    }

    public function lokasi()
    {
        $kode = $_GET['kode'] ?? null;

        if (!$kode) Respons::gagal('Kode ruangan wajib');

        $ruangan = $this->service->ambilByKode($kode);

        Respons::sukses($ruangan);
    }

    public function cekAkses()
    {
        $kode = $_GET['kode'] ?? null;
        $role = $_GET['role'] ?? 'user';

        if (!$kode) Respons::gagal('Kode ruangan wajib');

        $ruangan = $this->service->ambilByKode($kode);

        if (!$ruangan) Respons::gagal('Ruangan tidak ditemukan');

        
        if ($ruangan['status_akses'] == 'khusus' && $role != 'admin') {
            Respons::gagal('Akses ditolak');
        }

        Respons::sukses(['pesan' => 'Akses diizinkan']);
    }

   
    public function tambah()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) Respons::gagal("Data tidak valid");

        $id = $this->service->catat($data);

        Respons::sukses(['id' => $id]);
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

        if (!$data) Respons::gagal("Ruangan tidak ditemukan", 404);

        Respons::sukses($data);
    }

    
    public function update()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) Respons::gagal("ID wajib");

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) Respons::gagal("Data update tidak valid");

        $hasil = $this->service->update($id, $data);

        if (!$hasil) Respons::gagal("Gagal memperbarui ruangan");

        Respons::sukses(['pesan' => 'Ruangan diperbarui']);
    }

    public function hapus()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) Respons::gagal("ID wajib");

        $this->service->hapus($id);

        Respons::sukses(['pesan' => 'Ruangan dihapus']);
    }
}
