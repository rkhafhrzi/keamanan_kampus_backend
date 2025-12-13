<?php
class QRGenerator {
    
    public static function buatBase64($teks) {
        
        $url = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($teks);
        $data = @file_get_contents($url);
        if ($data === false) return null;
        return 'data:image/png;base64,' . base64_encode($data);
    }
}
