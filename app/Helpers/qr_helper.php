<?php
// app/Helpers/qr_helper.php

/**
 * Generate QR Code URL
 */
function qrCodeUrl(string $token, int $size = 300): string
{
    return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($token);
}

/**
 * Format sisa waktu sesi absen
 */
function sisaWaktu(string $expiredAt): string
{
    $now = time();
    $expired = strtotime($expiredAt);
    $selisih = $expired - $now;
    
    if ($selisih <= 0) {
        return 'Expired';
    }
    
    $menit = floor($selisih / 60);
    $detik = $selisih % 60;
    
    if ($menit > 0) {
        return $menit . ' Menit ' . $detik . ' Detik';
    }
    
    return $detik . ' Detik';
}

/**
 * Format durasi absen
 */
function formatDurasi(int $menit): string
{
    if ($menit < 60) {
        return $menit . ' Menit';
    }
    
    $jam = floor($menit / 60);
    $sisa = $menit % 60;
    
    return $jam . ' Jam ' . ($sisa > 0 ? $sisa . ' Menit' : '');
}

/**
 * Generate kode cadangan untuk absen manual
 */
function generateKodeCadangan(int $panjang = 6): string
{
    return strtoupper(substr(bin2hex(random_bytes($panjang)), 0, $panjang));
}