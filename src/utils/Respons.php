<?php

class Respons {
    public static function sukses($data = null, $kode = 200) {
        http_response_code($kode);
        echo json_encode([
            'status' => 'sukses',
            'data' => $data
        ]);
        exit;
    }

    public static function gagal($pesan = 'Terjadi kesalahan', $kode = 400) {
        http_response_code($kode);
        echo json_encode([
            'status' => 'gagal',
            'pesan' => $pesan
        ]);
        exit;
    }
}
