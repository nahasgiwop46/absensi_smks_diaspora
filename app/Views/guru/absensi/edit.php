<div class="page-title">Edit Absensi</div>
<div class="page-subtitle">Ubah data absensi siswa</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('guru/absensi/update/' . $absensi['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status_id" class="form-select" required>
                        <?php foreach ($status_absensi as $st): ?>
                        <option value="<?= $st['id'] ?>" <?= ($absensi['status_id'] ?? '') == $st['id'] ? 'selected' : '' ?>>
                            <?= esc($st['label']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Menit Keterlambatan</label>
                    <input type="number" name="menit_keterlambatan" class="form-input" 
                           value="<?= esc($absensi['menit_keterlambatan'] ?? 0) ?>" min="0">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-textarea" rows="3"><?= esc($absensi['keterangan'] ?? '') ?></textarea>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:20px;">
                <a href="<?= base_url('guru/absensi') ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>