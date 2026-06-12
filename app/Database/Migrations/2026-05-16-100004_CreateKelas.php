<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateKelas extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE kelas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tingkat VARCHAR(5) NOT NULL,
            jurusan_id INT NULL,
            rombel VARCHAR(10) NULL,
            kapasitas INT DEFAULT 30,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_by INT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_kelas (tingkat, jurusan_id, rombel),
            CONSTRAINT fk_kelas_jurusan FOREIGN KEY (jurusan_id) REFERENCES jurusan(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_kelas_updated FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS kelas");
    }
}