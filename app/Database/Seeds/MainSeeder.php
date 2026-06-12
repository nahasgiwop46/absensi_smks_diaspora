<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // Matikan FK check sementara
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Jalankan seeder secara berurutan
        $this->call('MasterStatusSeeder');
        $this->call('UserSeeder');

        // Aktifkan kembali FK check
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "\n==============================\n";
        echo "  SEMUA SEEDER SELESAI! ✅\n";
        echo "==============================\n";
        echo "\nData login:\n";
        echo "  Admin  : admin01 / admin123\n";
        echo "  Guru   : guru1 / guru123\n";
        echo "  Siswa  : 2024001 / siswa123\n";
        echo "  Kepsek : kepsek01 / kepsek123\n";
    }
}