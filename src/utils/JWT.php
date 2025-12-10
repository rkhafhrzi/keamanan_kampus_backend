<?php


class JWT {
    private static $secret = null;

    private static function secret() {
        if (self::$secret === null) {
            
            self::$secret = getenv('JWT_SECRET') ?: 'RAHASIA_JWT_KEAMANAN_KAMPUS_DEV';
        }
        return self::$secret;
    }

    private static function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64url_decode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function buatToken(array $payload) {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $header_enc = self::base64url_encode(json_encode($header));

        
        if (!isset($payload['iat'])) $payload['iat'] = time();
        if (!isset($payload['exp'])) $payload['exp'] = time() + (60 * 60 * 24); 

        $payload_enc = self::base64url_encode(json_encode($payload));

        $sig = hash_hmac('sha256', "$header_enc.$payload_enc", self::secret(), true);
        $sig_enc = self::base64url_encode($sig);

        return "$header_enc.$payload_enc.$sig_enc";
    }

    public static function validasi($token) {
        if (!is_string($token) || trim($token) === '') return false;

        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        list($header_enc, $payload_enc, $sig_enc) = $parts;

        $expected_sig = hash_hmac('sha256', "$header_enc.$payload_enc", self::secret(), true);
        $expected_sig_enc = self::base64url_encode($expected_sig);

        
        if (!hash_equals($expected_sig_enc, $sig_enc)) return false;

        $payload_json = self::base64url_decode($payload_enc);
        $payload = json_decode($payload_json, true);
        if (!is_array($payload)) return false;

        if (isset($payload['exp']) && time() > (int)$payload['exp']) return false;

        return $payload;
    }
}
