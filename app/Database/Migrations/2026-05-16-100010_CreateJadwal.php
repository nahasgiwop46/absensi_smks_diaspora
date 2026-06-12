<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateJadwal extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE jadwal (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kelas_id INT NOT NULL,
            semester_id INT NOT NULL,
            hari ENUM('senin','selasa','rabu','kamis','jumat','sabtu') NOT NULL,
            jam_mulai TIME NOT NULL,
            jam_selesai TIME NOT NULL,
            mata_pelajaran_id INT NOT NULL,
            guru_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_jadwal (kelas_id, semester_id, hari, jam_mulai),
            CONSTRAINT fk_jadwal_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_jadwal_semester FOREIGN KEY (semester_id) REFERENCES semester(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_jadwal_mapel FOREIGN KEY (mata_pelajaran_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_jadwal_guru FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS jadwal");
    }
}