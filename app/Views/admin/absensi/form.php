<div class="page-title">Input Absensi Manual</div>
<div class="page-subtitle">Tanggal: <?= formatTanggal($tanggal ?? date('Y-m-d'), 'l, d F Y') ?></div>

<a href="/admin/absensi" class="btn btn-outline" style="margin-bottom:16px;"><i class="fas fa-arrow-left"></i> Kembali</a>

<!-- Filter Kelas -->
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
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>
        </form>
    </div>
</div>

<?php if (!empty($siswa)): ?>
<form action="/admin/absensi/store" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="tanggal" value="<?= esc($tanggal ?? date('Y-m-d')) ?>">
    <input type="hidden" name="kelas_id" value="<?= esc($selected_kelas) ?>">

    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Siswa (<?= count($siswa) ?>)</div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Absensi</button>
        </div>
        <div class="card-body">
            <div class="table">
                <table style="min-width:700px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Terlambat (mnt)</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($siswa as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($s['nis'] ?? '-') ?></td>
                            <td><strong><?= esc($s['nama_lengkap'] ?? '') ?></strong></td>
                            <td>
                                <select name="absensi[<?= $s['id'] ?>][status_id]" class="form-select" style="min-width:120px;">
                                    <?php foreach ($status as $st): ?>
                                    <option value="<?= $st['id'] ?>" <?= $st['kode'] === 'HADIR' ? 'selected' : '' ?>>
                                        <?= esc($st['label']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="absensi[<?= $s['id'] ?>][menit_keterlambatan]" 
                                       class="form-input" value="0" min="0" style="width:80px;">
                            </td>
                            <td>
                                <input type="text" name="absensi[<?= $s['id'] ?>][keterangan]" 
                                       class="form-input" placeholder="Keterangan...">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-hand-pointer"></i>
                <p>Pilih kelas terlebih dahulu</p>
            </div>
        </div>
    </div>
<?php endif; ?>