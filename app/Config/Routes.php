<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('debug', 'Test::siswa');
// ==================== ROOT ====================
$routes->get('/', function() {
    return redirect()->to('/home');
});
// Debug route
$routes->get('siswa/absensi/getStatusHariIni', 'Siswa\Absensi::getStatusHariIni');
// ==================== AUTH ====================
// ==================== AUTH ====================
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::sendResetLink');
$routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:any)', 'Auth::updatePassword/$1');
$routes->get('auth/forgot-password', 'Auth::forgotPassword');
$routes->post('auth/send-reset-link', 'Auth::sendResetLink');
$routes->get('auth/reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('auth/update-password/(:any)', 'Auth::updatePassword/$1');
$routes->post('auth/update-password', 'Auth::updatePassword');
$routes->get('auth/logout', 'Auth::logout');

// ==================== ADMIN ====================
$routes->group('admin', function($routes) {

    // Dashboard
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');
$routes->get('setup', 'Admin\Setup::index');
// Penempatan Kelas
    $routes->get('siswa/penempatan-kelas', 'Admin\Siswa::penempatanKelas');
    $routes->post('siswa/simpan-penempatan-kelas', 'Admin\Siswa::simpanPenempatanKelas');
    $routes->get('guru/penempatan', 'Admin\Guru::penempatan');
    // ==================== USERS ====================
    $routes->get('users', 'Admin\UserManagement::index');
    $routes->get('users/create', 'Admin\UserManagement::create');
    $routes->post('users/store', 'Admin\UserManagement::store');
    $routes->get('users/edit/(:num)', 'Admin\UserManagement::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\UserManagement::update/$1');
    $routes->get('users/detail/(:num)', 'Admin\UserManagement::detail/$1');
    $routes->post('users/delete/(:num)', 'Admin\UserManagement::delete/$1');
    $routes->post('users/toggle-status/(:num)', 'Admin\UserManagement::toggleStatus/$1');
    $routes->get('users/delete/(:num)', 'Admin\UserManagement::delete/$1');
    $routes->post('users/toggle-status/(:num)', 'Admin\UserManagement ::toggleStatus/$1');
    $routes->post('users/store', 'Admin\UserManagement::store');

    // ==================== GURU ====================
    $routes->get('guru', 'Admin\Guru::index');
    $routes->get('guru/create', 'Admin\Guru::create');
    $routes->post('guru/store', 'Admin\Guru::store');
    $routes->get('guru/edit/(:num)', 'Admin\Guru::edit/$1');
    $routes->post('guru/update/(:num)', 'Admin\Guru::update/$1');
    $routes->get('guru/detail/(:num)', 'Admin\Guru::detail/$1');
    $routes->post('guru/delete/(:num)', 'Admin\Guru::delete/$1');

    // ==================== SISWA ====================
    $routes->get('siswa', 'Admin\Siswa::index');
    $routes->get('siswa/create', 'Admin\Siswa::create');
    $routes->post('siswa/store', 'Admin\Siswa::store');
    $routes->get('siswa/edit/(:num)', 'Admin\Siswa::edit/$1');
    $routes->post('siswa/update/(:num)', 'Admin\Siswa::update/$1');
    $routes->get('siswa/detail/(:num)', 'Admin\Siswa::detail/$1');
    $routes->post('siswa/delete/(:num)', 'Admin\Siswa::delete/$1');
    $routes->get('siswa/qrcode/(:num)', 'Admin\Siswa::qrcode/$1');
    $routes->get('siswa/export', 'Admin\Siswa::export');
    $routes->post('siswa/import', 'Admin\Siswa::import');

    // ==================== JURUSAN ====================
    $routes->get('jurusan', 'Admin\Jurusan::index');
    $routes->get('jurusan/create', 'Admin\Jurusan::create');
    $routes->post('jurusan/store', 'Admin\Jurusan::store');
    $routes->get('jurusan/edit/(:num)', 'Admin\Jurusan::edit/$1');
    $routes->post('jurusan/update/(:num)', 'Admin\Jurusan::update/$1');
    $routes->post('jurusan/delete/(:num)', 'Admin\Jurusan::delete/$1');
    $routes->get('jurusan/detail/(:num)', 'Admin\Jurusan::detail/$1');

    // ==================== KELAS ====================
    $routes->get('kelas', 'Admin\Kelas::index');
    $routes->get('kelas/create', 'Admin\Kelas::create');
    $routes->post('kelas/store', 'Admin\Kelas::store');
    $routes->get('kelas/edit/(:num)', 'Admin\Kelas::edit/$1');
    $routes->post('kelas/update/(:num)', 'Admin\Kelas::update/$1');
    $routes->get('kelas/detail/(:num)', 'Admin\Kelas::detail/$1');
    $routes->post('kelas/delete/(:num)', 'Admin\Kelas::delete/$1');
    $routes->get('kelas/siswa/(:num)', 'Admin\Kelas::siswa/$1');

    // ==================== MATA PELAJARAN ====================
    $routes->get('mapel', 'Admin\MataPelajaran::index');
    $routes->get('mapel/create', 'Admin\MataPelajaran::create');
    $routes->post('mapel/store', 'Admin\MataPelajaran::store');
    $routes->get('mapel/edit/(:num)', 'Admin\MataPelajaran::edit/$1');
    $routes->post('mapel/update/(:num)', 'Admin\MataPelajaran::update/$1');
    $routes->post('mapel/delete/(:num)', 'Admin\MataPelajaran::delete/$1');

    // ==================== SEMESTER ====================
    $routes->get('semester', 'Admin\Semester::index');
    $routes->get('semester/create', 'Admin\Semester::create');
    $routes->post('semester/store', 'Admin\Semester::store');
    $routes->get('semester/edit/(:num)', 'Admin\Semester::edit/$1');
    $routes->post('semester/update/(:num)', 'Admin\Semester::update/$1');
    $routes->post('semester/delete/(:num)', 'Admin\Semester::delete/$1');
    $routes->get('semester/aktifkan/(:num)', 'Admin\Semester::aktifkan/$1');

    // ==================== TAHUN AJARAN ====================
    $routes->get('tahun-ajaran', 'Admin\TahunAjaran::index');
    $routes->get('tahun-ajaran/create', 'Admin\TahunAjaran::create');
    $routes->post('tahun-ajaran/store', 'Admin\TahunAjaran::store');
    $routes->get('tahun-ajaran/detail/(:num)', 'Admin\TahunAjaran::detail/$1');
    $routes->get('tahun-ajaran/edit/(:num)', 'Admin\TahunAjaran::edit/$1');
    $routes->post('tahun-ajaran/update/(:num)', 'Admin\TahunAjaran::update/$1');
    $routes->post('tahun-ajaran/delete/(:num)', 'Admin\TahunAjaran::delete/$1');
    $routes->get('tahun-ajaran/aktifkan/(:num)', 'Admin\TahunAjaran::aktifkan/$1');

    // ==================== JADWAL ====================
    $routes->get('jadwal', 'Admin\Jadwal::index');
    $routes->get('jadwal/create', 'Admin\Jadwal::create');
    $routes->post('jadwal/store', 'Admin\Jadwal::store');
    $routes->get('jadwal/edit/(:num)', 'Admin\Jadwal::edit/$1');
    $routes->post('jadwal/update/(:num)', 'Admin\Jadwal::update/$1');
    $routes->post('jadwal/delete/(:num)', 'Admin\Jadwal::delete/$1');
    $routes->get('jadwal/guru/(:num)', 'Admin\Jadwal::byGuru/$1');
    $routes->get('jadwal/kelas/(:num)', 'Admin\Jadwal::byKelas/$1');

    // ==================== ABSENSI ====================
    $routes->get('absensi', 'Admin\Absensi::index');
    $routes->get('absensi/create', 'Admin\Absensi::create');
    $routes->post('absensi/store', 'Admin\Absensi::store');
    $routes->get('absensi/detail/(:num)', 'Admin\Absensi::detail/$1');
    $routes->post('absensi/delete/(:num)', 'Admin\Absensi::delete/$1');
    $routes->get('absensi/belum-absen', 'Admin\Absensi::belumAbsen');
    $routes->get('absensi/rekap', 'Admin\Absensi::rekap');
    $routes->get('absensi/export', 'Admin\Absensi::export');
    $routes->get('absensi/monitoring', 'Admin\Absensi::monitoring');
    $routes->get('absensi/rekapSesi/(:num)', 'Admin\Absensi::detailSesi/$1');


    // 
    


    


    // ==================== LAPORAN ====================
    $routes->get('laporan', 'Admin\Laporan::index');
    $routes->get('laporan/absensi', 'Admin\Laporan::absensi');
    $routes->get('laporan/siswa', 'Admin\Laporan::siswa');
    $routes->get('laporan/guru', 'Admin\Laporan::guru');
    $routes->get('laporan/export/(:any)', 'Admin\Laporan::export/$1');
    $routes->get('laporan/rekap-bulanan', 'Admin\Laporan::rekapBulanan');
    $routes->get('laporan/rekap-sesi', 'Admin\Laporan::rekapSesi');
    $routes->get('absensi/rekapSesi/(:num)', 'Admin\Laporan::rekapSesi/$1');
    $routes->get('laporan/rekap-sesi', 'Admin\Laporan::rekapSesi');
   // Laporan - Export PDF
$routes->get('laporan/exportPdf', 'Admin\Laporan::exportPdf');
// Laporan
$routes->get('laporan/rekap-harian', 'Admin\Laporan::rekapHarian');
$routes->get('laporan/rekapSesi', 'Admin\Laporan::rekapSesi');  

    

// Profil
$routes->get('profil', 'Admin\Dashboard::profil');
$routes->post('profil/uploadFoto', 'Admin\Dashboard::uploadFoto');
$routes->post('profil/update', 'Admin\Dashboard::updateProfil');
$routes->post('profil/uploadFoto', 'Admin\Dashboard::uploadFoto');
    // ==================== LOG AKTIVITAS ====================
    $routes->get('log-aktivitas', 'Admin\LogAktivitas::index');
    $routes->get('log-aktivitas/detail/(:num)', 'Admin\LogAktivitas::detail/$1');
    $routes->get('log-aktivitas/export', 'Admin\LogAktivitas::export');

    // ==================== PENGATURAN ====================
    $routes->get('pengaturan', 'Admin\Pengaturan::index');
    $routes->post('pengaturan/update', 'Admin\Pengaturan::update');
    $routes->post('pengaturan/backup', 'Admin\Pengaturan::backup');

// ==================== BACKUP ====================
$routes->get('backup', 'Admin\Backup::index');
$routes->get('backup/downloadFull', 'Admin\Backup::downloadFull');
$routes->get('backup/downloadDatabase', 'Admin\Backup::downloadDatabase');
$routes->get('backup/downloadSiswa', 'Admin\Backup::downloadSiswa');
$routes->get('backup/downloadAbsensi', 'Admin\Backup::downloadAbsensi');
$routes->get('backup/hapusBackup/(:any)', 'Admin\Backup::hapusBackup/$1');


});

// ==================== GURU ====================
$routes->group('guru', function($routes) {

    // Dashboard
    $routes->get('/', 'Guru\Dashboard::index');
    $routes->get('dashboard', 'Guru\Dashboard::index');
// Profil Guru
$routes->get('profil', 'Guru\Dashboard::profil');
    // QR Code
    $routes->get('qrcode', 'Guru\QrCode::index');
    $routes->post('qrcode/generate', 'Guru\QrCode::generate');
    $routes->get('qrcode/tampilkan/(:any)', 'Guru\QrCode::tampilkan/$1');
    $routes->get('qrcode/tutup/(:num)', 'Guru\QrCode::tutup/$1');
    $routes->get('qrcode/nonaktifkan/(:num)', 'Guru\QrCode::nonaktifkan/$1');
    $routes->get('qrcode/stop', 'Guru\QrCode::stop');

    // Absensi
    $routes->get('absensi', 'Guru\Absensi::index');
    $routes->get('absensi/create', 'Guru\Absensi::create');
    $routes->post('absensi/store', 'Guru\Absensi::store');
    $routes->post('absensi/store-batch', 'Guru\Absensi::storeBatch');
    $routes->get('absensi/edit/(:num)', 'Guru\Absensi::edit/$1');
    $routes->post('absensi/update/(:num)', 'Guru\Absensi::update/$1');
    $routes->get('absensi/delete/(:num)', 'Guru\Absensi::delete/$1');
    $routes->post('absensi/delete/(:num)', 'Guru\Absensi::delete/$1');
    $routes->get('absensi/riwayat', 'Guru\Absensi::riwayat');
    $routes->get('absensi/riwayat/(:num)', 'Guru\Absensi::riwayat/$1');
    $routes->get('absensi/rekap', 'Guru\Absensi::rekap');
    $routes->get('absensi/kelas', 'Guru\Absensi::rekap');
    $routes->get('absensi/statistik', 'Guru\Absensi::statistik');
    $routes->post('absensi/scan', 'Guru\Absensi::scanQR');;
    $routes->post('absensi/storeBatch', 'Guru\Absensi::storeBatch');
    


    // Scan QR Siswa
    $routes->get('scan', 'Guru\Scan::index');
    $routes->post('scan/proses', 'Guru\Scan::proses');

    // Siswa
    $routes->get('siswa', 'Guru\Siswa::index');
    $routes->get('siswa/detail/(:num)', 'Guru\Siswa::detail/$1');
});
// ==================== SISWA ====================
$routes->group('siswa', function($routes) {
$routes->get('rekap', 'Siswa\Dashboard::rekap');
    // Dashboard
    $routes->get('/', 'Siswa\Dashboard::index');
     $routes->get('dashboard', 'Siswa\Dashboard::index');
// Profil Siswa
$routes->get('profil', 'Siswa\Dashboard::profil');
    // Absensi Saya
    $routes->get('absensi', 'Siswa\Absensi::index');
    $routes->get('absensi/detail/(:num)', 'Siswa\Absensi::detail/$1');

    // QR Code Saya
    $routes->get('qrcode', 'Siswa\QRCode::index');
    $routes->get('scan', 'Siswa\Scan::index');
    $routes->get('scan/(:any)', 'Siswa\Scan::index/$1');
    
    // API Endpoints
    $routes->post('absensi/verifikasi', 'Siswa\Absensi::verifikasi');
    $routes->post('absensi/konfirmasi', 'Siswa\Absensi::konfirmasi');
    $routes->get('absensi/getStatusHariIni', 'Siswa\Absensi::getStatusHariIni');
   $routes->get('absensi/scan', 'Siswa\Scan::index');
    $routes->get('absen', 'Siswa\Scan::index');  // ✅ biar /siswa/absen juga bisa

   $routes->get('absensi/reset', 'Siswa\Absensi::resetStatus');
    // Profil
    $routes->get('profil', 'Siswa\Profil::index');
    $routes->post('profil/update', 'Siswa\Profil::update');
});

// ==================== KEPALA SEKOLAH ====================
$routes->group('kepsek', function($routes) {

    // Dashboard
    $routes->get('/', 'Kepsek\Dashboard::index');

    // Monitoring
    $routes->get('monitoring', 'Kepsek\Monitoring::index');
    $routes->get('monitoring/kelas/(:num)', 'Kepsek\Monitoring::kelas/$1');

    // Laporan
    $routes->get('laporan', 'Kepsek\Laporan::index');
    $routes->get('laporan/absensi', 'Kepsek\Laporan::absensi');
    $routes->get('laporan/export/(:any)', 'Kepsek\Laporan::export/$1');

    // Guru
    $routes->get('guru', 'Kepsek\Guru::index');
    $routes->get('guru/detail/(:num)', 'Kepsek\Guru::detail/$1');
});

$routes->get('favicon-16.png', 'FaviconController::generate/16');
$routes->get('favicon-32.png', 'FaviconController::generate/32');
$routes->get('favicon-180.png', 'FaviconController::generate/180');


$routes->get('notifikasi/kirimSemuaAlpa', 'NotifikasiWhatsApp::kirimSemuaAlpa');
$routes->get('izin/(:any)', 'Izin::form/$1');
$routes->post('izin/kirim', 'Izin::kirim');




// Keamanan
$routes->get('admin/keamanan', 'Admin\Keamanan::index');
$routes->get('admin/keamanan/blokirIP/(:any)', 'Admin\Keamanan::blokirIP/$1');
$routes->get('admin/keamanan/bukaBlokir/(:num)', 'Admin\Keamanan::bukaBlokir/$1');
$routes->get('admin/keamanan/blokirSemua', 'Admin\Keamanan::blokirSemua');

// Blocked page
$routes->get('blocked', function() {
    return view('errors/blocked');
});









// $routes->set404Override(function() {
//     $data = [
//         'title' => 'Page Not Found',
//         'message' => 'The requested page could not be found.'
//     ];
//     return view('errors/html/error_404', $data, ['http_code' => 404]);
// });



