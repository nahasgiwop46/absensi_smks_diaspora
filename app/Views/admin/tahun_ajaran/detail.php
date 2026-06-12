<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
    <div>
        <h2 class="page-title" style="margin-bottom:4px;">
            <i class="fas fa-calendar" style="color:#2563EB;"></i> Detail Tahun Ajaran
        </h2>
        <p class="page-subtitle">Informasi lengkap tahun ajaran dan semester</p>
    </div>
    <div style="display:flex;gap:8px;">
        <?php if (($tahun_ajaran['is_active'] ?? 0) != 1): ?>
        <a href="/admin/tahun-ajaran/edit/<?= $tahun_ajaran['id'] ?>" class="btn btn-outline btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
        <?php endif; ?>
        <a href="/admin/tahun-ajaran" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Informasi Tahun Ajaran -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-info-circle"></i> Informasi Tahun Ajaran</div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:20px;">
            
            <!-- Nama Tahun Ajaran -->
            <div>
                <label style="display:block;font-weight:600;color:#6B7280;margin-bottom:6px;">📅 Nama Tahun Ajaran</label>
                <div style="font-size:18px;font-weight:600;color:#1F2937;">
                    <?= esc($tahun_ajaran['nama'] ?? '') ?>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label style="display:block;font-weight:600;color:#6B7280;margin-bottom:6px;">Status</label>
                <div>
                    <?php if (($tahun_ajaran['is_active'] ?? 0) == 1): ?>
                        <span class="badge badge-success" style="font-size:14px;padding:8px 12px;">
                            <i class="fas fa-check-circle"></i> Aktif
                        </span>
                    <?php else: ?>
                        <span class="badge badge-light" style="font-size:14px;padding:8px 12px;">
                            <i class="fas fa-circle"></i> Nonaktif
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tanggal Mulai -->
            <div>
                <label style="display:block;font-weight:600;color:#6B7280;margin-bottom:6px;">📅 Tanggal Mulai</label>
                <div style="font-size:16px;color:#1F2937;">
                    <?= formatTanggal($tahun_ajaran['tanggal_mulai'] ?? '', 'd F Y') ?>
                </div>
            </div>

            <!-- Tanggal Selesai -->
            <div>
                <label style="display:block;font-weight:600;color:#6B7280;margin-bottom:6px;">📅 Tanggal Selesai</label>
                <div style="font-size:16px;color:#1F2937;">
                    <?= formatTanggal($tahun_ajaran['tanggal_selesai'] ?? '', 'd F Y') ?>
                </div>
            </div>

            <!-- Durasi -->
            <div>
                <label style="display:block;font-weight:600;color:#6B7280;margin-bottom:6px;">⏱️ Durasi</label>
                <div style="font-size:16px;color:#1F2937;">
                    <?php
                    $mulai = new DateTime($tahun_ajaran['tanggal_mulai'] ?? '');
                    $selesai = new DateTime($tahun_ajaran['tanggal_selesai'] ?? '');
                    $interval = $mulai->diff($selesai);
                    $hari = $interval->days;
                    $bulan = floor($hari / 30);
                    echo $bulan . ' bulan ' . ($hari % 30) . ' hari';
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Data Semester -->
<div class="card" style="margin-top:20px;">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-book"></i> Daftar Semester</div>
        <a href="/admin/semester?tahun_ajaran_id=<?= $tahun_ajaran['id'] ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Semester
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($semester)): ?>
            <div class="empty-state">
                <i class="fas fa-book" style="font-size:48px;"></i>
                <p>Belum ada semester</p>
                <a href="/admin/semester?tahun_ajaran_id=<?= $tahun_ajaran['id'] ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Semester
                </a>
            </div>
        <?php else: ?>
            <div style="padding:0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Semester</th>
                            <th>📅 Tgl Mulai</th>
                            <th>📅 Tgl Selesai</th>
                            <th>Status</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($semester as $s): ?>
                        <tr style="<?= ($s['is_active'] ?? 0) == 1 ? 'background:#F0FDF4;' : '' ?>">
                            <td><?= $no++ ?></td>
                            <td>
                                <strong><?= esc($s['nama'] ?? '') ?></strong>
                                <?php if (($s['is_active'] ?? 0) == 1): ?>
                                    <span class="badge badge-success" style="margin-left:6px;">Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td><?= formatTanggal($s['tanggal_mulai'] ?? '', 'd/m/Y') ?></td>
                            <td><?= formatTanggal($s['tanggal_selesai'] ?? '', 'd/m/Y') ?></td>
                            <td>
                                <?php if (($s['is_active'] ?? 0) == 1): ?>
                                    <span class="badge badge-success">✅ Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-light">⚪ Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" style="justify-content:center;">
                                    <a href="/admin/semester/edit/<?= $s['id'] ?>" class="btn-icon edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ringkasan -->
<div class="card" style="margin-top:20px;">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-chart-pie"></i> Ringkasan</div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));gap:16px;">
            <div style="padding:16px;background:#F3F4F6;border-radius:8px;text-align:center;">
                <div style="font-size:28px;font-weight:700;color:#2563EB;">
                    <?= count($semester ?? []) ?>
                </div>
                <div style="color:#6B7280;">Total Semester</div>
            </div>
            <div style="padding:16px;background:#F3F4F6;border-radius:8px;text-align:center;">
                <div style="font-size:28px;font-weight:700;color:#10B981;">
                    <?= array_sum(array_column($semester ?? [], 'is_active')) ?>
                </div>
                <div style="color:#6B7280;">Semester Aktif</div>
            </div>
        </div>
    </div>
</div>

<!-- Aksi -->
<div style="display:flex;gap:8px;margin-top:20px;flex-wrap:wrap;">
    <?php if (($tahun_ajaran['is_active'] ?? 0) != 1): ?>
    <a href="/admin/tahun-ajaran/aktifkan/<?= $tahun_ajaran['id'] ?>" class="btn btn-success"
       onclick="return confirm('Aktifkan tahun ajaran «<?= esc($tahun_ajaran['nama']) ?>»? Semua tahun ajaran lain akan dinonaktifkan.')">
        <i class="fas fa-check-circle"></i> Aktifkan Tahun Ajaran
    </a>
    <a href="/admin/tahun-ajaran/delete/<?= $tahun_ajaran['id'] ?>" class="btn btn-danger"
       onclick="return confirm('Hapus tahun ajaran «<?= esc($tahun_ajaran['nama']) ?>»? Semua data terkait akan terpengaruh.')">
        <i class="fas fa-trash"></i> Hapus Tahun Ajaran
    </a>
    <?php endif; ?>
    <a href="/admin/tahun-ajaran" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
