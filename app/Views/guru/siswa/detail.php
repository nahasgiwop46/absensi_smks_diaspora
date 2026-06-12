<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
    <div>
        <h2 class="page-title" style="margin-bottom:4px;">
            <i class="fas fa-user" style="color:#059669;"></i> Detail Siswa
        </h2>
        <p class="page-subtitle">Informasi lengkap siswa</p>
    </div>
    <a href="/guru/siswa" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<?php if (!empty($siswa)): ?>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-id-card"></i> Data Pribadi</div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:20px;">
            
            <!-- Foto & Identitas Utama -->
            <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                <?php if (!empty($siswa['foto'])): ?>
                    <img src="/uploads/<?= esc($siswa['foto']) ?>" 
                         alt="<?= esc($siswa['nama_lengkap']) ?>" 
                         style="width:150px;height:150px;border-radius:8px;object-fit:cover;border:3px solid #E5E7EB;">
                <?php else: ?>
                    <div style="width:150px;height:150px;border-radius:8px;background:#E5E7EB;display:flex;align-items:center;justify-content:center;color:#9CA3AF;">
                        <i class="fas fa-user-circle" style="font-size:60px;"></i>
                    </div>
                <?php endif; ?>
                <h3 style="margin:0;text-align:center;color:#1F2937;">
                    <?= esc($siswa['nama_lengkap']) ?>
                </h3>
                <div style="text-align:center;color:#6B7280;font-size:14px;">
                    <div>NIS: <strong><?= esc($siswa['nis']) ?></strong></div>
                    <div>NISN: <strong><?= esc($siswa['nisn'] ?? '-') ?></strong></div>
                </div>
            </div>

            <!-- Informasi Detail -->
            <div style="display:flex;flex-direction:column;gap:12px;">
                
                <!-- Jenis Kelamin -->
                <div style="display:flex;align-items:center;gap:8px;">
                    <label style="min-width:100px;font-weight:600;color:#374151;">Jenis Kelamin</label>
                    <div>
                        <?php 
                        $jk = $siswa['jenis_kelamin'] ?? '';
                        $jkText = ($jk === 'L') ? 'Laki-laki' : (($jk === 'P') ? 'Perempuan' : '-');
                        ?>
                        <?= esc($jkText) ?>
                    </div>
                </div>

                <!-- Tempat Lahir -->
                <div style="display:flex;align-items:flex-start;gap:8px;">
                    <label style="min-width:100px;font-weight:600;color:#374151;">Tempat Lahir</label>
                    <div><?= esc($siswa['tempat_lahir'] ?? '-') ?></div>
                </div>

                <!-- Tanggal Lahir -->
                <div style="display:flex;align-items:center;gap:8px;">
                    <label style="min-width:100px;font-weight:600;color:#374151;">Tanggal Lahir</label>
                    <div>
                        <?php 
                        if (!empty($siswa['tanggal_lahir'])) {
                            echo esc(date('d F Y', strtotime($siswa['tanggal_lahir'])));
                        } else {
                            echo '-';
                        }
                        ?>
                    </div>
                </div>

                <!-- Email -->
                <div style="display:flex;align-items:center;gap:8px;">
                    <label style="min-width:100px;font-weight:600;color:#374151;">Email</label>
                    <div style="word-break:break-all;">
                        <?= !empty($siswa['email']) ? '<a href="mailto:' . esc($siswa['email']) . '">' . esc($siswa['email']) . '</a>' : '-' ?>
                    </div>
                </div>

                <!-- No HP -->
                <div style="display:flex;align-items:center;gap:8px;">
                    <label style="min-width:100px;font-weight:600;color:#374151;">No HP</label>
                    <div>
                        <?= !empty($siswa['no_hp']) ? '<a href="tel:' . esc($siswa['no_hp']) . '">' . esc($siswa['no_hp']) . '</a>' : '-' ?>
                    </div>
                </div>

                <!-- Status -->
                <div style="display:flex;align-items:center;gap:8px;">
                    <label style="min-width:100px;font-weight:600;color:#374151;">Status</label>
                    <div>
                        <?php if (($siswa['is_active'] ?? 1) == 1): ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Nonaktif</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Alamat -->
<div class="card" style="margin-top:16px;">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-map-marker-alt"></i> Alamat</div>
    </div>
    <div class="card-body">
        <p style="margin:0;line-height:1.6;color:#374151;">
            <?= !empty($siswa['alamat']) ? nl2br(esc($siswa['alamat'])) : '<span style="color:#9CA3AF;">Tidak ada data alamat</span>' ?>
        </p>
    </div>
</div>

<!-- QR Code (jika ada) -->
<?php if (!empty($siswa['qr_code'])): ?>
<div class="card" style="margin-top:16px;">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-qrcode"></i> QR Code</div>
    </div>
    <div class="card-body" style="display:flex;justify-content:center;">
        <img src="/uploads/<?= esc($siswa['qr_code']) ?>" 
             alt="QR Code" 
             style="width:200px;height:200px;border:2px solid #E5E7EB;padding:4px;">
    </div>
</div>
<?php endif; ?>

<!-- Aksi -->
<div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap;">
    <a href="/guru/absensi/riwayat/<?= $siswa['id'] ?>" class="btn btn-primary">
        <i class="fas fa-clock"></i> Riwayat Absensi
    </a>
    <a href="/guru/siswa" class="btn btn-outline">
        <i class="fas fa-times"></i> Tutup
    </a>
</div>

<?php else: ?>

<div class="empty-state">
    <i class="fas fa-user-slash"></i>
    <p>Siswa tidak ditemukan</p>
    <a href="/guru/siswa" class="btn btn-outline">Kembali ke Daftar Siswa</a>
</div>

<?php endif; ?>
