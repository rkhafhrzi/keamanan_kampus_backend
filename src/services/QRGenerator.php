<?php
class QRGenerator {
    // membutuhkan library imagick atau GD. Kita implementasi GD sederhana via phpqrcode lib kecil:
    public static function buatBase64($teks) {
        // gunakan library phpqrcode jika tersedia (rekomendasi: composer "endroid/qr-code" lebih baik)
        // fallback: generate via Google Chart API (tidak ideal untuk produksi)
        $url = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($teks);
        $data = @file_get_contents($url);
        if ($data === false) return null;
        return 'data:image/png;base64,' . base64_encode($data);
    }
}
