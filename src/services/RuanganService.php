<?php


require_once dirname(__DIR__) . '/models/Ruangan.php';

class RuanganService
{
    private $ruangan;

    public function __construct()
    {
        $this->ruangan = new Ruangan();
    }

    public function semua()
    {
        return $this->ruangan->semua();
    }

    public function ambil($id)
    {
        return $this->ruangan->ambil($id);
    }

    public function ambilByKode($kode)
    {
        return $this->ruangan->ambilByKode($kode);
    }

    public function catat(array $data)
    {
        return $this->ruangan->buat($data);
    }

    public function update($id, array $data)
    {
        return $this->ruangan->update($id, $data);
    }

    public function hapus($id)
    {
        return $this->ruangan->hapus($id);
    }
}
