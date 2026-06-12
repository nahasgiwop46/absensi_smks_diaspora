<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AkademikSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // ========== MATIKAN FOREIGN KEY CHECKS ==========
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // ========== 0. INSERT USER DASAR ==========
        $users = [
            [
                'id'           => 1,
                'role_id'      => 1,
                'username'     => 'admin',
                'password'     => password_hash('admin123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Administrator',
                'is_active'    => 1,
                'created_at'   => $now,
            ],
            [
                'id'           => 2,
                'role_id'      => 2,
                'username'     => 'guru1',
                'password'     => password_hash('guru123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Budi Santoso, S.Kom',
                'nuptk'        => '1234567890123456',
                'is_active'    => 1,
                'created_at'   => $now,
            ],
            [
                'id'           => 3,
                'role_id'      => 2,
                'username'     => 'guru2',
                'password'     => password_hash('guru123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Siti Aminah, S.Pd',
                'nuptk'        => '1234567890123457',
                'is_active'    => 1,
                'created_at'   => $now,
            ],
        ];

        foreach ($users as $u) {
            $existing = $this->db->table('user')
                ->where('username', $u['username'])
                ->get()
                ->getRowArray();

            if (!$existing) {
                $this->db->table('user')->insert($u);
                echo "User {$u['username']} ditambahkan\n";
            } else {
                echo "User {$u['username']} sudah ada\n";
            }
        }

        // ========== 1. INSERT TAHUN AJARAN ==========
        $tahunAjaran = [
            'nama'           => '2025/2026',
            'tanggal_mulai'  => '2025-07-14',
            'tanggal_selesai' => '2026-06-20',
            'semester'       => '1',
            'is_active'      => 1,
            'created_by'     => 1,
            'created_at'     => $now,
        ];

        $existing = $this->db->table('tahun_ajaran')
            ->where('nama', '2025/2026')
            ->get()
            ->getRowArray();

        if (!$existing) {
            $this->db->table('tahun_ajaran')->insert($tahunAjaran);
            $tahunAjaranId = $this->db->insertID();
            echo "Tahun Ajaran 2025/2026 ditambahkan (ID: {$tahunAjaranId})\n";
        } else {
            $tahunAjaranId = $existing['id'];
            $this->db->table('tahun_ajaran')
                ->set('is_active', 1)
                ->where('id', $tahunAjaranId)
                ->update();
            echo "Tahun Ajaran 2025/2026 sudah ada (ID: {$tahunAjaranId})\n";
        }

        // ========== 2. INSERT SEMESTER ==========
        $semesters = [
            [
                'tahun_ajaran_id' => $tahunAjaranId,
                'nama'            => 'Semester Ganjil',
                'kode'            => '1',
                'tanggal_mulai'   => '2025-07-14',
                'tanggal_selesai' => '2025-12-20',
                'is_active'       => 1,
                'created_by'      => 1,
                'created_at'      => $now,
            ],
            [
                'tahun_ajaran_id' => $tahunAjaranId,
                'nama'            => 'Semester Genap',
                'kode'            => '2',
                'tanggal_mulai'   => '2026-01-05',
                'tanggal_selesai' => '2026-06-20',
                'is_active'       => 0,
                'created_by'      => 1,
                'created_at'      => $now,
            ],
        ];

        foreach ($semesters as $semester) {
            $existingSemester = $this->db->table('semester')
                ->where('tahun_ajaran_id', $semester['tahun_ajaran_id'])
                ->where('kode', $semester['kode'])
                ->get()
                ->getRowArray();

            if (!$existingSemester) {
                $this->db->table('semester')->insert($semester);
                echo "Semester {$semester['nama']} ditambahkan\n";
            } else {
                $this->db->table('semester')
                    ->set('is_active', $semester['is_active'])
                    ->where('id', $existingSemester['id'])
                    ->update();
                echo "Semester {$semester['nama']} sudah ada\n";
            }
        }

        // ========== 3. INSERT JURUSAN ==========
        $jurusan = [
            ['kode' => 'RPL', 'singkatan' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak', 'is_active' => 1],
            ['kode' => 'TKJ', 'singkatan' => 'TKJ', 'nama' => 'Teknik Komputer dan Jaringan', 'is_active' => 1],
            ['kode' => 'MM',  'singkatan' => 'MM',  'nama' => 'Multimedia', 'is_active' => 1],
        ];

        foreach ($jurusan as $j) {
            $existingJurusan = $this->db->table('jurusan')
                ->where('kode', $j['kode'])
                ->get()
                ->getRowArray();

            if (!$existingJurusan) {
                $this->db->table('jurusan')->insert($j);
                echo "Jurusan {$j['nama']} ditambahkan\n";
            }
        }

        // ========== 4. INSERT KELAS ==========
        $jurusanIds = [];
        $allJurusan = $this->db->table('jurusan')->get()->getResultArray();
        foreach ($allJurusan as $j) {
            $jurusanIds[$j['kode']] = $j['id'];
        }

        $kelas = [
            ['tingkat' => 'X',  'jurusan_id' => $jurusanIds['RPL'] ?? null, 'rombel' => 'A', 'kapasitas' => 30],
            ['tingkat' => 'X',  'jurusan_id' => $jurusanIds['TKJ'] ?? null, 'rombel' => 'A', 'kapasitas' => 30],
            ['tingkat' => 'X',  'jurusan_id' => $jurusanIds['MM'] ?? null,  'rombel' => 'A', 'kapasitas' => 30],
            ['tingkat' => 'XI', 'jurusan_id' => $jurusanIds['RPL'] ?? null, 'rombel' => 'A', 'kapasitas' => 30],
            ['tingkat' => 'XI', 'jurusan_id' => $jurusanIds['TKJ'] ?? null, 'rombel' => 'A', 'kapasitas' => 30],
            ['tingkat' => 'XI', 'jurusan_id' => $jurusanIds['MM'] ?? null,  'rombel' => 'A', 'kapasitas' => 30],
            ['tingkat' => 'XII','jurusan_id' => $jurusanIds['RPL'] ?? null, 'rombel' => 'A', 'kapasitas' => 30],
            ['tingkat' => 'XII','jurusan_id' => $jurusanIds['TKJ'] ?? null, 'rombel' => 'A', 'kapasitas' => 30],
            ['tingkat' => 'XII','jurusan_id' => $jurusanIds['MM'] ?? null,  'rombel' => 'A', 'kapasitas' => 30],
        ];

        $inserted = 0;
        $skipped = 0;

        foreach ($kelas as $k) {
            $existing = $this->db->table('kelas')
                ->where('tingkat', $k['tingkat'])
                ->where('jurusan_id', $k['jurusan_id'])
                ->where('rombel', $k['rombel'])
                ->get()
                ->getRowArray();

            if (!$existing) {
                $k['is_active'] = 1;
                $k['created_at'] = $now;
                $this->db->table('kelas')->insert($k);
                $inserted++;
            } else {
                $skipped++;
            }
        }

        echo "Kelas: {$inserted} ditambahkan, {$skipped} sudah ada (dilewati)\n";

        // ========== 5. INSERT MATA PELAJARAN ==========
        $mapel = [
            ['kode' => 'MTK', 'nama' => 'Matematika'],
            ['kode' => 'BIN', 'nama' => 'Bahasa Indonesia'],
            ['kode' => 'BIG', 'nama' => 'Bahasa Inggris'],
            ['kode' => 'AGM', 'nama' => 'Agama'],
            ['kode' => 'PKN', 'nama' => 'PKN'],
            ['kode' => 'PJK', 'nama' => 'Penjaskes'],
            ['kode' => 'PWD', 'nama' => 'Pemrograman Web Dasar'],
            ['kode' => 'PWL', 'nama' => 'Pemrograman Web Lanjut'],
            ['kode' => 'BDT', 'nama' => 'Basis Data'],
            ['kode' => 'JRG', 'nama' => 'Jaringan Komputer'],
        ];

        foreach ($mapel as $m) {
            $existingMapel = $this->db->table('mata_pelajaran')
                ->where('kode', $m['kode'])
                ->get()
                ->getRowArray();

            if (!$existingMapel) {
                $this->db->table('mata_pelajaran')->insert($m);
            }
        }
        echo "Mata Pelajaran: " . count($mapel) . " data\n";

        // ========== 6. UPDATE PENGATURAN SEKOLAH ==========
        $pengaturan = $this->db->table('pengaturan_sekolah')->get()->getRowArray();

        if (!$pengaturan) {
            $this->db->table('pengaturan_sekolah')->insert([
                'nama_sekolah'       => 'SMKS Diaspora',
                'npsn'               => '12345678',
                'alamat'             => 'Jl. Pendidikan No. 123, Jakarta',
                'jam_masuk'          => '07:00',
                'jam_toleransi'      => '07:15',
                'jam_pulang'         => '14:00',
                'tahun_ajaran_aktif' => $tahunAjaranId,
                'created_by'         => 1,
                'created_at'         => $now,
            ]);
            echo "Pengaturan sekolah ditambahkan\n";
        } else {
            $this->db->table('pengaturan_sekolah')
                ->set('tahun_ajaran_aktif', $tahunAjaranId)
                ->where('id', $pengaturan['id'])
                ->update();
            echo "Pengaturan sekolah diupdate\n";
        }

        // ========== AKTIFKAN KEMBALI FOREIGN KEY CHECKS ==========
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "\n==============================\n";
        echo "  AKADEMIK SEEDER SELESAI! ✅\n";
        echo "==============================\n";
        echo "Login Admin: admin01 / admin123\n";
        echo "Login Guru : guru1 / guru123\n";
    }
}