<div class="page-header">
    <div>
        <div class="page-title">
            <i class="fas fa-book-open"></i>
            Detail Jurusan
        </div>
        <div class="page-subtitle">
            Informasi lengkap mengenai data jurusan
        </div>
    </div>

    <div class="header-action">
        <?= badgeAktif($jurusan['is_active'] ?? 0) ?>
    </div>
</div>

<div style="display:flex;gap:12px;margin-bottom:24px;">
    <a href="/admin/jurusan/edit/<?= $jurusan['id'] ?>" class="btn btn-outline">
        <i class="fas fa-edit"></i> Edit
    </a>
    <a href="/admin/kelas?jurusan_id=<?= $jurusan['id'] ?>" class="btn btn-outline">
        <i class="fas fa-door-open"></i> Lihat Kelas
    </a>
    <a href="/admin/jurusan" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="detail-wrapper">

    <!-- LEFT -->
    <div class="detail-main">

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Jurusan
                </div>
            </div>

            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Kode Jurusan</div>
                        <div class="detail-value">
                            <span class="badge badge-primary" style="font-size:16px;"><?= esc($jurusan['kode'] ?? '-') ?></span>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Singkatan</div>
                        <div class="detail-value"><?= esc($jurusan['singkatan'] ?? '-') ?></div>
                    </div>

                    <div class="detail-item full">
                        <div class="detail-label">Nama Jurusan</div>
                        <div class="detail-value big"><?= esc($jurusan['nama'] ?? '-') ?></div>
                    </div>

                    <div class="detail-item full">
                        <div class="detail-label">Deskripsi</div>
                        <div class="detail-description">
                            <?= !empty($jurusan['deskripsi'])
                                ? nl2br(esc($jurusan['deskripsi']))
                                : '<span class="empty-text">Belum ada deskripsi.</span>' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="detail-sidebar">

        <div class="card stats-card">
            <div class="stats-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stats-info">
                <div class="stats-number"><?= esc($jurusan['total_kelas'] ?? 0) ?></div>
                <div class="stats-label">Total Kelas</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-clock"></i>
                    Informasi Sistem
                </div>
            </div>

            <div class="card-body">
                <div class="info-list">
                    <div class="info-item">
                        <span>Dibuat Pada</span>
                        <strong><?= formatTanggal($jurusan['created_at'] ?? '', 'd F Y H:i') ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Terakhir Update</span>
                        <strong><?= !empty($jurusan['updated_at']) ? formatTanggal($jurusan['updated_at'], 'd F Y H:i') : '-' ?></strong>
                    </div>

                    <?php if (!empty($jurusan['updated_by'])): ?>
                    <div class="info-item">
                        <span>Diupdate Oleh</span>
                        <strong>User ID: <?= esc($jurusan['updated_by']) ?></strong>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
.page-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;gap:20px;}
.page-title{font-size:28px;font-weight:700;color:#111827;display:flex;align-items:center;gap:12px;}
.page-subtitle{margin-top:8px;color:#6B7280;font-size:15px;}
.detail-wrapper{display:grid;grid-template-columns:2fr 1fr;gap:24px;}
.detail-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;}
.detail-item{background:#F9FAFB;border:1px solid #E5E7EB;border-radius:16px;padding:20px;}
.detail-item.full{grid-column:1 / -1;}
.detail-label{font-size:12px;font-weight:700;text-transform:uppercase;color:#6B7280;margin-bottom:10px;letter-spacing:.6px;}
.detail-value{font-size:16px;font-weight:600;color:#111827;}
.detail-value.big{font-size:22px;}
.detail-description{line-height:1.8;color:#374151;}
.empty-text{color:#9CA3AF;font-style:italic;}
.stats-card{display:flex;align-items:center;gap:18px;padding:24px;}
.stats-icon{width:70px;height:70px;border-radius:18px;background:linear-gradient(135deg,#3B82F6,#2563EB);display:flex;align-items:center;justify-content:center;color:white;font-size:28px;}
.stats-number{font-size:32px;font-weight:700;color:#111827;}
.stats-label{color:#6B7280;margin-top:4px;}
.info-list{display:flex;flex-direction:column;gap:16px;}
.info-item{display:flex;justify-content:space-between;gap:16px;padding-bottom:14px;border-bottom:1px solid #E5E7EB;}
.info-item:last-child{border-bottom:none;padding-bottom:0;}
@media(max-width:992px){.detail-wrapper{grid-template-columns:1fr;}}
@media(max-width:768px){.page-header{flex-direction:column;}.detail-grid{grid-template-columns:1fr;}}
</style>