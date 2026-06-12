<div class="page-title">🔒 Keamanan Sistem</div>
<div class="page-subtitle">Monitoring & proteksi aktivitas mencurigakan</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-value text-danger"><?= $total_failed ?? 0 ?></div>
        <div class="stat-label">Login Gagal Hari Ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-user-secret"></i></div>
        <div class="stat-value text-warning"><?= count($suspicious_ips ?? []) ?></div>
        <div class="stat-label">IP Mencurigakan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-ban"></i></div>
        <div class="stat-value"><?= $total_blocked ?? 0 ?></div>
        <div class="stat-label">IP Diblokir</div>
    </div>
    <div class="stat-card" style="display:flex;align-items:center;justify-content:center;">
        <a href="/admin/keamanan/blokirSemua" class="btn btn-danger btn-sm" 
           onclick="return confirm('Blokir semua IP mencurigakan?')">
            <i class="fas fa-shield-alt"></i> Blokir Semua
        </a>
    </div>
</div>

<!-- IP Mencurigakan -->
<?php if (!empty($suspicious_ips)): ?>
<div class="card" style="border-left:4px solid #DC2626;">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-skull"></i> ⚠️ IP Mencurigakan (Gagal Login >5x Hari Ini)</div>
        <span class="badge badge-danger"><?= count($suspicious_ips) ?> IP</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Percobaan</th>
                        <th>Username</th>
                        <th>User Agent</th>
                        <th>Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suspicious_ips as $ip): ?>
                    <tr>
                        <td><code style="background:#FEF2F2;padding:2px 8px;border-radius:4px;"><?= esc($ip['ip_address']) ?></code></td>
                        <td><span class="badge badge-danger"><?= $ip['total'] ?>x</span></td>
                        <td><?= esc($ip['last_username'] ?? '-') ?></td>
                        <td style="font-size:11px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= esc($ip['user_agent'] ?? '') ?>">
                            <?= esc(substr($ip['user_agent'] ?? '-', 0, 50)) ?>
                        </td>
                        <td style="font-size:11px;"><?= esc($ip['last_attempt']) ?></td>
                        <td>
                            <a href="/admin/keamanan/blokirIP/<?= $ip['ip_address'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Blokir IP <?= $ip['ip_address'] ?>?')">
                                <i class="fas fa-ban"></i> Blokir
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Blocked IPs -->
<?php if (!empty($blocked_ips)): ?>
<div class="card" style="border-left:4px solid #7C3AED;">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-ban"></i> 🚫 IP Diblokir</div>
        <span class="badge badge-purple"><?= count($blocked_ips) ?> IP</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table">
            <thead>
                <tr>
                    <th>IP Address</th>
                    <th>Alasan</th>
                    <th>Diblokir Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blocked_ips as $bip): ?>
                <tr>
                    <td><code><?= esc($bip['ip_address']) ?></code></td>
                    <td><?= esc($bip['reason'] ?? '-') ?></td>
                    <td style="font-size:11px;"><?= esc($bip['created_at']) ?></td>
                    <td>
                        <a href="/admin/keamanan/bukaBlokir/<?= $bip['id'] ?>" 
                           class="btn btn-sm btn-success"
                           onclick="return confirm('Buka blokir IP <?= esc($bip['ip_address']) ?>?')">
                            <i class="fas fa-unlock"></i> Buka Blokir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Login Logs -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-history"></i> Log Login Hari Ini</div>
        <span style="font-size:12px;color:#6B7280;"><?= count($logs ?? []) ?> aktivitas</span>
    </div>
    <div class="card-body" style="padding:0;max-height:400px;overflow-y:auto;">
        <?php if (empty($logs)): ?>
            <div class="empty-state">
                <i class="fas fa-check-circle" style="color:#10B981;"></i>
                <p>Tidak ada aktivitas login hari ini</p>
            </div>
        <?php else: ?>
        <table class="table" style="font-size:12px;">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Username</th>
                    <th>IP Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td style="font-size:11px;"><?= esc($log['created_at']) ?></td>
                    <td><?= esc($log['username'] ?? '-') ?></td>
                    <td><code style="font-size:11px;"><?= esc($log['ip_address']) ?></code></td>
                    <td>
                        <?php if ($log['status'] === 'success'): ?>
                            <span class="badge badge-success">✅ Sukses</span>
                        <?php elseif ($log['status'] === 'blocked'): ?>
                            <span class="badge badge-purple">🚫 Diblokir</span>
                        <?php else: ?>
                            <span class="badge badge-danger">❌ Gagal</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- Activity Logs -->
<?php if (!empty($activities)): ?>
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-list-alt"></i> Log Aktivitas User</div>
    </div>
    <div class="card-body" style="padding:0;max-height:400px;overflow-y:auto;">
        <table class="table" style="font-size:12px;">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $act): ?>
                <tr>
                    <td style="font-size:11px;"><?= esc($act['created_at']) ?></td>
                    <td><?= esc($act['nama_lengkap'] ?? '-') ?></td>
                    <td>
                        <strong><?= esc($act['action']) ?></strong>
                        <?php if (!empty($act['description'])): ?>
                            <br><small style="color:#6B7280;"><?= esc($act['description']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><code style="font-size:11px;"><?= esc($act['ip_address']) ?></code></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Info -->
<div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:12px;padding:16px;margin-top:16px;display:flex;gap:10px;align-items:flex-start;">
    <i class="fas fa-info-circle" style="color:#2563EB;font-size:20px;margin-top:2px;"></i>
    <div style="font-size:12px;color:#1E40AF;">
        <strong>Fitur Keamanan Aktif:</strong><br>
        ✅ Deteksi IP mencurigakan (gagal login >5x)<br>
        ✅ Auto-blokir IP (gagal login >10x)<br>
        ✅ Rate limiting (max 100 request/menit)<br>
        ✅ CSRF Protection di semua form<br>
        ✅ XSS Protection (input sanitization)<br>
        ✅ Security Headers (CSP, HSTS, X-Frame)<br>
        ✅ Session auto-expire (30 menit idle)
    </div>
</div>