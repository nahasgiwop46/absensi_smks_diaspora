<?php

/**
 * Format tanggal ke format Indonesia
 * Contoh: 
 *   formatTanggal('2026-05-17', 'd F Y')      -> "17 Mei 2026"
 *   formatTanggal('2026-05-17', 'l, d F Y')   -> "Minggu, 17 Mei 2026"
 *   formatTanggal('2026-05-17 14:30', 'H:i')  -> "14:30"
 */
function formatTanggal($tanggal, $format = 'd F Y')
{
    $bulan = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    $hari = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    ];

    $timestamp = is_numeric($tanggal) ? $tanggal : strtotime($tanggal);
    
    if ($timestamp === false) {
        return $tanggal; // Return asli jika gagal parse
    }
    
    $d = date('d', $timestamp);
    $m = date('n', $timestamp);
    $Y = date('Y', $timestamp);
    $l = $hari[date('l', $timestamp)] ?? date('l', $timestamp);
    $H = date('H', $timestamp);
    $i = date('i', $timestamp);
    $s = date('s', $timestamp);
    
    // Ganti format
    $result = str_replace(
        ['l', 'd', 'F', 'Y', 'H', 'i', 's'],
        [$l, $d, $bulan[(int)$m] ?? '', $Y, $H, $i, $s],
        $format
    );
    
    return $result;
}

/**
 * Format tanggal lengkap dengan hari
 */
function tanggalLengkap($tanggal = null): string
{
    $tanggal = $tanggal ?? date('Y-m-d');
    return formatTanggal($tanggal, 'l, d F Y');
}

/**
 * Format tanggal pendek
 */
function tanggalPendek($tanggal = null): string
{
    $tanggal = $tanggal ?? date('Y-m-d');
    return formatTanggal($tanggal, 'd/m/Y');
}

/**
 * Format datetime lengkap
 */
function tanggalWaktu($tanggal = null): string
{
    $tanggal = $tanggal ?? date('Y-m-d H:i:s');
    return formatTanggal($tanggal, 'd F Y, H:i');
}

