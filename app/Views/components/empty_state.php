<?php 
$message = $message ?? 'Tidak ada data';
$icon = $icon ?? 'bi-inbox';
?>
<div class="text-center py-5">
    <i class="bi <?= $icon ?> fs-1 text-muted"></i>
    <p class="text-muted mt-2"><?= esc($message) ?></p>
    <?php if (!empty($actionUrl) && !empty($actionLabel)): ?>
    <a href="<?= $actionUrl ?>" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i><?= esc($actionLabel) ?>
    </a>
    <?php endif; ?>
</div>