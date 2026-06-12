<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateMasterRole extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE master_role (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kode VARCHAR(20) NOT NULL UNIQUE,
            nama VARCHAR(50) NOT NULL,
            deskripsi TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS master_role");
    }
}