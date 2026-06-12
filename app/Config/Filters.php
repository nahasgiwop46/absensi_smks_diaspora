<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'            => CSRF::class,
        'toolbar'         => DebugToolbar::class,
        'honeypot'        => Honeypot::class,
        'invalidchars'    => InvalidChars::class,
        'secureheaders'   => SecureHeaders::class,
        'cors'            => Cors::class,
        'forcehttps'      => ForceHTTPS::class,
        'pagecache'       => PageCache::class,
        'performance'     => PerformanceMetrics::class,
        'auth'            => \App\Filters\AuthFilter::class,
        // ✅ KEAMANAN
        'security'        => \App\Filters\SecurityFilter::class,
        'loginRateLimit'  => \App\Filters\LoginRateLimitFilter::class,
    ];

    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
            'secureheaders',  // ✅ Security headers global
        ],
    ];

    public array $globals = [
        'before' => [
            'csrf' => ['except' => [
                'guru/scan/proses',
                'siswa/scan/verifikasi',
                'siswa/scan/konfirmasi',
                'siswa/absensi/konfirmasi',
                'siswa/absensi/getStatusHariIni',
            ]],
            'invalidchars',
            'security',  // ✅ Filter keamanan global
        ],
        'after' => [
            'toolbar',
            'security',  // ✅ Security headers
        ],
    ];

    public array $methods = [];

    public array $filters = [
        // Login rate limit
        'loginRateLimit' => [
            'before' => ['auth/authenticate'],
        ],

        // Auth filters
        'auth:admin' => [
            'before' => [
                'admin/*',
                'admin/role/*',
                'admin/user/*',
                'admin/keamanan/*',
            ],
        ],

       // ✅ GABUNG JADI SATU
    'auth:admin,guru' => [
        'before' => [
            'guru/*',
            'absensi/*',
            'jadwal/*',
            'api/*',
        ],
    ],

        'auth:siswa' => [
            'before' => [
                'siswa/*',
                'scan/*',
            ],
        ],

        'auth:admin,kepsek' => [
            'before' => [
                'kepsek/*',
            ],
        ],

        'auth:admin,guru,kepsek' => [
            'before' => [
                'laporan/*',
            ],
        ],

        'auth:admin,guru,kepsek,siswa' => [
            'before' => [
                'dashboard',
                'profile/*',
            ],
        ],

       
    ];
}