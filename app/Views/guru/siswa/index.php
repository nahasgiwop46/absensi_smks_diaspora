<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
    <div>
        <h2 class="page-title" style="margin-bottom:4px;">
            <i class="fas fa-users" style="color:#059669;"></i> Daftar Siswa
        </h2>
        <p class="page-subtitle">Siswa yang Anda ajar</p>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-users"></i></div>
        <div class="stat-value text-success"><?= count($siswa ?? []) ?></div>
        <div class="stat-label">Total Siswa</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-door-open"></i></div>
        <div class="stat-value text-info"><?= count($kelas ?? []) ?></div>
        <div class="stat-label">Kelas Diampu</div>
    </div>
</div>

<!-- Filter Kelas -->
<?php if (!empty($kelas)): ?>
<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
    <a href="/guru/siswa" class="btn btn-sm <?= empty($filter_kelas) ? 'btn-primary' : 'btn-outline' ?>">Semua</a>
    <?php foreach ($kelas as $k): ?>
    <a href="/guru/siswa?kelas=<?= $k['id'] ?>" class="btn btn-sm <?= ($filter_kelas ?? '') == $k['id'] ? 'btn-primary' : 'btn-outline' ?>">
        <?= esc($k['tingkat'] . ' ' . $k['rombel']) ?>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Tabel Siswa -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-list"></i> Daftar Siswa</div>
        <input type="text" class="form-input" placeholder="🔍 Cari..." 
               oninput="filterSiswa(this.value)" style="width:200px;">
    </div>
    <div class="card-body" style="padding:0;">
        <?php if (!empty($siswa)): ?>
        <div class="table-responsive">
            <table class="table" id="tabelSiswa">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Kelas</th>
                        <th>No HP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($siswa as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($s['nis']) ?></td>
                        <td><?= esc($s['nisn'] ?? '-') ?></td>
                        <td><strong><?= esc($s['nama_lengkap']) ?></strong></td>
                        <td><?= ($s['jenis_kelamin'] ?? '') == 'L' ? 'L' : 'P' ?></td>
                        <td><?= esc(($s['tingkat'] ?? '') . ' ' . ($s['jurusan_singkatan'] ?? '') . ' ' . ($s['rombel'] ?? '')) ?></td>
                        <td><?= esc($s['no_hp'] ?? '-') ?></td>
                        <td>
                            <?= ($s['is_active'] ?? 1) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>' ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/guru/siswa/detail/<?= $s['id'] ?>" class="btn-icon" title="Detail"><i class="fas fa-eye" style="color:#3B82F6;"></i></a>
                                <a href="/guru/absensi/riwayat/<?= $s['id'] ?>" class="btn-icon" title="Riwayat"><i class="fas fa-clock" style="color:#059669;"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <p>Tidak ada siswa</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterSiswa(query) {
    document.querySelectorAll('#tabelSiswa tbody tr').forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(query.toLowerCase()) ? '' : 'none';
    });
}
</script>