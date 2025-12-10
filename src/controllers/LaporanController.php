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

        
        $insiden = $this->insiden->semua(1000);
        $jumlahInsiden = array_reduce($insiden, function ($total, $row) use ($tanggal) {
            return $total + (strpos($row['incident_date'], $tanggal) === 0 ? 1 : 0);
        }, 0);


        $tamu = $this->tamu->semua(1000);
        $jumlahTamu = array_reduce($tamu, function ($total, $row) use ($tanggal) {
            return $total + (strpos($row['jam_masuk'], $tanggal) === 0 ? 1 : 0);
        }, 0);

        
        $jumlahKendaraan = count($this->kendaraan->semua());

        $laporan = [
            "tanggal" => $tanggal,
            "jumlah_insiden" => $jumlahInsiden,
            "jumlah_tamu" => $jumlahTamu,
            "jumlah_kendaraan_terdaftar" => $jumlahKendaraan,
            "rekomendasi" => $this->analitik->rekomendasi()
        ];

        Respons::sukses($laporan);
    }


   
    public function laporanBulanan() {
        $tahun = $_GET['tahun'] ?? date('Y');
        $bulan = $_GET['bulan'] ?? date('m');

        $tanggalAwal = "$tahun-$bulan-01";
        $tanggalAkhir = date("Y-m-t", strtotime($tanggalAwal));

        
        $insiden = $this->insiden->semua(10000);
        $insidenBulan = array_filter($insiden, function($row) use ($tanggalAwal, $tanggalAkhir) {
            return $row['incident_date'] >= $tanggalAwal && $row['incident_date'] <= $tanggalAkhir;
        });

        
        $tamu = $this->tamu->semua(10000);
        $tamuBulan = array_filter($tamu, function($row) use ($tanggalAwal, $tanggalAkhir) {
            return $row['jam_masuk'] >= $tanggalAwal && $row['jam_masuk'] <= $tanggalAkhir;
        });

        $laporan = [
            "periode" => "$tahun-$bulan",
            "jumlah_insiden" => count($insidenBulan),
            "jumlah_tamu" => count($tamuBulan),
            "rekomendasi" => $this->analitik->rekomendasi()
        ];

        Respons::sukses($laporan);
    }


    
    public function exportHarianCsv() {
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="laporan_harian_' . $tanggal . '.csv"');

        $csv = fopen('php://output', 'w');

        fputcsv($csv, ['Tanggal', 'Insiden', 'Tamu', 'Kendaraan', 'Rekomendasi']);

        $laporan = [
            $tanggal,
            count($this->insiden->semua()),
            count($this->tamu->semua()),
            count($this->kendaraan->semua()),
            implode(" | ", $this->analitik->rekomendasi())
        ];

        fputcsv($csv, $laporan);
        fclose($csv);
        exit;
    }
}
