<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateAbsensi extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE absensi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            siswa_id INT NOT NULL,
            jadwal_id INT NULL,
            qr_session_id INT NULL,
            tanggal DATE NOT NULL,
            jam_absen DATETIME NOT NULL,
            tipe_absensi ENUM('masuk','pulang') DEFAULT 'masuk',
            status_id INT NOT NULL DEFAULT 1,
            metode_absensi ENUM('manual_guru','scan_qr_guru','scan_qr_siswa','fingerprint','online') DEFAULT 'scan_qr_guru',
            menit_keterlambatan SMALLINT UNSIGNED DEFAULT 0,
            keterangan TEXT NULL,
            koordinat POINT NULL,
            device_info VARCHAR(255) NULL,
            foto_absensi VARCHAR(255) NULL,
            petugas_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            UNIQUE KEY unique_absensi (siswa_id, jadwal_id, tanggal, tipe_absensi),
            CONSTRAINT fk_absensi_siswa FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_absensi_jadwal FOREIGN KEY (jadwal_id) REFERENCES jadwal(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_absensi_qr FOREIGN KEY (qr_session_id) REFERENCES qr_sessions(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_absensi_status FOREIGN KEY (status_id) REFERENCES master_status_absensi(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_absensi_petugas FOREIGN KEY (petugas_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS absensi");
    }
}