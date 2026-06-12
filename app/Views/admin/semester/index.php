<div class="page-title">📅 Data Semester</div>
<div class="page-subtitle">Kelola data semester</div>

<!-- Filter -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-body">
        <div class="toolbar">
            <select class="filter-select" onchange="window.location='?tahun_ajaran_id='+this.value" style="min-width:250px;">
                <option value="">📆 Semua Tahun Ajaran</option>
                <?php foreach ($tahun_ajaran as $ta): ?>
                <option value="<?= $ta['id'] ?>" <?= ($selected_ta ?? '') == $ta['id'] ? 'selected' : '' ?>>
                    <?= esc($ta['nama'] ?? '') ?>
                </option>
                <?php endforeach; ?>
            </select>
            <a href="/admin/semester/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Semester
            </a>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Daftar Semester
        </div>
        <span style="font-size:13px;color:#6B7280;"><?= count($semester ?? []) ?> data</span>
    </div>
    <div class="card-body">
        <?php if (empty($semester)): ?>
            <div class="empty-state">
                <i class="fas fa-clock" style="font-size:64px;"></i>
                <p style="font-size:18px;">
                    <?= !empty($selected_ta) ? 'Belum ada semester untuk tahun ajaran ini' : 'Pilih Tahun Ajaran terlebih dahulu' ?>
                </p>
                <?php if (!empty($selected_ta)): ?>
                <a href="/admin/semester/create" class="btn btn-primary mt-2">
                    <i class="fas fa-plus"></i> Tambah Semester
                </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kode</th>
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
                        <td><strong><?= esc($s['nama'] ?? '') ?></strong></td>
                        <td>
                            <span class="badge <?= ($s['kode'] ?? '') == '1' ? 'badge-primary' : 'badge-info' ?>">
                                <?= ($s['kode'] ?? '') == '1' ? '1️⃣ Ganjil' : '2️⃣ Genap' ?>
                            </span>
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
                                <?php if (($s['is_active'] ?? 0) != 1): ?>
                                <a href="/admin/semester/aktifkan/<?= $s['id'] ?>" 
                                   class="btn-icon view" title="Aktifkan"
                                   onclick="return confirm('Aktifkan semester «<?= esc($s['nama']) ?>»?')">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                                <?php endif; ?>
                                <a href="/admin/semester/edit/<?= $s['id'] ?>" class="btn-icon edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if (($s['is_active'] ?? 0) != 1): ?>
                                <a href="/admin/semester/delete/<?= $s['id'] ?>" 
                                   class="btn-icon delete" title="Hapus"
                                   onclick="return confirm('Hapus semester «<?= esc($s['nama']) ?>»?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
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