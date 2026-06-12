<?php

namespace App\Controllers;

class NotifikasiWhatsApp extends BaseController
{
    // ✅ Ambil dari .env atau config
    private $apiKey;
    private $countryCode = '62';
    
    public function __construct()
    {
        $this->apiKey = getenv('FONNTE_API_KEY') ?: 'Qp1NZuApeQUQjnCbGfX5';
    }
    
    /**
     * Kirim pesan WhatsApp via Fonnte API
     */
    private function kirimWa($target, $pesan)
    {
        if (empty($target)) {
            log_message('error', 'NotifikasiWhatsApp: Nomor tujuan kosong');
            return false;
        }
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target'      => $target,
                'message'     => $pesan,
                'countryCode' => $this->countryCode,
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->apiKey
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            log_message('error', 'NotifikasiWhatsApp: ' . $error);
            return false;
        }
        
        if ($httpCode != 200) {
            log_message('error', 'NotifikasiWhatsApp: HTTP ' . $httpCode . ' - ' . $response);
            return false;
        }
        
        return true;
    }
    
    public function kirimNotifAlpa($siswaId)
    {
        $db = \Config\Database::connect();
        $siswa = $db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        
        if (!$siswa || empty($siswa['no_hp_ortu'])) {
            return false;
        }
        
        // Generate token izin
        $token = bin2hex(random_bytes(16));
        $db->table('siswa')->where('id', $siswaId)->update(['token_izin' => $token]);
        
        $linkIzin = base_url("izin/{$token}");
        
        $pesan = "📢 *PEMBERITAHUAN ABSENSI*\n\n"
               . "Yth. Orang Tua dari *{$siswa['nama_lengkap']}*\n\n"
               . "Anak Anda tercatat *ALPA* pada:\n"
               . "📅 Tanggal: " . date('d/m/Y') . "\n\n"
               . "Jika sakit atau ada keperluan, silakan klik link izin:\n"
               . "🔗 {$linkIzin}\n\n"
               . "Terima kasih.";
        
        return $this->kirimWa($siswa['no_hp_ortu'], $pesan);
    }
    
    // Kirim notif ke SEMUA siswa Alpa
    public function kirimSemuaAlpa()
    {
        $db = \Config\Database::connect();
        
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jayapura'));
        $tanggal = $now->format('Y-m-d');
        
        $semuaSiswa = $db->table('siswa')
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->get()
            ->getResultArray();
        
        $terkirim = 0;
        foreach ($semuaSiswa as $s) {
            $absen = $db->table('absensi')
                ->where('siswa_id', $s['id'])
                ->where('tanggal', $tanggal)
                ->where('deleted_at', null)
                ->get()
                ->getRowArray();
            
            if (!$absen && !empty($s['no_hp_ortu'])) {
                $this->kirimNotifAlpa($s['id']);
                $terkirim++;
            }
        }
        
        echo "Notif terkirim ke {$terkirim} orang tua.";
    }

    public function kirimNotifHadir($siswaId)
    {
        $db = \Config\Database::connect();
        $siswa = $db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        
        if (!$siswa || empty($siswa['no_hp_ortu'])) {
            return false;
        }
        
        $pesan = "✅ *ABSENSI HADIR*\n\n"
               . "Yth. Orang Tua dari *{$siswa['nama_lengkap']}*\n\n"
               . "Anak Anda telah *HADIR* di sekolah pada:\n"
               . "📅 Tanggal: " . date('d/m/Y') . "\n"
               . "⏰ Jam: " . date('H:i') . " WIT\n\n"
               . "Terima kasih.";
        
        return $this->kirimWa($siswa['no_hp_ortu'], $pesan);
    }

    public function kirimNotifIzin($siswaId, $alasan)
    {
        $db = \Config\Database::connect();
        $siswa = $db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        
        if (!$siswa || empty($siswa['no_hp_ortu'])) {
            return false;
        }
        
        $pesan = "📝 *ABSENSI IZIN*\n\n"
               . "Yth. Orang Tua dari *{$siswa['nama_lengkap']}*\n\n"
               . "Anak Anda tercatat *IZIN* pada:\n"
               . "📅 Tanggal: " . date('d/m/Y') . "\n"
               . "📋 Alasan: {$alasan}\n\n"
               . "Terima kasih.";
        
        return $this->kirimWa($siswa['no_hp_ortu'], $pesan);
    }

    public function kirimNotifSakit($siswaId)
    {
        $db = \Config\Database::connect();
        $siswa = $db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        
        if (!$siswa || empty($siswa['no_hp_ortu'])) {
            return false;
        }
        
        $pesan = "🏥 *ABSENSI SAKIT*\n\n"
               . "Yth. Orang Tua dari *{$siswa['nama_lengkap']}*\n\n"
               . "Anak Anda tercatat *SAKIT* pada:\n"
               . "📅 Tanggal: " . date('d/m/Y') . "\n\n"
               . "Semoga lekas sembuh. 🙏\n\n"
               . "Terima kasih.";
        
        return $this->kirimWa($siswa['no_hp_ortu'], $pesan);
    }
}