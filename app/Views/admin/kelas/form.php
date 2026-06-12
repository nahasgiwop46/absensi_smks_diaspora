<div class="page-title"><?= isset($kelas) ? '✏️ Edit Kelas' : '➕ Tambah Kelas' ?></div>
<div class="page-subtitle">Lengkapi data kelas</div>

<div class="card">
    <div class="card-body">
        <form action="<?= isset($kelas) ? '/admin/kelas/update/' . $kelas['id'] : '/admin/kelas/store' ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tingkat <span style="color:red;">*</span></label>
                    <select name="tingkat" class="form-select" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="X" <?= old('tingkat', $kelas['tingkat'] ?? '') == 'X' ? 'selected' : '' ?>>📗 X (Sepuluh)</option>
                        <option value="XI" <?= old('tingkat', $kelas['tingkat'] ?? '') == 'XI' ? 'selected' : '' ?>>📘 XI (Sebelas)</option>
                        <option value="XII" <?= old('tingkat', $kelas['tingkat'] ?? '') == 'XII' ? 'selected' : '' ?>>📙 XII (Dua Belas)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jurusan</label>
                    <select name="jurusan_id" class="form-select">
                        <option value="">-- Umum --</option>
                        <?php foreach ($jurusan as $j): ?>
                        <option value="<?= $j['id'] ?>" <?= old('jurusan_id', $kelas['jurusan_id'] ?? '') == $j['id'] ? 'selected' : '' ?>>
                            <?= esc($j['singkatan']) ?> - <?= esc($j['nama']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Rombel (Rombongan Belajar)</label>
                    <select name="rombel" class="form-select">
                        <option value="">-- Pilih --</option>
                        <?php foreach (['A', 'B', 'C', 'D', 'E'] as $r): ?>
                        <option value="<?= $r ?>" <?= old('rombel', $kelas['rombel'] ?? '') == $r ? 'selected' : '' ?>><?= $r ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color:#6B7280;">Contoh: A, B, C</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Kapasitas</label>
                    <input type="number" name="kapasitas" class="form-input" 
                           value="<?= old('kapasitas', $kelas['kapasitas'] ?? 30) ?>" min="10" max="50">
                    <small style="color:#6B7280;">Maksimal 50 siswa per kelas</small>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" <?= old('is_active', $kelas['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>✅ Aktif</option>
                    <option value="0" <?= old('is_active', $kelas['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>❌ Nonaktif</option>
                </select>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <a href="/admin/kelas" class="btn btn-outline"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($kelas) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>