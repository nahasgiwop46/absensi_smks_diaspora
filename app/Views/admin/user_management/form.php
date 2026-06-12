<div class="page-title">
    <?= isset($user) ? '✏️ Edit User' : '➕ Tambah User' ?>
</div>
<p class="page-subtitle">
    <?= isset($user) ? 'Perbarui data akun pengguna' : 'Buat akun login untuk Guru atau Siswa' ?>
</p>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-user-cog"></i> Form User
        </div>
    </div>
    <div class="card-body">
        <form action="<?= base_url(isset($user) ? 'admin/users/update/' . $user['id'] : 'admin/users/store') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Pilih Role -->
            <div class="form-group">
                <label class="form-label">👤 Role <span style="color:red;">*</span></label>
                <select name="role_id" id="roleSelect" class="form-select" required <?= isset($user) ? 'disabled' : '' ?>>
                    <option value="">-- Pilih Role --</option>
                    <?php foreach ($roles as $role): ?>
                        <?php if (in_array(strtoupper($role['kode']), ['ADMIN', 'KEPSEK'])) continue; ?>
                        <option value="<?= $role['id'] ?>" <?= old('role_id', $user['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                            <?php if (strtoupper($role['kode']) === 'GURU'): ?>
                                👨‍🏫 <?= esc($role['nama']) ?>
                            <?php elseif (strtoupper($role['kode']) === 'SISWA'): ?>
                                🧑‍🎓 <?= esc($role['nama']) ?>
                            <?php else: ?>
                                <?= esc($role['nama']) ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($user)): ?>
                    <input type="hidden" name="role_id" value="<?= $user['role_id'] ?>">
                    <small style="color:#6B7280;">⚠️ Role tidak dapat diubah setelah user dibuat</small>
                <?php endif; ?>
            </div>

            <!-- Pilih Siswa (muncul jika role=Siswa) -->
            <div class="form-group" id="pilihSiswaBox" style="display:none;">
                <label class="form-label">
                    🧑‍🎓 Pilih Siswa <span style="color:red;">*</span>
                </label>
                <select name="siswa_id" id="siswaSelect" class="form-select">
                    <option value="">-- Pilih Siswa --</option>
                    <?php foreach ($siswa_list ?? [] as $s): ?>
                    <option value="<?= $s['id'] ?>" 
                        data-nis="<?= esc($s['nis']) ?>"
                        data-nama="<?= esc($s['nama_lengkap']) ?>"
                        <?= old('siswa_id') == $s['id'] ? 'selected' : '' ?>>
                        <?= esc($s['nis']) ?> — <?= esc($s['nama_lengkap']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <small style="color:#6B7280;">
                    <?= empty($siswa_list) ? '✅ Semua siswa sudah memiliki akun' : 'Siswa yang belum memiliki akun login' ?>
                </small>
            </div>

            <!-- Pilih Guru (muncul jika role=Guru) -->
            <div class="form-group" id="pilihGuruBox" style="display:none;">
                <label class="form-label">
                    👨‍🏫 Pilih Guru <span style="color:red;">*</span>
                </label>
                <select name="guru_id" id="guruSelect" class="form-select">
                    <option value="">-- Pilih Guru --</option>
                    <?php foreach ($guru_list ?? [] as $g): ?>
                    <option value="<?= $g['id'] ?>" 
                        data-nuptk="<?= esc($g['nuptk'] ?? '') ?>"
                        data-nama="<?= esc($g['nama_lengkap']) ?>"
                        <?= old('guru_id') == $g['id'] ? 'selected' : '' ?>>
                        <?= esc($g['nama_lengkap']) ?> <?= !empty($g['nuptk']) ? '— ' . esc($g['nuptk']) : '' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <small style="color:#6B7280;">
                    <?= empty($guru_list) ? '✅ Semua guru sudah memiliki akun' : 'Guru yang belum memiliki akun login' ?>
                </small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        🔑 Username <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="username" id="usernameInput" 
                           value="<?= old('username', $user['username'] ?? '') ?>" 
                           class="form-input" required
                           placeholder="Username untuk login">
                    <small style="color:#6B7280;">Minimal 5 karakter, huruf & angka</small>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        🔒 Password <?= isset($user) ? '<small style="color:#6B7280;">(kosongkan jika tidak diubah)</small>' : '<span style="color:red;">*</span>' ?>
                    </label>
                    <input type="password" name="password" 
                           placeholder="<?= isset($user) ? '••••••' : 'Minimal 6 karakter' ?>" 
                           class="form-input" <?= isset($user) ? '' : 'required' ?>>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        📛 Nama Lengkap <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" id="namaInput" 
                           value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>" 
                           class="form-input" required
                           placeholder="Nama lengkap pengguna">
                </div>
                <div class="form-group">
                    <label class="form-label">📧 Email</label>
                    <input type="email" name="email" 
                           value="<?= old('email', $user['email'] ?? '') ?>" 
                           class="form-input"
                           placeholder="email@example.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">📱 No HP</label>
                    <input type="text" name="no_hp" 
                           value="<?= old('no_hp', $user['no_hp'] ?? '') ?>" 
                           class="form-input"
                           placeholder="08xxxxxxxxxx" maxlength="15">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" <?= old('is_active', $user['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>✅ Aktif</option>
                        <option value="0" <?= old('is_active', $user['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>❌ Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Info -->
            <?php if (!isset($user)): ?>
            <div class="alert alert-info" style="margin-top:16px;">
                <i class="fas fa-info-circle"></i>
                <strong>Tips:</strong> Pilih role terlebih dahulu, lalu pilih Siswa/Guru dari dropdown untuk auto-fill username & nama.
            </div>
            <?php endif; ?>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:20px;padding-top:20px;border-top:1px solid #E5E7EB;">
                <a href="<?= base_url('admin/users') ?>" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($user) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
var roleSelect = document.getElementById('roleSelect');
var pilihSiswaBox = document.getElementById('pilihSiswaBox');
var pilihGuruBox = document.getElementById('pilihGuruBox');
var siswaSelect = document.getElementById('siswaSelect');
var guruSelect = document.getElementById('guruSelect');
var usernameInput = document.getElementById('usernameInput');
var namaInput = document.getElementById('namaInput');

// Toggle box berdasarkan role
function toggleRoleBoxes() {
    if (!roleSelect) return;
    var text = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();
    
    pilihSiswaBox.style.display = text.includes('siswa') ? 'block' : 'none';
    pilihGuruBox.style.display = text.includes('guru') ? 'block' : 'none';
    
    // Set required
    if (siswaSelect) siswaSelect.required = text.includes('siswa');
    if (guruSelect) guruSelect.required = text.includes('guru');
}

if (roleSelect) {
    roleSelect.addEventListener('change', toggleRoleBoxes);
    toggleRoleBoxes(); // Jalankan saat load
}

// Auto-fill dari pilihan siswa
if (siswaSelect) {
    siswaSelect.addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        if (opt.dataset.nis && usernameInput) {
            usernameInput.value = opt.dataset.nis;
        }
        if (opt.dataset.nama && namaInput) {
            namaInput.value = opt.dataset.nama;
        }
    });
}

// Auto-fill dari pilihan guru
if (guruSelect) {
    guruSelect.addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        if (opt.dataset.nuptk && usernameInput) {
            usernameInput.value = opt.dataset.nuptk;
        }
        if (opt.dataset.nama && namaInput) {
            namaInput.value = opt.dataset.nama;
        }
    });
}
</script>