<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateNotifikasiAbsensi extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE notifikasi_absensi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            siswa_id INT NOT NULL,
            absensi_id INT NOT NULL,
            tipe ENUM('whatsapp','sms','email') DEFAULT 'whatsapp',
            nomor_tujuan VARCHAR(20) NULL,
            status_kirim ENUM('pending','terkirim','gagal') DEFAULT 'pending',
            pesan TEXT NULL,
            dikirim_oleh INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_notif_siswa FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_notif_absensi FOREIGN KEY (absensi_id) REFERENCES absensi(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_notif_user FOREIGN KEY (dikirim_oleh) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS notifikasi_absensi");
    }
}