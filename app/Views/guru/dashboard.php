<div style="background:linear-gradient(135deg, #059669, #047857);border-radius:20px;padding:32px;color:white;margin-bottom:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <h1 style="font-size:28px;margin-bottom:8px;">Selamat Datang, <?= esc(session()->get('nama_lengkap') ?? 'Guru') ?>!</h1>
            <p style="opacity:0.9;"><i class="fas fa-calendar-alt"></i> <?= date('l, d F Y') ?></p>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a href="/guru/qrcode" class="btn" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);">
                <i class="fas fa-qrcode"></i> Generate QR
            </a>
            <a href="/guru/absensi" class="btn" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);">
                <i class="fas fa-clipboard-list"></i> Absensi Manual
            </a>
            <a href="/guru/scan" class="btn" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);">
                <i class="fas fa-camera"></i> Scan QR
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value"><?= esc($hadir ?? 0) ?>/<?= esc($totalSiswa ?? 0) ?></div>
        <div class="stat-label">Hadir Hari Ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
        <div class="stat-value"><?= esc($terlambat ?? 0) ?></div>
        <div class="stat-label">Terlambat</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fas fa-file-alt"></i></div>
        <div class="stat-value"><?= esc($izin ?? 0) ?></div>
        <div class="stat-label">Izin</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FED7AA;color:#9A3412;"><i class="fas fa-times-circle"></i></div>
        <div class="stat-value"><?= esc($alpa ?? 0) ?></div>
        <div class="stat-label">Alpa</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-broadcast-tower"></i></div>
        <div class="stat-value"><?= esc($sesi_aktif ?? 0) ?></div>
        <div class="stat-label">Sesi Aktif</div>
    </div>
</div>

<!-- Jadwal + Absensi -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <!-- Absensi Hari Ini -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-clipboard-check" style="color:#059669;"></i> Absensi Hari Ini</div>
            <a href="/guru/absensi" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (!empty($absensi)): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>NIS</th><th>Nama</th><th>Status</th><th>Jam</th></tr></thead>
                        <tbody>
                            <?php foreach ($absensi as $a): 
                                $label = strtolower($a['status_label'] ?? '');
                                $badge = match($label) {
                                    'hadir' => 'badge-success', 'izin' => 'badge-warning',
                                    'sakit' => 'badge-info', 'alpa' => 'badge-danger',
                                    default => 'badge-info'
                                };
                            ?>
                            <tr>
                                <td><?= esc($a['nis'] ?? '-') ?></td>
                                <td><strong><?= esc($a['nama_lengkap'] ?? '') ?></strong></td>
                                <td><span class="badge <?= $badge ?>"><?= esc($a['status_label'] ?? '') ?></span></td>
                                <td><?= esc(substr($a['jam_absen'] ?? '-', 0, 5)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state"><i class="fas fa-clipboard-list"></i><p>Belum ada absensi hari ini</p></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-calendar-week" style="color:#059669;"></i> Jadwal Hari Ini</div>
        </div>
        <div class="card-body">
            <?php if (!empty($jadwal)): ?>
                <?php foreach ($jadwal as $i => $j): ?>
                <div style="padding:12px;border-radius:8px;margin-bottom:12px;<?= $i === 0 ? 'background:#ECFDF5;border-left:3px solid #059669;' : 'border-left:3px solid #E5E7EB;' ?>">
                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                        <div>
                            <?php if ($i === 0): ?>
                                <span class="badge badge-success">🔵 Berlangsung</span>
                            <?php endif; ?>
                            <h3 style="font-size:16px;margin:4px 0;"><?= esc($j['mapel'] ?? '') ?></h3>
                            <p style="color:#6B7280;font-size:13px;">
                                <i class="fas fa-clock"></i> <?= esc($j['jam_mulai'] ?? '') ?> - <?= esc($j['jam_selesai'] ?? '') ?> 
                                | 🏫 <?= esc($j['nama_kelas'] ?? '') ?>
                            </p>
                        </div>
                        <?php if ($i === 0): ?>
                        <a href="/guru/qrcode" class="btn btn-primary btn-sm"><i class="fas fa-qrcode"></i> Buka Absen QR</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state"><i class="fas fa-calendar"></i><p>Tidak ada jadwal hari ini</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Tombol Notifikasi -->
<div style="margin-top:16px;text-align:right;">
    <a href="/notifikasi/kirimSemuaAlpa" class="btn btn-outline" 
       style="color:#EF4444;border-color:#EF4444;"
       onclick="return confirm('Kirim notifikasi WhatsApp ke orang tua siswa yang ALPA?')">
        <i class="fab fa-whatsapp"></i> Kirim Notif Alpa
    </a>
</div>