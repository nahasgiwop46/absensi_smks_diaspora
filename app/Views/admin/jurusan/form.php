<div class="page-title">
    <?= isset($jurusan) ? '✏️ Edit Jurusan' : '➕ Tambah Jurusan' ?>
</div>

<div class="page-subtitle">
    <?= isset($jurusan) 
        ? 'Perbarui informasi data jurusan' 
        : 'Lengkapi form untuk menambahkan jurusan baru' ?>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-book-open"></i>
            Form Jurusan
        </div>
    </div>

    <div class="card-body">

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger" style="margin-bottom:20px;">
                <ul style="margin:0;padding-left:18px;">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= isset($jurusan) 
            ? '/admin/jurusan/update/' . $jurusan['id'] 
            : '/admin/jurusan/store' ?>" method="post">

            <?= csrf_field() ?>

            <div class="form-grid">

                <!-- Kode -->
                <div class="form-group">
                    <label class="form-label">
                        Kode Jurusan <span class="required">*</span>
                    </label>
                    <input 
                        type="text"
                        name="kode"
                        class="form-input"
                        placeholder="Contoh: RPL, TKJ, MM"
                        value="<?= old('kode', $jurusan['kode'] ?? '') ?>"
                        maxlength="10"
                        required>
                    <small style="color:#6B7280;">Maksimal 10 karakter, harus unik</small>
                </div>

                <!-- Singkatan -->
                <div class="form-group">
                    <label class="form-label">Singkatan</label>
                    <input 
                        type="text"
                        name="singkatan"
                        class="form-input"
                        placeholder="Contoh: TKJ"
                        value="<?= old('singkatan', $jurusan['singkatan'] ?? '') ?>"
                        maxlength="10">
                    <small style="color:#6B7280;">Opsional, maksimal 10 karakter</small>
                </div>

                <!-- Nama -->
                <div class="form-group full">
                    <label class="form-label">
                        Nama Jurusan <span class="required">*</span>
                    </label>
                    <input 
                        type="text"
                        name="nama"
                        class="form-input"
                        placeholder="Masukkan nama lengkap jurusan"
                        value="<?= old('nama', $jurusan['nama'] ?? '') ?>"
                        required>
                </div>

                <!-- Deskripsi -->
                <div class="form-group full">
                    <label class="form-label">Deskripsi</label>
                    <textarea 
                        name="deskripsi"
                        class="form-textarea"
                        rows="5"
                        placeholder="Tulis deskripsi jurusan..."><?= old('deskripsi', $jurusan['deskripsi'] ?? '') ?></textarea>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" <?= old('is_active', $jurusan['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>
                            ✅ Aktif
                        </option>
                        <option value="0" <?= old('is_active', $jurusan['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>
                            ❌ Nonaktif
                        </option>
                    </select>
                </div>

            </div>

            <!-- Action -->
            <div class="form-action">
                <a href="/admin/jurusan" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <?= isset($jurusan) ? 'Update Data' : 'Simpan Data' ?>
                </button>
            </div>

        </form>

    </div>
</div>

<style>
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;}
.form-group{display:flex;flex-direction:column;}
.form-group.full{grid-column:1 / -1;}
.form-label{font-size:14px;font-weight:600;color:#374151;margin-bottom:8px;}
.required{color:#EF4444;}
.form-input,.form-select,.form-textarea{width:100%;border:1px solid #D1D5DB;border-radius:12px;padding:12px 14px;font-size:14px;transition:all .2s ease;background:#fff;}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:#2563EB;outline:none;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.form-textarea{resize:vertical;}
.form-action{display:flex;justify-content:flex-end;gap:12px;margin-top:32px;padding-top:24px;border-top:1px solid #E5E7EB;}
.alert{padding:14px 16px;border-radius:12px;font-size:14px;}
.alert-danger{background:#FEF2F2;color:#B91C1C;border:1px solid #FECACA;}
small{font-size:12px;margin-top:4px;}
@media(max-width:768px){.form-grid{grid-template-columns:1fr;}.form-action{flex-direction:column-reverse;}.form-action .btn{width:100%;justify-content:center;}}
</style>