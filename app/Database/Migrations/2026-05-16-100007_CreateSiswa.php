<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateSiswa extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE siswa (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL UNIQUE,
            nis VARCHAR(20) NOT NULL UNIQUE,
            nisn VARCHAR(20) NULL UNIQUE,
            nama_lengkap VARCHAR(100) NOT NULL,
            jenis_kelamin ENUM('L','P') NULL,
            tempat_lahir VARCHAR(50) NULL,
            tanggal_lahir DATE NULL,
            alamat TEXT NULL,
            no_hp VARCHAR(20) NULL,
            email VARCHAR(100) NULL,
            qr_code TEXT NULL,
            foto VARCHAR(255) NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_by INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_by INT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            CONSTRAINT fk_siswa_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_siswa_created FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_siswa_updated FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS siswa");
    }
}