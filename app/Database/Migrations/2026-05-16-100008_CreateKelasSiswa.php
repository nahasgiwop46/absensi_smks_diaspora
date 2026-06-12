<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateKelasSiswa extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE kelas_siswa (
            id INT AUTO_INCREMENT PRIMARY KEY,
            siswa_id INT NOT NULL,
            kelas_id INT NOT NULL,
            tahun_ajaran_id INT NOT NULL,
            semester_id INT NULL,
            wali_kelas_id INT NULL,
            tanggal_masuk DATE NULL,
            tanggal_keluar DATE NULL,
            status ENUM('aktif','pindah','lulus','dikeluarkan','mengundurkan_diri') DEFAULT 'aktif',
            keterangan TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_siswa_kelas (siswa_id, kelas_id, tahun_ajaran_id),
            CONSTRAINT fk_ks_siswa FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_ks_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_ks_tahun FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_ks_semester FOREIGN KEY (semester_id) REFERENCES semester(id) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_ks_wali FOREIGN KEY (wali_kelas_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
        )");
    }
    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS kelas_siswa");
    }
}