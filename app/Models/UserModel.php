<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';  // Sesuai dengan skema, primary key adalah `user_id`

    // Kolom yang diizinkan untuk diisi secara massal
    protected $allowedFields = ['username', 'full_name', 'email', 'password_hash', 'remember_token', 'role'];

    // Mengaktifkan timestamps untuk created_at dan updated_at
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Hash password sebelum disimpan
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    // Fungsi untuk meng-hash password
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password_hash'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password_hash'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}
