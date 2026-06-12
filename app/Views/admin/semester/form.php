<?php
/** @var array $tahun_ajaran */
/** @var array|null $semester */
?>

<div class="page-title">
    <?= isset($semester) && is_array($semester) ? '✏️ Edit Semester' : '➕ Tambah Semester' ?>
</div>
<div class="page-subtitle">
    <?= isset($semester) && is_array($semester) ? 'Perbarui data semester' : 'Lengkapi data semester' ?>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-calendar-alt"></i> Form Semester
        </div>
    </div>
    <div class="card-body">
        <form action="<?= isset($semester) && is_array($semester) ? '/admin/semester/update/' . $semester['id'] : '/admin/semester/store' ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label">
                    Tahun Ajaran <span style="color:red;">*</span>
                </label>
                <select name="tahun_ajaran_id" class="form-select" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    <?php foreach ($tahun_ajaran as $ta): ?>
                    <option value="<?= $ta['id'] ?>" <?= old('tahun_ajaran_id', $semester['tahun_ajaran_id'] ?? '') == $ta['id'] ? 'selected' : '' ?>>
                        📆 <?= esc($ta['nama'] ?? '') ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        Nama Semester <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="nama" class="form-input" 
                           value="<?= old('nama', $semester['nama'] ?? '') ?>" required
                           placeholder="Contoh: Semester 1, Semester Ganjil">
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Kode <span style="color:red;">*</span>
                    </label>
                    <select name="kode" class="form-select" required>
                        <option value="">-- Pilih Kode --</option>
                        <option value="1" <?= old('kode', $semester['kode'] ?? '') == '1' ? 'selected' : '' ?>>
                            1️⃣ Semester 1 (Ganjil)
                        </option>
                        <option value="2" <?= old('kode', $semester['kode'] ?? '') == '2' ? 'selected' : '' ?>>
                            2️⃣ Semester 2 (Genap)
                        </option>
                    </select>
                    <small style="color:#6B7280;">Kode 1 = Ganjil, Kode 2 = Genap</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        📅 Tanggal Mulai <span style="color:red;">*</span>
                    </label>
                    <input type="date" name="tanggal_mulai" class="form-input" 
                           value="<?= old('tanggal_mulai', $semester['tanggal_mulai'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        📅 Tanggal Selesai <span style="color:red;">*</span>
                    </label>
                    <input type="date" name="tanggal_selesai" class="form-input" 
                           value="<?= old('tanggal_selesai', $semester['tanggal_selesai'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Info -->
            <div class="alert alert-info" style="margin-top:16px;">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Hanya satu semester yang bisa aktif dalam satu waktu. Semester baru akan otomatis nonaktif saat dibuat.
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:24px;">
                <a href="/admin/semester" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($semester) && is_array($semester) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>