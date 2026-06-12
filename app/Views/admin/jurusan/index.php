<div class="page-title">📚 Data Jurusan</div>
<div class="page-subtitle">Kelola data jurusan</div>

<!-- Toolbar -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div class="toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchJurusan" placeholder="Cari jurusan..." onkeyup="filterJurusan()">
            </div>
            <select class="filter-select" id="filterStatus" onchange="filterJurusan()">
                <option value="">Semua Status</option>
                <option value="✅ Aktif">✅ Aktif</option>
                <option value="❌ Nonaktif">❌ Nonaktif</option>
            </select>
            <a href="/admin/jurusan/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Jurusan
            </a>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Jurusan</div>
        <span style="font-size:14px;color:#6B7280;" id="totalData">
            <?= count($jurusan ?? []) ?> data
        </span>
    </div>
    <div class="card-body">
        <?php if (empty($jurusan)): ?>
            <div style="text-align:center;padding:60px;color:#9CA3AF;" id="emptyState">
                <i class="fas fa-book" style="font-size:64px;display:block;margin-bottom:16px;"></i>
                <p style="font-size:18px;">Belum ada data jurusan</p>
                <a href="/admin/jurusan/create" class="btn btn-primary" style="margin-top:12px;">
                    <i class="fas fa-plus"></i> Tambah Jurusan
                </a>
            </div>
        <?php else: ?>
        <div class="table">
            <table id="jurusanTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Singkatan</th>
                        <th>Nama Jurusan</th>
                        <th>Total Kelas</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($jurusan as $j): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <span class="badge badge-primary"><?= esc($j['kode'] ?? '') ?></span>
                        </td>
                        <td><strong><?= esc($j['singkatan'] ?? '-') ?></strong></td>
                        <td><?= esc($j['nama'] ?? '') ?></td>
                        <td>
                            <span class="badge badge-info"><?= esc($j['total_kelas'] ?? 0) ?> kelas</span>
                        </td>
                        <td><?= badgeAktif($j['is_active'] ?? 0) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="/admin/jurusan/detail/<?= $j['id'] ?>" class="btn-action btn-detail" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/jurusan/edit/<?= $j['id'] ?>" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/jurusan/delete/<?= $j['id'] ?>" 
                                   class="btn-action btn-delete" title="Hapus"
                                   onclick="return confirm('Hapus jurusan «<?= esc($j['nama']) ?>»?')">
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

<style>
.action-buttons{display:flex;align-items:center;justify-content:center;gap:8px;}
.btn-action{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;text-decoration:none;transition:all .2s ease;font-size:14px;}
.btn-action:hover{transform:translateY(-2px);}
.btn-detail{background:#EFF6FF;color:#2563EB;}
.btn-detail:hover{background:#DBEAFE;}
.btn-edit{background:#FEF3C7;color:#D97706;}
.btn-edit:hover{background:#FDE68A;}
.btn-delete{background:#FEE2E2;color:#DC2626;}
.btn-delete:hover{background:#FECACA;}
</style>

<script>
function filterJurusan()
{
    const search = document.getElementById('searchJurusan')?.value?.toLowerCase() || '';
    const status = document.getElementById('filterStatus')?.value || '';
    const rows = document.querySelectorAll('#jurusanTable tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowStatus = row.cells[5]?.textContent?.trim() || '';
        const matchSearch = text.includes(search);
        const matchStatus = !status || rowStatus.includes(status.replace('✅ ', '').replace('❌ ', ''));
        
        if (matchSearch && matchStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const totalEl = document.getElementById('totalData');
    if (totalEl) {
        totalEl.textContent = visibleCount + ' data';
    }

    let emptyRow = document.getElementById('emptyFilterRow');
    if (visibleCount === 0 && rows.length > 0) {
        if (!emptyRow) {
            emptyRow = document.createElement('tr');
            emptyRow.id = 'emptyFilterRow';
            emptyRow.innerHTML = `
                <td colspan="7" style="text-align:center;padding:40px;color:#9CA3AF;">
                    <i class="fas fa-search" style="font-size:48px;display:block;margin-bottom:12px;"></i>
                    Tidak ada jurusan yang cocok
                </td>`;
            document.querySelector('#jurusanTable tbody').appendChild(emptyRow);
        }
        emptyRow.style.display = '';
    } else if (emptyRow) {
        emptyRow.style.display = 'none';
    }
}
</script>