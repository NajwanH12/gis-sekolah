<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username', 'email', 'password', 'nama_lengkap', 
        'role', 'school_id', 'foto', 'last_login', 'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function ensureDefaultAccounts()
    {
        $accounts = [
            [
                'username' => 'superadmin',
                'email' => 'superadmin@example.com',
                'password' => 'superadmin123',
                'nama_lengkap' => 'Super Admin',
                'role' => 'admin_super',
                'school_id' => null,
                'is_active' => 1,
            ],
            [
                'username' => 'admin_sekolah_user',
                'email' => 'admin_sekolah_user@example.com',
                'password' => 'password123',
                'nama_lengkap' => 'Admin Sekolah',
                'role' => 'admin_sekolah',
                'school_id' => null,
                'is_active' => 1,
            ],
            [
                'username' => 'admin',
                'email' => 'admin@lintau-buo.go.id',
                'password' => 'admin123',
                'nama_lengkap' => 'Administrator',
                'role' => 'admin_super',
                'school_id' => null,
                'is_active' => 1,
            ],
            [
                'username' => 'operator',
                'email' => 'operator@lintau-buo.go.id',
                'password' => 'operator123',
                'nama_lengkap' => 'Operator Sekolah',
                'role' => 'admin_sekolah',
                'school_id' => null,
                'is_active' => 1,
            ],
        ];

        foreach ($accounts as $account) {
            $existing = $this->where('username', $account['username'])->first();
            $data = array_merge($account, [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($existing) {
                $this->update($existing['id'], $data);
            } else {
                $this->insert($data);
            }
        }
    }
    
    // Login untuk semua role
    public function login($username, $password)
    {
        $this->ensureDefaultAccounts();

        $user = $this->where('username', $username)
                     ->where('is_active', 1)
                     ->first();
        
        if (!$user) {
            return false;
        }
        
        if ($user['password'] == $password) {
            return $user;
        }
        
        return false;
    }
    
    // Cek apakah user adalah super admin
    public function isSuperAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'admin_super';
    }
    
    // Cek apakah user adalah admin sekolah
    public function isAdminSekolah($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'admin_sekolah';
    }
    
    // Get sekolah untuk admin sekolah
    public function getSekolahByAdmin($userId)
    {
        $user = $this->find($userId);
        if ($user && $user['role'] === 'admin_sekolah') {
            return $user['school_id'];
        }
        return null;
    }
}