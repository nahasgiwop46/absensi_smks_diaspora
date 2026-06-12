<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ========== 1. INSERT ROLE ==========
        $roles = [
            ['kode' => 'admin',  'nama' => 'Administrator', 'deskripsi' => 'Administrator sistem'],
            ['kode' => 'guru',   'nama' => 'Guru',          'deskripsi' => 'Tenaga pengajar'],
            ['kode' => 'siswa',  'nama' => 'Siswa',         'deskripsi' => 'Peserta didik'],
            ['kode' => 'kepsek', 'nama' => 'Kepala Sekolah','deskripsi' => 'Kepala sekolah'],
        ];

        foreach ($roles as $role) {
            $existing = $this->db->table('master_role')
                ->where('kode', $role['kode'])
                ->get()
                ->getRowArray();

            if (!$existing) {
                $this->db->table('master_role')->insert($role);
            }
        }

        // Ambil ID role
        $roleIds = [];
        $allRoles = $this->db->table('master_role')->get()->getResultArray();
        foreach ($allRoles as $r) {
            $roleIds[$r['kode']] = $r['id'];
        }

        echo "Role: " . count($allRoles) . " data\n";

        // ========== 2. INSERT USER SATU PER SATU ==========
        // Hindari insertBatch, gunakan insert satu per satu
        
        $now = date('Y-m-d H:i:s');
        
        // Admin
        if (!$this->db->table('user')->where('username', 'admin01')->get()->getRowArray()) {
            $this->db->table('user')->insert([
                'role_id'      => $roleIds['admin'],
                'username'     => 'admin01',
                'password'     => password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]),
                'nama_lengkap' => 'Administrator',
                'email'        => 'admin@smks.sch.id',
                'no_hp'        => '081234567890',
                'jenis_kelamin' => 'L',
                'is_active'    => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            echo "User admin01 ditambahkan\n";
        }

        // Guru 1
        if (!$this->db->table('user')->where('username', 'budi.hartono')->get()->getRowArray()) {
            $this->db->table('user')->insert([
                'role_id'      => $roleIds['guru'],
                'username'     => 'budi.hartono',
                'password'     => password_hash('guru123', PASSWORD_BCRYPT, ['cost' => 12]),
                'nama_lengkap' => 'Budi Hartono, S.Pd',
                'nuptk'        => '1234567890123456',
                'nip'          => '198501012010011001',
                'email'        => 'budi.hartono@smks.sch.id',
                'no_hp'        => '081234567891',
                'jenis_kelamin' => 'L',
                'is_active'    => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            echo "User budi.hartono ditambahkan\n";
        }

        // Guru 2
        if (!$this->db->table('user')->where('username', 'siti.aminah')->get()->getRowArray()) {
            $this->db->table('user')->insert([
                'role_id'      => $roleIds['guru'],
                'username'     => 'siti.aminah',
                'password'     => password_hash('guru123', PASSWORD_BCRYPT, ['cost' => 12]),
                'nama_lengkap' => 'Siti Aminah, S.Kom',
                'nuptk'        => '1234567890123457',
                'nip'          => '199001012015012001',
                'email'        => 'siti.aminah@smks.sch.id',
                'no_hp'        => '081234567892',
                'jenis_kelamin' => 'P',
                'is_active'    => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            echo "User siti.aminah ditambahkan\n";
        }

        // Siswa 1
        if (!$this->db->table('user')->where('username', '2024001')->get()->getRowArray()) {
            $this->db->table('user')->insert([
                'role_id'      => $roleIds['siswa'],
                'username'     => '2024001',
                'password'     => password_hash('siswa123', PASSWORD_BCRYPT, ['cost' => 12]),
                'nama_lengkap' => 'Ahmad Fauzi',
                'email'        => 'ahmad.fauzi@smks.sch.id',
                'jenis_kelamin' => 'L',
                'is_active'    => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            echo "User 2024001 ditambahkan\n";
        }

        // Siswa 2
        if (!$this->db->table('user')->where('username', '2024002')->get()->getRowArray()) {
            $this->db->table('user')->insert([
                'role_id'      => $roleIds['siswa'],
                'username'     => '2024002',
                'password'     => password_hash('siswa123', PASSWORD_BCRYPT, ['cost' => 12]),
                'nama_lengkap' => 'Dewi Lestari',
                'email'        => 'dewi.lestari@smks.sch.id',
                'jenis_kelamin' => 'P',
                'is_active'    => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            echo "User 2024002 ditambahkan\n";
        }

        // Kepsek
        if (!$this->db->table('user')->where('username', 'kepsek01')->get()->getRowArray()) {
            $this->db->table('user')->insert([
                'role_id'      => $roleIds['kepsek'],
                'username'     => 'kepsek01',
                'password'     => password_hash('kepsek123', PASSWORD_BCRYPT, ['cost' => 12]),
                'nama_lengkap' => 'Dr. H. Muhammad Ridwan, M.Pd',
                'nip'          => '197501012005011001',
                'email'        => 'kepsek@smks.sch.id',
                'no_hp'        => '081234567893',
                'jenis_kelamin' => 'L',
                'is_active'    => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            echo "User kepsek01 ditambahkan\n";
        }

        echo "\n✅ Seeder selesai!\n";
    }
}