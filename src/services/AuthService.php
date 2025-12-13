<?php

require_once dirname(__DIR__) . '/models/Pengguna.php';
require_once dirname(__DIR__) . '/utils/JWT.php';

class AuthService
{
    private $pengguna;

    public function __construct()
    {
        $this->pengguna = new Pengguna();
    }

    public function registrasi(array $data)
    {
        if (empty($data['email']) || empty($data['password']) || empty($data['nama'])) {
            return ['sukses' => false, 'pesan' => 'Data tidak lengkap'];
        }

        if ($this->pengguna->findByEmail($data['email'])) {
            return ['sukses' => false, 'pesan' => 'Email sudah terdaftar'];
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $id = $this->pengguna->buat($data);

        return ['sukses' => true, 'id' => $id];
    }

    public function login($email, $password)
    {
        $user = $this->pengguna->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return ['sukses' => false, 'pesan' => 'Email atau password salah'];
        }

        $token = JWT::encode([
            'id' => $user['id'],
            'role' => $user['role']
        ]);

        unset($user['password']);

        return [
            'sukses' => true,
            'token' => $token,
            'pengguna' => $user
        ];
    }
}
