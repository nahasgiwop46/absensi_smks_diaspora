<div class="page-title">📆 Tahun Ajaran</div>
<div class="page-subtitle">Kelola tahun ajaran dan semester</div>

<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;">
    <a href="/admin/tahun-ajaran/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Tahun Ajaran
    </a>
    <a href="/admin/semester" class="btn btn-outline">
        <i class="fas fa-clock"></i> Kelola Semester
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Daftar Tahun Ajaran
        </div>
        <span style="font-size:14px;color:#6B7280;"><?= count($tahun_ajaran ?? []) ?> data</span>
    </div>
    <div class="card-body">
        <?php if (empty($tahun_ajaran)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar" style="font-size:64px;"></i>
                <p style="font-size:18px;">Belum ada tahun ajaran</p>
                <a href="/admin/tahun-ajaran/create" class="btn btn-primary mt-2">
                    <i class="fas fa-plus"></i> Tambah Tahun Ajaran
                </a>
            </div>
        <?php else: ?>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Ajaran</th>
                        <th>📅 Tgl Mulai</th>
                        <th>📅 Tgl Selesai</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($tahun_ajaran as $ta): ?>
                    <tr style="<?= ($ta['is_active'] ?? 0) == 1 ? 'background:#F0FDF4;' : '' ?>">
                        <td><?= $no++ ?></td>
                        <td>
                            <strong><?= esc($ta['nama'] ?? '') ?></strong>
                            <?php if (($ta['is_active'] ?? 0) == 1): ?>
                                <span class="badge badge-success" style="margin-left:6px;">🔵 Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td><?= formatTanggal($ta['tanggal_mulai'] ?? '', 'd/m/Y') ?></td>
                        <td><?= formatTanggal($ta['tanggal_selesai'] ?? '', 'd/m/Y') ?></td>
                        <td>
                            <a href="/admin/semester?tahun_ajaran_id=<?= $ta['id'] ?>" style="text-decoration:none;">
                                <span class="badge badge-info"><?= esc($ta['total_semester'] ?? 0) ?> Semester</span>
                            </a>
                        </td>
                        <td>
                            <?php if (($ta['is_active'] ?? 0) == 1): ?>
                                <span class="badge badge-success">✅ Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-light">⚪ Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" style="justify-content:center;">
                                <a href="/admin/tahun-ajaran/detail/<?= $ta['id'] ?>" class="btn-icon view" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if (($ta['is_active'] ?? 0) != 1): ?>
                                <a href="/admin/tahun-ajaran/aktifkan/<?= $ta['id'] ?>" 
                                   class="btn-icon edit" title="Aktifkan"
                                   onclick="return confirm('Aktifkan tahun ajaran «<?= esc($ta['nama']) ?>»? Semua semester di tahun ajaran lain akan dinonaktifkan.')">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                                <?php endif; ?>
                                <a href="/admin/tahun-ajaran/edit/<?= $ta['id'] ?>" class="btn-icon edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if (($ta['is_active'] ?? 0) != 1): ?>
                                <a href="/admin/tahun-ajaran/delete/<?= $ta['id'] ?>" 
                                   class="btn-icon delete" title="Hapus"
                                   onclick="return confirm('Hapus tahun ajaran «<?= esc($ta['nama']) ?>»? Semua data terkait akan terpengaruh.')">
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