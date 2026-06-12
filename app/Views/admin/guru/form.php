<div class="page-title"><?= isset($guru) ? 'Edit Guru' : 'Tambah Guru' ?></div>
<div class="page-subtitle">Lengkapi data guru dengan benar</div>

<div class="card">
    <div class="card-body">
        <form action="<?= isset($guru) ? '/admin/guru/update/' . $guru['id'] : '/admin/guru/store' ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Username <span style="color:red;">*</span></label>
                    <input type="text" name="username" class="form-input" 
                           value="<?= old('username', $guru['username'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Password <?= isset($guru) ? '<small style="color:#6B7280;">(kosongkan jika tidak diubah)</small>' : '<span style="color:red;">*</span>' ?>
                    </label>
                    <input type="password" name="password" class="form-input" 
                           placeholder="<?= isset($guru) ? '••••••' : '' ?>"
                           <?= isset($guru) ? '' : 'required' ?>>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:red;">*</span></label>
                <input type="text" name="nama_lengkap" class="form-input" 
                       value="<?= old('nama_lengkap', $guru['nama_lengkap'] ?? '') ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">NUPTK</label>
                    <input type="text" name="nuptk" class="form-input" 
                           value="<?= old('nuptk', $guru['nuptk'] ?? '') ?>" maxlength="16">
                </div>
                <div class="form-group">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-input" 
                           value="<?= old('nip', $guru['nip'] ?? '') ?>" maxlength="18">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="L" <?= old('jenis_kelamin', $guru['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' ?>>👨 Laki-laki</option>
                        <option value="P" <?= old('jenis_kelamin', $guru['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' ?>>👩 Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" 
                           value="<?= old('email', $guru['email'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-input" 
                           value="<?= old('no_hp', $guru['no_hp'] ?? '') ?>" maxlength="15">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" <?= old('is_active', $guru['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>✅ Aktif</option>
                        <option value="0" <?= old('is_active', $guru['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>❌ Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-textarea" rows="3" placeholder="Alamat lengkap..."><?= old('alamat', $guru['alamat'] ?? '') ?></textarea>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <a href="/admin/guru" class="btn btn-outline"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($guru) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Info -->
<div style="margin-top:12px;padding:12px;background:#EFF6FF;border-radius:8px;font-size:13px;color:#2563EB;">
    <i class="fas fa-info-circle"></i> 
    Untuk mengatur <strong>jadwal mengajar</strong> dan <strong>kelas</strong>, gunakan menu 
    <a href="/admin/jadwal" style="color:#2563EB;font-weight:700;">Jadwal</a>.
</div>