<div class="page-title">📋 Penempatan Kelas</div>
<div class="page-subtitle">Masukkan siswa ke dalam kelas di tahun ajaran aktif</div>

<?php if (empty($tahun_ajaran_aktif)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> 
        Tidak ada Tahun Ajaran aktif. Silakan setup di <a href="/admin/setup">Setup Wizard</a> terlebih dahulu.
    </div>
<?php else: ?>

<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center;">
    <span style="font-size:13px;color:#6B7280;">📆 Tahun Ajaran:</span>
    <span class="badge badge-info" style="font-size:13px;padding:8px 14px;">
        <?= esc($tahun_ajaran_aktif['nama']) ?>
    </span>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">🏫 Pilih Kelas Tujuan</label>
            <select class="filter-select" onchange="window.location='?kelas_id='+this.value" style="min-width:300px;">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($kelas as $k): ?>
                <option value="<?= $k['id'] ?>" <?= ($selected_kelas ?? '') == $k['id'] ? 'selected' : '' ?>>
                    <?= esc($k['tingkat']) ?> <?= esc($k['jurusan_singkatan'] ?? '') ?> <?= esc($k['rombel']) ?> 
                    (Kapasitas: <?= $k['kapasitas'] ?> siswa)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<?php if (!empty($selected_kelas)): ?>
    <?php 
    // Cari info kelas terpilih
    $kelasInfo = null;
    foreach ($kelas as $k) {
        if ($k['id'] == $selected_kelas) { $kelasInfo = $k; break; }
    }
    ?>
    
    <?php if (!empty($siswa_belum_ditempatkan)): ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-users"></i> Siswa Belum Ditempatkan
                <?php if ($kelasInfo): ?>
                <span style="font-weight:400;color:#6B7280;font-size:13px;">
                    → <?= esc($kelasInfo['tingkat']) ?> <?= esc($kelasInfo['jurusan_singkatan'] ?? '') ?> <?= esc($kelasInfo['rombel']) ?>
                </span>
                <?php endif; ?>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <span class="badge badge-warning"><?= count($siswa_belum_ditempatkan) ?> siswa</span>
                <button type="button" class="btn btn-outline btn-sm" onclick="centangSemua()">
                    <i class="fas fa-check-double"></i> Centang Semua
                </button>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            <form action="<?= base_url('admin/siswa/simpan-penempatan-kelas') ?>" method="post" id="formPenempatan">
                <?= csrf_field() ?>
                <input type="hidden" name="tahun_ajaran_id" value="<?= $tahun_ajaran_aktif['id'] ?>">
                <input type="hidden" name="kelas_id" value="<?= $selected_kelas ?>">
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="checkAll" onchange="toggleAll(this)">
                                </th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>JK</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($siswa_belum_ditempatkan as $s): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="siswa_ids[]" value="<?= $s['id'] ?>" class="siswa-check">
                                </td>
                                <td><?= esc($s['nis']) ?></td>
                                <td><strong><?= esc($s['nama_lengkap']) ?></strong></td>
                                <td><?= ($s['jenis_kelamin'] ?? '') == 'L' ? '👨 L' : '👩 P' ?></td>
                                <td><?= badgeAktif($s['is_active'] ?? 1) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div style="padding:16px;text-align:right;border-top:1px solid #E5E7EB;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Penempatan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-check-circle" style="font-size:64px;color:#10B981;"></i>
                <p style="font-weight:600;color:#10B981;font-size:18px;">Semua siswa sudah ditempatkan! 🎉</p>
                <p style="color:#6B7280;">Tidak ada siswa yang belum memiliki kelas di tahun ajaran ini.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php endif; ?>

<?php endif; ?>

<script>
function toggleAll(el) {
    document.querySelectorAll('.siswa-check').forEach(function(cb) { cb.checked = el.checked; });
}
function centangSemua() {
    document.querySelectorAll('.siswa-check').forEach(function(cb) { cb.checked = true; });
    document.getElementById('checkAll').checked = true;
}
</script>