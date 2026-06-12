<div class="page-title">👤 Profil Saya</div>
<div class="page-subtitle">Informasi data diri Anda</div>

<div class="card" style="max-width:600px;margin:0 auto;">
    <!-- Foto & Info Dasar -->
    <div class="card-body" style="text-align:center;padding:30px;">
        <?php 
        $fotoPath = '';
        if (!empty($siswa['foto'])) {
            if (file_exists(FCPATH . 'uploads/siswa/' . $siswa['foto'])) {
                $fotoPath = base_url('uploads/siswa/' . $siswa['foto']);
            } elseif (file_exists(FCPATH . 'uploads/foto/' . $siswa['foto'])) {
                $fotoPath = base_url('uploads/foto/' . $siswa['foto']);
            }
        }
        ?>

        <?php if (!empty($fotoPath)): ?>
            <img src="<?= $fotoPath ?>" 
                 style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #E5E7EB;">
        <?php else: ?>
            <div style="width:90px;height:90px;background:linear-gradient(135deg,#2563EB,#7C3AED);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:36px;border:3px solid #E5E7EB;">
                <?= esc(strtoupper(substr($siswa['nama_lengkap'] ?? 'S', 0, 1))) ?>
            </div>
        <?php endif; ?>
        
        <h3 style="margin-top:12px;font-size:20px;"><?= esc($siswa['nama_lengkap'] ?? '-') ?></h3>
        <p style="color:#6B7280;margin:4px 0;">NIS: <?= esc($siswa['nis'] ?? '-') ?></p>
        
        <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;">
            <span class="badge badge-purple">🧑‍🎓 Siswa</span>
            <?php if (!empty($nama_kelas)): ?>
                <span class="badge badge-info">🏫 <?= esc($nama_kelas) ?></span>
            <?php endif; ?>
            <?= badgeAktif($siswa['is_active'] ?? 1) ?>
        </div>
    </div>

    <!-- Detail Data -->
    <div style="border-top:1px solid #E5E7EB;padding:20px 24px;">
        <table style="width:100%;">
            <tr>
                <td style="padding:10px 0;width:140px;font-weight:600;color:#6B7280;">NIS</td>
                <td style="padding:10px 0;">: <?= esc($siswa['nis'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-weight:600;color:#6B7280;">NISN</td>
                <td style="padding:10px 0;">: <?= esc($siswa['nisn'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-weight:600;color:#6B7280;">Nama Lengkap</td>
                <td style="padding:10px 0;">: <strong><?= esc($siswa['nama_lengkap'] ?? '-') ?></strong></td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-weight:600;color:#6B7280;">Jenis Kelamin</td>
                <td style="padding:10px 0;">: <?= ($siswa['jenis_kelamin'] ?? '') == 'L' ? '👨 Laki-laki' : '👩 Perempuan' ?></td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-weight:600;color:#6B7280;">Tempat, Tgl Lahir</td>
                <td style="padding:10px 0;">: <?= esc($siswa['tempat_lahir'] ?? '-') ?>, <?= !empty($siswa['tanggal_lahir']) && $siswa['tanggal_lahir'] != '0000-00-00' ? formatTanggal($siswa['tanggal_lahir'], 'd F Y') : '-' ?></td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-weight:600;color:#6B7280;">Kelas</td>
                <td style="padding:10px 0;">: <?= esc($nama_kelas ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-weight:600;color:#6B7280;">Alamat</td>
                <td style="padding:10px 0;">: <?= esc($siswa['alamat'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-weight:600;color:#6B7280;">No HP</td>
                <td style="padding:10px 0;">: <?= esc($siswa['no_hp'] ?? '-') ?></td>
            </tr>
            <tr>
                <td style="padding:10px 0;font-weight:600;color:#6B7280;">Email</td>
                <td style="padding:10px 0;">: <?= esc($siswa['email'] ?? '-') ?></td>
            </tr>
        </table>
    </div>
</div>

<div style="text-align:center;margin-top:20px;">
    <a href="/siswa" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>