<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class EnsureRoleAccountsSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            [
                'username' => 'superadmin',
                'password' => 'superadmin123',
                'role' => 'admin_super',
                'nama_lengkap' => 'Super Admin',
                'email' => 'superadmin@example.com',
            ],
            [
                'username' => 'admin_sekolah_user',
                'password' => 'password123',
                'role' => 'admin_sekolah',
                'nama_lengkap' => 'Admin Sekolah',
                'email' => 'admin_sekolah_user@example.com',
            ],
        ];

        $userModel = new UserModel();

        foreach ($accounts as $account) {
            $existing = $userModel->where('username', $account['username'])->first();
            $data = [
                'username' => $account['username'],
                'email' => $account['email'],
                'password' => $account['password'],
                'nama_lengkap' => $account['nama_lengkap'],
                'role' => $account['role'],
                'school_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($existing) {
                $userModel->update($existing['id'], $data);
            } else {
                $userModel->insert($data);
            }
        }
    }
}
