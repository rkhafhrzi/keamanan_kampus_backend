<?php


require_once dirname(__DIR__) . '/models/Pengguna.php';
require_once dirname(__DIR__) . '/utils/JWT.php';

class AuthService
{
    private $pengguna;
    private $jwt;

    public function __construct()
    {
        $this->pengguna = new Pengguna();
        $this->jwt = new JWT();
    }

    
    public function login($email, $password)
    {
        $user = $this->pengguna->findByEmail($email);

        if (!$user) {
            return ['sukses' => false, 'pesan' => 'Email tidak ditemukan'];
        }

        if (!isset($user['password']) || !password_verify($password, $user['password'])) {
            return ['sukses' => false, 'pesan' => 'Password salah'];
        }

        $token = $this->jwt->buatToken([
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);

        return [
            'sukses' => true,
            'token' => $token,
            'pengguna' => [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ];
    }

    public function registrasi(array $data)
    {
        
        if (empty($data['email']) || empty($data['password']) || empty($data['nama'])) {
            return ['sukses' => false, 'pesan' => 'Nama, email, dan password wajib diisi'];
        }

        
        if ($this->pengguna->findByEmail($data['email'])) {
            return ['sukses' => false, 'pesan' => 'Email sudah terdaftar'];
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $id = $this->pengguna->buat($data);
        if ($id) return ['sukses' => true, 'id' => $id];

        return ['sukses' => false, 'pesan' => 'Gagal menyimpan pengguna'];
    }
}
