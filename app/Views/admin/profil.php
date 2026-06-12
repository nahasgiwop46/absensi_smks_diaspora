<div class="page-title">Profil Saya</div>
<div class="page-subtitle">Informasi akun Anda</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    <!-- Foto Profil -->
    <div class="card">
        <div class="card-body" style="text-align:center;">
            <?php 
            $fotoPath = '';
            if (!empty($user['foto'])) {
                if (file_exists(FCPATH . 'uploads/foto/' . $user['foto'])) {
                    $fotoPath = base_url('uploads/foto/' . $user['foto']);
                }
            }
            ?>
            <div style="position:relative;display:inline-block;">
                <?php if (!empty($fotoPath)): ?>
                    <img src="<?= $fotoPath ?>" id="previewFoto"
                         style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #E5E7EB;">
                <?php else: ?>
                    <div id="defaultAvatar" style="width:120px;height:120px;background:linear-gradient(135deg,#2563EB,#8B5CF6);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:48px;border:4px solid #E5E7EB;">
                        <?= esc(strtoupper(substr($user['nama_lengkap'] ?? 'A', 0, 1))) ?>
                    </div>
                    <img id="previewFoto" src="" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #E5E7EB;display:none;">
                <?php endif; ?>
                
                <form action="/admin/profil/uploadFoto" method="post" enctype="multipart/form-data" id="formUploadFoto">
                    <?= csrf_field() ?>
                    <label for="fotoInput" style="cursor:pointer;position:absolute;bottom:0;right:0;background:#2563EB;color:white;width:35px;height:35px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;border:3px solid white;">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="fotoInput" name="foto" accept="image/*" 
                           style="display:none;" onchange="previewDanUpload()">
                </form>
            </div>
            
            <h3 style="margin-top:12px;"><?= esc($user['nama_lengkap'] ?? '-') ?></h3>
            <span class="badge badge-info"><?= esc($user['role_nama'] ?? 'Admin') ?></span>
        </div>
    </div>

    <!-- Edit Data -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-edit"></i> Edit Profil</div>
        </div>
        <div class="card-body">
            <form action="/admin/profil/update" method="post">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:red;">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-input" 
                           value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" 
                           value="<?= old('email', $user['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-input" 
                           value="<?= old('no_hp', $user['no_hp'] ?? '') ?>" maxlength="15">
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        Password Baru 
                        <small style="color:#6B7280;">(kosongkan jika tidak diubah)</small>
                    </label>
                    <input type="password" name="password" class="form-input" placeholder="••••••">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirm" class="form-input" placeholder="••••••">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

</div>

<script>
function previewDanUpload() {
    var file = document.getElementById('fotoInput').files[0];
    if (!file) return;
    
    // Preview
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview = document.getElementById('previewFoto');
        var defaultAvatar = document.getElementById('defaultAvatar');
        
        preview.src = e.target.result;
        preview.style.display = 'inline-block';
        
        if (defaultAvatar) {
            defaultAvatar.style.display = 'none';
        }
    };
    reader.readAsDataURL(file);
    
    // Upload otomatis
    document.getElementById('formUploadFoto').submit();
}
</script>