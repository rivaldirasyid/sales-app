<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
            ],
            [
                'username' => 'user',
                'email' => 'user@gmail.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
            ]
        ];

        // Using Query Builder to insert data
        $this->db->table('users')->insertBatch($data);
    }
}
