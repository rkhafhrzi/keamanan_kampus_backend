<?php

require_once dirname(__DIR__) . '/models/Tamu.php';

class TamuService
{
    private $tamu;

    public function __construct()
    {
        $this->tamu = new Tamu();
    }

    public function semua()
    {
        return $this->tamu->semua();
    }

    public function ambil($id)
    {
        return $this->tamu->ambil($id);
    }

    public function catat(array $data)
    {
        return $this->tamu->catatMasuk($data);
    }

    public function update($id, array $data)
    {
        return $this->tamu->update($id, $data);
    }

    public function keluar($id)
    {
        return $this->tamu->catatKeluar($id);
    }

    public function hapus($id)
    {
        return $this->tamu->hapus($id);
    }
}
