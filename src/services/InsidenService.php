<?php

require_once dirname(__DIR__) . '/models/Insiden.php';

class InsidenService
{
    private $insiden;

    public function __construct()
    {
        $this->insiden = new Insiden();
    }

    public function semua()
    {
        return $this->insiden->semua();
    }

    public function ambil($id)
    {
        return $this->insiden->ambil($id);
    }

    public function buat(array $data)
    {
        if (empty($data['judul']) || empty($data['kategori']))
            return ['error' => 'Judul dan kategori wajib'];

        return $this->insiden->buat($data);
    }

    public function update($id, array $data)
    {
        return $this->insiden->update($id, $data);
    }

    public function hapus($id)
    {
        return $this->insiden->hapus($id);
    }
}
