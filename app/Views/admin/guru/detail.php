<div class="page-title">Detail Guru</div>
<div class="page-subtitle">Informasi lengkap data guru</div>

<div style="display:flex;gap:12px;margin-bottom:20px;">
    <a href="/admin/guru/edit/<?= $guru['id'] ?>" class="btn btn-outline"><i class="fas fa-edit"></i> Edit</a>
    <a href="/admin/guru/penempatan?guru_id=<?= $guru['id'] ?>" class="btn btn-outline"><i class="fas fa-calendar-alt"></i> Jadwal Mengajar</a>
    <a href="/admin/guru" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:24px;">
    <!-- Profil -->
    <div class="card">
        <div class="card-body" style="text-align:center;">
            <?php if (!empty($guru['foto'])): ?>
                <img src="<?= base_url('uploads/guru/' . $guru['foto']) ?>" 
                     style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin-bottom:16px;">
            <?php else: ?>
                <div style="width:120px;height:120px;background:#EFF6FF;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:48px;margin-bottom:16px;color:#2563EB;">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
            <h3 style="font-size:18px;font-weight:700;"><?= esc($guru['nama_lengkap']) ?></h3>
            <p style="color:#6B7280;"><?= esc($guru['nuptk'] ?? 'NUPTK: -') ?></p>
            <p style="color:#6B7280;font-size:13px;"><?= esc($guru['nip'] ?? 'NIP: -') ?></p>
            <span class="badge <?= ($guru['is_active'] ?? 0) == 1 ? 'badge-success' : 'badge-danger' ?>">
                <?= ($guru['is_active'] ?? 0) == 1 ? '✅ Aktif' : '❌ Nonaktif' ?>
            </span>
        </div>
    </div>

    <!-- Detail -->
    <div class="card">
        <div class="card-header"><div class="card-title">Data Pribadi</div></div>
        <div class="card-body">
            <div class="table">
                <table>
                    <tr>
                        <td width="160"><strong>NUPTK</strong></td>
                        <td>: <?= esc($guru['nuptk'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>NIP</strong></td>
                        <td>: <?= esc($guru['nip'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Lengkap</strong></td>
                        <td>: <?= esc($guru['nama_lengkap'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Username</strong></td>
                        <td>: <?= esc($guru['username'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin</strong></td>
                        <td>: <?= ($guru['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : (($guru['jenis_kelamin'] ?? '') === 'P' ? 'Perempuan' : '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>: <?= esc($guru['email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>No HP</strong></td>
                        <td>: <?= esc($guru['no_hp'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>: <?= esc($guru['alamat'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Terdaftar</strong></td>
                        <td>: <?= formatTanggal($guru['created_at'] ?? '', 'd F Y H:i') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>