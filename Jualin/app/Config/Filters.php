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
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, class-string|list<class-string>>
     *
     * [filter_name => classname]
     * or [filter_name => [classname1, classname2, ...]]
     */
    public array $aliases = [
        // 🔐 Proteksi CSRF untuk mencegah serangan *Cross-Site Request Forgery*
        'csrf' => CSRF::class,

        // 🐞 Debug Toolbar bawaan CodeIgniter, muncul di bawah halaman saat mode development aktif
        'toolbar' => DebugToolbar::class,

        // 🐝 Perlindungan tambahan terhadap bot/spam dengan memasukkan field tersembunyi (honeypot) di form
        'honeypot' => Honeypot::class,

        // 🚫 Mencegah karakter tidak valid (misalnya karakter kontrol) dalam input HTTP
        'invalidchars' => InvalidChars::class,

        // 🛡️ Menambahkan header keamanan seperti `Content-Security-Policy`, `X-Frame-Options`, dll.
        'secureheaders' => SecureHeaders::class,

        // 🌍 Mengizinkan atau menolak permintaan lintas domain (Cross-Origin Resource Sharing)
        'cors' => Cors::class,

        // 🔐 Memaksa semua request menggunakan protokol HTTPS
        'forcehttps' => ForceHTTPS::class,

        // 🗃️ Meng-cache halaman (static page) untuk meningkatkan performa
        'pagecache' => PageCache::class,

        // 📊 Mengukur performa halaman (waktu proses, memori, dsb.)
        'performance' => PerformanceMetrics::class,

        // ⏱️ Membatasi jumlah permintaan (rate limiting), mencegah spam/penyalahgunaan
        'throttle' => \App\Filters\ThrottleFilter::class,

        // 🔐 Cek sesi aktif, validasi IP, user agent, dan token login
        'userauth' => \App\Filters\UserAuth::class,

        // 👮‍♂️ Filter khusus untuk memverifikasi bahwa pengguna adalah admin (role 1)
        'admin_access' => \App\Filters\AdminAccessFilter::class,
    ];



    /**
     * List of special required filters.
     *
     * The filters listed here are special. They are applied before and after
     * other kinds of filters, and always applied even if a route does not exist.
     *
     * Filters set by default provide framework functionality. If removed,
     * those functions will no longer work.
     *
     * @see https://codeigniter.com/user_guide/incoming/filters.html#provided-filters
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps', // Force Global Secure Requests
            'pagecache',  // Web Page Caching
        ],
        'after' => [
            'pagecache',   // Web Page Caching
            'performance', // Performance Metrics
            'toolbar',     // Debug Toolbar
        ],
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, list<string>>
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'POST' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [];
}
