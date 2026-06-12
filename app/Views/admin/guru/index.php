<div class="page-title">Data Guru</div>
<div class="page-subtitle">Kelola data guru dan tenaga pengajar</div>

<!-- Toolbar -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div class="toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchGuru" placeholder="Cari nama/NUPTK/NIP..." onkeyup="filterTable()">
            </div>
            <select class="filter-select" id="filterStatus" onchange="filterTable()">
                <option value="">Semua Status</option>
                <option value="Aktif">✅ Aktif</option>
                <option value="Nonaktif">❌ Nonaktif</option>
            </select>
            <a href="/admin/guru/penempatan" class="btn btn-outline">
                <i class="fas fa-calendar-alt"></i> Penempatan
            </a>
            <a href="/admin/guru/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Guru
            </a>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Guru</div>
        <span style="font-size:14px;color:#6B7280;"><?= count($guru ?? []) ?> data</span>
    </div>
    <div class="card-body">
        <?php if (empty($guru)): ?>
            <div style="text-align:center;padding:60px;color:#9CA3AF;">
                <i class="fas fa-chalkboard-teacher" style="font-size:64px;display:block;margin-bottom:16px;"></i>
                <p style="font-size:18px;">Belum ada data guru</p>
                <a href="/admin/guru/create" class="btn btn-primary" style="margin-top:12px;">
                    <i class="fas fa-plus"></i> Tambah Guru
                </a>
            </div>
        <?php else: ?>
        <div class="table">
            <table id="guruTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NUPTK</th>
                        <th>Nama Lengkap</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($guru as $g): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($g['nuptk'] ?? '-') ?></td>
                        <td>
                            <a href="/admin/guru/detail/<?= $g['id'] ?>" style="text-decoration:none;">
                                <strong><?= esc($g['nama_lengkap'] ?? '-') ?></strong>
                            </a>
                        </td>
                        <td><?= esc($g['nip'] ?? '-') ?></td>
                        <td><?= esc($g['email'] ?? '-') ?></td>
                        <td><?= esc($g['no_hp'] ?? '-') ?></td>
                        <td>
                            <?= badgeAktif($g['is_active'] ?? 0) ?>
                        </td>
                        <td>
                            <div class="btn-group" style="justify-content:center;">
                                <a href="/admin/guru/detail/<?= $g['id'] ?>" class="btn-icon view" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/guru/edit/<?= $g['id'] ?>" class="btn-icon edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/guru/delete/<?= $g['id'] ?>" 
                                   class="btn-icon delete" title="Hapus"
                                   onclick="return confirm('Hapus guru ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterTable() {
    const search = document.getElementById('searchGuru')?.value?.toLowerCase() || '';
    const status = document.getElementById('filterStatus')?.value || '';
    const rows = document.querySelectorAll('#guruTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowStatus = row.cells[6]?.textContent?.trim() || '';
        const matchStatus = !status || rowStatus.includes(status.replace('✅ ', '').replace('❌ ', ''));
        row.style.display = (text.includes(search) && matchStatus) ? '' : 'none';
    });
}
</script>