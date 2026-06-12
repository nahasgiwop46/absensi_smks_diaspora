<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            [
                'kode'       => 'hadir',
                'label'      => 'Hadir',
                'warna'      => '#10b981',
                'is_hadir'   => 1,
                'deskripsi'  => 'Siswa hadir tepat waktu',
            ],
            [
                'kode'       => 'izin',
                'label'      => 'Izin',
                'warna'      => '#3b82f6',
                'is_hadir'   => 0,
                'deskripsi'  => 'Siswa tidak hadir karena izin',
            ],
            [
                'kode'       => 'sakit',
                'label'      => 'Sakit',
                'warna'      => '#f59e0b',
                'is_hadir'   => 0,
                'deskripsi'  => 'Siswa tidak hadir karena sakit',
            ],
            [
                'kode'       => 'alpa',
                'label'      => 'Alpa',
                'warna'      => '#ef4444',
                'is_hadir'   => 0,
                'deskripsi'  => 'Siswa tidak hadir tanpa keterangan',
            ],
        ];

        $data = [];
        foreach ($statuses as $status) {
            $existing = $this->db->table('master_status_absensi')
                ->where('kode', $status['kode'])
                ->get()
                ->getRowArray();
            
            if (!$existing) {
                $data[] = $status;
            }
        }

        if (!empty($data)) {
            $this->db->table('master_status_absensi')->insertBatch($data);
        }

        echo "Master Status Absensi: " . count($data) . " data ditambahkan\n";
    }
}