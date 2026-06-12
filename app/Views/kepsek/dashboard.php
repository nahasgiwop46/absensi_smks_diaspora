<?= $this->extend('layouts/kepsek') ?>
<?= $this->section('breadcrumb') ?><li class="breadcrumb-item active">Dashboard</li><?= $this->endSection() ?>

<?= $this->section('content') ?>
<h4 class="fw-bold mb-3">Dashboard Kepala Sekolah</h4>

<div class="row g-2 g-sm-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-purple"><i class="bi bi-people-fill stat-icon-bg"></i><div class="stat-label">Total Siswa</div><div class="stat-value"><?= $total_siswa ?></div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-green"><i class="bi bi-check-circle-fill stat-icon-bg"></i><div class="stat-label">Kehadiran</div><div class="stat-value"><?= $persentase ?>%</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-orange"><i class="bi bi-graph-up stat-icon-bg"></i><div class="stat-label">Rata-rata</div><div class="stat-value"><?= $rata_rata ?>%</div></div>
    </div>
    <div class="col-6 col-md-3">
        <a href="<?= base_url('kepsek/bermasalah') ?>" class="text-decoration-none">
            <div class="stat-card stat-red"><i class="bi bi-exclamation-triangle-fill stat-icon-bg"></i><div class="stat-label">Bermasalah</div><div class="stat-value"><?= $bermasalah ?></div></div>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">Kehadiran Per Kelas Hari Ini</div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead><tr><th>Kelas</th><th>Total</th><th>Hadir</th><th>%</th></tr></thead>
            <tbody>
                <?php foreach ($per_kelas as $pk): ?>
                <tr class="<?= $pk['persen'] < 85 ? 'table-danger' : '' ?>">
                    <td><?= esc($pk['nama']) ?></td>
                    <td><?= $pk['total'] ?></td>
                    <td><?= $pk['hadir'] ?></td>
                    <td><strong><?= $pk['persen'] ?>%</strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>