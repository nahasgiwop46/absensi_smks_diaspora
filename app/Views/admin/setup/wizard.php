<style>
    .wizard-steps { display: flex; margin-bottom: 28px; position: relative; }
    .wizard-steps::before { content: ''; position: absolute; top: 17px; left: 12%; right: 12%; height: 2px; background: #E5E7EB; z-index: 0; }
    .wizard-step { text-align: center; z-index: 1; flex: 1; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .wizard-step:hover { transform: translateY(-2px); }
    .wizard-step .circle { width: 36px; height: 36px; border-radius: 50%; background: #E5E7EB; color: #6B7280; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px; font-weight: 700; font-size: 14px; transition: all 0.3s; }
    .wizard-step.active .circle { background: #2563EB; color: white; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
    .wizard-step.done .circle { background: #10B981; color: white; }
    .wizard-step .label { font-size: 10px; color: #6B7280; font-weight: 500; }
    .wizard-step.active .label { color: #2563EB; font-weight: 600; }
    .wizard-step.done .label { color: #10B981; }
    .quick-list { background: #F9FAFB; border-radius: 10px; padding: 10px 14px; margin-top: 8px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; border: 1px solid #E5E7EB; }
    .quick-list:hover { background: #F0F9FF; }
    .inline-form { display: flex; gap: 8px; flex-wrap: wrap; align-items: flex-end; }
    .inline-form .form-input, .inline-form .form-select { flex: 1; min-width: 100px; }
    .step-icon { font-size: 40px; margin-bottom: 8px; display: block; }
    
    /* ✅ TAMBAHAN: Responsive wizard */
    @media (max-width: 768px) {
        .wizard-steps { flex-direction: column; gap: 12px; }
        .wizard-steps::before { display: none; }
        .wizard-step { display: flex; align-items: center; gap: 10px; }
        .wizard-step .label { text-align: left; }
        .inline-form { flex-direction: column; }
        .inline-form .form-input, .inline-form .form-select { width: 100%; }
    }
</style>

<div class="page-title">
    <i class="fas fa-magic" style="color:#2563EB;margin-right:8px;"></i> Setup Wizard
</div>
<div class="page-subtitle">Panduan langkah demi langkah setup sistem absensi QR Code</div>

<!-- WIZARD STEPS -->
<div class="wizard-steps">
    <a href="?step=1" class="wizard-step <?= ($step ?? 1) == 1 ? 'active' : '' ?> <?= ($step ?? 1) > 1 ? 'done' : '' ?>" style="text-decoration:none;">
        <div class="circle"><?= ($step ?? 1) > 1 ? '✓' : '1' ?></div>
        <div class="label">📅 Tahun Ajaran<br>& Semester</div>
    </a>
    <a href="?step=2" class="wizard-step <?= ($step ?? 1) == 2 ? 'active' : '' ?> <?= ($step ?? 1) > 2 ? 'done' : '' ?>" style="text-decoration:none;">
        <div class="circle"><?= ($step ?? 1) > 2 ? '✓' : '2' ?></div>
        <div class="label">📚 Jurusan<br>& Mapel</div>
    </a>
    <a href="?step=3" class="wizard-step <?= ($step ?? 1) == 3 ? 'active' : '' ?> <?= ($step ?? 1) > 3 ? 'done' : '' ?>" style="text-decoration:none;">
        <div class="circle"><?= ($step ?? 1) > 3 ? '✓' : '3' ?></div>
        <div class="label">🏫 Kelas<br>& Penempatan</div>
    </a>
    <a href="?step=4" class="wizard-step <?= ($step ?? 1) == 4 ? 'active' : '' ?> <?= ($step ?? 1) > 4 ? 'done' : '' ?>" style="text-decoration:none;">
        <div class="circle"><?= ($step ?? 1) > 4 ? '✓' : '4' ?></div>
        <div class="label">📋 Jadwal<br>Pelajaran</div>
    </a>
</div>

<!-- CONTENT CARD -->
<div class="card">
    <div class="card-body" style="padding:24px;">

        <!-- ============ STEP 1: TAHUN AJARAN & SEMESTER ============ -->
        <?php if (($step ?? 1) == 1): ?>
            <div class="card-title" style="font-size:18px;margin-bottom:4px;">
                <i class="fas fa-calendar-range" style="color:#2563EB;"></i> Tahun Ajaran & Semester
            </div>
            <p style="color:#6B7280;font-size:13px;margin-bottom:20px;">Langkah pertama: tentukan tahun ajaran dan semester yang aktif.</p>
            
            <!-- TAHUN AJARAN -->
            <div style="background:#F9FAFB;border-radius:12px;padding:20px;margin-bottom:20px;">
                <h6 style="font-size:14px;margin-bottom:12px;"><i class="fas fa-calendar-check" style="color:#2563EB;"></i> Tahun Ajaran</h6>
                
                <?php if (!empty($tahun_ajaran_aktif)): ?>
                    <div class="alert alert-success" style="margin-bottom:0;">
                        <i class="fas fa-check-circle"></i> 
                        <strong>Tahun Ajaran Aktif:</strong> <?= esc($tahun_ajaran_aktif['nama']) ?>
                        <span style="font-size:11px;margin-left:8px;opacity:0.7;">
                            (<?= formatTanggal($tahun_ajaran_aktif['tanggal_mulai'], 'd/m/Y') ?> - <?= formatTanggal($tahun_ajaran_aktif['tanggal_selesai'], 'd/m/Y') ?>)
                        </span>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning" style="margin-bottom:12px;">
                        <i class="fas fa-exclamation-triangle"></i> Belum ada Tahun Ajaran aktif.
                    </div>
                    <form action="<?= base_url('admin/tahun-ajaran/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="redirect" value="/admin/setup?step=2">
                        <div class="inline-form">
                            <input type="text" name="nama" class="form-input" placeholder="Nama: 2026/2027" required style="min-width:150px;">
                            <input type="date" name="tanggal_mulai" class="form-input" required style="min-width:130px;">
                            <input type="date" name="tanggal_selesai" class="form-input" required style="min-width:130px;">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <!-- SEMESTER -->
            <?php if (!empty($tahun_ajaran_aktif)): ?>
            <div style="background:#F9FAFB;border-radius:12px;padding:20px;">
                <h6 style="font-size:14px;margin-bottom:12px;"><i class="fas fa-clock" style="color:#2563EB;"></i> Semester</h6>
                
                <?php if (!empty($semester_aktif)): ?>
                    <div class="alert alert-success" style="margin-bottom:0;">
                        <i class="fas fa-check-circle"></i> 
                        <strong>Semester Aktif:</strong> <?= esc($semester_aktif['nama']) ?> (Kode <?= $semester_aktif['kode'] ?>)
                        <span style="font-size:11px;margin-left:8px;opacity:0.7;">
                            (<?= formatTanggal($semester_aktif['tanggal_mulai'], 'd/m/Y') ?> - <?= formatTanggal($semester_aktif['tanggal_selesai'], 'd/m/Y') ?>)
                        </span>
                    </div>
                <?php else: ?>
                    <form action="<?= base_url('admin/semester/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="redirect" value="/admin/setup?step=2">
                        <input type="hidden" name="tahun_ajaran_id" value="<?= $tahun_ajaran_aktif['id'] ?>">
                        <div class="inline-form">
                            <input type="text" name="nama" class="form-input" placeholder="Semester Ganjil" required style="min-width:150px;">
                            <select name="kode" class="form-select" required style="min-width:120px;">
                                <option value="1">1 - Ganjil</option>
                                <option value="2">2 - Genap</option>
                            </select>
                            <input type="date" name="tanggal_mulai" class="form-input" required style="min-width:130px;">
                            <input type="date" name="tanggal_selesai" class="form-input" required style="min-width:130px;">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Status -->
            <div style="text-align:center;margin-top:20px;">
                <?php if (!empty($tahun_ajaran_aktif) && !empty($semester_aktif)): ?>
                    <span class="badge badge-success" style="font-size:13px;padding:8px 16px;">
                        <i class="fas fa-check"></i> Step 1 Selesai! Lanjut ke Step 2
                    </span>
                <?php else: ?>
                    <span class="badge badge-warning" style="font-size:13px;padding:8px 16px;">
                        <i class="fas fa-hourglass-half"></i> Lengkapi data di atas
                    </span>
                <?php endif; ?>
            </div>

        <!-- ============ STEP 2: JURUSAN & MAPEL ============ -->
        <?php elseif (($step ?? 1) == 2): ?>
            <div class="card-title" style="font-size:18px;margin-bottom:4px;">
                <i class="fas fa-diagram-3" style="color:#2563EB;"></i> Jurusan & Mata Pelajaran
            </div>
            <p style="color:#6B7280;font-size:13px;margin-bottom:20px;">Langkah kedua: tambahkan jurusan dan mata pelajaran.</p>

            <!-- JURUSAN -->
            <div style="background:#F9FAFB;border-radius:12px;padding:20px;margin-bottom:20px;">
                <h6 style="font-size:14px;margin-bottom:12px;"><i class="fas fa-graduation-cap" style="color:#2563EB;"></i> Jurusan</h6>
                <?php if (!empty($jurusan_list)): ?>
                    <div style="margin-bottom:12px;">
                        <?php foreach ($jurusan_list as $j): ?>
                        <div class="quick-list">
                            <span><strong><?= esc($j['kode']) ?></strong> - <?= esc($j['nama']) ?> (<?= esc($j['singkatan'] ?? '-') ?>)</span>
                            <span><?= badgeAktif($j['is_active'] ?? 1) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning" style="margin-bottom:12px;">Belum ada jurusan.</div>
                <?php endif; ?>
                <form action="<?= base_url('admin/jurusan/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="redirect" value="/admin/setup?step=2">
                    <div class="inline-form">
                        <input type="text" name="kode" class="form-input" placeholder="Kode: RPL" required>
                        <input type="text" name="singkatan" class="form-input" placeholder="Singkatan: RPL">
                        <input type="text" name="nama" class="form-input" placeholder="Nama: Rekayasa Perangkat Lunak" required>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</button>
                    </div>
                </form>
            </div>

            <!-- MAPEL -->
            <div style="background:#F9FAFB;border-radius:12px;padding:20px;">
                <h6 style="font-size:14px;margin-bottom:12px;"><i class="fas fa-book" style="color:#2563EB;"></i> Mata Pelajaran</h6>
                <?php if (!empty($mapel_list)): ?>
                    <div style="margin-bottom:12px;">
                        <?php foreach ($mapel_list as $m): ?>
                        <div class="quick-list">
                            <span><strong><?= esc($m['kode']) ?></strong> - <?= esc($m['nama']) ?></span>
                            <span><span class="badge badge-primary"><?= esc($m['kode']) ?></span></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning" style="margin-bottom:12px;">Belum ada mata pelajaran.</div>
                <?php endif; ?>
                <form action="<?= base_url('admin/mapel/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="redirect" value="/admin/setup?step=2">
                    <div class="inline-form">
                        <input type="text" name="kode" class="form-input" placeholder="Kode: PW" required>
                        <input type="text" name="nama" class="form-input" placeholder="Nama: Pemrograman Web" required>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</button>
                    </div>
                </form>
            </div>

        <!-- ============ STEP 3: KELAS ============ -->
        <?php elseif (($step ?? 1) == 3): ?>
            <div class="card-title" style="font-size:18px;margin-bottom:4px;">
                <i class="fas fa-door-open" style="color:#2563EB;"></i> Kelas & Penempatan
            </div>
            <p style="color:#6B7280;font-size:13px;margin-bottom:20px;">Langkah ketiga: buat kelas dan tempatkan siswa.</p>

            <div style="background:#F9FAFB;border-radius:12px;padding:20px;margin-bottom:20px;">
                <h6 style="font-size:14px;margin-bottom:12px;"><i class="fas fa-door-open" style="color:#2563EB;"></i> Daftar Kelas</h6>
                <?php if (!empty($kelas_list)): ?>
                    <?php foreach ($kelas_list as $k): ?>
                    <div class="quick-list">
                        <span><strong><?= esc($k['tingkat']) ?> <?= esc($k['rombel']) ?></strong> - Kapasitas: <?= $k['kapasitas'] ?> siswa</span>
                        <?= badgeAktif($k['is_active'] ?? 1) ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning" style="margin-bottom:12px;">Belum ada kelas.</div>
                <?php endif; ?>
                <form action="<?= base_url('admin/kelas/store') ?>" method="post" style="margin-top:8px;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="redirect" value="/admin/setup?step=3">
                    <div class="inline-form">
                        <select name="tingkat" class="form-select" required style="min-width:90px;">
                            <option value="">Tingkat</option>
                            <option>X</option><option>XI</option><option>XII</option>
                        </select>
                        <select name="jurusan_id" class="form-select" style="min-width:140px;">
                            <option value="">Jurusan</option>
                            <?php foreach ($jurusan_list as $j): ?>
                            <option value="<?= $j['id'] ?>"><?= esc($j['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="rombel" class="form-input" placeholder="Rombel: A" required style="min-width:100px;">
                        <input type="number" name="kapasitas" class="form-input" placeholder="30" value="30" style="min-width:80px;">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</button>
                    </div>
                </form>
            </div>

            <div style="text-align:center;">
                <p style="color:#6B7280;font-size:13px;margin-bottom:8px;">Setelah kelas dibuat, masukkan siswa ke dalam kelas:</p>
                <a href="<?= base_url('admin/siswa/penempatan-kelas') ?>" class="btn btn-outline">
                    <i class="fas fa-people-arrows"></i> Buka Penempatan Kelas
                </a>
            </div>

        <!-- ============ STEP 4: JADWAL ============ -->
        <?php elseif (($step ?? 1) == 4): ?>
            <div class="card-title" style="font-size:18px;margin-bottom:4px;">
                <i class="fas fa-calendar-alt" style="color:#2563EB;"></i> Jadwal Pelajaran
            </div>
            <p style="color:#6B7280;font-size:13px;margin-bottom:20px;">Langkah terakhir: buat jadwal pelajaran untuk setiap kelas.</p>

            <div style="background:#F9FAFB;border-radius:12px;padding:20px;margin-bottom:20px;">
                <h6 style="font-size:14px;margin-bottom:12px;"><i class="fas fa-list" style="color:#2563EB;"></i> Daftar Jadwal</h6>
                <?php if (!empty($jadwal_list)): ?>
                    <?php foreach ($jadwal_list as $j): ?>
                    <div class="quick-list">
                        <span>
                            <strong style="text-transform:capitalize;"><?= esc($j['hari']) ?></strong> | 
                            <?= esc($j['jam_mulai']) ?>-<?= esc($j['jam_selesai']) ?>
                            <?php if (!empty($j['mapel_nama'])): ?> | <?= esc($j['mapel_nama']) ?><?php endif; ?>
                        </span>
                        <span><span class="badge badge-primary"><?= esc($j['hari']) ?></span></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning" style="margin-bottom:12px;">Belum ada jadwal pelajaran.</div>
                <?php endif; ?>
            </div>

            <div style="text-align:center;">
                <a href="<?= base_url('admin/jadwal/create') ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle"></i> Tambah Jadwal Pelajaran
                </a>
                <p style="color:#6B7280;font-size:12px;margin-top:8px;">Atau buka menu Jadwal di sidebar untuk mengelola</p>
            </div>

            <!-- SELESAI -->
            <div style="text-align:center;margin-top:24px;padding:20px;background:#ECFDF5;border-radius:12px;">
                <i class="fas fa-check-circle" style="font-size:40px;color:#10B981;"></i>
                <h4 style="color:#059669;margin:8px 0;">Setup Selesai! 🎉</h4>
                <p style="color:#6B7280;font-size:13px;">Sistem absensi QR Code siap digunakan oleh Guru dan Siswa.</p>
                <a href="<?= base_url('admin') ?>" class="btn btn-primary mt-2">
                    <i class="fas fa-home"></i> Mulai Gunakan Sistem
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- NAVIGATION -->
<div style="display:flex;justify-content:space-between;margin-top:16px;">
    <div>
        <?php if (($step ?? 1) > 1): ?>
            <a href="?step=<?= ($step ?? 1) - 1 ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Sebelumnya
            </a>
        <?php endif; ?>
    </div>
    <div>
        <?php if (($step ?? 1) < 4): ?>
            <a href="?step=<?= ($step ?? 1) + 1 ?>" class="btn btn-primary">
                Selanjutnya <i class="fas fa-arrow-right"></i>
            </a>
        <?php endif; ?>
    </div>
</div>