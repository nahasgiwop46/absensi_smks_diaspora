<div class="page-title"><?= isset($jadwal) ? 'Edit Jadwal' : 'Tambah Jadwal' ?></div>
<div class="page-subtitle">Lengkapi data jadwal pelajaran</div>

<div class="card">
    <div class="card-body">
        <form action="<?= isset($jadwal) ? '/admin/jadwal/update/' . $jadwal['id'] : '/admin/jadwal/store' ?>" method="post">
            <?= csrf_field() ?>
            
            <?php if (!empty($semester_aktif['id'])): ?>
                <input type="hidden" name="semester_id" value="<?= esc($semester_aktif['id']) ?>">
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kelas <span style="color:red;">*</span></label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= old('kelas_id', $jadwal['kelas_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                            <?= esc($k['tingkat'] ?? '') ?> <?= esc($k['jurusan_singkatan'] ?? '') ?> <?= esc($k['rombel'] ?? '') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Hari <span style="color:red;">*</span></label>
                    <select name="hari" class="form-select" required>
                        <option value="">-- Pilih Hari --</option>
                        <?php 
                        $hariList = [
                            'senin' => '📅 Senin', 
                            'selasa' => '📅 Selasa', 
                            'rabu' => '📅 Rabu', 
                            'kamis' => '📅 Kamis', 
                            'jumat' => '📅 Jumat', 
                            'sabtu' => '📅 Sabtu'
                        ]; 
                        ?>
                        <?php foreach ($hariList as $val => $label): ?>
                        <option value="<?= $val ?>" <?= old('hari', $jadwal['hari'] ?? '') == $val ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Mata Pelajaran <span style="color:red;">*</span></label>
                    <select name="mata_pelajaran_id" class="form-select" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php foreach ($mapel as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= old('mata_pelajaran_id', $jadwal['mata_pelajaran_id'] ?? '') == $m['id'] ? 'selected' : '' ?>>
                            <?= esc($m['nama'] ?? '') ?> (<?= esc($m['kode'] ?? '') ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Guru Pengajar <span style="color:red;">*</span></label>
                    <select name="guru_id" class="form-select" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($guru as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= old('guru_id', $jadwal['guru_id'] ?? '') == $g['id'] ? 'selected' : '' ?>>
                            <?= esc($g['nama_lengkap'] ?? '') ?> <?= !empty($g['nuptk']) ? '(' . esc($g['nuptk']) . ')' : '' ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jam Mulai <span style="color:red;">*</span></label>
                    <input type="time" name="jam_mulai" class="form-input" 
                           value="<?= old('jam_mulai', $jadwal['jam_mulai'] ?? '') ?>" required>
                    <small style="color:#6B7280;">Contoh: 07:00</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Selesai <span style="color:red;">*</span></label>
                    <input type="time" name="jam_selesai" class="form-input" 
                           value="<?= old('jam_selesai', $jadwal['jam_selesai'] ?? '') ?>" required>
                    <small style="color:#6B7280;">Harus lebih besar dari jam mulai</small>
                </div>
            </div>

            <!-- Info bentrok -->
            <div class="alert alert-info" style="margin-top:16px;">
                <i class="fas fa-info-circle"></i> 
                <strong>Catatan:</strong> Sistem akan otomatis mengecek bentrok jadwal. Jika jam pelajaran bertabrakan dengan jadwal lain di kelas yang sama, data tidak akan tersimpan.
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px;">
                <a href="/admin/jadwal" class="btn btn-outline"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($jadwal) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>