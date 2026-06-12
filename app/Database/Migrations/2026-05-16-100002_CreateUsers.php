<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            role_id INT NOT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            nama_lengkap VARCHAR(100) NOT NULL,
            nuptk VARCHAR(30) NULL UNIQUE,
            nip VARCHAR(30) NULL UNIQUE,
            jenis_kelamin ENUM('L','P') NULL,
            tempat_lahir VARCHAR(50) NULL,
            tanggal_lahir DATE NULL,
            alamat TEXT NULL,
            email VARCHAR(100) NULL,
            no_hp VARCHAR(20) NULL,
            foto VARCHAR(255) NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_by INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_by INT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES master_role(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_users_created FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_users_updated FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS users");
    }
}