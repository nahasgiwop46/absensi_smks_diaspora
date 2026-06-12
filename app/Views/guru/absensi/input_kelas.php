<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
    <div>
        <h2 class="page-title" style="margin-bottom:4px;">
            <i class="fas fa-edit" style="color:#059669;"></i> Input Absensi
        </h2>
        <p class="page-subtitle">
            Kelas: <strong><?= esc($kelas['tingkat'] ?? '') ?> <?= esc($kelas['rombel'] ?? '') ?></strong> • 
            <?= date('d F Y', strtotime($tanggal_filter ?? date('Y-m-d'))) ?>
        </p>
    </div>
    <a href="/guru/absensi/create?tanggal=<?= esc($tanggal_filter) ?>" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Pilih Kelas Lain
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div class="stat-value"><?= count($siswa_belum_absen ?? []) + count($siswa_sudah_absen ?? []) ?></div>
        <div class="stat-label">Total</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value text-success"><?= count($siswa_sudah_absen ?? []) ?></div>
        <div class="stat-label">Sudah Absen</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
        <div class="stat-value text-warning"><?= count($siswa_belum_absen ?? []) ?></div>
        <div class="stat-label">Belum Absen</div>
    </div>
</div>

<!-- SUDAH ABSEN -->
<?php if (!empty($siswa_sudah_absen)): ?>
<div class="card" style="margin-bottom:20px;">
    <div class="card-header" style="background:#F9FAFB;">
        <span class="card-title" style="font-size:16px;">
            <i class="fas fa-check-circle" style="color:#059669;"></i> Sudah Absen (<?= count($siswa_sudah_absen) ?>)
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table">
            <thead><tr><th>NIS</th><th>Nama</th><th>Status</th><th>Jam</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($siswa_sudah_absen as $a): ?>
                <tr>
                    <td><?= esc($a['nis'] ?? '-') ?></td>
                    <td><?= esc($a['nama_lengkap'] ?? '-') ?></td>
                    <td><span class="badge" style="background:<?= $a['status_warna'] ?? '#ccc' ?>;color:white;"><?= esc($a['status_label'] ?? '-') ?></span></td>
                    <td><?= isset($a['jam_absen']) ? date('H:i', strtotime($a['jam_absen'])) : '-' ?></td>
                    <td><a href="/guru/absensi/edit/<?= $a['id'] ?>" class="btn-icon edit"><i class="fas fa-edit"></i></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- BELUM ABSEN -->
<?php if (!empty($siswa_belum_absen)): ?>
<div class="card">
    <div class="card-header" style="background:#F9FAFB;">
        <span class="card-title" style="font-size:16px;">
            <i class="fas fa-clock" style="color:#F59E0B;"></i> Belum Absen (<?= count($siswa_belum_absen) ?>)
        </span>
        <button class="btn btn-sm btn-outline" onclick="tandaiSemuaHadir()">
            <i class="fas fa-check-double"></i> Tandai Semua Hadir
        </button>
    </div>
    <div class="card-body" style="padding:0;">
        <form action="<?= base_url('guru/absensi/store-batch') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="tanggal" value="<?= esc($tanggal_filter) ?>">
            <input type="hidden" name="kelas_id" value="<?= esc($kelas['id'] ?? '') ?>">
            
            <table class="table">
                <thead><tr><th>NIS</th><th>Nama</th><th>Status</th><th>Cepat</th></tr></thead>
                <tbody>
                    <?php foreach ($siswa_belum_absen as $s): ?>
                    <tr id="row-<?= $s['id'] ?>">
                        <td><?= esc($s['nis'] ?? '-') ?></td>
                        <td><?= esc($s['nama_lengkap'] ?? '-') ?></td>
                        <td>
                            <input type="hidden" name="siswa_ids[]" value="<?= $s['id'] ?>">
                            <select name="status_per_siswa[<?= $s['id'] ?>]" class="form-select form-select-sm status-select" style="width:130px;">
                                <?php foreach ($status_absensi as $st): ?>
                                <option value="<?= $st['id'] ?>" <?= ($st['kode'] ?? '') == 'hadir' ? 'selected' : '' ?>>
                                    <?= esc($st['label']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-success" onclick="setStatus(<?= $s['id'] ?>, 1)" title="Hadir"><i class="fas fa-check"></i></button>
                                <button type="button" class="btn btn-sm" style="background:#F59E0B;color:white;" onclick="setStatus(<?= $s['id'] ?>, 2)" title="Izin"><i class="fas fa-envelope"></i></button>
                                <button type="button" class="btn btn-sm" style="background:#3B82F6;color:white;" onclick="setStatus(<?= $s['id'] ?>, 3)" title="Sakit"><i class="fas fa-heart"></i></button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="setStatus(<?= $s['id'] ?>, 4)" title="Alpa"><i class="fas fa-times"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="display:flex;justify-content:space-between;align-items:center;padding:16px;border-top:1px solid var(--border);">
                <a href="/notifikasi/kirimSemuaAlpa" class="btn btn-sm" 
                   style="background:#FEE2E2;color:#DC2626;"
                   onclick="return confirm('Kirim notif WhatsApp ke orang tua?')">
                    <i class="fab fa-whatsapp"></i> Kirim Notif
                </a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Semua</button>
            </div>
        </form>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body text-center" style="padding:40px;">
        <i class="fas fa-check-circle" style="font-size:48px;color:#10B981;"></i>
        <h3 style="margin-top:12px;">Semua siswa sudah absen!</h3>
    </div>
</div>
<?php endif; ?>

<script>
function tandaiSemuaHadir() {
    document.querySelectorAll('.status-select').forEach(function(sel) { sel.value = '1'; });
}
function setStatus(siswaId, statusId) {
    var row = document.getElementById('row-' + siswaId);
    if (row) {
        row.querySelector('.status-select').value = statusId;
        row.style.background = '#ECFDF5';
        setTimeout(function() { row.style.background = ''; }, 1000);
    }
}
</script>