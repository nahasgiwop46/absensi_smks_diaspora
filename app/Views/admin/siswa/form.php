<div class="page-title">
    <?= isset($siswa) ? '✏️ Edit Siswa' : '➕ Tambah Siswa' ?>
</div>
<p class="page-subtitle">
    <?= isset($siswa) ? 'Perbarui data siswa' : 'Lengkapi data siswa dengan benar' ?>
</p>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-user-graduate"></i> Form Siswa</div>
    </div>
    <div class="card-body">
        <form action="<?= base_url(isset($siswa) ? 'admin/siswa/update/' . $siswa['id'] : 'admin/siswa/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">NIS <span style="color:red;">*</span></label>
                    <input type="text" name="nis" value="<?= old('nis', $siswa['nis'] ?? '') ?>" class="form-input" required placeholder="Nomor Induk Siswa">
                </div>
                <div class="form-group">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" value="<?= old('nisn', $siswa['nisn'] ?? '') ?>" class="form-input" placeholder="Nomor Induk Siswa Nasional" maxlength="10">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:red;">*</span></label>
                <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap', $siswa['nama_lengkap'] ?? '') ?>" class="form-input" required placeholder="Nama lengkap siswa">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="L" <?= old('jenis_kelamin', $siswa['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' ?>>👨 Laki-laki</option>
                        <option value="P" <?= old('jenis_kelamin', $siswa['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' ?>>👩 Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" value="<?= old('no_hp', $siswa['no_hp'] ?? '') ?>" class="form-input" placeholder="08xxxxxxxxxx" maxlength="15">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="<?= old('tempat_lahir', $siswa['tempat_lahir'] ?? '') ?>" class="form-input" placeholder="Kota kelahiran">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?= old('tanggal_lahir', $siswa['tanggal_lahir'] ?? '') ?>" class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="<?= old('email', $siswa['email'] ?? '') ?>" class="form-input" placeholder="email@example.com">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-textarea" rows="3" placeholder="Alamat lengkap"><?= old('alamat', $siswa['alamat'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas_list as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= old('kelas_id', $siswa['kelas_id'] ?? $kelasAktif['kelas_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                            <?= esc($k['tingkat'] . ' ' . ($k['jurusan_singkatan'] ?? '') . ' ' . $k['rombel']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" <?= old('is_active', $siswa['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>✅ Aktif</option>
                        <option value="0" <?= old('is_active', $siswa['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>❌ Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="form-action" style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;padding-top:24px;border-top:1px solid #E5E7EB;">
                <a href="<?= base_url('admin/siswa') ?>" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($siswa) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>