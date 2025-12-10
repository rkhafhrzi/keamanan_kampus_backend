<?php
require_once __DIR__ . "/../services/AnalitikService.php";
require_once __DIR__ . "/../models/Tamu.php";
require_once __DIR__ . "/../models/Kendaraan.php";
require_once __DIR__ . "/../models/Insiden.php";
require_once __DIR__ . "/../utils/Respons.php";

class LaporanController {
    private $analitik;
    private $tamu;
    private $kendaraan;
    private $insiden;

    public function __construct() {
        $this->analitik = new AnalitikService();
        $this->tamu = new Tamu();
        $this->kendaraan = new Kendaraan();
        $this->insiden = new Insiden();
    }

    public function laporanHarian() {
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        // rekap sederhana:
        $insidenAll = $this->insiden->semua(1000);
        $jumlahInsiden = 0;
        foreach($insidenAll as $i) {
            if (strpos($i['incident_date'],$tanggal) === 0) $jumlahInsiden++;
        }

        $tamuAll = $this->tamu->semua(1000);
        $jumlahTamu = 0;
        foreach($tamuAll as $t) {
            if (strpos($t['jam_masuk'],$tanggal) === 0) $jumlahTamu++;
        }

        $kendAll = $this->kendaraan->semua();
        $jumlahKendaraanMasuk = count($kendAll); // simplifikasi (butuh tabel masuk kendaraan jika detail)

        $laporan = [
            'tanggal'=>$tanggal,
            'jumlah_insiden'=>$jumlahInsiden,
            'jumlah_tamu'=>$jumlahTamu,
            'jumlah_kendaraan_terdaftar'=>$jumlahKendaraanMasuk,
            'rekomendasi'=>$this->analitik->rekomendasi()
        ];

        Respons::sukses($laporan);
    }
}
