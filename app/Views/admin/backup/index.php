<div class="page-title">💾 Backup & Download Data</div>
<div class="page-subtitle">Backup database, download data siswa, absensi</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-database"></i></div>
        <div class="stat-value"><?= $total_tables ?? 0 ?></div>
        <div class="stat-label">Tabel Database</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-value"><?= $total_siswa ?? 0 ?></div>
        <div class="stat-label">Data Siswa</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-value"><?= $total_guru ?? 0 ?></div>
        <div class="stat-label">Data Guru</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clipboard-check"></i></div>
        <div class="stat-value"><?= $total_absensi ?? 0 ?></div>
        <div class="stat-label">Data Absensi</div>
    </div>
</div>

<!-- Download Options -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-download"></i> Download Data</div>
    </div>
    <div class="card-body">
        <div class="stats-grid">
            <!-- Backup Lengkap -->
            <a href="/admin/backup/downloadFull" class="stat-card" style="text-decoration:none;color:inherit;">
                <div class="stat-icon blue"><i class="fas fa-file-archive"></i></div>
                <div class="stat-value" style="font-size:14px;">📦 Backup Lengkap</div>
                <div class="stat-label">Database + File Uploads + .env</div>
            </a>

            <!-- Database Saja -->
            <a href="/admin/backup/downloadDatabase" class="stat-card" style="text-decoration:none;color:inherit;">
                <div class="stat-icon green"><i class="fas fa-database"></i></div>
                <div class="stat-value" style="font-size:14px;">🗄️ Database SQL</div>
                <div class="stat-label">Hanya struktur & data database</div>
            </a>

            <!-- Data Siswa -->
            <a href="/admin/backup/downloadSiswa" class="stat-card" style="text-decoration:none;color:inherit;">
                <div class="stat-icon purple"><i class="fas fa-file-csv"></i></div>
                <div class="stat-value" style="font-size:14px;">👨‍🎓 Data Siswa</div>
                <div class="stat-label">Export ke CSV (Excel)</div>
            </a>

            <!-- Data Absensi -->
            <a href="/admin/backup/downloadAbsensi?tanggal=<?= date('Y-m-d') ?>" class="stat-card" style="text-decoration:none;color:inherit;">
                <div class="stat-icon orange"><i class="fas fa-file-excel"></i></div>
                <div class="stat-value" style="font-size:14px;">📋 Data Absensi</div>
                <div class="stat-label">Absensi hari ini (CSV)</div>
            </a>
        </div>
    </div>
</div>

<!-- Riwayat Backup -->
<?php if (!empty($existing_backups)): ?>
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-history"></i> Riwayat Backup</div>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama File</th>
                    <th>Ukuran</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($existing_backups as $backup): ?>
                <tr>
                    <td><strong><?= esc($backup['name']) ?></strong></td>
                    <td><?= esc($backup['size']) ?></td>
                    <td><?= esc($backup['date']) ?></td>
                    <td>
                        <a href="/writable/backups/<?= esc($backup['name']) ?>" class="btn btn-sm btn-outline" download>
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="/admin/backup/hapusBackup/<?= esc($backup['name']) ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Hapus backup ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>