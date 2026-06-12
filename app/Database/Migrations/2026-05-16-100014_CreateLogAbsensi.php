<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateLogAbsensi extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE log_absensi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            absensi_id INT NOT NULL,
            aksi ENUM('INSERT','UPDATE','DELETE') NOT NULL,
            field VARCHAR(50) NULL,
            nilai_lama TEXT NULL,
            nilai_baru TEXT NULL,
            user_id INT NULL,
            ip_address VARCHAR(45) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_log_absensi FOREIGN KEY (absensi_id) REFERENCES absensi(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_log_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS log_absensi");
    }
}