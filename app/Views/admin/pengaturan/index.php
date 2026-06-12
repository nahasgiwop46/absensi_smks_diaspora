<div class="page-title">⚙️ Pengaturan Sekolah</div>
<div class="page-subtitle">Konfigurasi sistem absensi</div>

<!-- Tab Navigation -->
<div style="display:flex;gap:4px;margin-bottom:20px;border-bottom:2px solid #E5E7EB;padding-bottom:0;">
    <a href="/admin/pengaturan" class="tab-link active" style="padding:10px 20px;border-bottom:2px solid #2563EB;color:#2563EB;font-weight:600;text-decoration:none;margin-bottom:-2px;">
        🏫 Informasi Sekolah
    </a>
    <a href="/admin/pengaturan/status-absensi" class="tab-link" style="padding:10px 20px;color:#6B7280;text-decoration:none;">
        📋 Status Absensi
    </a>
</div>

<!-- Informasi Sekolah -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-school"></i> Informasi Sekolah
        </div>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/pengaturan/update') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group" style="flex:2;">
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" class="form-input" 
                           value="<?= old('nama_sekolah', $pengaturan['nama_sekolah'] ?? 'SMKS Diaspora') ?>"
                           placeholder="Nama lengkap sekolah">
                </div>
                <div class="form-group" style="flex:1;">
                    <label class="form-label">NPSN</label>
                    <input type="text" name="npsn" class="form-input" 
                           value="<?= old('npsn', $pengaturan['npsn'] ?? '') ?>"
                           placeholder="Nomor NPSN">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Kepala Sekolah</label>
                    <input type="text" name="kepala_sekolah" class="form-input" 
                           value="<?= old('kepala_sekolah', $pengaturan['kepala_sekolah'] ?? '') ?>"
                           placeholder="Nama kepala sekolah">
                </div>
                <div class="form-group" style="flex:1;">
                    <label class="form-label">NIP Kepala Sekolah</label>
                    <input type="text" name="nip_kepsek" class="form-input" 
                           value="<?= old('nip_kepsek', $pengaturan['nip_kepsek'] ?? '') ?>"
                           placeholder="NIP kepala sekolah">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-input" 
                           value="<?= old('telepon', $pengaturan['telepon'] ?? '') ?>"
                           placeholder="Nomor telepon sekolah">
                </div>
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" 
                           value="<?= old('email', $pengaturan['email'] ?? '') ?>"
                           placeholder="Email resmi sekolah">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-textarea" rows="3" 
                          placeholder="Alamat lengkap sekolah"><?= old('alamat', $pengaturan['alamat'] ?? '') ?></textarea>
            </div>

            <!-- Info Tahun Ajaran & Semester -->
            <div class="alert alert-info" style="margin-top:16px;">
                <i class="fas fa-info-circle"></i>
                <strong>Tahun Ajaran & Semester</strong> diatur melalui menu 
                <a href="/admin/tahun-ajaran">Tahun Ajaran</a> dan 
                <a href="/admin/semester">Semester</a>.
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Info Sistem -->
<div class="card" style="margin-top:20px;">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-database"></i> Informasi Sistem
        </div>
    </div>
    <div class="card-body">
        <div class="table">
            <table>
                <tr>
                    <td width="200"><strong>Tahun Ajaran Aktif</strong></td>
                    <td>: <?= esc($tahunAjaranAktif['nama'] ?? 'Belum diatur') ?></td>
                </tr>
                <tr>
                    <td><strong>Semester Aktif</strong></td>
                    <td>: <?= esc($semesterAktif['nama'] ?? 'Belum diatur') ?></td>
                </tr>
                <tr>
                    <td><strong>Periode</strong></td>
                    <td>: <?= !empty($semesterAktif) ? formatTanggal($semesterAktif['tanggal_mulai'], 'd F Y') . ' - ' . formatTanggal($semesterAktif['tanggal_selesai'], 'd F Y') : '-' ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<style>
.tab-link.active{background:transparent;}
.tab-link:hover{color:#2563EB;}
</style>