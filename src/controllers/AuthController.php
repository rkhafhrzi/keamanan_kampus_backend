<?php

require_once dirname(__DIR__) . '/services/AuthService.php';
require_once dirname(__DIR__) . '/utils/Respons.php';

class AuthController
{
    private $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) Respons::gagal('Data tidak valid');

        $hasil = $this->auth->registrasi($data);

        if (isset($hasil['sukses']) && $hasil['sukses']) {
            Respons::sukses(['id' => $hasil['id'] ?? null], 201);
        }
        Respons::gagal($hasil['pesan'] ?? 'Registrasi gagal', 400);
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) Respons::gagal('Format JSON tidak valid', 400);

        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if ($email === '' || $password === '') {
            Respons::gagal('Email dan password wajib diisi', 400);
        }

        $hasil = $this->auth->login($email, $password);

        if (!isset($hasil['sukses']) || !$hasil['sukses']) {
            Respons::gagal($hasil['pesan'] ?? 'Login gagal', 401);
        }

        Respons::sukses([
            'token' => $hasil['token'],
            'pengguna' => $hasil['pengguna']
        ]);
    }

    public function logout()
    {
        Respons::sukses(['pesan' => 'Logout berhasil']);
    }
}
