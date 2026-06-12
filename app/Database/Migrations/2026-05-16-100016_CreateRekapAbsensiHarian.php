<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateRekapAbsensiHarian extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE rekap_absensi_harian (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tanggal DATE NOT NULL,
            kelas_id INT NOT NULL,
            total_siswa INT DEFAULT 0,
            hadir INT DEFAULT 0,
            izin INT DEFAULT 0,
            sakit INT DEFAULT 0,
            alpa INT DEFAULT 0,
            terlambat INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_rekap (tanggal, kelas_id),
            CONSTRAINT fk_rekap_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS rekap_absensi_harian");
    }
}