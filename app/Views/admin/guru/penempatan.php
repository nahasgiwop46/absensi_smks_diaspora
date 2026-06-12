<div class="page-title">Penempatan Guru</div>
<div class="page-subtitle">Lihat guru mengajar di kelas mana</div>

<?php if (empty($semester_aktif)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> Tidak ada semester aktif. Setup dulu di Setup Wizard.
    </div>
<?php else: ?>

<!-- Pilih Guru -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Pilih Guru</label>
            <select class="filter-select" onchange="window.location='?guru_id='+this.value" style="min-width:250px;">
                <option value="">-- Pilih Guru --</option>
                <?php foreach ($guru_list as $g): ?>
                <option value="<?= $g['id'] ?>" <?= ($selected_guru ?? '') == $g['id'] ? 'selected' : '' ?>>
                    <?= esc($g['nama_lengkap']) ?> (<?= esc($g['nuptk'] ?? $g['nip'] ?? '-') ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<?php if (!empty($selected_guru) && !empty($jadwal_guru)): ?>

<!-- Info Guru -->
<?php 
$guruInfo = null;
foreach ($guru_list as $g) {
    if ($g['id'] == $selected_guru) { $guruInfo = $g; break; }
}
?>

<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-chalkboard-teacher"></i> 
            <?= esc($guruInfo['nama_lengkap'] ?? 'Guru') ?>
        </div>
        <span class="badge badge-info"><?= count($jadwal_guru) ?> jadwal</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jadwal_guru as $j): ?>
                    <tr>
                        <td style="text-transform:capitalize;"><strong><?= esc($j['hari']) ?></strong></td>
                        <td><?= esc($j['jam_mulai']) ?> - <?= esc($j['jam_selesai']) ?></td>
                        <td><?= esc($j['mapel_nama'] ?? '-') ?></td>
                        <td><?= esc($j['nama_kelas'] ?? '-') ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="/admin/jadwal/edit/<?= $j['id'] ?>" class="btn-icon edit"><i class="fas fa-edit"></i></a>
                                <a href="/admin/jadwal/delete/<?= $j['id'] ?>" class="btn-icon delete" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tombol Tambah -->
<div style="text-align:center;margin-top:16px;">
    <a href="<?= base_url('admin/jadwal/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Tambah Jadwal untuk Guru Ini
    </a>
</div>

<?php elseif (!empty($selected_guru) && empty($jadwal_guru)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state<div class="page-title">Penempatan Guru</div>
<div class="page-subtitle">Lihat jadwal mengajar guru</div>

<?php if (empty($semester_aktif)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> 
        Tidak ada semester aktif. Silakan <a href="/admin/setup?step=2">setup semester</a> terlebih dahulu.
    </div>
<?php else: ?>

<!-- Pilih Guru -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="get" class="toolbar">
            <div class="form-group" style="min-width:280px;">
                <label class="form-label">Pilih Guru</label>
                <select name="guru_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Pilih Guru --</option>
                    <?php foreach ($guru_list as $g): ?>
                    <option value="<?= $g['id'] ?>" <?= ($selected_guru ?? '') == $g['id'] ? 'selected' : '' ?>>
                        <?= esc($g['nama_lengkap']) ?> (<?= esc($g['nuptk'] ?? $g['nip'] ?? '-') ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($selected_guru)): ?>

<?php 
$guruInfo = null;
foreach ($guru_list as $g) {
    if ($g['id'] == $selected_guru) { $guruInfo = $g; break; }
}
?>

<!-- Info Guru -->
<div style="display:flex;gap:12px;align-items:center;margin-bottom:20px;">
    <div style="width:60px;height:60px;background:#EFF6FF;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:24px;color:#2563EB;">
        <i class="fas fa-chalkboard-teacher"></i>
    </div>
    <div>
        <h3 style="font-size:18px;font-weight:700;margin:0;"><?= esc($guruInfo['nama_lengkap'] ?? 'Guru') ?></h3>
        <p style="color:#6B7280;margin:0;">
            <?= esc($guruInfo['nuptk'] ?? '') ?> <?= !empty($guruInfo['nip']) ? '| NIP: ' . esc($guruInfo['nip']) : '' ?>
        </p>
    </div>
</div>

<?php if (!empty($jadwal_guru)): ?>
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-calendar-alt"></i> Jadwal Mengajar
        </div>
        <span class="badge badge-info"><?= count($jadwal_guru) ?> jadwal</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="100">Hari</th>
                        <th width="150">Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th width="100" style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jadwal_guru as $j): ?>
                    <tr>
                        <td style="text-transform:capitalize;">
                            <strong><?= esc($j['hari']) ?></strong>
                        </td>
                        <td>
                            <span class="badge badge-light"><?= esc($j['jam_mulai']) ?> - <?= esc($j['jam_selesai']) ?></span>
                        </td>
                        <td><?= esc($j['mapel_nama'] ?? '-') ?></td>
                        <td><?= esc($j['nama_kelas'] ?? '-') ?></td>
                        <td>
                            <div class="btn-group" style="justify-content:center;">
                                <a href="/admin/jadwal/edit/<?= $j['id'] ?>" class="btn-icon edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/jadwal/delete/<?= $j['id'] ?>" class="btn-icon delete" title="Hapus"
                                   onclick="return confirm('Hapus jadwal ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="text-align:center;margin-top:16px;">
    <a href="<?= base_url('admin/jadwal/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Tambah Jadwal
    </a>
</div>

<?php else: ?>
<div class="card">
    <div class="card-body">
        <div class="empty-state">
            <i class="fas fa-calendar-times" style="font-size:48px;"></i>
            <p>Guru ini belum memiliki jadwal mengajar</p>
            <a href="<?= base_url('admin/jadwal/create') ?>" class="btn btn-primary mt-2">
                <i class="fas fa-plus"></i> Buat Jadwal
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>

<?php endif; ?>
                <i class="fas fa-calendar-times"></i>
                <p>Guru ini belum memiliki jadwal mengajar</p>
                <a href="<?= base_url('admin/jadwal/create') ?>" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-plus"></i> Buat Jadwal
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php endif; ?>