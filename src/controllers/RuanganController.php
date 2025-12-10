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
}
