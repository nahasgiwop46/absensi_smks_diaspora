<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateMataPelajaran extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE mata_pelajaran (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kode VARCHAR(20) NOT NULL UNIQUE,
            nama VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS mata_pelajaran");
    }
}