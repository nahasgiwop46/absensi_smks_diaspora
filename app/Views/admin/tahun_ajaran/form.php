<div class="page-title">
    <?= isset($tahun_ajaran) ? '✏️ Edit Tahun Ajaran' : '➕ Tambah Tahun Ajaran' ?>
</div>
<div class="page-subtitle">
    <?= isset($tahun_ajaran) ? 'Perbarui data tahun ajaran' : 'Lengkapi data tahun ajaran baru' ?>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-calendar-alt"></i> Form Tahun Ajaran
        </div>
    </div>
    <div class="card-body">
        <form action="<?= isset($tahun_ajaran) ? '/admin/tahun-ajaran/update/' . $tahun_ajaran['id'] : '/admin/tahun-ajaran/store' ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label">
                    Nama Tahun Ajaran <span style="color:red;">*</span>
                </label>
                <input type="text" name="nama" class="form-input" 
                       value="<?= old('nama', $tahun_ajaran['nama'] ?? '') ?>" 
                       placeholder="Contoh: 2024/2025, 2025/2026" required>
                <small style="color:#6B7280;">Format umum: TahunAwal/TahunAkhir</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        📅 Tanggal Mulai <span style="color:red;">*</span>
                    </label>
                    <input type="date" name="tanggal_mulai" class="form-input" 
                           value="<?= old('tanggal_mulai', $tahun_ajaran['tanggal_mulai'] ?? '') ?>" required>
                    <small style="color:#6B7280;">Awal tahun ajaran (biasanya Juli)</small>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        📅 Tanggal Selesai <span style="color:red;">*</span>
                    </label>
                    <input type="date" name="tanggal_selesai" class="form-input" 
                           value="<?= old('tanggal_selesai', $tahun_ajaran['tanggal_selesai'] ?? '') ?>" required>
                    <small style="color:#6B7280;">Akhir tahun ajaran (biasanya Juni tahun berikutnya)</small>
                </div>
            </div>

            <!-- Info -->
            <div class="alert alert-info" style="margin-top:16px;">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Hanya satu tahun ajaran yang bisa aktif dalam satu waktu. Tahun ajaran baru akan otomatis nonaktif saat dibuat.
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <a href="/admin/tahun-ajaran" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($tahun_ajaran) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>