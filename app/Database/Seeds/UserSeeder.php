<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'   => 'admin',
            'email'      => 'admin@caffe.com',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT),
            'role'       => 'admin', // <-- Tambahkan baris ini di sini!
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Using Query Builder
        $this->db->table('users')->insert($data);
    }
}
