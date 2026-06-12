<div class="page-title">
    <?= isset($mapel) ? '✏️ Edit Mata Pelajaran' : '➕ Tambah Mata Pelajaran' ?>
</div>
<div class="page-subtitle">
    <?= isset($mapel) ? 'Perbarui data mata pelajaran' : 'Lengkapi data mata pelajaran baru' ?>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-book"></i> Form Mata Pelajaran
        </div>
    </div>
    <div class="card-body">
        <form action="<?= isset($mapel) ? '/admin/mapel/update/' . $mapel['id'] : '/admin/mapel/store' ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        Kode <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="kode" class="form-input" 
                           value="<?= old('kode', $mapel['kode'] ?? '') ?>" 
                           required maxlength="20"
                           placeholder="Contoh: MTK, BIN, BIG">
                    <small style="color:#6B7280;">Maksimal 20 karakter, harus unik</small>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Nama Mata Pelajaran <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="nama" class="form-input" 
                           value="<?= old('nama', $mapel['nama'] ?? '') ?>" 
                           required
                           placeholder="Contoh: Matematika, Bahasa Indonesia">
                </div>
            </div>

            <!-- Info -->
            <?php if (isset($mapel)): ?>
            <div class="alert alert-info" style="margin-top:16px;">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Mengubah kode atau nama mata pelajaran akan mempengaruhi semua jadwal yang menggunakan mapel ini.
            </div>
            <?php endif; ?>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <a href="/admin/mapel" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($mapel) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>