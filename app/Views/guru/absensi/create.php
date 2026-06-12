<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
    <div>
        <h2 class="page-title" style="margin-bottom:4px;">
            <i class="fas fa-clipboard-check" style="color:#059669;"></i> Input Absensi
        </h2>
        <p class="page-subtitle">Tanggal: <?= date('d F Y', strtotime($filter_tanggal ?? date('Y-m-d'))) ?></p>
    </div>
    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-plus-circle" style="color:#059669;"></i> Form Absensi Manual</span>
    </div>
    <div class="card-body">
        <form action="<?= base_url('guru/absensi/store-batch') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="tanggal" value="<?= esc($filter_tanggal ?? date('Y-m-d')) ?>">

            <!-- Pilih Jadwal -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label fw-semibold">Jadwal / Mata Pelajaran</label>
                    <select name="jadwal_id" class="form-select" required>
                        <option value="">-- Pilih Jadwal --</option>
                        <?php foreach ($jadwal_list as $j): ?>
                        <option value="<?= $j['id'] ?>" <?= ($filter_jadwal ?? '') == $j['id'] ? 'selected' : '' ?>>
                            <?= esc($j['mapel']) ?> | <?= esc($j['nama_kelas']) ?> | <?= esc($j['jam_mulai']) ?>-<?= esc($j['jam_selesai']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label fw-semibold">Status Default</label>
                    <select name="status_id" class="form-select">
                        <?php foreach ($status_absensi as $st): ?>
                        <option value="<?= $st['id'] ?>" <?= ($st['kode'] ?? '') == 'hadir' ? 'selected' : '' ?>>
                            <?= esc($st['label']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label fw-semibold">&nbsp;</label>
                    <button type="button" class="btn btn-outline w-100" onclick="tandaiSemuaHadir()">
                        <i class="fas fa-check-double"></i> Tandai Semua Hadir
                    </button>
                </div>
            </div>

            <!-- Daftar Siswa -->
            <div class="form-group">
                <label class="form-label fw-semibold">
                    Daftar Siswa 
                    <?php if (!empty($siswa_list)): ?>
                    <span class="badge bg-primary"><?= count($siswa_list) ?> siswa</span>
                    <?php endif; ?>
                </label>
                
                <?php if (!empty($siswa_list)): ?>
                <div style="max-height:400px;overflow-y:auto;border:2px solid var(--border);border-radius:10px;padding:8px;">
                    <?php foreach ($siswa_list as $s): ?>
                    <label style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;cursor:pointer;transition:all 0.2s;" 
                           onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background=''">
                        <input type="checkbox" name="siswa_ids[]" value="<?= $s['id'] ?>" checked 
                               style="accent-color:#059669;width:16px;height:16px;">
                        <span style="flex:1;">
                            <strong><?= esc($s['nama_lengkap']) ?></strong>
                            <small class="text-muted ms-2">NIS: <?= esc($s['nis']) ?></small>
                        </span>
                        <select name="status_per_siswa[<?= $s['id'] ?>]" class="form-select form-select-sm" style="width:120px;">
                            <?php foreach ($status_absensi as $st): ?>
                            <option value="<?= $st['id'] ?>" <?= ($st['kode'] ?? '') == 'hadir' ? 'selected' : '' ?>>
                                <?= esc($st['label']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-users" style="font-size:40px;"></i>
                    <p class="mt-2">Pilih jadwal terlebih dahulu untuk melihat daftar siswa</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Tombol -->
            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:16px;">
                <a href="<?= base_url('guru/absensi') ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <?php if (!empty($siswa_list)): ?>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Sudah Tercatat -->
<?php if (!empty($absensi_tercatat)): ?>
<div class="card mt-3">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-check-circle" style="color:#10B981;"></i> Sudah Tercatat (<?= count($absensi_tercatat) ?>)</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table table-sm">
            <thead><tr><th>NIS</th><th>Nama</th><th>Status</th><th>Jam</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($absensi_tercatat as $a): ?>
                <tr>
                    <td><?= esc($a['nis'] ?? '') ?></td>
                    <td><?= esc($a['nama_lengkap'] ?? '') ?></td>
                    <td><span class="badge" style="background:<?= $a['status_warna'] ?? '#ccc' ?>;color:white;"><?= esc($a['status_label'] ?? '') ?></span></td>
                    <td><?= esc(substr($a['jam_absen'] ?? '', 0, 5)) ?></td>
                    <td>
                        <a href="<?= base_url('guru/absensi/edit/' . $a['id']) ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<script>
function tandaiSemuaHadir() {
    document.querySelectorAll('select[name^="status_per_siswa"]').forEach(function(sel) {
        sel.value = '1';
    });
}
</script>