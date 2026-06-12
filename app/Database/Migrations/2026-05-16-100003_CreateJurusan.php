<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateJurusan extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE jurusan (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kode VARCHAR(10) NOT NULL UNIQUE,
            singkatan VARCHAR(10) NULL,
            nama VARCHAR(100) NOT NULL,
            deskripsi TEXT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_by INT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_jurusan_updated FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS jurusan");
    }
}