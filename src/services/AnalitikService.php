<?php


require_once dirname(__DIR__) . '/models/Insiden.php';
require_once dirname(__DIR__) . '/models/Kendaraan.php';
require_once dirname(__DIR__) . '/models/Tamu.php';

class AnalitikService
{
    private $insiden;
    private $kendaraan;
    private $tamu;

    public function __construct()
    {
        $this->insiden = new Insiden();
        $this->kendaraan = new Kendaraan();
        $this->tamu = new Tamu();
    }

    public function ringkas()
    {
        return [
            'total_insiden' => count($this->insiden->semua()),
            'total_kendaraan' => count($this->kendaraan->semua()),
            'total_tamu_hari_ini' => count($this->filterTamuHariIni())
        ];
    }

    private function filterTamuHariIni()
    {
        $list = $this->tamu->semua();
        $today = date('Y-m-d');

        return array_filter($list, function ($t) use ($today) {
            return substr($t['jam_masuk'], 0, 10) === $today;
        });
    }
}
