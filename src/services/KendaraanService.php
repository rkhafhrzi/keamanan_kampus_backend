<?php


require_once dirname(__DIR__) . '/models/Kendaraan.php';

class KendaraanService
{
    private $kendaraan;

    public function __construct()
    {
        $this->kendaraan = new Kendaraan();
    }

    public function semua()
    {
        return $this->kendaraan->semua();
    }

    public function ambil($id)
    {
        return $this->kendaraan->ambil($id);
    }

    public function catat(array $data)
    {
        return $this->kendaraan->buat($data);
    }

    public function update($id, array $data)
    {
        return $this->kendaraan->update($id, $data);
    }

    public function hapus($id)
    {
        return $this->kendaraan->hapus($id);
    }

    public function cariByPlat($plat)
    {
        return $this->kendaraan->cariByPlat($plat);
    }
}
