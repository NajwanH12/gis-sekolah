<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllowApplicationUserRoles extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('role', 'users')) {
            $this->forge->modifyColumn('users', [
                'role' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
            ]);
        }

        $this->db->query(
            "UPDATE users
             SET role = CASE username
                 WHEN 'superadmin' THEN 'admin_super'
                 WHEN 'admin_sekolah_user' THEN 'admin_sekolah'
                 WHEN 'admin' THEN 'admin_super'
                 WHEN 'operator' THEN 'admin_sekolah'
                 ELSE role
             END
             WHERE username IN ('superadmin', 'admin_sekolah_user', 'admin', 'operator')"
        );
    }

    public function down()
    {
        if ($this->db->fieldExists('role', 'users')) {
            $this->forge->modifyColumn('users', [
                'role' => [
                    'type'       => 'ENUM',
                    'constraint' => ['admin_super', 'admin_sekolah', 'superadmin', 'admin', 'operator'],
                    'default'    => 'operator',
                ],
            ]);
        }
    }
}
