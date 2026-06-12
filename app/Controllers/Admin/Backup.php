<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Backup extends BaseController
{
    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $db = \Config\Database::connect();
        
        // ✅ Info database
        $tables = $db->listTables();
        $totalTables = count($tables);
        
        // ✅ Hitung total data
        $totalSiswa = $db->table('siswa')->where('deleted_at', null)->countAllResults();
        $totalGuru = $db->table('users')->where('role_id', 2)->where('deleted_at', null)->countAllResults();
        $totalAbsensi = $db->table('absensi')->where('deleted_at', null)->countAllResults();
        $totalQR = $db->table('qr_sessions')->countAllResults();

        // ✅ Daftar backup sebelumnya
        $backupPath = WRITEPATH . 'backups/';
        $existingBackups = [];
        if (is_dir($backupPath)) {
            $files = scandir($backupPath);
            foreach ($files as $file) {
                if (strpos($file, 'backup_') === 0) {
                    $existingBackups[] = [
                        'name' => $file,
                        'size' => $this->formatBytes(filesize($backupPath . $file)),
                        'date' => date('d/m/Y H:i:s', filemtime($backupPath . $file)),
                    ];
                }
            }
            // Sort terbaru di atas
            rsort($existingBackups);
        }

        $this->setPageTitle('Backup & Restore');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Backup');

        return $this->render('admin/backup/index', [
            'total_tables'    => $totalTables,
            'total_siswa'     => $totalSiswa,
            'total_guru'      => $totalGuru,
            'total_absensi'   => $totalAbsensi,
            'total_qr'        => $totalQR,
            'existing_backups' => $existingBackups,
        ]);
    }

    /**
     * Download backup lengkap (database + file)
     */
    public function downloadFull()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        try {
            $backupPath = WRITEPATH . 'backups/';
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $timestamp = date('Ymd_His');
            $backupFile = $backupPath . 'backup_' . $timestamp . '.zip';

            $zip = new ZipArchive();
            if ($zip->open($backupFile, ZipArchive::CREATE) !== true) {
                return redirect()->back()->with('error', 'Gagal membuat file backup');
            }

            // ✅ 1. Backup database ke SQL
            $sqlFile = $this->generateSQLBackup();
            $zip->addFromString('database.sql', $sqlFile);

            // ✅ 2. Backup file uploads
            $uploadsPath = FCPATH . 'uploads/';
            if (is_dir($uploadsPath)) {
                $this->addFolderToZip($zip, $uploadsPath, 'uploads/');
            }

            // ✅ 3. Backup file .env
            if (file_exists(ROOTPATH . '.env')) {
                $zip->addFile(ROOTPATH . '.env', '.env');
            }

            // ✅ 4. Info backup
            $info = json_encode([
                'tanggal'    => date('Y-m-d H:i:s'),
                'sistem'     => 'Absensi QR SMK',
                'version'    => '1.0.0',
                'php'        => PHP_VERSION,
                'database'   => 'absensi_smks_006',
                'total_siswa'=> $this->countTable('siswa'),
                'total_guru' => $this->countTable('users', ['role_id' => 2]),
            ], JSON_PRETTY_PRINT);
            $zip->addFromString('backup_info.json', $info);

            $zip->close();

            logActivity('backup', 'Download backup lengkap');

            return $this->response->download($backupFile, null);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal backup: ' . $e->getMessage());
        }
    }

    /**
     * Download database saja
     */
    public function downloadDatabase()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        try {
            $sql = $this->generateSQLBackup();
            
            $fileName = 'database_' . date('Ymd_His') . '.sql';
            
            logActivity('backup', 'Download backup database');

            return $this->response
                ->setHeader('Content-Type', 'application/sql')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->setBody($sql);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal backup database: ' . $e->getMessage());
        }
    }

    /**
     * Download data siswa (Excel)
     */
   public function downloadSiswa()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    try {
        $db = \Config\Database::connect();
        $siswa = $db->table('siswa')
            ->select('nis, nisn, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, no_hp, email')
            ->where('deleted_at', null)
            ->orderBy('nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'NISN');
        $sheet->setCellValue('C1', 'Nama Lengkap');
        $sheet->setCellValue('D1', 'JK');
        $sheet->setCellValue('E1', 'Tempat Lahir');
        $sheet->setCellValue('F1', 'Tanggal Lahir');
        $sheet->setCellValue('G1', 'Alamat');
        $sheet->setCellValue('H1', 'No HP');
        $sheet->setCellValue('I1', 'Email');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2563EB']],
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Data
        $row = 2;
        foreach ($siswa as $s) {
            $sheet->setCellValue('A' . $row, $s['nis']);
            $sheet->setCellValue('B' . $row, $s['nisn']);
            $sheet->setCellValue('C' . $row, $s['nama_lengkap']);
            $sheet->setCellValue('D' . $row, $s['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('E' . $row, $s['tempat_lahir']);
            $sheet->setCellValue('F' . $row, $s['tanggal_lahir']);
            $sheet->setCellValue('G' . $row, $s['alamat']);
            $sheet->setCellValue('H' . $row, $s['no_hp']);
            $sheet->setCellValue('I' . $row, $s['email']);
            $row++;
        }

        // Auto width
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'data_siswa_' . date('Ymd_His') . '.xlsx';
        $filePath = WRITEPATH . 'temp/' . $fileName;

        if (!is_dir(WRITEPATH . 'temp')) {
            mkdir(WRITEPATH . 'temp', 0755, true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filePath);

        logActivity('backup', 'Download data siswa Excel');

        return $this->response->download($filePath, null)->setFileName($fileName);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal download: ' . $e->getMessage());
    }
}
    /**
     * Download data absensi (per tanggal)
     */
    public function downloadAbsensi()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        try {
            $db = \Config\Database::connect();
            $absensi = $db->table('absensi a')
                ->select('a.tanggal, a.jam_absen, s.nis, s.nama_lengkap, msa.label as status, mp.nama as mapel, a.metode_absensi, a.menit_keterlambatan')
                ->join('siswa s', 's.id = a.siswa_id')
                ->join('master_status_absensi msa', 'msa.id = a.status_id')
                ->join('qr_sessions qs', 'qs.id = a.qr_session_id', 'left')
                ->join('mata_pelajaran mp', 'mp.id = qs.mata_pelajaran_id', 'left')
                ->where('a.tanggal', $tanggal)
                ->where('a.deleted_at', null)
                ->orderBy('a.jam_absen', 'DESC')
                ->get()
                ->getResultArray();

            $csv = "Tanggal,Jam,NIS,Nama,Status,Mapel,Metode,Terlambat\n";
            foreach ($absensi as $a) {
                $csv .= implode(',', array_map(function($val) {
                    return '"' . str_replace('"', '""', $val ?? '') . '"';
                }, $a)) . "\n";
            }

            $fileName = 'absensi_' . $tanggal . '.csv';

            logActivity('backup', 'Download data absensi: ' . $tanggal);

            return $this->response
                ->setHeader('Content-Type', 'text/csv')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->setBody($csv);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal download absensi: ' . $e->getMessage());
        }
    }

    /**
     * Hapus backup lama
     */
    public function hapusBackup($file)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $file = basename($file); // Cegah path traversal
        $backupPath = WRITEPATH . 'backups/' . $file;

        if (file_exists($backupPath) && strpos($file, 'backup_') === 0) {
            unlink($backupPath);
            return redirect()->back()->with('success', 'Backup dihapus.');
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    // ==================== HELPER ====================

   private function generateSQLBackup(): string
{
    $db = \Config\Database::connect();
    $database = $db->getDatabase();
    $tables = $db->listTables();

    $sql = "-- Backup Database: {$database}\n";
    $sql .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n";
    $sql .= "-- Sistem: Absensi QR SMK\n\n";
    $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

    foreach ($tables as $table) {
        // ✅ Ambil create table
        $result = $db->query("SHOW CREATE TABLE `{$table}`")->getRowArray();
        $createSQL = $result['Create Table'] ?? '';
        
        if (empty($createSQL)) {
            // Fallback: ambil kolom manual
            $columns = $db->getFieldData($table);
            $createSQL = "CREATE TABLE `{$table}` (\n";
            $colDefs = [];
            foreach ($columns as $col) {
                $colDefs[] = "  `{$col->name}` {$col->type}";
            }
            $createSQL .= implode(",\n", $colDefs) . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        }
        
        $sql .= $createSQL . ";\n\n";

        // ✅ Insert data (max 1000 rows per insert)
        $totalRows = $db->table($table)->countAllResults();
        if ($totalRows > 0) {
            $offset = 0;
            $limit = 500;
            
            while ($offset < $totalRows) {
                $rows = $db->table($table)->get($limit, $offset)->getResultArray();
                if (!empty($rows)) {
                    $sql .= "INSERT INTO `{$table}` VALUES\n";
                    $values = [];
                    foreach ($rows as $row) {
                        $vals = array_map(function($val) use ($db) {
                            if ($val === null) return 'NULL';
                            return "'" . $db->escapeString((string)$val) . "'";
                        }, array_values($row));
                        $values[] = '(' . implode(', ', $vals) . ')';
                    }
                    $sql .= implode(",\n", $values) . ";\n\n";
                }
                $offset += $limit;
            }
        }
    }

    $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

    return $sql;
}
    private function addFolderToZip($zip, $source, $prefix)
    {
        if (!is_dir($source)) return;

        $dir = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relativePath = $prefix . substr($filePath, strlen($source));

            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    private function countTable($table, $where = []): int
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table);
        if (!empty($where)) {
            $builder->where($where);
        }
        return $builder->countAllResults();
    }

    private function formatBytes($bytes): string
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' bytes';
    }
}