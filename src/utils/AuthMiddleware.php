<?php

require_once __DIR__ . '/JWT.php';
require_once __DIR__ . '/Respons.php';

class AuthMiddleware
{
    
    public static function auth_protect()
    {
        
        $headers = [];
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        }

        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        if (!$authHeader) {
            http_response_code(401);
            Respons::gagal('Authorization header tidak ditemukan', 401);
        }

        if (strpos($authHeader, 'Bearer ') === 0) {
            $token = trim(substr($authHeader, 7));
        } else {
            http_response_code(401);
            Respons::gagal('Format Authorization harus Bearer <token>', 401);
        }

        $payload = JWT::validasi($token);
        if (!$payload) {
            http_response_code(401);
            Respons::gagal('Token tidak valid atau kadaluarsa', 401);
        }

        return $payload;
    }
}
