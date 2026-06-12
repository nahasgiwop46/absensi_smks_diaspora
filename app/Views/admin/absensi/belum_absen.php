<div class="page-title">Siswa Belum Absen</div>
<div class="page-subtitle">Tanggal: <?= formatTanggal($tanggal ?? date('Y-m-d'), 'l, d F Y') ?></div>

<a href="/admin/absensi" class="btn btn-outline" style="margin-bottom:16px;"><i class="fas fa-arrow-left"></i> Kembali</a>

<!-- Filter -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-body">
        <form method="get" class="toolbar">
            <div style="min-width:220px;">
                <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= ($selected_kelas ?? '') == $k['id'] ? 'selected' : '' ?>>
                        <?= esc($k['tingkat'] . ' ' . ($k['jurusan_singkatan'] ?? '') . ' ' . $k['rombel']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="min-width:150px;">
                <input type="date" name="tanggal" class="form-input" value="<?= esc($tanggal ?? date('Y-m-d')) ?>">
            </div>
            <button type="submit" class="btn btn-outline"><i class="fas fa-search"></i> Tampilkan</button>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Siswa Belum Absen</div>
        <span style="font-size:13px;color:#6B7280;"><?= count($belum_absen ?? []) ?> siswa</span>
    </div>
    <div class="card-body">
        <?php if (empty($belum_absen)): ?>
            <div class="empty-state">
                <i class="fas fa-check-circle" style="color:#10B981;"></i>
                <p><?= $selected_kelas ? 'Semua siswa sudah absen 🎉' : 'Pilih kelas terlebih dahulu' ?></p>
            </div>
        <?php else: ?>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($belum_absen as $b): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($b['nis'] ?? '-') ?></td>
                        <td><strong><?= esc($b['nama_lengkap'] ?? '-') ?></strong></td>
                        <td>
                            <a href="/admin/absensi/create?kelas_id=<?= $selected_kelas ?>&tanggal=<?= $tanggal ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-user-check"></i> Absen Manual
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>