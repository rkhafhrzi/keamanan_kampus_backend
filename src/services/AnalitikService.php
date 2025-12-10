<?php

class AnalitikService
{
    
    public function hitungStatistik($insiden, $tamu, $kendaraan)
    {
        return [
            'total_insiden' => count($insiden),
            'total_tamu' => count($tamu),
            'total_kendaraan' => count($kendaraan),
            'insiden_per_kategori' => $this->kelompokkanInsiden($insiden),
            'tamu_per_hari' => $this->kelompokkanTamu($tamu)
        ];
    }

    
    public function rekomendasi()
    {
        return [
            "Perbanyak patroli keamanan pada jam rawan.",
            "Perlu pemasangan CCTV tambahan pada area parkir belakang.",
            "Sosialisasi keamanan bagi mahasiswa baru.",
            "Tingkatkan pencatatan tamu dengan verifikasi identitas.",
            "Optimalkan penggunaan QR Code untuk akses ruangan."
        ];
    }

    
    private function kelompokkanInsiden($data)
    {
        $hasil = [];
        foreach ($data as $row) {
            $kat = $row['kategori'] ?? 'lainnya';
            if (!isset($hasil[$kat])) $hasil[$kat] = 0;
            $hasil[$kat]++;
        }
        return $hasil;
    }

   
    private function kelompokkanTamu($data)
    {
        $hasil = [];
        foreach ($data as $row) {
            $tgl = substr($row['jam_masuk'], 0, 10);
            if (!isset($hasil[$tgl])) $hasil[$tgl] = 0;
            $hasil[$tgl]++;
        }
        return $hasil;
    }
}
