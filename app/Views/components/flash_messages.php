<?php 
// File: app/Views/components/flash_messages.php
?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible alert-flash fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?= esc((string) session()->getFlashdata('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible alert-flash fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <?= esc((string) session()->getFlashdata('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('warning')): ?>
<div class="alert alert-warning alert-dismissible alert-flash fade show" role="alert">
    <i class="bi bi-exclamation-circle-fill me-2"></i>
    <?= esc((string) session()->getFlashdata('warning')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php $errors = session()->getFlashdata('errors'); ?>
<?php if (!empty($errors) && is_array($errors)): ?>
<div class="alert alert-danger alert-dismissible alert-flash fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <strong>Error!</strong>
    <ul class="mb-0 mt-1">
        <?php foreach ($errors as $error): ?>
            <li><?= esc((string) $error) ?></li>
        <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>