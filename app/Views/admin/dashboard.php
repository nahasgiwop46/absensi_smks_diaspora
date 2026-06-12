



<div class="page-title">Dashboard Admin</div>
<div class="page-subtitle">Selamat datang, <?= esc(session()->get('nama_lengkap')) ?>!</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:48px;height:48px;background:#EFF6FF;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#2563EB;font-size:22px;">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="stat-value"><?= esc($total_siswa ?? 0) ?></div>
        <div class="stat-label">Total Siswa</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:48px;height:48px;background:#ECFDF5;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#10B981;font-size:22px;">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
        <div class="stat-value"><?= esc($total_guru ?? 0) ?></div>
        <div class="stat-label">Total Guru</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:48px;height:48px;background:#F5F3FF;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#8B5CF6;font-size:22px;">
                <i class="fas fa-school"></i>
            </div>
        </div>
        <div class="stat-value"><?= esc($total_kelas ?? 0) ?></div>
        <div class="stat-label">Total Kelas</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:48px;height:48px;background:#FFF7ED;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#F97316;font-size:22px;">
                <i class="fas fa-clipboard-check"></i>
            </div>
        </div>
        <div class="stat-value"><?= esc($hadir_hari_ini ?? 0) ?></div>
        <div class="stat-label">Hadir Hari Ini</div>
    </div>
</div>

<!-- Ringkasan Absensi -->
<!-- Ringkasan Absensi -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-chart-pie" style="color:#2563EB;"></i> 
            Ringkasan Absensi Hari Ini
        </div>
        <span style="font-size:13px;color:#6B7280;background:#F3F4F6;padding:4px 12px;border-radius:20px;">
            <i class="far fa-calendar-alt"></i> <?= date('d F Y') ?>
        </span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:center;">
            
            <!-- Tabel Status -->
            <div>
                <table class="table" style="min-width:auto;">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th style="text-align:center;">Jumlah</th>
                            <th style="text-align:center;">Persen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_absensi = ($hadir_hari_ini ?? 0) + ($izin_hari_ini ?? 0) + ($sakit_hari_ini ?? 0) + ($alpa_hari_ini ?? 0) + ($terlambat_hari_ini ?? 0);
                        $total_absensi = $total_absensi > 0 ? $total_absensi : 1; // hindari division by zero
                        ?>
                        <tr>
                            <td>
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Hadir
                                </span>
                            </td>
                            <td style="text-align:center;"><strong><?= esc($hadir_hari_ini ?? 0) ?></strong></td>
                            <td style="text-align:center;"><?= round(($hadir_hari_ini ?? 0) / $total_absensi * 100) ?>%</td>
                        </tr>
                        <tr>
                            <td>
                                <span class="badge badge-warning">
                                    <i class="fas fa-envelope"></i> Izin
                                </span>
                            </td>
                            <td style="text-align:center;"><strong><?= esc($izin_hari_ini ?? 0) ?></strong></td>
                            <td style="text-align:center;"><?= round(($izin_hari_ini ?? 0) / $total_absensi * 100) ?>%</td>
                        </tr>
                        <tr>
                            <td>
                                <span class="badge badge-info">
                                    <i class="fas fa-heart"></i> Sakit
                                </span>
                            </td>
                            <td style="text-align:center;"><strong><?= esc($sakit_hari_ini ?? 0) ?></strong></td>
                            <td style="text-align:center;"><?= round(($sakit_hari_ini ?? 0) / $total_absensi * 100) ?>%</td>
                        </tr>
                        <tr>
                            <td>
                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle"></i> Alpa
                                </span>
                            </td>
                            <td style="text-align:center;"><strong><?= esc($alpa_hari_ini ?? 0) ?></strong></td>
                            <td style="text-align:center;"><?= round(($alpa_hari_ini ?? 0) / $total_absensi * 100) ?>%</td>
                        </tr>
                        <tr>
                            <td>
                                <span class="badge badge-purple">
                                    <i class="fas fa-clock"></i> Terlambat
                                </span>
                            </td>
                            <td style="text-align:center;"><strong><?= esc($terlambat_hari_ini ?? 0) ?></strong></td>
                            <td style="text-align:center;"><?= round(($terlambat_hari_ini ?? 0) / $total_absensi * 100) ?>%</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background:#F9FAFB;font-weight:700;">
                            <td>Total</td>
                            <td style="text-align:center;"><?= $total_absensi ?></td>
                            <td style="text-align:center;">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Grafik Donut -->
            <div style="position:relative;height:300px;display:flex;align-items:center;justify-content:center;">
                <canvas id="absensiChart"></canvas>
            </div>
            
        </div>
    </div>
</div>


<!-- Menu Cepat -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Menu Cepat</div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;">
            <a href="/admin/siswa" class="btn btn-outline" style="justify-content:center;flex-direction:column;padding:16px;">
                <i class="fas fa-user-graduate" style="font-size:24px;margin-bottom:6px;color:#2563EB;"></i>
                Siswa
            </a>
            <a href="/admin/guru" class="btn btn-outline" style="justify-content:center;flex-direction:column;padding:16px;">
                <i class="fas fa-chalkboard-teacher" style="font-size:24px;margin-bottom:6px;color:#10B981;"></i>
                Guru
            </a>
            <a href="/admin/absensi" class="btn btn-outline" style="justify-content:center;flex-direction:column;padding:16px;">
                <i class="fas fa-clipboard-list" style="font-size:24px;margin-bottom:6px;color:#F59E0B;"></i>
                Absensi
            </a>
            <a href="/admin/jadwal" class="btn btn-outline" style="justify-content:center;flex-direction:column;padding:16px;">
                <i class="fas fa-calendar-alt" style="font-size:24px;margin-bottom:6px;color:#8B5CF6;"></i>
                Jadwal
            </a>
            <a href="/admin/laporan" class="btn btn-outline" style="justify-content:center;flex-direction:column;padding:16px;">
                <i class="fas fa-file-pdf" style="font-size:24px;margin-bottom:6px;color:#EF4444;"></i>
                Laporan
            </a>
            <a href="/admin/users" class="btn btn-outline" style="justify-content:center;flex-direction:column;padding:16px;">
                <i class="fas fa-users" style="font-size:24px;margin-bottom:6px;color:#7C3AED;"></i>
                User
            </a>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('absensiChart');
    
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpa', 'Terlambat'],
                datasets: [{
                    data: [
                        <?= $hadir_hari_ini ?? 0 ?>,
                        <?= $izin_hari_ini ?? 0 ?>,
                        <?= $sakit_hari_ini ?? 0 ?>,
                        <?= $alpa_hari_ini ?? 0 ?>,
                        <?= $terlambat_hari_ini ?? 0 ?>
                    ],
                    backgroundColor: [
                        '#10B981', // Hadir
                        '#F59E0B', // Izin
                        '#3B82F6', // Sakit
                        '#EF4444', // Alpa
                        '#8B5CF6'  // Terlambat
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyleWidth: 12,
                            pointStyleHeight: 12,
                            font: {
                                size: 12,
                                family: "'Segoe UI', sans-serif"
                            },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                return data.labels.map((label, i) => ({
                                    text: `${label} (${data.datasets[0].data[i]})`,
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    strokeStyle: data.datasets[0].backgroundColor[i],
                                    pointStyle: 'circle',
                                    index: i
                                }));
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: {
                            size: 13
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.parsed;
                                const percent = total > 0 ? Math.round((value / total) * 100) : 0;
                                return ` ${context.label}: ${value} (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>