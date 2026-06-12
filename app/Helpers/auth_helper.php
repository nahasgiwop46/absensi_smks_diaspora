<?php
// app/Helpers/auth_helper.php

/**
 * Cek apakah user sudah login
 */
function is_logged_in(): bool
{
    return session()->get('is_logged_in') === true;
}

/**
 * Cek apakah user memiliki role tertentu
 */
function has_role(string $role): bool
{
    return strtoupper(session()->get('role_kode') ?? '') === strtoupper($role);
}

/**
 * Cek apakah user termasuk dalam group roles
 */
function in_roles(array $roles): bool
{
    return in_array(strtoupper(session()->get('role_kode') ?? ''), array_map('strtoupper', $roles));
}

/**
 * Alias untuk method di atas (bisa multiple params)
 * Contoh: in_groups('admin', 'guru')
 */
function in_groups(string ...$roles): bool
{
    return in_roles($roles);
}

/**
 * Get current user data
 */
function current_user(?string $field = null)
{
    if (!is_logged_in()) {
        return null;
    }
    
    if ($field) {
        return session()->get($field);
    }
    
    return session()->get();
}

/**
 * Get user display name
 */
function user_display_name(): string
{
    return session()->get('nama_lengkap') ?? 'Guest';
}

/**
 * Get user role badge HTML
 */
function role_badge(): string
{
    $role = session()->get('role_nama') ?? 'Guest';
    
    $colors = [
        'Administrator'  => 'danger',
        'Guru'           => 'primary',
        'Siswa'          => 'success',
        'Kepala Sekolah' => 'warning',
    ];
    
    $color = $colors[$role] ?? 'secondary';
    
    return "<span class='badge bg-{$color}'>{$role}</span>";
}

/**
 * Check permission for specific action
 */
function can(string $permission): bool
{
    $rolePermissions = [
        'ADMIN'  => ['manage_users', 'manage_roles', 'manage_all', 'view_reports', 'delete_data'],
        'GURU'   => ['manage_absensi', 'create_qr', 'view_siswa', 'edit_absensi'],
        'SISWA'  => ['scan_qr', 'view_own_absensi'],
        'KEPSEK' => ['view_reports', 'view_all_data'],
    ];
    
    $userRole = strtoupper(session()->get('role_kode') ?? '');
    
    return in_array($permission, $rolePermissions[$userRole] ?? []);
    
}
/**
 * Tampilkan badge status aktif/nonaktif
 */
function badgeAktif($isActive): string
{
    if ($isActive == 1 || $isActive === true || $isActive === '1') {
        return '<span class="badge badge-success">Aktif</span>';
    }
    return '<span class="badge badge-danger">Nonaktif</span>';
}

/**
 * Get role kode lowercase
 */
function role_kode(): string
{
    return strtolower(session()->get('role_kode') ?? '');
}

/**
 * Get user ID
 */
function user_id(): ?int
{
    return session()->get('user_id') ? (int) session()->get('user_id') : null;
}

/**
 * Cek apakah user adalah admin
 */
function is_admin(): bool
{
    return has_role('ADMIN');
}

/**
 * Cek apakah user adalah guru
 */
function is_guru(): bool
{
    return has_role('GURU');
}

/**
 * Cek apakah user adalah siswa
 */
function is_siswa(): bool
{
    return has_role('SISWA');
}

/**
 * Format jumlah ke format ribuan
 */
function formatRibuan($angka): string
{
    return number_format($angka, 0, ',', '.');
}

/**
 * Generate warna random (untuk chart/graph)
 */
function randomColor(): string
{
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}