<div class="page-title">Profil Saya</div>
<div class="page-subtitle">Informasi akun Anda</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <div style="text-align:center;margin-bottom:20px;">
            <?php 
            $fotoPath = '';
            if (!empty($user['foto'])) {
                if (file_exists(FCPATH . 'uploads/foto/' . $user['foto'])) {
                    $fotoPath = base_url('uploads/foto/' . $user['foto']);
                } elseif (file_exists(FCPATH . 'uploads/user/' . $user['foto'])) {
                    $fotoPath = base_url('uploads/user/' . $user['foto']);
                }
            }
            ?>

            <?php if (!empty($fotoPath)): ?>
                <img src="<?= $fotoPath ?>" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #E5E7EB;">
            <?php else: ?>
                <div style="width:80px;height:80px;background:linear-gradient(135deg,#059669,#8B5CF6);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:32px;border:3px solid #E5E7EB;">
                    <?= esc(strtoupper(substr($user['nama_lengkap'] ?? 'G', 0, 1))) ?>
                </div>
            <?php endif; ?>
            
            <h3 style="margin-top:8px;"><?= esc($user['nama_lengkap'] ?? '-') ?></h3>
            <span class="badge badge-success"><?= esc($user['role_nama'] ?? 'Guru') ?></span>
        </div>

        <table class="table table-borderless" style="min-width:auto;">
            <tr><td width="130"><strong>Username</strong></td><td>: <?= esc($user['username'] ?? '-') ?></td></tr>
            <tr><td><strong>Nama Lengkap</strong></td><td>: <?= esc($user['nama_lengkap'] ?? '-') ?></td></tr>
            <tr><td><strong>Email</strong></td><td>: <?= esc($user['email'] ?? '-') ?></td></tr>
            <tr><td><strong>No HP</strong></td><td>: <?= esc($user['no_hp'] ?? '-') ?></td></tr>
            <?php if (!empty($user['nuptk'])): ?>
            <tr><td><strong>NUPTK</strong></td><td>: <?= esc($user['nuptk']) ?></td></tr>
            <?php endif; ?>
            <?php if (!empty($user['nip'])): ?>
            <tr><td><strong>NIP</strong></td><td>: <?= esc($user['nip']) ?></td></tr>
            <?php endif; ?>
            <tr><td><strong>Status</strong></td><td>: <?= ($user['is_active'] ?? 1) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>' ?></td></tr>
        </table>
    </div>
</div>