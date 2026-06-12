<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateTahunAjaran extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE tahun_ajaran (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(50) NOT NULL,
            tanggal_mulai DATE NULL,
            tanggal_selesai DATE NULL,
            is_active TINYINT(1) DEFAULT 0,
            created_by INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_tahun_created FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS tahun_ajaran");
    }
}