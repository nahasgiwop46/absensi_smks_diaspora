<div class="page-title">Detail Kelas</div>
<div class="page-subtitle">Informasi lengkap data kelas</div>

<div style="display:flex;gap:12px;margin-bottom:20px;">
    <a href="/admin/kelas/edit/<?= $kelas['id'] ?>" class="btn btn-outline"><i class="fas fa-edit"></i> Edit</a>
    <a href="/admin/siswa?kelas_id=<?= $kelas['id'] ?>" class="btn btn-outline"><i class="fas fa-users"></i> Lihat Siswa</a>
    <a href="/admin/jadwal?kelas_id=<?= $kelas['id'] ?>" class="btn btn-outline"><i class="fas fa-calendar-alt"></i> Jadwal</a>
    <a href="/admin/kelas" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<!-- Info Kelas -->
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-info-circle"></i> Informasi Kelas
        </div>
    </div>
    <div class="card-body">
        <div class="table">
            <table>
                <tr>
                    <td width="160"><strong>Nama Kelas</strong></td>
                    <td>: <strong><?= esc($kelas['tingkat']) ?> <?= esc($kelas['jurusan_singkatan'] ?? '') ?> <?= esc($kelas['rombel']) ?></strong></td>
                </tr>
                <tr>
                    <td><strong>Tingkat</strong></td>
                    <td>: <span class="badge badge-primary"><?= esc($kelas['tingkat']) ?></span></td>
                </tr>
                <tr>
                    <td><strong>Jurusan</strong></td>
                    <td>: <?= esc($kelas['jurusan_nama'] ?? 'Umum') ?> (<?= esc($kelas['jurusan_singkatan'] ?? '-') ?>)</td>
                </tr>
                <tr>
                    <td><strong>Kapasitas</strong></td>
                    <td>: <?= esc($kelas['kapasitas'] ?? 0) ?> siswa</td>
                </tr>
                <tr>
                    <td><strong>Jumlah Siswa</strong></td>
                    <td>: <strong><?= count($siswa ?? []) ?></strong> siswa aktif</td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>: <?= badgeAktif($kelas['is_active'] ?? 0) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Daftar Siswa -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-users"></i> Daftar Siswa (<?= count($siswa ?? []) ?>)
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($siswa)): ?>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($siswa as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($s['nis'] ?? '') ?></td>
                        <td><?= esc($s['nisn'] ?? '-') ?></td>
                        <td><strong><?= esc($s['nama_lengkap'] ?? '') ?></strong></td>
                        <td><?= ($s['jenis_kelamin'] ?? '') == 'L' ? '👨 L' : '👩 P' ?></td>
                        <td>
                            <a href="/admin/siswa/detail/<?= $s['id'] ?>" class="btn-icon view" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-user-graduate"></i>
                <p>Belum ada siswa di kelas ini</p>
                <a href="/admin/siswa/penempatan-kelas?kelas_id=<?= $kelas['id'] ?>" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-plus"></i> Tambah Siswa
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>