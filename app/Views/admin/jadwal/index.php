<div class="page-title">Jadwal Pelajaran</div>
<div class="page-subtitle">
    <?php if (!empty($semester_aktif)): ?>
        📚 <?= esc($semester_aktif['nama'] ?? '') ?> - T.A <?= esc($semester_aktif['tahun_ajaran_nama'] ?? '') ?>
    <?php else: ?>
        ⚠️ Semester belum diatur — <a href="/admin/setup?step=2">Setup sekarang</a>
    <?php endif; ?>
</div>

<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center;">
    <select class="filter-select" onchange="window.location='?kelas_id='+this.value" style="min-width:250px;">
        <option value="">-- Pilih Kelas --</option>
        <?php foreach ($kelas as $k): ?>
        <option value="<?= $k['id'] ?>" <?= ($selected_kelas ?? '') == $k['id'] ? 'selected' : '' ?>>
            <?= esc($k['tingkat'] ?? '') ?> <?= esc($k['jurusan_singkatan'] ?? '') ?> <?= esc($k['rombel'] ?? '') ?>
        </option>
        <?php endforeach; ?>
    </select>
    <a href="/admin/jadwal/create" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Jadwal</a>
    
    <?php if (!empty($selected_kelas)): ?>
    <a href="/admin/guru/penempatan" class="btn btn-outline">
        <i class="fas fa-chalkboard-teacher"></i> Penempatan Guru
    </a>
    <?php endif; ?>
</div>

<?php if (empty($selected_kelas)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-calendar-week" style="font-size:64px;"></i>
                <p style="font-size:18px;">Pilih kelas untuk melihat jadwal</p>
                <p style="color:#9CA3AF;">Gunakan dropdown di atas untuk memfilter jadwal per kelas</p>
            </div>
        </div>
    </div>
<?php elseif (empty($jadwal)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-calendar-times" style="font-size:64px;"></i>
                <p style="font-size:18px;">Belum ada jadwal untuk kelas ini</p>
                <a href="/admin/jadwal/create" class="btn btn-primary mt-2">
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </a>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php 
    $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
    $hariIcons = [
        'senin' => '📅', 'selasa' => '📅', 'rabu' => '📅',
        'kamis' => '📅', 'jumat' => '🕌', 'sabtu' => '📅'
    ];
    $jadwalByHari = [];
    foreach ($jadwal as $j) {
        $jadwalByHari[$j['hari']][] = $j;
    }
    $totalMapel = count($jadwal);
    ?>
    
    <!-- Info Ringkasan -->
    <div class="stats-grid" style="margin-bottom:16px;">
        <div class="stat-card" style="text-align:center;">
            <div class="stat-value"><?= $totalMapel ?></div>
            <div class="stat-label">Total Mapel</div>
        </div>
        <div class="stat-card" style="text-align:center;">
            <div class="stat-value"><?= count(array_filter($jadwalByHari, fn($h) => !empty($h))) ?></div>
            <div class="stat-label">Hari Aktif</div>
        </div>
        <div class="stat-card" style="text-align:center;">
            <?php 
            $guruUnik = array_unique(array_column($jadwal, 'guru_nama'));
            ?>
            <div class="stat-value"><?= count($guruUnik) ?></div>
            <div class="stat-label">Guru Pengajar</div>
        </div>
    </div>

    <?php foreach ($hariList as $hari): ?>
    <div class="card" style="margin-bottom:14px;">
        <div class="card-header" style="text-transform:capitalize;">
            <div class="card-title">
                <?= $hariIcons[$hari] ?? '📅' ?> <?= $hari ?>
            </div>
            <span style="font-size:12px;color:#6B7280;">
                <?= count($jadwalByHari[$hari] ?? []) ?> mapel
            </span>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (!empty($jadwalByHari[$hari])): ?>
            <div class="table">
                <table style="min-width:600px;">
                    <thead>
                        <tr>
                            <th style="width:5%;">No</th>
                            <th style="width:25%;">Jam</th>
                            <th style="width:30%;">Mata Pelajaran</th>
                            <th style="width:25%;">Guru</th>
                            <th style="width:15%;text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($jadwalByHari[$hari] as $j): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <span class="badge badge-light" style="font-size:13px;">
                                    <?= esc($j['jam_mulai'] ?? '') ?> - <?= esc($j['jam_selesai'] ?? '') ?>
                                </span>
                            </td>
                            <td><strong><?= esc($j['mapel_nama'] ?? '') ?></strong></td>
                            <td>
                                <a href="/admin/guru/detail/<?= $j['guru_id'] ?? '' ?>" style="text-decoration:none;">
                                    <?= esc($j['guru_nama'] ?? '') ?>
                                </a>
                            </td>
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
            <?php else: ?>
                <p style="text-align:center;color:#9CA3AF;padding:20px;">— Tidak ada jadwal —</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>