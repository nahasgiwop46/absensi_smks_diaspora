<?= $this->extend('layouts/guru') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-person-circle text-primary me-2"></i>Profil Saya</h4>
        <p class="text-muted mb-0">Kelola informasi profil pribadi Anda</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <?php if (isset($guru) && $guru): ?>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nama Lengkap</label>
                            <p class="fw-bold"><?= esc((string)($guru['nama_guru'] ?? '-')) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">NIP</label>
                            <p class="fw-bold"><?= esc((string)($guru['nip'] ?? '-')) ?></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Email</label>
                            <p class="fw-bold"><?= esc((string)($user['email'] ?? '-')) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Status</label>
                            <p class="fw-bold">
                                <?php if ((string)($user['is_active'] ?? '0') == '1'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Nonaktif</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Jenis Kelamin</label>
                            <p class="fw-bold"><?= esc((string)($guru['jenis_kelamin'] ?? '-')) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Tempat Lahir</label>
                            <p class="fw-bold"><?= esc((string)($guru['tempat_lahir'] ?? '-')) ?></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Tanggal Lahir</label>
                            <p class="fw-bold"><?= isset($guru['tanggal_lahir']) ? date('d F Y', strtotime($guru['tanggal_lahir'])) : '-' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Alamat</label>
                            <p class="fw-bold"><?= esc((string)($guru['alamat'] ?? '-')) ?></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nomor Telepon</label>
                            <p class="fw-bold"><?= esc((string)($guru['no_telepon'] ?? '-')) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Bidang Studi</label>
                            <p class="fw-bold"><?= esc((string)($guru['bidang_studi'] ?? '-')) ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Data profil tidak ditemukan.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="card-title fw-bold mb-3">Informasi Akun</h6>
                <div class="mb-3">
                    <label class="form-label text-muted small">Username</label>
                    <p class="fw-bold"><?= esc((string)($user['username'] ?? '-')) ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Role</label>
                    <p class="fw-bold">
                        <span class="badge bg-info">Guru</span>
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Terakhir Login</label>
                    <p class="fw-bold text-muted small"><?= isset($user['last_login']) && $user['last_login'] ? date('d F Y H:i', strtotime((string)($user['last_login'] ?? '') )) : 'Belum login' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
