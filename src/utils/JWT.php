<?php

class JWT
{
    private static $secret = 'RAHASIA_SUPER_AMAN_GANTI_INI';

    public static function encode(array $payload)
    {
        $header = base64_encode(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]));

        $body = base64_encode(json_encode($payload));

        $signature = base64_encode(
            hash_hmac('sha256', "$header.$body", self::$secret, true)
        );

        return "$header.$body.$signature";
    }

    public static function decode($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$header, $body, $signature] = $parts;

        $valid = base64_encode(
            hash_hmac('sha256', "$header.$body", self::$secret, true)
        );

        if ($signature !== $valid) {
            return null;
        }

        return json_decode(base64_decode($body), true);
    }

    // ✅ METHOD BARU HARUS DI DALAM CLASS
    public static function validasi($token)
    {
        return self::decode($token);
    }
}
