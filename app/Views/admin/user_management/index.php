<div class="page-title">👥 Manajemen User</div>
<div class="page-subtitle">Kelola semua pengguna sistem</div>

<!-- Statistik -->
<div class="stats-grid" style="margin-bottom:20px;">
    <?php 
    $totalAdmin = 0; $totalGuru = 0; $totalSiswa = 0; $totalKepsek = 0; $totalAktif = 0;
    if (!empty($users)) {
        foreach ($users as $u) {
            $roleKode = strtoupper($u['role_kode'] ?? '');
            if ($roleKode === 'ADMIN') $totalAdmin++;
            elseif ($roleKode === 'GURU') $totalGuru++;
            elseif ($roleKode === 'SISWA') $totalSiswa++;
            elseif ($roleKode === 'KEPSEK') $totalKepsek++;
            if (($u['is_active'] ?? 0) == 1) $totalAktif++;
        }
    }
    ?>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#DC2626;"><?= $totalAdmin ?></div>
        <div class="stat-label">👑 Admin</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#2563EB;"><?= $totalGuru ?></div>
        <div class="stat-label">👨‍🏫 Guru</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#059669;"><?= $totalSiswa ?></div>
        <div class="stat-label">🧑‍🎓 Siswa</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#7C3AED;"><?= $totalKepsek ?></div>
        <div class="stat-label">🏫 Kepsek</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#10B981;"><?= $totalAktif ?></div>
        <div class="stat-label">✅ Aktif</div>
    </div>
</div>

<!-- Toolbar -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div class="toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchUser" placeholder="Cari username, nama, atau email..." onkeyup="filterUsers()">
            </div>
            <select class="filter-select" id="filterRole" onchange="filterUsers()">
                <option value="">👤 Semua Role</option>
                <?php foreach ($roles as $role): ?>
                <option value="<?= esc($role['nama'] ?? '') ?>">
                    <?php 
                    $icon = match(strtoupper($role['kode'] ?? '')) {
                        'ADMIN' => '👑', 'GURU' => '👨‍🏫', 'SISWA' => '🧑‍🎓', 'KEPSEK' => '🏫',
                        default => '👤'
                    };
                    ?>
                    <?= $icon ?> <?= esc($role['nama'] ?? '') ?>
                </option>
                <?php endforeach; ?>
            </select>
            <select class="filter-select" id="filterStatus" onchange="filterUsers()">
                <option value="">📋 Semua Status</option>
                <option value="✅ Aktif">✅ Aktif</option>
                <option value="❌ Nonaktif">❌ Nonaktif</option>
            </select>
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-list"></i> Daftar User
        </div>
        <span style="font-size:14px;color:#6B7280;" id="totalData"><?= count($users ?? []) ?> user</span>
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <i class="fas fa-users" style="font-size:64px;"></i>
                <p style="font-size:18px;">Tidak ada data user</p>
                <a href="/admin/users/create" class="btn btn-primary mt-2">
                    <i class="fas fa-plus"></i> Tambah User
                </a>
            </div>
        <?php else: ?>
        <div class="table">
            <table id="userTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($users as $user): 
                        $roleKode = strtoupper($user['role_kode'] ?? '');
                        $roleBadge = match($roleKode) {
                            'ADMIN' => 'badge-danger',
                            'GURU' => 'badge-primary',
                            'SISWA' => 'badge-success',
                            'KEPSEK' => 'badge-purple',
                            default => 'badge-info'
                        };
                        $roleIcon = match($roleKode) {
                            'ADMIN' => '👑', 'GURU' => '👨‍🏫', 'SISWA' => '🧑‍🎓', 'KEPSEK' => '🏫',
                            default => '👤'
                        };
                        $isSelf = $user['id'] == session()->get('user_id');
                    ?>
                    <tr style="<?= $isSelf ? 'background:#F0F9FF;' : '' ?>">
                        <td><?= $no++ ?></td>
                        <td>
                            <strong><?= esc($user['username'] ?? '') ?></strong>
                            <?php if ($isSelf): ?>
                                <span class="badge badge-info" style="margin-left:6px;">🔵 Anda</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($user['nama_lengkap'] ?? '') ?></td>
                        <td>
                            <span class="badge <?= $roleBadge ?>"><?= $roleIcon ?> <?= esc($user['role_nama'] ?? '') ?></span>
                        </td>
                        <td><?= esc($user['email'] ?? '-') ?></td>
                        <td><?= badgeAktif($user['is_active'] ?? 0) ?></td>
                        <td>
                            <div class="btn-group" style="justify-content:center;">
                                <a href="/admin/users/edit/<?= $user['id'] ?>" class="btn-icon edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if (!$isSelf): ?>
                                <button onclick="toggleStatus(<?= $user['id'] ?>)" class="btn-icon view" title="Toggle Status">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <a href="/admin/users/delete/<?= $user['id'] ?>" 
                                   class="btn-icon delete" title="Hapus"
                                   onclick="return confirm('Hapus user «<?= esc($user['username']) ?>»?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
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
function filterUsers() {
    const search = document.getElementById('searchUser')?.value?.toLowerCase() || '';
    const role   = document.getElementById('filterRole')?.value || '';
    const status = document.getElementById('filterStatus')?.value || '';
    let count = 0;
    
    const rows = document.querySelectorAll('#userTable tbody tr');
    rows.forEach(row => {
        const text      = row.textContent.toLowerCase();
        const rowRole   = row.cells[3]?.textContent?.trim() || '';
        const rowStatus = row.cells[5]?.textContent?.trim() || '';
        
        const match = text.includes(search) && 
                      (!role || rowRole.includes(role.replace(/^[^\s]+\s/, ''))) && 
                      (!status || rowStatus.includes(status.replace('✅ ', '').replace('❌ ', '')));
        
        row.style.display = match ? '' : 'none';
        if (match) count++;
    });
    
    var total = document.getElementById('totalData');
    if (total) total.textContent = count + ' user';
}

function toggleStatus(id) {
    if (!confirm('Ubah status user ini?')) return;
    
    fetch('/admin/users/toggle-status/' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Gagal mengubah status');
        }
    });
}
</script>