<?php
// app/Helpers/absensi_helper.php

/**
 * Format menit keterlambatan ke string
 */
function formatKeterlambatan(int $menit): string
{
    if ($menit <= 0) {
        return 'Tepat Waktu';
    }
    
    if ($menit < 60) {
        return $menit . ' Menit';
    }
    
    $jam = floor($menit / 60);
    $sisaMenit = $menit % 60;
    
    if ($sisaMenit > 0) {
        return $jam . ' Jam ' . $sisaMenit . ' Menit';
    }
    
    return $jam . ' Jam';
}

/**
 * Format status absensi ke badge HTML
 */
function badgeStatus(string $status, string $warna = '#28a745'): string
{
    $icons = [
        'Hadir'     => '✅',
        'Izin'      => '📝',
        'Sakit'     => '🏥',
        'Alpa'      => '❌',
        'Terlambat' => '⏰',
    ];
    
    $icon = $icons[$status] ?? '';
    
    return "<span class='badge' style='background-color: {$warna};'>{$icon} {$status}</span>";
}

/**
 * Hitung persentase kehadiran
 */
function persentaseKehadiran(int $hadir, int $total): float
{
    if ($total <= 0) return 0;
    return round(($hadir / $total) * 100, 1);
}

/**
 * Get warna berdasarkan persentase
 */
function warnaPersentase(float $persen): string
{
    if ($persen >= 90) return '#28a745'; // Hijau
    if ($persen >= 75) return '#ffc107'; // Kuning
    return '#dc3545'; // Merah
}