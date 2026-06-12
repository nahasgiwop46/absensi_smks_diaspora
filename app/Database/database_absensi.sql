-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2026 at 02:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi_smks_006`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `jadwal_id` int(11) DEFAULT NULL,
  `qr_session_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam_absen` datetime NOT NULL,
  `tipe_absensi` enum('hadir','izin','sakit','alpa') DEFAULT 'hadir',
  `status_id` int(11) NOT NULL DEFAULT 1,
  `metode_absensi` enum('manual_guru','scan_qr_guru','scan_qr_siswa','fingerprint','online') DEFAULT 'scan_qr_guru',
  `menit_keterlambatan` smallint(5) UNSIGNED DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `koordinat` point DEFAULT NULL,
  `koordinat_siswa` point DEFAULT NULL COMMENT 'Lokasi siswa saat absen',
  `device_info` varchar(255) DEFAULT NULL,
  `device_siswa` varchar(100) DEFAULT NULL COMMENT 'Tipe HP/model siswa',
  `user_agent_siswa` varchar(255) DEFAULT NULL COMMENT 'Browser/OS siswa',
  `foto_absensi` varchar(255) DEFAULT NULL,
  `foto_verifikasi` varchar(255) DEFAULT NULL COMMENT 'Selfie verifikasi (opsional)',
  `petugas_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `siswa_id`, `jadwal_id`, `qr_session_id`, `tanggal`, `jam_absen`, `tipe_absensi`, `status_id`, `metode_absensi`, `menit_keterlambatan`, `keterangan`, `koordinat`, `koordinat_siswa`, `device_info`, `device_siswa`, `user_agent_siswa`, `foto_absensi`, `foto_verifikasi`, `petugas_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(17, 1, NULL, 63, '2026-06-02', '2026-06-02 22:48:39', 'hadir', 1, 'scan_qr_guru', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, '2026-06-02 13:48:39', '2026-06-02 13:48:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'logout', 'Logout', '::1', '2026-06-08 03:07:06'),
(2, 1, 'login', 'Login berhasil', '::1', '2026-06-10 02:39:56'),
(3, 1, 'logout', 'Logout', '::1', '2026-06-10 02:40:23'),
(4, 1, 'login', 'Login berhasil', '::1', '2026-06-10 04:27:51'),
(5, 1, 'login', 'Login berhasil', '::1', '2026-06-10 05:09:52'),
(6, 1, 'logout', 'Logout', '::1', '2026-06-10 05:11:26'),
(7, 9, 'login', 'Login berhasil', '::1', '2026-06-10 05:11:40'),
(8, 9, 'logout', 'Logout', '::1', '2026-06-10 05:12:24'),
(9, 1, 'login', 'Login berhasil', '::1', '2026-06-10 05:12:41'),
(10, 9, 'login', 'Login berhasil', '::1', '2026-06-10 05:58:35'),
(11, 9, 'logout', 'Logout', '::1', '2026-06-10 05:58:49'),
(12, 1, 'login', 'Login berhasil', '::1', '2026-06-10 06:15:45'),
(13, NULL, 'logout', 'Logout', '::1', '2026-06-10 06:17:35'),
(14, 9, 'login', 'Login berhasil', '::1', '2026-06-10 06:19:23'),
(15, 9, 'logout', 'Logout', '::1', '2026-06-10 06:19:38'),
(16, 1, 'login', 'Login berhasil', '::1', '2026-06-10 06:34:44'),
(17, 1, 'backup', 'Download backup database', '::1', '2026-06-10 06:37:03'),
(18, 1, 'backup', 'Download data absensi: 2026-06-10', '::1', '2026-06-10 06:37:23'),
(19, 1, 'backup', 'Download data absensi: 2026-06-10', '::1', '2026-06-10 06:39:12'),
(20, 1, 'backup', 'Download data siswa Excel', '::1', '2026-06-10 06:55:15');

-- --------------------------------------------------------

--
-- Table structure for table `blocked_ips`
--

CREATE TABLE `blocked_ips` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `blocked_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `hari` enum('senin','selasa','rabu','kamis','jumat','sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `kelas_id`, `semester_id`, `hari`, `jam_mulai`, `jam_selesai`, `mata_pelajaran_id`, `guru_id`, `created_at`) VALUES
(3, 2, 3, 'selasa', '09:00:00', '10:00:00', 1, 2, '2026-05-17 01:01:33'),
(4, 1, 3, 'senin', '19:53:00', '20:53:00', 1, 9, '2026-05-18 00:36:49'),
(5, 1, 3, 'kamis', '07:00:00', '10:16:00', 1, 9, '2026-05-18 15:00:54'),
(8, 6, 3, 'kamis', '08:00:00', '10:00:00', 14, 11, '2026-05-29 09:14:13'),
(9, 6, 3, 'jumat', '13:00:00', '15:00:00', 18, 11, '2026-05-29 09:14:13'),
(10, 8, 3, 'senin', '10:00:00', '12:00:00', 15, 12, '2026-05-29 09:14:16');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL,
  `kode` varchar(10) NOT NULL,
  `singkatan` varchar(10) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id`, `kode`, `singkatan`, `nama`, `deskripsi`, `is_active`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'TKJ', 'TKJ', 'Teknik Komputer dan Jaringan', NULL, 1, '2026-05-16 09:54:34', NULL, NULL),
(2, 'RPL', 'RPL', 'Rekayasa Perangkat Lunak', NULL, 1, '2026-05-16 09:54:34', NULL, NULL),
(3, 'MM', 'MM', 'Multimedia', NULL, 1, '2026-05-16 09:54:34', NULL, NULL),
(4, 'AKL_1', 'AKL', 'Akuntansi dan Keuangan Lembaga', 'nama', 1, '2026-05-16 09:54:34', NULL, '2026-05-17 23:09:21'),
(5, 'MT02', 'LGK', 'Teknik Logistik', 'Belajar', 1, '2026-05-16 21:05:55', NULL, '2026-05-18 16:48:29'),
(6, 'PW04', 'MT3', 'Seni Budaya', 'Semangat belajar', 1, '2026-05-17 17:49:45', NULL, '2026-05-17 17:49:45'),
(8, 'OTKP', 'OTKP', 'Otomatisasi dan Tata Kelola Perkantoran', 'Jurusan administrasi perkantoran modern', 1, '2026-05-29 09:12:28', NULL, NULL),
(9, 'BDP', 'BDP', 'Bisnis Daring dan Pemasaran', 'Jurusan pemasaran dan bisnis online', 1, '2026-05-29 09:12:28', NULL, NULL),
(10, 'AKL_2', 'AKL', 'Akuntansi dan Keuangan Lembaga', 'Jurusan akuntansi profesional', 1, '2026-05-29 09:12:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `tingkat` varchar(5) NOT NULL,
  `jurusan_id` int(11) DEFAULT NULL,
  `rombel` varchar(10) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT 30,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `tingkat`, `jurusan_id`, `rombel`, `kapasitas`, `is_active`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'XI', 3, 'A', 30, 1, '2026-05-16 07:26:35', NULL, '2026-05-17 23:08:27'),
(2, 'XI', 1, 'A', 30, 1, '2026-05-16 07:26:59', NULL, '2026-05-16 07:26:59'),
(3, 'XII', 2, 'B', 30, 1, '2026-05-16 21:04:07', NULL, '2026-05-17 23:08:52'),
(6, 'X', 1, 'A', 36, 1, '2026-05-29 09:12:30', NULL, NULL),
(7, 'X', 1, 'B', 36, 1, '2026-05-29 09:12:30', NULL, NULL),
(8, 'X', 2, 'A', 36, 1, '2026-05-29 09:12:30', NULL, NULL),
(9, 'X', 3, 'A', 36, 1, '2026-05-29 09:12:30', NULL, NULL),
(10, 'XI', 1, 'B', 30, 1, '2026-05-29 09:12:30', NULL, NULL),
(11, 'XI', 2, 'A', 30, 1, '2026-05-29 09:12:30', NULL, NULL),
(12, 'XII', 1, 'A', 30, 1, '2026-05-29 09:12:30', NULL, NULL),
(13, 'XII', 3, 'A', 30, 1, '2026-05-29 09:12:30', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelas_siswa`
--

CREATE TABLE `kelas_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `wali_kelas_id` int(11) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `status` enum('aktif','pindah','lulus','dikeluarkan','mengundurkan_diri') DEFAULT 'aktif',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas_siswa`
--

INSERT INTO `kelas_siswa` (`id`, `siswa_id`, `kelas_id`, `tahun_ajaran_id`, `semester_id`, `wali_kelas_id`, `tanggal_masuk`, `tanggal_keluar`, `status`, `keterangan`, `created_at`) VALUES
(26, 1, 1, 1, NULL, NULL, '2026-05-29', NULL, 'aktif', NULL, '2026-05-19 13:51:15'),
(31, 1, 3, 1, NULL, NULL, '2026-05-20', NULL, '', NULL, '2026-05-20 13:38:42'),
(34, 5, 2, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-20 14:10:11'),
(35, 6, 1, 1, NULL, NULL, '2026-05-29', NULL, 'aktif', NULL, '2026-05-28 22:57:22'),
(36, 7, 1, 1, NULL, NULL, '2026-05-29', NULL, 'aktif', NULL, '2026-05-28 22:57:22'),
(37, 8, 1, 1, NULL, NULL, '2026-05-29', NULL, 'aktif', NULL, '2026-05-28 22:57:22'),
(38, 9, 1, 1, NULL, NULL, '2026-05-29', NULL, 'aktif', NULL, '2026-05-28 22:57:22'),
(39, 10, 1, 1, NULL, NULL, '2026-05-29', NULL, 'aktif', NULL, '2026-05-28 22:57:22'),
(40, 11, 1, 1, NULL, NULL, '2026-05-29', NULL, 'aktif', NULL, '2026-05-28 22:57:22'),
(41, 12, 1, 1, NULL, NULL, '2026-05-29', NULL, 'aktif', NULL, '2026-05-28 22:57:22'),
(51, 8, 6, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-29 09:14:06'),
(52, 9, 6, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-29 09:14:06'),
(53, 10, 6, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-29 09:14:06'),
(54, 11, 7, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-29 09:14:08'),
(55, 12, 7, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-29 09:14:08'),
(56, 13, 7, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-29 09:14:08'),
(57, 14, 8, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-29 09:14:10'),
(58, 15, 8, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-29 09:14:10'),
(59, 16, 8, 1, NULL, NULL, '2026-05-20', NULL, 'aktif', NULL, '2026-05-29 09:14:10');

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('success','failed','blocked') DEFAULT 'failed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `username`, `ip_address`, `user_agent`, `status`, `created_at`) VALUES
(1, 'siswa1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'failed', '2026-06-08 03:07:22'),
(2, 'siswa1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'failed', '2026-06-08 03:07:30'),
(3, 'siswa1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'failed', '2026-06-08 03:07:36'),
(4, 'siswa1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'failed', '2026-06-08 03:07:40'),
(5, 'siswa1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'failed', '2026-06-08 03:07:45'),
(6, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'success', '2026-06-10 02:39:56'),
(7, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'success', '2026-06-10 04:27:51'),
(8, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'success', '2026-06-10 05:09:52'),
(9, 'guru1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'success', '2026-06-10 05:11:40'),
(10, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'success', '2026-06-10 05:12:41'),
(11, 'guru1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'success', '2026-06-10 05:58:35'),
(12, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-06-10 06:15:34'),
(13, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-06-10 06:15:45'),
(14, 'guru1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'success', '2026-06-10 06:19:23'),
(15, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'success', '2026-06-10 06:34:44');

-- --------------------------------------------------------

--
-- Table structure for table `log_absensi`
--

CREATE TABLE `log_absensi` (
  `id` int(11) NOT NULL,
  `absensi_id` int(11) NOT NULL,
  `aksi` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `field` varchar(50) DEFAULT NULL,
  `nilai_lama` text DEFAULT NULL,
  `nilai_baru` text DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_scan_qr`
--

CREATE TABLE `log_scan_qr` (
  `id` int(11) NOT NULL,
  `qr_session_id` int(11) NOT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `status_scan` enum('berhasil','gagal','expired','duplikat','di_luar_area') NOT NULL,
  `pesan_error` varchar(255) DEFAULT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `koordinat` point DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_role`
--

CREATE TABLE `master_role` (
  `id` int(11) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_role`
--

INSERT INTO `master_role` (`id`, `kode`, `nama`, `deskripsi`, `created_at`) VALUES
(1, 'ADMIN', 'Administrator', 'Pengelola sistem', '2026-05-16 09:54:34'),
(2, 'GURU', 'Guru', 'Tenaga pengajar', '2026-05-16 09:54:34'),
(3, 'SISWA', 'Siswa', 'Peserta didik', '2026-05-16 09:54:34'),
(4, 'KEPSEK', 'Kepala Sekolah', 'Pimpinan sekolah', '2026-05-16 09:54:34');

-- --------------------------------------------------------

--
-- Table structure for table `master_status_absensi`
--

CREATE TABLE `master_status_absensi` (
  `id` int(11) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `label` varchar(50) NOT NULL,
  `warna` varchar(7) DEFAULT NULL,
  `is_hadir` tinyint(1) DEFAULT 1,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_status_absensi`
--

INSERT INTO `master_status_absensi` (`id`, `kode`, `label`, `warna`, `is_hadir`, `deskripsi`) VALUES
(1, 'HADIR', 'Hadir', '#28a745', 1, 'Siswa hadir tepat waktu'),
(2, 'TERLAMBAT', 'Terlambat', '#ffc107', 1, 'Siswa hadir terlambat'),
(3, 'IZIN', 'Izin', '#17a2b8', 0, 'Siswa tidak hadir karena izin'),
(4, 'SAKIT', 'Sakit', '#6f42c1', 0, 'Siswa tidak hadir karena sakit'),
(5, 'ALPA', 'Alpa', '#dc3545', 0, 'Siswa tidak hadir tanpa keterangan');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id` int(11) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id`, `kode`, `nama`, `created_at`) VALUES
(1, 'PW02', 'Matematika', '2026-05-16 07:41:25'),
(2, 'MT02', 'Bahasa Inggris', '2026-05-16 21:07:20'),
(3, 'BIN', 'Bahasa Indonesia', '2026-05-29 09:12:31'),
(4, 'BIG', 'Bahasa Inggris', '2026-05-29 09:12:31'),
(5, 'MTK', 'Matematika', '2026-05-29 09:12:31'),
(6, 'PAI', 'Pendidikan Agama Islam', '2026-05-29 09:12:31'),
(7, 'PKN', 'Pendidikan Kewarganegaraan', '2026-05-29 09:12:31'),
(8, 'PJOK', 'Pendidikan Jasmani Olahraga dan Kesehatan', '2026-05-29 09:12:31'),
(9, 'SENI', 'Seni Budaya', '2026-05-29 09:12:31'),
(10, 'KWU', 'Kewirausahaan', '2026-05-29 09:12:31'),
(11, 'FIS', 'Fisika', '2026-05-29 09:12:31'),
(12, 'KIM', 'Kimia', '2026-05-29 09:12:31'),
(13, 'ASJ', 'Administrasi Sistem Jaringan', '2026-05-29 09:12:31'),
(14, 'PBO', 'Pemrograman Berorientasi Objek', '2026-05-29 09:12:31'),
(15, 'DDG', 'Desain Grafis', '2026-05-29 09:12:31'),
(16, 'ANIM', 'Animasi 2D/3D', '2026-05-29 09:12:31'),
(17, 'BD', 'Basis Data', '2026-05-29 09:12:31'),
(18, 'PWPB', 'Pemrograman Web dan Perangkat Bergerak', '2026-05-29 09:12:31');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(18, '2026-05-16-100001', 'App\\Database\\Migrations\\CreateMasterRole', 'default', 'App', 1778925274, 1),
(19, '2026-05-16-100002', 'App\\Database\\Migrations\\CreateUsers', 'default', 'App', 1778925274, 1),
(20, '2026-05-16-100003', 'App\\Database\\Migrations\\CreateJurusan', 'default', 'App', 1778925274, 1),
(21, '2026-05-16-100004', 'App\\Database\\Migrations\\CreateKelas', 'default', 'App', 1778925274, 1),
(22, '2026-05-16-100005', 'App\\Database\\Migrations\\CreateTahunAjaran', 'default', 'App', 1778925274, 1),
(23, '2026-05-16-100006', 'App\\Database\\Migrations\\CreateSemester', 'default', 'App', 1778925274, 1),
(24, '2026-05-16-100007', 'App\\Database\\Migrations\\CreateSiswa', 'default', 'App', 1778925274, 1),
(25, '2026-05-16-100008', 'App\\Database\\Migrations\\CreateKelasSiswa', 'default', 'App', 1778925274, 1),
(26, '2026-05-16-100009', 'App\\Database\\Migrations\\CreateMataPelajaran', 'default', 'App', 1778925274, 1),
(27, '2026-05-16-100010', 'App\\Database\\Migrations\\CreateJadwal', 'default', 'App', 1778925274, 1),
(28, '2026-05-16-100011', 'App\\Database\\Migrations\\CreateMasterStatusAbsensi', 'default', 'App', 1778925274, 1),
(29, '2026-05-16-100012', 'App\\Database\\Migrations\\CreateQrSessions', 'default', 'App', 1778925274, 1),
(30, '2026-05-16-100013', 'App\\Database\\Migrations\\CreateAbsensi', 'default', 'App', 1778925274, 1),
(31, '2026-05-16-100014', 'App\\Database\\Migrations\\CreateLogAbsensi', 'default', 'App', 1778925274, 1),
(32, '2026-05-16-100015', 'App\\Database\\Migrations\\CreateNotifikasiAbsensi', 'default', 'App', 1778925274, 1),
(33, '2026-05-16-100016', 'App\\Database\\Migrations\\CreateRekapAbsensiHarian', 'default', 'App', 1778925274, 1),
(34, '2026-05-16-100017', 'App\\Database\\Migrations\\SeedDefaultData', 'default', 'App', 1778925274, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi_absensi`
--

CREATE TABLE `notifikasi_absensi` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `absensi_id` int(11) NOT NULL,
  `tipe` enum('whatsapp','sms','email') DEFAULT 'whatsapp',
  `nomor_tujuan` varchar(20) DEFAULT NULL,
  `status_kirim` enum('pending','terkirim','gagal') DEFAULT 'pending',
  `pesan` text DEFAULT NULL,
  `dikirim_oleh` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi_siswa`
--

CREATE TABLE `notifikasi_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `qr_session_id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('absen_dibuka','absen_ditutup','info','warning','reminder') DEFAULT 'absen_dibuka',
  `is_dibaca` tinyint(1) DEFAULT 0,
  `is_clicked` tinyint(1) DEFAULT 0 COMMENT 'Apakah siswa sudah klik untuk absen',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifikasi_siswa`
--

INSERT INTO `notifikasi_siswa` (`id`, `siswa_id`, `qr_session_id`, `judul`, `pesan`, `tipe`, `is_dibaca`, `is_clicked`, `created_at`, `read_at`) VALUES
(2, 6, 22, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:08!', 'absen_dibuka', 0, 0, '2026-05-29 02:53:53', NULL),
(3, 7, 22, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:08!', 'absen_dibuka', 0, 0, '2026-05-29 02:53:53', NULL),
(4, 8, 22, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:08!', 'absen_dibuka', 0, 0, '2026-05-29 02:53:53', NULL),
(5, 9, 22, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:08!', 'absen_dibuka', 0, 0, '2026-05-29 02:53:53', NULL),
(6, 10, 22, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:08!', 'absen_dibuka', 0, 0, '2026-05-29 02:53:53', NULL),
(7, 11, 22, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:08!', 'absen_dibuka', 0, 0, '2026-05-29 02:53:53', NULL),
(8, 12, 22, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:08!', 'absen_dibuka', 0, 0, '2026-05-29 02:53:53', NULL),
(10, 6, 23, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:14!', 'absen_dibuka', 0, 0, '2026-05-29 02:59:33', NULL),
(11, 7, 23, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:14!', 'absen_dibuka', 0, 0, '2026-05-29 02:59:33', NULL),
(12, 8, 23, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:14!', 'absen_dibuka', 0, 0, '2026-05-29 02:59:33', NULL),
(13, 9, 23, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:14!', 'absen_dibuka', 0, 0, '2026-05-29 02:59:33', NULL),
(14, 10, 23, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:14!', 'absen_dibuka', 0, 0, '2026-05-29 02:59:33', NULL),
(15, 11, 23, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:14!', 'absen_dibuka', 0, 0, '2026-05-29 02:59:33', NULL),
(16, 12, 23, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:14!', 'absen_dibuka', 0, 0, '2026-05-29 02:59:33', NULL),
(18, 6, 23, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 03:07:52', NULL),
(19, 7, 23, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 03:07:52', NULL),
(20, 8, 23, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 03:07:52', NULL),
(21, 9, 23, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 03:07:52', NULL),
(22, 10, 23, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 03:07:52', NULL),
(23, 11, 23, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 03:07:52', NULL),
(24, 12, 23, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 03:07:52', NULL),
(26, 6, 24, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:23!', 'absen_dibuka', 0, 0, '2026-05-29 03:08:00', NULL),
(27, 7, 24, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:23!', 'absen_dibuka', 0, 0, '2026-05-29 03:08:00', NULL),
(28, 8, 24, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:23!', 'absen_dibuka', 0, 0, '2026-05-29 03:08:00', NULL),
(29, 9, 24, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:23!', 'absen_dibuka', 0, 0, '2026-05-29 03:08:00', NULL),
(30, 10, 24, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:23!', 'absen_dibuka', 0, 0, '2026-05-29 03:08:00', NULL),
(31, 11, 24, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:23!', 'absen_dibuka', 0, 0, '2026-05-29 03:08:00', NULL),
(32, 12, 24, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:23!', 'absen_dibuka', 0, 0, '2026-05-29 03:08:00', NULL),
(34, 6, 25, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:39!', 'absen_dibuka', 0, 0, '2026-05-29 03:24:32', NULL),
(35, 7, 25, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:39!', 'absen_dibuka', 0, 0, '2026-05-29 03:24:32', NULL),
(36, 8, 25, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:39!', 'absen_dibuka', 0, 0, '2026-05-29 03:24:32', NULL),
(37, 9, 25, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:39!', 'absen_dibuka', 0, 0, '2026-05-29 03:24:32', NULL),
(38, 10, 25, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:39!', 'absen_dibuka', 0, 0, '2026-05-29 03:24:32', NULL),
(39, 11, 25, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:39!', 'absen_dibuka', 0, 0, '2026-05-29 03:24:32', NULL),
(40, 12, 25, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:39!', 'absen_dibuka', 0, 0, '2026-05-29 03:24:32', NULL),
(41, 6, 26, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:52!', 'absen_dibuka', 0, 0, '2026-05-29 03:37:10', NULL),
(42, 7, 26, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:52!', 'absen_dibuka', 0, 0, '2026-05-29 03:37:10', NULL),
(43, 8, 26, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:52!', 'absen_dibuka', 0, 0, '2026-05-29 03:37:10', NULL),
(44, 9, 26, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:52!', 'absen_dibuka', 0, 0, '2026-05-29 03:37:10', NULL),
(45, 10, 26, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:52!', 'absen_dibuka', 0, 0, '2026-05-29 03:37:10', NULL),
(46, 11, 26, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:52!', 'absen_dibuka', 0, 0, '2026-05-29 03:37:10', NULL),
(47, 12, 26, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:52!', 'absen_dibuka', 0, 0, '2026-05-29 03:37:10', NULL),
(48, 6, 27, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:58!', 'absen_dibuka', 0, 0, '2026-05-29 03:43:03', NULL),
(49, 7, 27, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:58!', 'absen_dibuka', 0, 0, '2026-05-29 03:43:03', NULL),
(50, 8, 27, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:58!', 'absen_dibuka', 0, 0, '2026-05-29 03:43:03', NULL),
(51, 9, 27, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:58!', 'absen_dibuka', 0, 0, '2026-05-29 03:43:03', NULL),
(52, 10, 27, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:58!', 'absen_dibuka', 0, 0, '2026-05-29 03:43:03', NULL),
(53, 11, 27, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:58!', 'absen_dibuka', 0, 0, '2026-05-29 03:43:03', NULL),
(54, 12, 27, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:58!', 'absen_dibuka', 0, 0, '2026-05-29 03:43:03', NULL),
(55, 1, 28, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 13:43!', 'absen_dibuka', 0, 0, '2026-05-29 03:53:45', NULL),
(56, 6, 28, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 13:43!', 'absen_dibuka', 0, 0, '2026-05-29 03:53:45', NULL),
(57, 7, 28, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 13:43!', 'absen_dibuka', 0, 0, '2026-05-29 03:53:45', NULL),
(58, 8, 28, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 13:43!', 'absen_dibuka', 0, 0, '2026-05-29 03:53:45', NULL),
(59, 9, 28, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 13:43!', 'absen_dibuka', 0, 0, '2026-05-29 03:53:45', NULL),
(60, 10, 28, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 13:43!', 'absen_dibuka', 0, 0, '2026-05-29 03:53:45', NULL),
(61, 11, 28, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 13:43!', 'absen_dibuka', 0, 0, '2026-05-29 03:53:45', NULL),
(62, 12, 28, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 13:43!', 'absen_dibuka', 0, 0, '2026-05-29 03:53:45', NULL),
(63, 1, 28, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 04:14:37', NULL),
(64, 6, 28, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 04:14:37', NULL),
(65, 7, 28, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 04:14:37', NULL),
(66, 8, 28, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 04:14:37', NULL),
(67, 9, 28, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 04:14:37', NULL),
(68, 10, 28, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 04:14:37', NULL),
(69, 11, 28, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 04:14:37', NULL),
(70, 12, 28, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-05-29 04:14:37', NULL),
(71, 1, 30, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:12', NULL),
(72, 6, 30, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:12', NULL),
(73, 7, 30, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:12', NULL),
(74, 8, 30, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:12', NULL),
(75, 9, 30, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:12', NULL),
(76, 10, 30, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:12', NULL),
(77, 11, 30, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:12', NULL),
(78, 12, 30, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:12', NULL),
(79, 1, 31, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:35', NULL),
(80, 6, 31, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:35', NULL),
(81, 7, 31, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:35', NULL),
(82, 8, 31, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:35', NULL),
(83, 9, 31, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:35', NULL),
(84, 10, 31, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:35', NULL),
(85, 11, 31, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:35', NULL),
(86, 12, 31, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:05!', 'absen_dibuka', 0, 0, '2026-05-29 04:15:35', NULL),
(87, 1, 33, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:35!', 'absen_dibuka', 0, 0, '2026-05-29 04:45:24', NULL),
(88, 6, 33, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:35!', 'absen_dibuka', 0, 0, '2026-05-29 04:45:24', NULL),
(89, 7, 33, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:35!', 'absen_dibuka', 0, 0, '2026-05-29 04:45:24', NULL),
(90, 8, 33, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:35!', 'absen_dibuka', 0, 0, '2026-05-29 04:45:24', NULL),
(91, 9, 33, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:35!', 'absen_dibuka', 0, 0, '2026-05-29 04:45:24', NULL),
(92, 10, 33, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:35!', 'absen_dibuka', 0, 0, '2026-05-29 04:45:24', NULL),
(93, 11, 33, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:35!', 'absen_dibuka', 0, 0, '2026-05-29 04:45:24', NULL),
(94, 12, 33, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:35!', 'absen_dibuka', 0, 0, '2026-05-29 04:45:24', NULL),
(95, 1, 34, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:53!', 'absen_dibuka', 0, 0, '2026-05-29 05:38:17', NULL),
(96, 6, 34, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:53!', 'absen_dibuka', 0, 0, '2026-05-29 05:38:17', NULL),
(97, 7, 34, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:53!', 'absen_dibuka', 0, 0, '2026-05-29 05:38:17', NULL),
(98, 8, 34, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:53!', 'absen_dibuka', 0, 0, '2026-05-29 05:38:17', NULL),
(99, 9, 34, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:53!', 'absen_dibuka', 0, 0, '2026-05-29 05:38:17', NULL),
(100, 10, 34, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:53!', 'absen_dibuka', 0, 0, '2026-05-29 05:38:17', NULL),
(101, 11, 34, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:53!', 'absen_dibuka', 0, 0, '2026-05-29 05:38:17', NULL),
(102, 12, 34, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:53!', 'absen_dibuka', 0, 0, '2026-05-29 05:38:17', NULL),
(103, 1, 35, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:00!', 'absen_dibuka', 0, 0, '2026-05-29 05:45:59', NULL),
(104, 6, 35, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:00!', 'absen_dibuka', 0, 0, '2026-05-29 05:45:59', NULL),
(105, 7, 35, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:00!', 'absen_dibuka', 0, 0, '2026-05-29 05:45:59', NULL),
(106, 8, 35, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:00!', 'absen_dibuka', 0, 0, '2026-05-29 05:45:59', NULL),
(107, 9, 35, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:00!', 'absen_dibuka', 0, 0, '2026-05-29 05:45:59', NULL),
(108, 10, 35, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:00!', 'absen_dibuka', 0, 0, '2026-05-29 05:45:59', NULL),
(109, 11, 35, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:00!', 'absen_dibuka', 0, 0, '2026-05-29 05:45:59', NULL),
(110, 12, 35, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:00!', 'absen_dibuka', 0, 0, '2026-05-29 05:45:59', NULL),
(111, 1, 36, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:07!', 'absen_dibuka', 0, 0, '2026-05-29 05:52:59', NULL),
(112, 6, 36, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:07!', 'absen_dibuka', 0, 0, '2026-05-29 05:52:59', NULL),
(113, 7, 36, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:07!', 'absen_dibuka', 0, 0, '2026-05-29 05:52:59', NULL),
(114, 8, 36, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:07!', 'absen_dibuka', 0, 0, '2026-05-29 05:52:59', NULL),
(115, 9, 36, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:07!', 'absen_dibuka', 0, 0, '2026-05-29 05:52:59', NULL),
(116, 10, 36, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:07!', 'absen_dibuka', 0, 0, '2026-05-29 05:52:59', NULL),
(117, 11, 36, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:07!', 'absen_dibuka', 0, 0, '2026-05-29 05:52:59', NULL),
(118, 12, 36, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:07!', 'absen_dibuka', 0, 0, '2026-05-29 05:52:59', NULL),
(119, 1, 38, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 06:50!', 'absen_dibuka', 0, 0, '2026-05-31 21:00:58', NULL),
(120, 6, 38, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 06:50!', 'absen_dibuka', 0, 0, '2026-05-31 21:00:58', NULL),
(121, 7, 38, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 06:50!', 'absen_dibuka', 0, 0, '2026-05-31 21:00:58', NULL),
(122, 8, 38, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 06:50!', 'absen_dibuka', 0, 0, '2026-05-31 21:00:58', NULL),
(123, 9, 38, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 06:50!', 'absen_dibuka', 0, 0, '2026-05-31 21:00:58', NULL),
(124, 10, 38, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 06:50!', 'absen_dibuka', 0, 0, '2026-05-31 21:00:58', NULL),
(125, 11, 38, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 06:50!', 'absen_dibuka', 0, 0, '2026-05-31 21:00:58', NULL),
(126, 12, 38, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 06:50!', 'absen_dibuka', 0, 0, '2026-05-31 21:00:58', NULL),
(127, 1, 39, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:28 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:13:08', NULL),
(128, 6, 39, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:28 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:13:08', NULL),
(129, 7, 39, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:28 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:13:08', NULL),
(130, 8, 39, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:28 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:13:08', NULL),
(131, 9, 39, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:28 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:13:08', NULL),
(132, 10, 39, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:28 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:13:08', NULL),
(133, 11, 39, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:28 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:13:08', NULL),
(134, 12, 39, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:28 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:13:08', NULL),
(135, 1, 40, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:31:08', NULL),
(136, 6, 40, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:31:08', NULL),
(137, 7, 40, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:31:08', NULL),
(138, 8, 40, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:31:08', NULL),
(139, 9, 40, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:31:08', NULL),
(140, 10, 40, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:31:08', NULL),
(141, 11, 40, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:31:08', NULL),
(142, 12, 40, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:31:08', NULL),
(143, 1, 41, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:57 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:41:43', NULL),
(144, 6, 41, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:57 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:41:43', NULL),
(145, 7, 41, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:57 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:41:43', NULL),
(146, 8, 41, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:57 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:41:43', NULL),
(147, 9, 41, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:57 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:41:43', NULL),
(148, 10, 41, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:57 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:41:43', NULL),
(149, 11, 41, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:57 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:41:43', NULL),
(150, 12, 41, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 15:57 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:41:43', NULL),
(151, 1, 42, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:33 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:43:00', NULL),
(152, 6, 42, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:33 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:43:00', NULL),
(153, 7, 42, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:33 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:43:00', NULL),
(154, 8, 42, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:33 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:43:00', NULL),
(155, 9, 42, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:33 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:43:00', NULL),
(156, 10, 42, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:33 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:43:00', NULL),
(157, 11, 42, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:33 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:43:00', NULL),
(158, 12, 42, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:33 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:43:00', NULL),
(159, 1, 43, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:49 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:59:17', NULL),
(160, 6, 43, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:49 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:59:17', NULL),
(161, 7, 43, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:49 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:59:17', NULL),
(162, 8, 43, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:49 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:59:17', NULL),
(163, 9, 43, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:49 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:59:17', NULL),
(164, 10, 43, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:49 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:59:17', NULL),
(165, 11, 43, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:49 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:59:17', NULL),
(166, 12, 43, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 16:49 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 06:59:17', NULL),
(167, 1, 44, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 17:08 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 07:53:40', NULL),
(168, 6, 44, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 17:08 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 07:53:40', NULL),
(169, 7, 44, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 17:08 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 07:53:40', NULL),
(170, 8, 44, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 17:08 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 07:53:40', NULL),
(171, 9, 44, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 17:08 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 07:53:40', NULL),
(172, 10, 44, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 17:08 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 07:53:40', NULL),
(173, 11, 44, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 17:08 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 07:53:40', NULL),
(174, 12, 44, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 17:08 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 07:53:40', NULL),
(175, 1, 45, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:36', NULL),
(176, 6, 45, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:36', NULL),
(177, 7, 45, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:36', NULL),
(178, 8, 45, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:36', NULL),
(179, 9, 45, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:36', NULL),
(180, 10, 45, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:36', NULL),
(181, 11, 45, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:36', NULL),
(182, 12, 45, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:36', NULL),
(183, 1, 46, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:38', NULL),
(184, 6, 46, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:38', NULL),
(185, 7, 46, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:38', NULL),
(186, 8, 46, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:38', NULL),
(187, 9, 46, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:38', NULL),
(188, 10, 46, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:38', NULL),
(189, 11, 46, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:38', NULL),
(190, 12, 46, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:06 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:16:38', NULL),
(191, 1, 47, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:37 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:47:52', NULL),
(192, 6, 47, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:37 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:47:52', NULL),
(193, 7, 47, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:37 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:47:52', NULL),
(194, 8, 47, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:37 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:47:52', NULL),
(195, 9, 47, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:37 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:47:52', NULL),
(196, 10, 47, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:37 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:47:52', NULL),
(197, 11, 47, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:37 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:47:52', NULL),
(198, 12, 47, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:37 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:47:52', NULL),
(199, 1, 48, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:54:51', NULL),
(200, 6, 48, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:54:51', NULL),
(201, 7, 48, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:54:51', NULL),
(202, 8, 48, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:54:51', NULL),
(203, 9, 48, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:54:51', NULL),
(204, 10, 48, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:54:51', NULL),
(205, 11, 48, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:54:51', NULL),
(206, 12, 48, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 20:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 10:54:51', NULL),
(207, 1, 49, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:14 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 11:59:36', NULL),
(208, 6, 49, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:14 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 11:59:36', NULL),
(209, 7, 49, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:14 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 11:59:36', NULL),
(210, 8, 49, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:14 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 11:59:36', NULL),
(211, 9, 49, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:14 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 11:59:36', NULL),
(212, 10, 49, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:14 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 11:59:36', NULL),
(213, 11, 49, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:14 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 11:59:36', NULL),
(214, 12, 49, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:14 WIT!', 'absen_dibuka', 0, 0, '2026-06-01 11:59:36', NULL),
(215, 1, 50, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:41 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 02:51:58', NULL),
(216, 6, 50, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:41 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 02:51:58', NULL),
(217, 7, 50, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:41 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 02:51:58', NULL),
(218, 8, 50, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:41 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 02:51:58', NULL),
(219, 9, 50, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:41 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 02:51:58', NULL),
(220, 10, 50, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:41 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 02:51:58', NULL),
(221, 11, 50, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:41 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 02:51:58', NULL),
(222, 12, 50, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 12:41 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 02:51:58', NULL),
(223, 1, 51, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:54:10', NULL),
(224, 6, 51, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:54:10', NULL),
(225, 7, 51, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:54:10', NULL),
(226, 8, 51, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:54:10', NULL),
(227, 9, 51, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:54:10', NULL),
(228, 10, 51, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:54:10', NULL),
(229, 11, 51, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:54:10', NULL),
(230, 12, 51, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:44 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:54:10', NULL),
(231, 1, 52, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:11 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:56:29', NULL),
(232, 6, 52, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:11 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:56:29', NULL),
(233, 7, 52, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:11 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:56:29', NULL),
(234, 8, 52, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:11 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:56:29', NULL),
(235, 9, 52, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:11 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:56:29', NULL),
(236, 10, 52, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:11 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:56:29', NULL),
(237, 11, 52, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:11 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:56:29', NULL),
(238, 12, 52, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:11 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:56:29', NULL),
(239, 1, 53, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:13 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:58:33', NULL),
(240, 6, 53, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:13 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:58:33', NULL),
(241, 7, 53, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:13 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:58:33', NULL),
(242, 8, 53, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:13 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:58:33', NULL),
(243, 9, 53, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:13 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:58:33', NULL),
(244, 10, 53, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:13 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:58:33', NULL),
(245, 11, 53, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:13 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:58:33', NULL),
(246, 12, 53, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:13 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 04:58:33', NULL),
(247, 1, 54, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:06:42', NULL),
(248, 6, 54, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:06:42', NULL),
(249, 7, 54, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:06:42', NULL),
(250, 8, 54, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:06:42', NULL),
(251, 9, 54, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:06:42', NULL),
(252, 10, 54, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:06:42', NULL),
(253, 11, 54, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:06:42', NULL),
(254, 12, 54, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:06:42', NULL),
(255, 1, 55, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:22 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:07:02', NULL),
(256, 6, 55, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:22 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:07:02', NULL),
(257, 7, 55, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:22 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:07:02', NULL),
(258, 8, 55, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:22 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:07:02', NULL),
(259, 9, 55, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:22 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:07:02', NULL),
(260, 10, 55, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:22 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:07:02', NULL),
(261, 11, 55, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:22 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:07:02', NULL),
(262, 12, 55, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 14:22 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 05:07:02', NULL),
(263, 1, 56, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 11:31:16', NULL),
(264, 6, 56, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 11:31:16', NULL),
(265, 7, 56, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 11:31:16', NULL),
(266, 8, 56, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 11:31:16', NULL),
(267, 9, 56, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 11:31:16', NULL),
(268, 10, 56, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 11:31:16', NULL),
(269, 11, 56, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 11:31:16', NULL),
(270, 12, 56, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:21 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 11:31:16', NULL),
(271, 1, 57, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:16 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:26:32', NULL),
(272, 6, 57, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:16 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:26:32', NULL),
(273, 7, 57, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:16 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:26:32', NULL),
(274, 8, 57, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:16 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:26:32', NULL),
(275, 9, 57, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:16 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:26:32', NULL),
(276, 10, 57, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:16 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:26:32', NULL),
(277, 11, 57, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:16 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:26:32', NULL),
(278, 12, 57, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:16 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:26:32', NULL),
(279, 1, 57, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:32:42', NULL),
(280, 6, 57, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:32:42', NULL),
(281, 7, 57, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:32:42', NULL),
(282, 8, 57, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:32:42', NULL),
(283, 9, 57, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:32:42', NULL),
(284, 10, 57, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:32:42', NULL),
(285, 11, 57, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:32:42', NULL),
(286, 12, 57, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:32:42', NULL),
(287, 1, 58, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:47 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:32:53', NULL),
(288, 6, 58, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:47 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:32:53', NULL),
(289, 7, 58, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:47 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:32:53', NULL),
(290, 8, 58, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:47 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:32:53', NULL),
(291, 9, 58, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:47 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:32:53', NULL),
(292, 10, 58, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:47 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:32:53', NULL),
(293, 11, 58, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:47 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:32:53', NULL),
(294, 12, 58, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:47 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:32:53', NULL),
(295, 1, 58, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:40:54', NULL),
(296, 6, 58, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:40:54', NULL),
(297, 7, 58, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:40:54', NULL),
(298, 8, 58, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:40:54', NULL),
(299, 9, 58, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:40:54', NULL),
(300, 10, 58, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:40:54', NULL),
(301, 11, 58, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:40:54', NULL),
(302, 12, 58, 'Absen Ditutup', 'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.', 'absen_ditutup', 0, 0, '2026-06-02 12:40:54', NULL),
(303, 1, 59, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:55 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:40:59', NULL),
(304, 6, 59, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:55 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:40:59', NULL),
(305, 7, 59, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:55 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:40:59', NULL),
(306, 8, 59, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:55 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:40:59', NULL),
(307, 9, 59, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:55 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:40:59', NULL),
(308, 10, 59, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:55 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:40:59', NULL),
(309, 11, 59, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:55 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:40:59', NULL),
(310, 12, 59, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 21:55 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:40:59', NULL),
(311, 1, 60, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:00 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:45:56', NULL),
(312, 6, 60, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:00 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:45:56', NULL),
(313, 7, 60, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:00 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:45:56', NULL),
(314, 8, 60, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:00 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:45:56', NULL);
INSERT INTO `notifikasi_siswa` (`id`, `siswa_id`, `qr_session_id`, `judul`, `pesan`, `tipe`, `is_dibaca`, `is_clicked`, `created_at`, `read_at`) VALUES
(315, 9, 60, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:00 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:45:56', NULL),
(316, 10, 60, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:00 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:45:56', NULL),
(317, 11, 60, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:00 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:45:56', NULL),
(318, 12, 60, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:00 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:45:56', NULL),
(319, 1, 61, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:09 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:54:08', NULL),
(320, 6, 61, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:09 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:54:08', NULL),
(321, 7, 61, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:09 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:54:08', NULL),
(322, 8, 61, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:09 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:54:08', NULL),
(323, 9, 61, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:09 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:54:08', NULL),
(324, 10, 61, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:09 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:54:08', NULL),
(325, 11, 61, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:09 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:54:08', NULL),
(326, 12, 61, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:09 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 12:54:08', NULL),
(327, 1, 62, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:18 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:03:33', NULL),
(328, 6, 62, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:18 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:03:33', NULL),
(329, 7, 62, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:18 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:03:33', NULL),
(330, 8, 62, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:18 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:03:33', NULL),
(331, 9, 62, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:18 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:03:33', NULL),
(332, 10, 62, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:18 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:03:33', NULL),
(333, 11, 62, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:18 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:03:33', NULL),
(334, 12, 62, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 22:18 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:03:33', NULL),
(335, 1, 63, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 23:03 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:48:01', NULL),
(336, 6, 63, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 23:03 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:48:01', NULL),
(337, 7, 63, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 23:03 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:48:01', NULL),
(338, 8, 63, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 23:03 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:48:01', NULL),
(339, 9, 63, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 23:03 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:48:01', NULL),
(340, 10, 63, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 23:03 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:48:01', NULL),
(341, 11, 63, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 23:03 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:48:01', NULL),
(342, 12, 63, 'Absen Dibuka!', 'Guru membuka absen Matematika kelas XI A. Segera absen sebelum pukul 23:03 WIT!', 'absen_dibuka', 0, 0, '2026-06-02 13:48:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_sekolah`
--

CREATE TABLE `pengaturan_sekolah` (
  `id` int(11) NOT NULL,
  `nama_sekolah` varchar(150) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo_sekolah` varchar(255) DEFAULT NULL,
  `kepala_sekolah` varchar(100) DEFAULT NULL,
  `nip_kepsek` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_sessions`
--

CREATE TABLE `qr_sessions` (
  `id` int(11) NOT NULL,
  `jadwal_id` int(11) DEFAULT NULL,
  `mata_pelajaran_id` int(11) DEFAULT NULL,
  `token` varchar(64) NOT NULL,
  `jenis_absensi` enum('masuk','pulang') NOT NULL,
  `tanggal` date NOT NULL,
  `dibuat_oleh` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `jam_mulai_absen` datetime NOT NULL,
  `jam_selesai_absen` datetime NOT NULL,
  `durasi_menit` int(11) NOT NULL DEFAULT 15 COMMENT 'Lama sesi absen dibuka (menit)',
  `expired_at` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `qr_image_url` varchar(255) DEFAULT NULL COMMENT 'Path gambar QR yang di-generate',
  `kode_cadangan` varchar(10) DEFAULT NULL COMMENT 'Kode 6 digit alternatif jika QR gagal',
  `metode_tampilan` enum('proyektor','layar_laptop','print_out','share_link') DEFAULT 'layar_laptop' COMMENT 'Cara guru menampilkan QR',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qr_sessions`
--

INSERT INTO `qr_sessions` (`id`, `jadwal_id`, `mata_pelajaran_id`, `token`, `jenis_absensi`, `tanggal`, `dibuat_oleh`, `kelas_id`, `jam_mulai_absen`, `jam_selesai_absen`, `durasi_menit`, `expired_at`, `is_active`, `qr_image_url`, `kode_cadangan`, `metode_tampilan`, `created_at`) VALUES
(6, 4, NULL, 'fca937c702368314fe6909a713b85a7592e19a0ab58b142e169f1ceb090e9126', 'masuk', '2026-05-18', 9, 1, '2026-05-18 09:52:07', '2026-05-18 10:07:07', 15, '2026-05-18 10:07:07', 0, NULL, NULL, 'layar_laptop', '2026-05-18 00:52:07'),
(7, 4, NULL, '629268671177964e66f7fa8536351d9d346f50dd46ab98ece4ad5dede4a9036c', 'pulang', '2026-05-18', 9, 1, '2026-05-18 10:01:03', '2026-05-18 10:16:03', 15, '2026-05-18 10:16:03', 0, NULL, NULL, 'layar_laptop', '2026-05-18 01:01:03'),
(8, 4, NULL, '843a57f227815dc1a3cb0330d742c8e17ef1b85089929b9a7071f67af069768f', 'masuk', '2026-05-19', 9, 1, '2026-05-19 12:23:41', '2026-05-19 12:38:41', 15, '2026-05-19 12:38:41', 0, NULL, NULL, 'layar_laptop', '2026-05-19 03:23:41'),
(9, 4, NULL, 'de83544fee427a52a01adb5471b625e52e0dbafe22cf3a0350441a3b771dd0cf', 'masuk', '2026-05-19', 9, 1, '2026-05-19 12:29:12', '2026-05-19 13:05:12', 15, '2026-05-19 13:05:12', 0, NULL, NULL, 'layar_laptop', '2026-05-19 03:29:12'),
(10, 4, NULL, '87cd7da7cf88fbd236abd5462f6b99e874afd6c6410cb6887323a789fe1d22dc', 'masuk', '2026-05-19', 9, 1, '2026-05-19 12:29:49', '2026-05-19 12:44:49', 15, '2026-05-19 12:44:49', 0, NULL, NULL, 'layar_laptop', '2026-05-19 03:29:49'),
(11, 5, NULL, 'c6554ca34378ccf89c229c6c87d672bb9c7e66c6c0b94aa925929ec43d8e9d64', 'masuk', '2026-05-19', 9, 1, '2026-05-19 12:30:09', '2026-05-19 13:00:09', 15, '2026-05-19 13:00:09', 0, NULL, NULL, 'layar_laptop', '2026-05-19 03:30:09'),
(12, 4, NULL, 'faa18274f8a5ea6ddd2a9b97a1e2a34bb08a49802a15eedf3c7354dea555da0a', 'masuk', '2026-05-19', 9, 1, '2026-05-19 13:20:57', '2026-05-19 13:35:57', 15, '2026-05-19 13:35:57', 0, NULL, NULL, 'layar_laptop', '2026-05-19 04:20:57'),
(13, 4, NULL, '6bcc0e7aa159fbbec2f55a08f4eaba527cab33a2eeccfbf91ce4805af7a05f60', 'masuk', '2026-05-19', 9, 1, '2026-05-19 13:36:04', '2026-05-19 13:51:04', 15, '2026-05-19 13:51:04', 0, NULL, NULL, 'layar_laptop', '2026-05-19 04:36:04'),
(14, 5, NULL, '18c9e5f9023188cfcbb0cc91656f0af95ab58b0762ddd30c18a1e91254c3b9fc', 'masuk', '2026-05-19', 9, 1, '2026-05-19 13:36:11', '2026-05-19 13:51:11', 15, '2026-05-19 13:51:11', 0, NULL, NULL, 'layar_laptop', '2026-05-19 04:36:11'),
(15, 4, NULL, '33fef300866f575b48f4016b82716088e3bfe6f5061e1a6bd5cc0af4104536c5', 'masuk', '2026-05-20', 9, 1, '2026-05-20 09:37:25', '2026-05-20 10:17:25', 15, '2026-05-20 10:17:25', 0, NULL, NULL, 'layar_laptop', '2026-05-20 00:37:25'),
(16, 4, NULL, '4016372e63a0cf4441cba1ec718500cbed7d5c3446eeca2bb1a16590eab69072', 'masuk', '2026-05-20', 9, 1, '2026-05-20 12:24:40', '2026-05-20 12:39:40', 15, '2026-05-20 12:39:40', 0, NULL, NULL, 'layar_laptop', '2026-05-20 03:24:40'),
(17, 4, NULL, '4abce2b1a9a45774acf6712c24be4591f0a92bcb957fba0e6514e4c153939025', 'masuk', '2026-05-20', 9, 1, '2026-05-20 12:26:29', '2026-05-20 12:41:29', 15, '2026-05-20 12:41:29', 0, NULL, NULL, 'layar_laptop', '2026-05-20 03:26:29'),
(18, 4, NULL, '29cc03ee1035d71bd3d78f451e8ccc7829e61352fa248abb407a390a736440b0', 'masuk', '2026-05-29', 9, 1, '2026-05-29 08:00:31', '2026-05-29 08:15:31', 15, '2026-05-29 08:15:31', 0, NULL, NULL, 'layar_laptop', '2026-05-28 23:00:31'),
(19, 4, NULL, '66231d70d9cff285452515bde522b360388658463fd94aa144e5d5523efb62e0', 'masuk', '2026-05-29', 9, 1, '2026-05-29 08:23:57', '2026-05-29 08:38:57', 15, '2026-05-29 08:38:57', 0, NULL, NULL, 'layar_laptop', '2026-05-28 23:23:57'),
(20, 4, 5, 'testtoken1234567890abcdef1234567890abcdef1234567890abcdef1234567', 'masuk', '2026-05-29', 9, 1, '2026-05-29 18:14:18', '2026-05-29 18:29:18', 15, '2026-05-29 18:29:18', 0, NULL, NULL, 'layar_laptop', '2026-05-29 09:14:18'),
(21, 5, NULL, '9e251ce83222b044ec0d1d83f009c7026f29393ce0fa0d0bdf2f5234ecc4107b', 'masuk', '2026-05-29', 9, 1, '2026-05-29 09:15:10', '2026-05-29 09:30:10', 15, '2026-05-29 09:30:10', 0, NULL, NULL, 'layar_laptop', '2026-05-29 00:15:10'),
(22, 4, 1, 'fd8edfb2fc100754c4e17d56ec34ec8d7b808f2b5ba5413843a7e4f4ee524839', 'masuk', '2026-05-29', 9, 1, '2026-05-29 11:53:53', '2026-05-29 12:08:53', 15, '2026-05-29 12:08:53', 0, 'http://localhost:8080/qr/generate/fd8edfb2fc100754c4e17d56ec34ec8d7b808f2b5ba5413843a7e4f4ee524839', '410565', 'proyektor', '2026-05-29 02:53:53'),
(23, 4, 1, '676e12e3744fd2cf067976e238ef0cd04507828438fd67b428d7e02da31c3fa2', 'masuk', '2026-05-29', 9, 1, '2026-05-29 11:59:33', '2026-05-29 12:14:33', 15, '2026-05-29 12:14:33', 0, 'http://localhost:8080/qr/generate/676e12e3744fd2cf067976e238ef0cd04507828438fd67b428d7e02da31c3fa2', '318361', 'layar_laptop', '2026-05-29 02:59:33'),
(24, 5, 1, 'd96eae74e0b5acc00552450d00c7636baf098c99d4c995e41032374690b824ee', 'masuk', '2026-05-29', 9, 1, '2026-05-29 12:08:00', '2026-05-29 12:23:00', 15, '2026-05-29 12:23:00', 0, 'http://localhost:8080/qr/generate/d96eae74e0b5acc00552450d00c7636baf098c99d4c995e41032374690b824ee', '25118C', 'layar_laptop', '2026-05-29 03:08:00'),
(25, 4, 1, '6b318ce9099aa75cd09caac07cd5fde1675de67e0899416adb167cdd99d66579', 'masuk', '2026-05-29', 9, 1, '2026-05-29 12:24:32', '2026-05-29 12:39:32', 15, '2026-05-29 12:39:32', 0, 'http://localhost:8080/qr/generate/6b318ce9099aa75cd09caac07cd5fde1675de67e0899416adb167cdd99d66579', '56D95E', 'layar_laptop', '2026-05-29 03:24:32'),
(26, 4, 1, '0504a41a423f99f81b08d488b6475163f2ae7908ec7b43def6e1bc5efb4d131b', 'masuk', '2026-05-29', 9, 1, '2026-05-29 12:37:10', '2026-05-29 12:52:10', 15, '2026-05-29 12:52:10', 0, 'http://localhost:8080/qr/generate/0504a41a423f99f81b08d488b6475163f2ae7908ec7b43def6e1bc5efb4d131b', '8532CF', 'layar_laptop', '2026-05-29 03:37:10'),
(27, 4, 1, '2a964a7494e3a833878f47ca4028887737f801d66940c74d47c60bc9ac1e980a', 'masuk', '2026-05-29', 9, 1, '2026-05-29 12:43:03', '2026-05-29 12:58:03', 15, '2026-05-29 12:58:03', 0, 'http://localhost:8080/qr/generate/2a964a7494e3a833878f47ca4028887737f801d66940c74d47c60bc9ac1e980a', 'FC7EAF', 'layar_laptop', '2026-05-29 03:43:03'),
(28, 4, 1, 'a3b83b60e344f68b24a21de67563e4ec1655ebae224e11151a314a374526b993', 'masuk', '2026-05-29', 9, 1, '2026-05-29 12:53:45', '2026-05-29 13:43:45', 50, '2026-05-29 13:43:45', 0, 'http://localhost:8080/qr/generate/a3b83b60e344f68b24a21de67563e4ec1655ebae224e11151a314a374526b993', '017B68', 'layar_laptop', '2026-05-29 03:53:45'),
(29, 4, 1, 'manualb5bac9fd5b5d11f1843c0a0027000011', 'masuk', '2026-05-29', 9, 1, '2026-05-29 21:55:46', '2026-05-29 22:10:46', 15, '2026-05-29 22:10:46', 0, NULL, NULL, 'layar_laptop', '2026-05-29 12:55:46'),
(30, 4, 1, '51271d7bacac7bbf5561e5aca653d7806e5f22237b9f10aae6361a8a83d9bc5a', 'masuk', '2026-05-29', 9, 1, '2026-05-29 13:15:12', '2026-05-29 14:05:12', 50, '2026-05-29 14:05:12', 0, 'http://localhost:8080/qr/generate/51271d7bacac7bbf5561e5aca653d7806e5f22237b9f10aae6361a8a83d9bc5a', '72DA96', 'layar_laptop', '2026-05-29 04:15:12'),
(31, 5, 1, '74facadf83db6ca7bbad54b879d7ad6467b6d2821a77fd7c3de795950722c9a6', 'masuk', '2026-05-29', 9, 1, '2026-05-29 13:15:35', '2026-05-29 14:05:35', 50, '2026-05-29 14:05:35', 0, 'http://localhost:8080/qr/generate/74facadf83db6ca7bbad54b879d7ad6467b6d2821a77fd7c3de795950722c9a6', '0C8814', 'layar_laptop', '2026-05-29 04:15:35'),
(32, 4, 1, 'manual9ec81ee75b6011f1843c0a0027000011', 'masuk', '2026-05-29', 9, 1, '2026-05-29 22:16:36', '2026-05-29 22:46:36', 30, '2026-05-29 22:46:36', 0, NULL, NULL, 'layar_laptop', '2026-05-29 13:16:36'),
(33, 4, 1, 'a19a56d2c7e616a75b5da0ca51c33a2d3b2e68deac852b20b7f5e6fdd01fa5b4', 'masuk', '2026-05-29', 9, 1, '2026-05-29 13:45:24', '2026-05-29 14:35:24', 50, '2026-05-29 14:35:24', 0, 'http://localhost:8080/qr/generate/a19a56d2c7e616a75b5da0ca51c33a2d3b2e68deac852b20b7f5e6fdd01fa5b4', '1D62EE', 'share_link', '2026-05-29 04:45:24'),
(34, 4, 1, '53a0427b39b0827933a8393c932f76d157c425ad538b4290239807fed811950a', 'masuk', '2026-05-29', 9, 1, '2026-05-29 14:38:17', '2026-05-29 14:53:17', 15, '2026-05-29 14:53:17', 0, 'http://localhost:8080/qr/generate/53a0427b39b0827933a8393c932f76d157c425ad538b4290239807fed811950a', 'D8F483', 'share_link', '2026-05-29 05:38:17'),
(35, 4, 1, '09c0e6c3c72f32cb798033905928566791ad3ebb26266a493d2499c2673e16e4', 'masuk', '2026-05-29', 9, 1, '2026-05-29 14:45:59', '2026-05-29 15:00:59', 15, '2026-05-29 15:00:59', 0, 'http://localhost:8080/qr/generate/09c0e6c3c72f32cb798033905928566791ad3ebb26266a493d2499c2673e16e4', '741772', 'share_link', '2026-05-29 05:45:59'),
(36, 4, 1, '36352ef8d208e94d7e16a68eef8071b281369ee0b6c6243974731253d1624fa0', 'masuk', '2026-05-29', 9, 1, '2026-05-29 14:52:59', '2026-05-29 15:07:59', 15, '2026-05-29 15:07:59', 0, 'http://localhost:8080/qr/generate/36352ef8d208e94d7e16a68eef8071b281369ee0b6c6243974731253d1624fa0', '663E9A', 'proyektor', '2026-05-29 05:52:59'),
(37, 4, 1, 'fix1780066545', 'masuk', '2026-05-29', 9, 1, '2026-05-29 23:55:45', '2026-05-30 01:55:45', 120, '2026-05-30 01:55:45', 0, NULL, NULL, 'layar_laptop', '2026-05-29 14:55:45'),
(38, 4, 1, 'd2afadf66a68c8c9470e8838baad6eb644442261678b7497da9fc591734d451b', 'masuk', '2026-06-01', 9, 1, '2026-06-01 06:00:58', '2026-06-01 06:50:58', 50, '2026-06-01 06:50:58', 0, 'http://localhost:8080/qr/generate/d2afadf66a68c8c9470e8838baad6eb644442261678b7497da9fc591734d451b', '7571D3', 'layar_laptop', '2026-05-31 21:00:58'),
(39, 4, 1, 'e437ebafb7e6d2ea7ff2d89e775db27a8baac9b49e0937dc0aaf0a6683110e18', 'masuk', '2026-06-01', 9, 1, '2026-06-01 15:13:08', '2026-06-01 15:28:08', 15, '2026-06-01 15:28:08', 0, 'http://localhost:8080/qr/generate/e437ebafb7e6d2ea7ff2d89e775db27a8baac9b49e0937dc0aaf0a6683110e18', '1A0965', 'layar_laptop', '2026-06-01 06:13:08'),
(40, 4, 1, 'cb2ea323a0d27ad61905fb4807ad2defc71193789ae5321edeebe54e2557791a', 'masuk', '2026-06-01', 9, 1, '2026-06-01 15:31:08', '2026-06-01 16:21:08', 50, '2026-06-01 16:21:08', 0, 'http://localhost:8080/qr/generate/cb2ea323a0d27ad61905fb4807ad2defc71193789ae5321edeebe54e2557791a', '039890', 'layar_laptop', '2026-06-01 06:31:08'),
(41, 4, 1, '11de9259529fadba17f6f2be349bc19afcab7a07952425a3bca041e089fd9aac', 'masuk', '2026-06-01', 9, 1, '2026-06-01 15:41:43', '2026-06-01 15:57:43', 16, '2026-06-01 15:57:43', 0, 'http://localhost:8080/qr/generate/11de9259529fadba17f6f2be349bc19afcab7a07952425a3bca041e089fd9aac', '0F6BBA', 'layar_laptop', '2026-06-01 06:41:43'),
(42, 4, 1, 'de55c0fa5f6498efe09fcc28b415849456ff6a641fcb0400411e8a359f0686b1', 'masuk', '2026-06-01', 9, 1, '2026-06-01 15:43:00', '2026-06-01 16:33:00', 50, '2026-06-01 16:33:00', 0, 'http://localhost:8080/qr/generate/de55c0fa5f6498efe09fcc28b415849456ff6a641fcb0400411e8a359f0686b1', '50A609', 'layar_laptop', '2026-06-01 06:43:00'),
(43, 5, 1, '3937523d29ee442c0efcf4acee4d77b58036b61c137fc69543ac27e7f4cc1cc9', 'masuk', '2026-06-01', 9, 1, '2026-06-01 15:59:17', '2026-06-01 16:49:17', 50, '2026-06-01 16:49:17', 0, 'http://localhost:8080/qr/generate/3937523d29ee442c0efcf4acee4d77b58036b61c137fc69543ac27e7f4cc1cc9', '138035', 'layar_laptop', '2026-06-01 06:59:17'),
(44, 5, 1, '6d7f2f3e78bbf4f3adca714166c9fa71b4985a749b3ded04cfe1a92c35525cde', 'masuk', '2026-06-01', 9, 1, '2026-06-01 16:53:40', '2026-06-01 17:08:40', 15, '2026-06-01 17:08:40', 0, 'http://localhost:8080/qr/generate/6d7f2f3e78bbf4f3adca714166c9fa71b4985a749b3ded04cfe1a92c35525cde', 'C252CF', 'layar_laptop', '2026-06-01 07:53:40'),
(45, 4, 1, '4d7e7ac6a0ee6154dbe17315c691ee57a7ff6765f7e4e738f7046e485456586e', 'masuk', '2026-06-01', 9, 1, '2026-06-01 19:16:36', '2026-06-01 20:06:36', 50, '2026-06-01 20:06:36', 0, 'https://balsamic-runny-reviver.ngrok-free.dev/qr/generate/4d7e7ac6a0ee6154dbe17315c691ee57a7ff6765f7e4e738f7046e485456586e', '94299B', 'layar_laptop', '2026-06-01 10:16:36'),
(46, 4, 1, '5eb656fd97cffbf650f3b30413b79c882b2d7036803785fd8672c74c54612e87', 'masuk', '2026-06-01', 9, 1, '2026-06-01 19:16:38', '2026-06-01 20:06:38', 50, '2026-06-01 20:06:38', 0, 'https://balsamic-runny-reviver.ngrok-free.dev/qr/generate/5eb656fd97cffbf650f3b30413b79c882b2d7036803785fd8672c74c54612e87', 'DF30FE', 'layar_laptop', '2026-06-01 10:16:38'),
(47, 4, 1, 'b8cc94d0c2195426ca725d7577d0e5060f7b1978fc04712b3616850235205e72', 'masuk', '2026-06-01', 9, 1, '2026-06-01 19:47:52', '2026-06-01 20:37:52', 50, '2026-06-01 20:37:52', 0, 'https://balsamic-runny-reviver.ngrok-free.dev/qr/generate/b8cc94d0c2195426ca725d7577d0e5060f7b1978fc04712b3616850235205e72', 'A832ED', 'layar_laptop', '2026-06-01 10:47:52'),
(48, 4, 1, '8639ec9011215ec6832e15b5346d4a893b3cb4a1dedc41d4bef9ab5ce23f7fc0', 'masuk', '2026-06-01', 9, 1, '2026-06-01 19:54:51', '2026-06-01 20:44:51', 50, '2026-06-01 20:44:51', 0, 'https://balsamic-runny-reviver.ngrok-free.dev/qr/generate/8639ec9011215ec6832e15b5346d4a893b3cb4a1dedc41d4bef9ab5ce23f7fc0', 'E6D431', 'layar_laptop', '2026-06-01 10:54:51'),
(49, 4, 1, 'fd81345f09a61b1d3954c11ce7cdd340b86a7df2a796f76263078e3e2f239231', 'masuk', '2026-06-01', 9, 1, '2026-06-01 20:59:36', '2026-06-01 21:14:36', 15, '2026-06-01 21:14:36', 0, 'https://balsamic-runny-reviver.ngrok-free.dev/qr/generate/fd81345f09a61b1d3954c11ce7cdd340b86a7df2a796f76263078e3e2f239231', 'E612F2', 'layar_laptop', '2026-06-01 11:59:36'),
(50, 4, 1, '54e400f73705552422623d5c562c0ae93324945b003fdf133989a089ca36fcc8', 'masuk', '2026-06-02', 9, 1, '2026-06-02 11:51:58', '2026-06-02 12:41:58', 50, '2026-06-02 12:41:58', 0, 'https://balsamic-runny-reviver.ngrok-free.dev/qr/generate/54e400f73705552422623d5c562c0ae93324945b003fdf133989a089ca36fcc8', '652B85', 'layar_laptop', '2026-06-02 02:51:58'),
(51, 4, 1, 'fef733e8e95139ea500e3c6ff77637082d29f51395755573e58a82c0a28f7dcc', 'masuk', '2026-06-02', 9, 1, '2026-06-02 13:54:10', '2026-06-02 14:44:10', 50, '2026-06-02 14:44:10', 0, 'http://localhost:8080/qr/generate/fef733e8e95139ea500e3c6ff77637082d29f51395755573e58a82c0a28f7dcc', '0FBD6C', 'layar_laptop', '2026-06-02 04:54:10'),
(52, 4, 1, '3b64141d0aeb0738d059b5f67bf5e3227c947e1b9dc66432a4776a4ac6bd0c43', 'masuk', '2026-06-02', 9, 1, '2026-06-02 13:56:29', '2026-06-02 14:11:29', 15, '2026-06-02 14:11:29', 0, 'http://localhost:8080/qr/generate/3b64141d0aeb0738d059b5f67bf5e3227c947e1b9dc66432a4776a4ac6bd0c43', 'E6F845', 'layar_laptop', '2026-06-02 04:56:29'),
(53, 5, 1, '12197f764a0af46b3ba0cf2b4576fdbd8d641b88700e792bac4053b6efd31041', 'masuk', '2026-06-02', 9, 1, '2026-06-02 13:58:33', '2026-06-02 14:13:33', 15, '2026-06-02 14:13:33', 0, 'http://localhost:8080/qr/generate/12197f764a0af46b3ba0cf2b4576fdbd8d641b88700e792bac4053b6efd31041', 'F3453D', 'layar_laptop', '2026-06-02 04:58:33'),
(54, 4, 1, '89bd18486ddd4129a7a3ac6572a8db42de1f550b5b27864463394c02db74a502', 'masuk', '2026-06-02', 9, 1, '2026-06-02 14:06:42', '2026-06-02 14:21:42', 15, '2026-06-02 14:21:42', 0, 'http://localhost:8080/qr/generate/89bd18486ddd4129a7a3ac6572a8db42de1f550b5b27864463394c02db74a502', '0EA426', 'layar_laptop', '2026-06-02 05:06:42'),
(55, 4, 1, 'ae5c79f4fbabf1220b3f29d4453222725f222e580fbcc3141ad3ae4b1f548622', 'masuk', '2026-06-02', 9, 1, '2026-06-02 14:07:02', '2026-06-02 14:22:02', 15, '2026-06-02 14:22:02', 0, 'http://localhost:8080/qr/generate/ae5c79f4fbabf1220b3f29d4453222725f222e580fbcc3141ad3ae4b1f548622', '97A126', 'layar_laptop', '2026-06-02 05:07:02'),
(56, 4, 1, 'd4294cbb1545a80e3db3fc9cda76b815d029c0769623b212ca1ba86d8e91c235', 'masuk', '2026-06-02', 9, 1, '2026-06-02 20:31:16', '2026-06-02 21:21:16', 50, '2026-06-02 21:21:16', 0, 'http://localhost:8080/qr/generate/d4294cbb1545a80e3db3fc9cda76b815d029c0769623b212ca1ba86d8e91c235', 'A12CDF', 'layar_laptop', '2026-06-02 11:31:16'),
(57, 4, 1, '78809ec54f9bd177fe1e21e8217c35f87c281d308ed7e53714e69eae316f8388', 'masuk', '2026-06-02', 9, 1, '2026-06-02 21:26:32', '2026-06-02 22:16:32', 50, '2026-06-02 22:16:32', 0, 'http://localhost:8080/qr/generate/78809ec54f9bd177fe1e21e8217c35f87c281d308ed7e53714e69eae316f8388', 'F022D7', 'layar_laptop', '2026-06-02 12:26:32'),
(58, 4, 1, 'aa11e235cc6e92264c7ab0dab2ce94d48fd88bdbda74f0cc89943da769030472', 'masuk', '2026-06-02', 9, 1, '2026-06-02 21:32:53', '2026-06-02 21:47:53', 15, '2026-06-02 21:47:53', 0, 'http://localhost:8080/qr/generate/aa11e235cc6e92264c7ab0dab2ce94d48fd88bdbda74f0cc89943da769030472', 'EAD35E', 'layar_laptop', '2026-06-02 12:32:53'),
(59, 4, 1, '0b6ad93c18c29664a799e57cb9fa03d5f0130a6dae1f35108cb8ac5c20005e07', 'masuk', '2026-06-02', 9, 1, '2026-06-02 21:40:59', '2026-06-02 21:55:59', 15, '2026-06-02 21:55:59', 0, 'http://localhost:8080/qr/generate/0b6ad93c18c29664a799e57cb9fa03d5f0130a6dae1f35108cb8ac5c20005e07', '6DA4AB', 'layar_laptop', '2026-06-02 12:40:59'),
(60, 4, 1, 'b02cfa5d9dab170618a9d8f7dec6fed29e36337a3252aa63387e571d4135f6dc', 'masuk', '2026-06-02', 9, 1, '2026-06-02 21:45:56', '2026-06-02 22:00:56', 15, '2026-06-02 22:00:56', 0, 'http://localhost:8080/qr/generate/b02cfa5d9dab170618a9d8f7dec6fed29e36337a3252aa63387e571d4135f6dc', '9BEC41', 'layar_laptop', '2026-06-02 12:45:56'),
(61, 5, 1, 'b24b401b44a322d390c6494b4569fed75d7d7185d5799eb566ce99d96f238a3a', 'masuk', '2026-06-02', 9, 1, '2026-06-02 21:54:08', '2026-06-02 22:09:08', 15, '2026-06-02 22:09:08', 0, 'http://localhost:8080/qr/generate/b24b401b44a322d390c6494b4569fed75d7d7185d5799eb566ce99d96f238a3a', '5A59AC', 'layar_laptop', '2026-06-02 12:54:08'),
(62, 5, 1, '99173db8e239a87b8449139a2a58202a95d1b49d78ec1be68e4ae9848a237df0', 'masuk', '2026-06-02', 9, 1, '2026-06-02 22:03:33', '2026-06-02 22:18:33', 15, '2026-06-02 22:18:33', 0, 'http://localhost:8080/qr/generate/99173db8e239a87b8449139a2a58202a95d1b49d78ec1be68e4ae9848a237df0', 'D1CC9F', 'layar_laptop', '2026-06-02 13:03:33'),
(63, 5, 1, 'bd168664afaca7d045c16a58ce403878e7030039c41a97e5e0c4ba4394b28548', 'masuk', '2026-06-02', 9, 1, '2026-06-02 22:48:01', '2026-06-02 23:03:01', 15, '2026-06-02 23:03:01', 1, 'http://localhost:8080/qr/generate/bd168664afaca7d045c16a58ce403878e7030039c41a97e5e0c4ba4394b28548', '771A71', 'layar_laptop', '2026-06-02 13:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `rekap_absensi_harian`
--

CREATE TABLE `rekap_absensi_harian` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `total_siswa` int(11) DEFAULT 0,
  `hadir` int(11) DEFAULT 0,
  `izin` int(11) DEFAULT 0,
  `sakit` int(11) DEFAULT 0,
  `alpa` int(11) DEFAULT 0,
  `terlambat` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `kode` enum('1','2') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`id`, `tahun_ajaran_id`, `nama`, `kode`, `tanggal_mulai`, `tanggal_selesai`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(3, 1, 'Semester 2', '1', '2027-01-17', '2027-07-20', 1, 1, '2026-05-16 21:29:34', 1, '2026-05-16 21:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nis` varchar(20) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `no_hp_ortu` varchar(20) DEFAULT NULL,
  `token_izin` varchar(64) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `qr_code` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `user_id`, `nis`, `nisn`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `no_hp_ortu`, `token_izin`, `email`, `qr_code`, `foto`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_at`) VALUES
(1, 2, '202311039', '1234567891', 'Amin Rais', 'L', 'Manggelum', '2000-05-10', 'Namas', '20300010010', '+6282148415635', '00b6b9efc19aa13d39bce1e5a7988829', 'wambongiwop49@gmail.com', 'QR-9BD1D381BD7ECE2EE58B4B23113C9722', NULL, 1, 1, '2026-05-16 06:52:01', 1, '2026-06-01 10:42:51', NULL),
(5, 15, '2024002', '0012345679', 'Budi Santoso', 'L', 'Jayapura', '2005-08-20', 'Jl. Sentani Raya No. 8, Jayapura', '081344556677', NULL, NULL, 'budi.santoso@smks.sch.id', 'QR-C6E7C54E1B0B7FF8', NULL, 1, 1, '2026-05-20 04:53:19', 1, '2026-05-29 09:13:51', NULL),
(6, 19, '2024003', '0012345680', 'Citra Lestari', 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-4912A3E7AEE209E4', NULL, 1, 1, '2026-05-20 04:53:19', 1, '2026-06-08 01:36:22', NULL),
(7, NULL, '2024004', '0012345681', 'Dewi Anggraini', 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-DC04E94A68FB5AB8', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(8, NULL, '2024005', '0012345682', 'Eko Pratama', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-A56554E853783854', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(9, NULL, '2024006', '0012345683', 'Fajar Nugroho', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-2C33EC0D85F4363C', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(10, NULL, '2024007', '0012345684', 'Gina Maharani', 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-D9F4A42217E1EDD1', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(11, NULL, '2024008', '0012345685', 'Hendra Saputra', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-9972D639255D9706', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(12, NULL, '2024009', '0012345686', 'Intan Permata', 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-8B389CE3E899C731', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(13, NULL, '2024010', '0012345687', 'Joko Susilo', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-C07B3750682A69D0', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(14, NULL, '2024011', '0012345688', 'Kartika Sari', 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-4CB2D8411DFE84E5', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(15, NULL, '2024012', '0012345689', 'Lukman Hakim', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-C805B9B3C50EFCD3', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(16, NULL, '2024013', '0012345690', 'Maya Putri', 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-3FEC8AECBB9C02D8', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(17, NULL, '2024014', '0012345691', 'Nanda Wijaya', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-93C879EC4D946DCF', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(18, NULL, '2024015', '0012345692', 'Oktavia Sari', 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-D911452976C25309', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(19, NULL, '2024016', '0012345693', 'Prasetyo Adi', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-CC39A3BF3EDFF5A3', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(20, NULL, '2024017', '0012345694', 'Qori Aulia', 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-5E92C862FC3905C3', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(21, NULL, '2024018', '0012345695', 'Rian Kurniawan', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-3B4AA7620218F5E3', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(22, NULL, '2024019', '0012345696', 'Siti Aisyah', 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-D07FB3922D09BE6E', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL),
(23, NULL, '2024020', '0012345697', 'Teguh Firmansyah', 'L', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QR-04A78F1A279A7149', NULL, 1, 1, '2026-05-20 04:53:19', NULL, '2026-05-20 04:53:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id`, `nama`, `tanggal_mulai`, `tanggal_selesai`, `is_active`, `created_by`, `created_at`) VALUES
(1, '2026/2027', '2026-05-17', '2026-05-17', 1, 1, '2026-05-16 12:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nuptk` varchar(30) DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `username`, `password`, `nama_lengkap`, `nuptk`, `nip`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `email`, `no_hp`, `foto`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`, `deleted_at`, `reset_token`) VALUES
(1, 1, 'admin', '$2y$10$HHMPOSiGJi48hxcxTUhvNuw3X0jo6uXTp1EQ.ERnQaWXEY3KdNieC', 'Nahas Giwop', NULL, NULL, NULL, NULL, NULL, 'Jl. Ba', 'admin@smks.sch.id', '082148455789', 'user_1_1780887720.jpg', 1, NULL, '2026-05-16 09:54:34', NULL, '2026-06-08 03:02:00', NULL, NULL),
(2, 3, 'siswa1', '$2y$10$D6JY9Y3qI2mlyBvfvif9vejjIt8w7Sz28lg7Q.mzsnbe2eo0m1hzW', 'Amin Rais', '1234567890123456', '20231102758', 'L', NULL, NULL, 'Nama', 'wambongiwop9@gmail.com', '20300010010', NULL, 1, NULL, '2026-05-16 07:21:42', NULL, '2026-05-19 13:27:45', NULL, NULL),
(8, 4, 'kepsek', '$2y$10$Oi0G15AvTHxryBclublcu.kQfLXiKj6RH.DyXaETtd6y7S/zdwZaO', 'Agus Jancuk', '1234567890123457', '198001012030011007', 'L', NULL, NULL, 'Jln. Ottonom', 'jhon89@gmail.com', '20300010018', NULL, 1, NULL, '2026-05-17 16:44:32', NULL, '2026-05-18 04:49:47', NULL, '5ef6c2d5c4334250d1908950a84333bdca9172e85b8d71b10366d685143d3983'),
(9, 2, 'guru1', '$2y$10$J06wGPU6cetuvjxex9lHi.88xNlmnrS89TUzFwrcz81rj0orLiqqC', 'GuruSystem', NULL, NULL, NULL, NULL, NULL, 'Jl. Markisa', 'guru@gmail.com', '20300010015', NULL, 1, NULL, '2026-05-17 22:38:41', NULL, '2026-05-19 13:20:37', NULL, NULL),
(10, 2, 'guru2', '$2y$10$hIENF3.2QGbjqJNiiSXGKecJLXapNXHokSAJjS8fLJD5kAt1eG8LC', 'Drs. Haryono, M.Pd', '1234567890123458', '197505151999031004', 'L', NULL, NULL, 'Jl. Pendidikan No. 20', 'haryono@smks.sch.id', '082155667788', NULL, 1, NULL, '2026-05-29 09:13:39', NULL, NULL, NULL, NULL),
(11, 2, 'guru3', '$2y$10$hIENF3.2QGbjqJNiiSXGKecJLXapNXHokSAJjS8fLJD5kAt1eG8LC', 'Siti Aminah, S.Kom', '1234567890123459', '198805102015042003', 'P', NULL, NULL, 'Jl. Anggrek No. 5', 'siti.aminah@smks.sch.id', '082166778899', NULL, 1, NULL, '2026-05-29 09:13:41', NULL, NULL, NULL, NULL),
(12, 2, 'guru4', '$2y$10$hIENF3.2QGbjqJNiiSXGKecJLXapNXHokSAJjS8fLJD5kAt1eG8LC', 'Robert Kurniawan, S.Ds', '1234567890123460', '199002102020011005', 'L', NULL, NULL, 'Jl. Mawar No. 12', 'robert.kurniawan@smks.sch.id', '082177889900', NULL, 1, NULL, '2026-05-29 09:13:43', NULL, NULL, NULL, NULL),
(13, 2, 'guru5', '$2y$10$hIENF3.2QGbjqJNiiSXGKecJLXapNXHokSAJjS8fLJD5kAt1eG8LC', 'Dra. Ratna Wulandari', '1234567890123461', '198207052010012004', 'P', NULL, NULL, 'Jl. Melati No. 3', 'ratna.wulandari@smks.sch.id', '082188990011', NULL, 1, NULL, '2026-05-29 09:13:44', NULL, NULL, NULL, NULL),
(14, 1, 'admin2', '$2y$10$hIENF3.2QGbjqJNiiSXGKecJLXapNXHokSAJjS8fLJD5kAt1eG8LC', 'Admin TU', NULL, NULL, NULL, NULL, NULL, NULL, 'admin.tu@smks.sch.id', '081234567890', NULL, 0, 1, '2026-05-29 09:13:46', NULL, '2026-05-29 02:18:08', NULL, NULL),
(15, 3, 'siswa5', '$2y$10$hIENF3.2QGbjqJNiiSXGKecJLXapNXHokSAJjS8fLJD5kAt1eG8LC', 'Budi Santoso', NULL, NULL, NULL, NULL, NULL, NULL, 'budi.santoso@smks.sch.id', NULL, NULL, 1, NULL, '2026-05-29 09:13:48', NULL, NULL, NULL, NULL),
(16, 3, 'siswa6', '$2y$10$hIENF3.2QGbjqJNiiSXGKecJLXapNXHokSAJjS8fLJD5kAt1eG8LC', 'Citra Lestari', NULL, NULL, NULL, NULL, NULL, NULL, 'citra.lestari@smks.sch.id', NULL, NULL, 1, NULL, '2026-05-29 09:13:48', NULL, '2026-06-08 01:36:44', '2026-06-08 01:36:44', NULL),
(17, 3, 'siswa7', '$2y$10$hIENF3.2QGbjqJNiiSXGKecJLXapNXHokSAJjS8fLJD5kAt1eG8LC', 'Dewi Anggraini', NULL, NULL, NULL, NULL, NULL, NULL, 'dewi.anggraini@smks.sch.id', NULL, NULL, 1, NULL, '2026-05-29 09:13:48', NULL, NULL, NULL, NULL),
(18, 3, 'siswa8', '$2y$10$hIENF3.2QGbjqJNiiSXGKecJLXapNXHokSAJjS8fLJD5kAt1eG8LC', 'Eko Pratama', NULL, NULL, NULL, NULL, NULL, NULL, 'eko.pratama@smks.sch.id', NULL, NULL, 1, NULL, '2026-05-29 09:13:48', NULL, NULL, NULL, NULL),
(19, 3, '2024003', '$2y$10$c6RYDyd8XgNP.AmCGSA10em4630XDDKbelaoZ4f0pjpyMoielS0/q', 'Citra Lestari', NULL, NULL, NULL, NULL, NULL, NULL, 'cintalestari29@gmail.com', '', NULL, 1, NULL, '2026-06-08 01:07:47', NULL, '2026-06-08 01:36:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_rekap_absen_sesi`
-- (See below for the actual view)
--
CREATE TABLE `v_rekap_absen_sesi` (
`qr_session_id` int(11)
,`tanggal` date
,`jam_mulai_absen` datetime
,`jam_selesai_absen` datetime
,`durasi_menit` int(11)
,`is_active` tinyint(1)
,`mata_pelajaran` varchar(100)
,`tingkat` varchar(5)
,`rombel` varchar(10)
,`jurusan` varchar(100)
,`guru_pengajar` varchar(100)
,`total_siswa_kelas` bigint(21)
,`total_sudah_absen` bigint(21)
,`total_hadir` bigint(21)
,`total_terlambat` bigint(21)
,`total_belum_absen` bigint(22)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_siswa_kelas_aktif`
-- (See below for the actual view)
--
CREATE TABLE `v_siswa_kelas_aktif` (
`kelas_siswa_id` int(11)
,`siswa_id` int(11)
,`kelas_id` int(11)
,`nis` varchar(20)
,`nama_lengkap` varchar(100)
,`jenis_kelamin` enum('L','P')
,`qr_code` text
,`foto` varchar(255)
,`no_hp` varchar(20)
,`email` varchar(100)
,`tingkat` varchar(5)
,`rombel` varchar(10)
,`nama_jurusan` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure for view `v_rekap_absen_sesi`
--
DROP TABLE IF EXISTS `v_rekap_absen_sesi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rekap_absen_sesi`  AS SELECT `qs`.`id` AS `qr_session_id`, `qs`.`tanggal` AS `tanggal`, `qs`.`jam_mulai_absen` AS `jam_mulai_absen`, `qs`.`jam_selesai_absen` AS `jam_selesai_absen`, `qs`.`durasi_menit` AS `durasi_menit`, `qs`.`is_active` AS `is_active`, `mp`.`nama` AS `mata_pelajaran`, `k`.`tingkat` AS `tingkat`, `k`.`rombel` AS `rombel`, `j`.`nama` AS `jurusan`, `u`.`nama_lengkap` AS `guru_pengajar`, count(distinct `vsk`.`siswa_id`) AS `total_siswa_kelas`, count(distinct `a`.`siswa_id`) AS `total_sudah_absen`, count(distinct case when `a`.`status_id` = 1 then `a`.`siswa_id` end) AS `total_hadir`, count(distinct case when `a`.`status_id` = 2 then `a`.`siswa_id` end) AS `total_terlambat`, count(distinct `vsk`.`siswa_id`) - count(distinct `a`.`siswa_id`) AS `total_belum_absen` FROM ((((((`qr_sessions` `qs` left join `mata_pelajaran` `mp` on(`qs`.`mata_pelajaran_id` = `mp`.`id`)) left join `kelas` `k` on(`qs`.`kelas_id` = `k`.`id`)) left join `jurusan` `j` on(`k`.`jurusan_id` = `j`.`id`)) left join `users` `u` on(`qs`.`dibuat_oleh` = `u`.`id`)) left join `v_siswa_kelas_aktif` `vsk` on(`vsk`.`kelas_id` = `qs`.`kelas_id`)) left join `absensi` `a` on(`a`.`qr_session_id` = `qs`.`id` and `a`.`siswa_id` = `vsk`.`siswa_id`)) GROUP BY `qs`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `v_siswa_kelas_aktif`
--
DROP TABLE IF EXISTS `v_siswa_kelas_aktif`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_siswa_kelas_aktif`  AS SELECT `ks`.`id` AS `kelas_siswa_id`, `ks`.`siswa_id` AS `siswa_id`, `ks`.`kelas_id` AS `kelas_id`, `s`.`nis` AS `nis`, `s`.`nama_lengkap` AS `nama_lengkap`, `s`.`jenis_kelamin` AS `jenis_kelamin`, `s`.`qr_code` AS `qr_code`, `s`.`foto` AS `foto`, `s`.`no_hp` AS `no_hp`, `s`.`email` AS `email`, `k`.`tingkat` AS `tingkat`, `k`.`rombel` AS `rombel`, `j`.`nama` AS `nama_jurusan` FROM (((`kelas_siswa` `ks` join `siswa` `s` on(`ks`.`siswa_id` = `s`.`id`)) join `kelas` `k` on(`ks`.`kelas_id` = `k`.`id`)) left join `jurusan` `j` on(`k`.`jurusan_id` = `j`.`id`)) WHERE `ks`.`status` = 'aktif' AND `s`.`is_active` = 1 AND `s`.`deleted_at` is null ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_absensi` (`siswa_id`,`qr_session_id`),
  ADD KEY `fk_absensi_jadwal` (`jadwal_id`),
  ADD KEY `fk_absensi_qr` (`qr_session_id`),
  ADD KEY `fk_absensi_petugas` (`petugas_id`),
  ADD KEY `fk_absensi_status` (`status_id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blocked_ips`
--
ALTER TABLE `blocked_ips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ip` (`ip_address`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_jadwal` (`kelas_id`,`semester_id`,`hari`,`jam_mulai`),
  ADD KEY `fk_jadwal_semester` (`semester_id`),
  ADD KEY `fk_jadwal_mapel` (`mata_pelajaran_id`),
  ADD KEY `fk_jadwal_guru` (`guru_id`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`),
  ADD KEY `fk_jurusan_updated` (`updated_by`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kelas_jurusan` (`jurusan_id`),
  ADD KEY `fk_kelas_updated` (`updated_by`);

--
-- Indexes for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_siswa_kelas` (`siswa_id`,`kelas_id`,`tahun_ajaran_id`),
  ADD KEY `fk_ks_kelas` (`kelas_id`),
  ADD KEY `fk_ks_tahun` (`tahun_ajaran_id`),
  ADD KEY `fk_ks_semester` (`semester_id`),
  ADD KEY `fk_ks_wali` (`wali_kelas_id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip` (`ip_address`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `log_absensi`
--
ALTER TABLE `log_absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_user` (`users_id`),
  ADD KEY `fk_log_absensi` (`absensi_id`);

--
-- Indexes for table `log_scan_qr`
--
ALTER TABLE `log_scan_qr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_logscan_session` (`qr_session_id`),
  ADD KEY `idx_logscan_siswa` (`siswa_id`),
  ADD KEY `idx_logscan_waktu` (`created_at`);

--
-- Indexes for table `master_role`
--
ALTER TABLE `master_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `master_status_absensi`
--
ALTER TABLE `master_status_absensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi_absensi`
--
ALTER TABLE `notifikasi_absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notif_user` (`dikirim_oleh`),
  ADD KEY `fk_notif_absensi` (`absensi_id`),
  ADD KEY `fk_notif_siswa` (`siswa_id`);

--
-- Indexes for table `notifikasi_siswa`
--
ALTER TABLE `notifikasi_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notif_siswa` (`siswa_id`),
  ADD KEY `fk_notif_session` (`qr_session_id`),
  ADD KEY `idx_notif_unread` (`siswa_id`,`is_dibaca`);

--
-- Indexes for table `pengaturan_sekolah`
--
ALTER TABLE `pengaturan_sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qr_sessions`
--
ALTER TABLE `qr_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `fk_qr_guru` (`dibuat_oleh`),
  ADD KEY `fk_qr_kelas` (`kelas_id`),
  ADD KEY `fk_qr_jadwal` (`jadwal_id`),
  ADD KEY `fk_qr_mapel` (`mata_pelajaran_id`);

--
-- Indexes for table `rekap_absensi_harian`
--
ALTER TABLE `rekap_absensi_harian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rekap` (`tanggal`,`kelas_id`),
  ADD KEY `fk_rekap_kelas` (`kelas_id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_semester` (`tahun_ajaran_id`,`kode`),
  ADD KEY `fk_semester_created` (`created_by`),
  ADD KEY `fk_semester_updated` (`updated_by`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `nisn` (`nisn`),
  ADD KEY `fk_siswa_created` (`created_by`),
  ADD KEY `fk_siswa_updated` (`updated_by`),
  ADD KEY `fk_siswa_user` (`user_id`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tahun_created` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `nuptk` (`nuptk`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `fk_users_role` (`role_id`),
  ADD KEY `fk_users_created` (`created_by`),
  ADD KEY `fk_users_updated` (`updated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `blocked_ips`
--
ALTER TABLE `blocked_ips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `log_absensi`
--
ALTER TABLE `log_absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_scan_qr`
--
ALTER TABLE `log_scan_qr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_role`
--
ALTER TABLE `master_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_status_absensi`
--
ALTER TABLE `master_status_absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `notifikasi_absensi`
--
ALTER TABLE `notifikasi_absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi_siswa`
--
ALTER TABLE `notifikasi_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

--
-- AUTO_INCREMENT for table `pengaturan_sekolah`
--
ALTER TABLE `pengaturan_sekolah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qr_sessions`
--
ALTER TABLE `qr_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `rekap_absensi_harian`
--
ALTER TABLE `rekap_absensi_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `fk_absensi_jadwal` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_absensi_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_absensi_qr` FOREIGN KEY (`qr_session_id`) REFERENCES `qr_sessions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_absensi_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_absensi_status` FOREIGN KEY (`status_id`) REFERENCES `master_status_absensi` (`id`);

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `fk_jadwal_guru` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_mapel` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD CONSTRAINT `fk_jurusan_updated` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `fk_kelas_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelas_updated` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD CONSTRAINT `fk_ks_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ks_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ks_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ks_tahun` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ks_wali` FOREIGN KEY (`wali_kelas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `log_absensi`
--
ALTER TABLE `log_absensi`
  ADD CONSTRAINT `fk_log_absensi` FOREIGN KEY (`absensi_id`) REFERENCES `absensi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `log_scan_qr`
--
ALTER TABLE `log_scan_qr`
  ADD CONSTRAINT `fk_lsq_session` FOREIGN KEY (`qr_session_id`) REFERENCES `qr_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lsq_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `notifikasi_absensi`
--
ALTER TABLE `notifikasi_absensi`
  ADD CONSTRAINT `fk_notif_absensi` FOREIGN KEY (`absensi_id`) REFERENCES `absensi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notif_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`dikirim_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `notifikasi_siswa`
--
ALTER TABLE `notifikasi_siswa`
  ADD CONSTRAINT `fk_ns_session` FOREIGN KEY (`qr_session_id`) REFERENCES `qr_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ns_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `qr_sessions`
--
ALTER TABLE `qr_sessions`
  ADD CONSTRAINT `fk_qr_guru` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_mapel` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `rekap_absensi_harian`
--
ALTER TABLE `rekap_absensi_harian`
  ADD CONSTRAINT `fk_rekap_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `semester`
--
ALTER TABLE `semester`
  ADD CONSTRAINT `fk_semester_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_semester_tahun` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_semester_updated` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `fk_siswa_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siswa_updated` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siswa_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD CONSTRAINT `fk_tahun_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `master_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_updated` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
