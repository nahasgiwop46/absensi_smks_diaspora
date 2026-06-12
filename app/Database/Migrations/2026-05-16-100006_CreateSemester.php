<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateSemester extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE semester (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tahun_ajaran_id INT NOT NULL,
            nama VARCHAR(20) NOT NULL,
            kode ENUM('1','2') NOT NULL,
            tanggal_mulai DATE NOT NULL,
            tanggal_selesai DATE NOT NULL,
            is_active TINYINT(1) DEFAULT 0,
            created_by INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_by INT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_semester (tahun_ajaran_id, kode),
            CONSTRAINT fk_semester_tahun FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_semester_created FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_semester_updated FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS semester");
    }
}