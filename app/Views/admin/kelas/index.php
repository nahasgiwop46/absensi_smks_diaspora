<div class="page-title">🏫 Data Kelas</div>
<div class="page-subtitle">Daftar kelas aktif</div>

<!-- Toolbar -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div class="toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchKelas" placeholder="Cari kelas..." onkeyup="filterKelas()">
            </div>
            <select class="filter-select" id="filterTingkat" onchange="filterKelas()">
                <option value="">Semua Tingkat</option>
                <option value="X">📗 X</option>
                <option value="XI">📘 XI</option>
                <option value="XII">📙 XII</option>
            </select>
            <select class="filter-select" id="filterJurusan" onchange="filterKelas()">
                <option value="">Semua Jurusan</option>
                <?php foreach ($jurusan ?? [] as $j): ?>
                <option value="<?= esc($j['singkatan'] ?? '') ?>"><?= esc($j['singkatan'] ?? '') ?></option>
                <?php endforeach; ?>
            </select>
            <select class="filter-select" id="filterStatus" onchange="filterKelas()">
                <option value="">Semua Status</option>
                <option value="✅ Aktif">✅ Aktif</option>
                <option value="❌ Nonaktif">❌ Nonaktif</option>
            </select>
            <a href="/admin/kelas/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kelas
            </a>
        </div>
    </div>
</div>

<!-- Grid Kelas -->
<div class="stats-grid" id="kelasGrid">
    <?php if (!empty($kelas)): ?>
        <?php foreach ($kelas as $k): ?>
        <?php 
        $iconTingkat = ['X' => '📗', 'XI' => '📘', 'XII' => '📙'];
        $icon = $iconTingkat[$k['tingkat']] ?? '🏫';
        ?>
        <div class="stat-card kelas-card" style="text-align:center;position:relative;"
             data-tingkat="<?= esc($k['tingkat']) ?>"
             data-jurusan="<?= esc($k['jurusan_singkatan'] ?? '') ?>"
             data-status="<?= $k['is_active'] == 1 ? '✅ Aktif' : '❌ Nonaktif' ?>">
            
            <div style="width:70px;height:70px;background:#EFF6FF;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:32px;color:#2563EB;margin-bottom:12px;">
                <?= $icon ?>
            </div>
            <h3 style="font-size:20px;font-weight:700;margin-bottom:4px;">
                <?= esc($k['tingkat']) ?> <?= esc($k['jurusan_singkatan'] ?? '') ?> <?= esc($k['rombel']) ?>
            </h3>
            <p style="color:#6B7280;margin-bottom:4px;font-size:14px;">
                <?= esc($k['jurusan_nama'] ?? 'Umum') ?>
            </p>
            <p style="color:#6B7280;margin-bottom:8px;font-size:13px;">
                👥 Kapasitas: <strong><?= $k['kapasitas'] ?></strong> | 
                🧑‍🎓 Siswa: <strong><?= $k['total_siswa'] ?? 0 ?></strong>
            </p>
            <?= badgeAktif($k['is_active'] ?? 0) ?>
            
            <div class="btn-group" style="justify-content:center;margin-top:12px;">
                <a href="/admin/kelas/detail/<?= $k['id'] ?>" class="btn-icon view" title="Detail">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="/admin/kelas/edit/<?= $k['id'] ?>" class="btn-icon edit" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="/admin/kelas/delete/<?= $k['id'] ?>" class="btn-icon delete" title="Hapus"
                   onclick="return confirm('Hapus kelas <?= esc($k['tingkat']) ?> <?= esc($k['jurusan_singkatan'] ?? '') ?> <?= esc($k['rombel']) ?>?')">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="text-align:center;padding:60px;color:#9CA3AF;grid-column:1/-1;">
            <i class="fas fa-school" style="font-size:64px;display:block;margin-bottom:16px;"></i>
            <p style="font-size:18px;">Belum ada data kelas</p>
            <a href="/admin/kelas/create" class="btn btn-primary mt-2">
                <i class="fas fa-plus"></i> Tambah Kelas
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function filterKelas() {
    const search   = document.getElementById('searchKelas')?.value?.toLowerCase() || '';
    const tingkat  = document.getElementById('filterTingkat')?.value || '';
    const jurusan  = document.getElementById('filterJurusan')?.value || '';
    const status   = document.getElementById('filterStatus')?.value || '';
    
    const cards = document.querySelectorAll('.kelas-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const text         = card.textContent.toLowerCase();
        const cardTingkat  = card.dataset.tingkat || '';
        const cardJurusan  = card.dataset.jurusan || '';
        const cardStatus   = card.dataset.status || '';
        
        const matchSearch  = text.includes(search);
        const matchTingkat = !tingkat || cardTingkat === tingkat.replace('📗 ', '').replace('📘 ', '').replace('📙 ', '');
        const matchJurusan = !jurusan || cardJurusan === jurusan;
        const matchStatus  = !status || cardStatus.includes(status.replace('✅ ', '').replace('❌ ', ''));
        
        if (matchSearch && matchTingkat && matchJurusan && matchStatus) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    const grid = document.getElementById('kelasGrid');
    let emptyMsg = grid.querySelector('.empty-filter');
    
    if (visibleCount === 0 && cards.length > 0) {
        if (!emptyMsg) {
            emptyMsg = document.createElement('div');
            emptyMsg.className = 'empty-filter';
            emptyMsg.style.cssText = 'text-align:center;padding:40px;color:#9CA3AF;grid-column:1/-1;';
            emptyMsg.innerHTML = '<i class="fas fa-search" style="font-size:48px;display:block;margin-bottom:12px;"></i><p>Tidak ada kelas yang cocok dengan filter</p>';
            grid.appendChild(emptyMsg);
        }
        emptyMsg.style.display = '';
    } else if (emptyMsg) {
        emptyMsg.style.display = 'none';
    }
}
</script>