<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateQrSessions extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE qr_sessions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            jadwal_id INT NOT NULL,
            token VARCHAR(64) NOT NULL UNIQUE,
            jenis_absensi ENUM('masuk','pulang') NOT NULL,
            tanggal DATE NOT NULL,
            dibuat_oleh INT NOT NULL,
            kelas_id INT NOT NULL,
            jam_mulai_absen DATETIME NOT NULL,
            jam_selesai_absen DATETIME NOT NULL,
            expired_at DATETIME NOT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_qr_jadwal FOREIGN KEY (jadwal_id) REFERENCES jadwal(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_qr_guru FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_qr_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS qr_sessions");
    }
}