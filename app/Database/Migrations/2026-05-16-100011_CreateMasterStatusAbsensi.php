<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateMasterStatusAbsensi extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE master_status_absensi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kode VARCHAR(20) NOT NULL UNIQUE,
            label VARCHAR(50) NOT NULL,
            warna VARCHAR(7) NULL,
            is_hadir TINYINT(1) DEFAULT 1,
            deskripsi TEXT NULL
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS master_status_absensi");
    }
}