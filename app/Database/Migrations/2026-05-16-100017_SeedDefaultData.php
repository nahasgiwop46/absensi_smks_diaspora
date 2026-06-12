<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class SeedDefaultData extends Migration
{
    public function up()
    {
        $this->db->query("INSERT INTO master_role (kode, nama, deskripsi) VALUES
            ('ADMIN', 'Administrator', 'Pengelola sistem'),
            ('GURU', 'Guru', 'Tenaga pengajar'),
            ('SISWA', 'Siswa', 'Peserta didik'),
            ('KEPSEK', 'Kepala Sekolah', 'Pimpinan sekolah')
        ");
        $this->db->query("INSERT INTO users (role_id, username, password, nama_lengkap, email, is_active) VALUES
            (1, 'admin', '".password_hash('admin123', PASSWORD_DEFAULT)."', 'Administrator Sistem', 'admin@smks.sch.id', 1)
        ");
        $this->db->query("INSERT INTO master_status_absensi (kode, label, warna, is_hadir, deskripsi) VALUES
            ('HADIR', 'Hadir', '#28a745', 1, 'Siswa hadir tepat waktu'),
            ('TERLAMBAT', 'Terlambat', '#ffc107', 1, 'Siswa hadir terlambat'),
            ('IZIN', 'Izin', '#17a2b8', 0, 'Siswa tidak hadir karena izin'),
            ('SAKIT', 'Sakit', '#6f42c1', 0, 'Siswa tidak hadir karena sakit'),
            ('ALPA', 'Alpa', '#dc3545', 0, 'Siswa tidak hadir tanpa keterangan')
        ");
        $this->db->query("INSERT INTO jurusan (kode, singkatan, nama) VALUES
            ('TKJ', 'TKJ', 'Teknik Komputer dan Jaringan'),
            ('RPL', 'RPL', 'Rekayasa Perangkat Lunak'),
            ('MM', 'MM', 'Multimedia'),
            ('AKL', 'AKL', 'Akuntansi dan Keuangan Lembaga')
        ");
    }
    public function down()
    {
        $this->db->query("DELETE FROM jurusan");
        $this->db->query("DELETE FROM master_status_absensi");
        $this->db->query("DELETE FROM users");
        $this->db->query("DELETE FROM master_role");
    }
}