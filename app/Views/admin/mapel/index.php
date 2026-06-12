<?php
/** @var array $mapel */
?>

<div class="page-title">📚 Mata Pelajaran</div>
<div class="page-subtitle">Kelola data mata pelajaran</div>

<!-- Statistik -->
<div class="stats-grid" style="margin-bottom:20px;">
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#2563EB;"><?= count($mapel ?? []) ?></div>
        <div class="stat-label">📖 Total Mapel</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <?php 
        $totalKode = !empty($mapel) ? count(array_unique(array_column($mapel, 'kode'))) : 0;
        ?>
        <div class="stat-value" style="color:#10B981;"><?= $totalKode ?></div>
        <div class="stat-label">🏷️ Kode Unik</div>
    </div>
</div>

<!-- Toolbar -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div class="toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchMapel" placeholder="Cari kode atau nama mapel..." onkeyup="filterMapel()">
            </div>
            <a href="/admin/mapel/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Mapel
            </a>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Daftar Mata Pelajaran
        </div>
        <span style="font-size:14px;color:#6B7280;" id="totalData"><?= count($mapel ?? []) ?> mapel</span>
    </div>
    <div class="card-body">
        <?php if (empty($mapel)): ?>
            <div class="empty-state">
                <i class="fas fa-book-open" style="font-size:64px;"></i>
                <p style="font-size:18px;">Belum ada mata pelajaran</p>
                <a href="/admin/mapel/create" class="btn btn-primary mt-2">
                    <i class="fas fa-plus"></i> Tambah Mapel
                </a>
            </div>
        <?php else: ?>
        <div class="table">
            <table id="mapelTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($mapel as $m): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <span class="badge badge-primary" style="font-size:13px;">
                                <?= esc($m['kode'] ?? '') ?>
                            </span>
                        </td>
                        <td><strong><?= esc($m['nama'] ?? '') ?></strong></td>
                        <td>
                            <div class="btn-group" style="justify-content:center;">
                                <a href="/admin/mapel/edit/<?= $m['id'] ?>" class="btn-icon edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/mapel/delete/<?= $m['id'] ?>" 
                                   class="btn-icon delete" title="Hapus"
                                   onclick="return confirm('Hapus mapel «<?= esc($m['nama']) ?>»?')">
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
function filterMapel() {
    const search = document.getElementById('searchMapel')?.value?.toLowerCase() || '';
    const rows = document.querySelectorAll('#mapelTable tbody tr');
    let count = 0;
    
    rows.forEach(row => {
        const match = row.textContent.toLowerCase().includes(search);
        row.style.display = match ? '' : 'none';
        if (match) count++;
    });
    
    const total = document.getElementById('totalData');
    if (total) total.textContent = count + ' mapel';
    
    // Empty state saat filter
    let emptyRow = document.getElementById('emptyFilterRow');
    if (count === 0 && rows.length > 0) {
        if (!emptyRow) {
            emptyRow = document.createElement('tr');
            emptyRow.id = 'emptyFilterRow';
            emptyRow.innerHTML = `
                <td colspan="4" style="text-align:center;padding:40px;color:#9CA3AF;">
                    <i class="fas fa-search" style="font-size:48px;display:block;margin-bottom:12px;"></i>
                    Tidak ada mapel yang cocok
                </td>`;
            document.querySelector('#mapelTable tbody').appendChild(emptyRow);
        }
        emptyRow.style.display = '';
    } else if (emptyRow) {
        emptyRow.style.display = 'none';
    }
}
</script>