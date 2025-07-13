<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// === ROUTE PUBLIK (tanpa sesi) ===
// Tambahkan filter untuk mencegah akses yang tidak diinginkan
$routes->get('/', 'Home::index', [
    'filter' => [
        'secureheaders',     // Tambahkan header keamanan
        'honeypot',          // Tambahan filter honeypot untuk mencegah bot
    ]
]);

$routes->get('/login', 'Home::login', [
    'filter' => [
        'secureheaders',     // Header keamanan
        //'throttle',          // Batasi jumlah percobaan login
        'honeypot'           // Tambahan perlindungan bot
    ]
]);

$routes->get('/logout', 'Home::logout');

$routes->post('/login', 'Home::prosesLogin', [
    'filter' => [
        //'csrf',              // Proteksi CSRF
        //'throttle',          // Batasi percobaan login
        //'honeypot'           // Perlindungan bot tambahan
    ]
]);

$routes->get('/registrasi', 'Home::registrasi', [
    'filter' => [
        'secureheaders',     // Header keamanan
        'honeypot',          // Cegah bot
        'throttle'           // Batasi jumlah registrasi
    ]
]);

$routes->post('/registrasi', 'Home::prosesRegistrasi', [
    'filter' => [
        //'honeypot',          // Tambahan perlindungan bot
        //'throttle'           // Batasi percobaan registrasi
    ]
]);

// === ROUTE USER (wajib login dan role user) ===
$routes->group('user', [
    'filter' => [
        'userauth',      // Cek sesi dan role
        'secureheaders'      // Header keamanan
    ]
], function ($routes) {
    // Batasi akses ke fitur-fitur user
    $routes->get('dashboard', 'User\Dashboard::index');
    $routes->get('produk', 'User\Produk::index');
    $routes->get('penjualan', 'User\Penjualan::index');
    $routes->get('pengaturan', 'User\Pengaturan::index');
    $routes->get('connection', 'User\SosmedConnection::index');

    // Tambahkan filter khusus untuk setiap rute jika diperlukan
    $routes->post('produk/tambah', 'User\Produk::tambah', [
        'filter' => [
            'csrf',          // Proteksi CSRF untuk aksi tambah
        ]
    ]);
});


$routes->group('kelola-produk', [
    'filter' => [
        'userauth',      // Cek sesi dan role
        'secureheaders'      // Header keamanan
    ]
], function ($routes) {
    $routes->get('GetAll', 'User\Produk::GetAll');
    $routes->post('Add', 'User\Produk::Add');
    $routes->post('updateStatus/(:any)', 'User\Produk::updateStatus/$1');
    $routes->post('Edit/(:any)', 'User\Produk::Edit/$1');
    $routes->post('Delete/(:any)', 'User\Produk::Delete/$1');
    $routes->get('search-products', 'User\Produk::searchProducts');
    $routes->get('filter-products', 'User\Produk::filterProducts');
    $routes->get('search-and-filter', 'User\Produk::searchAndFilter');
    $routes->get('filter-options', 'User\Produk::getFilterOptions');
});

// === ROUTE ADMIN (wajib login dan role admin) ===
$routes->group('admin', [
    'filter' => [
        'sessionCheck:1',      // Cek sesi dan role admin
        'secureheaders',     // Header keamanan
        'admin_access'       // Filter khusus untuk admin
    ]
], function ($routes) {

    // Tambahkan rute admin dengan filter tambahan
    $routes->post('user/delete', 'Admin\User::delete', [
        'filter' => [
            'csrf',          // Proteksi CSRF
            'throttle'       // Batasi aksi penghapusan
        ]
    ]);
});


// === ROUTE Connection ===

$routes->group('Connection', [
    'filter' => [
        'userauth',      // Cek sesi dan role
        'secureheaders',     // Header keamanan
        //'throttle',          // Batasi jumlah percobaan login
        'honeypot'           // Tambahan perlindungan bot
    ]
], function ($routes) {
    $routes->get('WhatsApp/(:any)', 'Connection\WhatsApp::index/$1');
    $routes->post('WhatsApp/Create/(:any)', 'Connection\WhatsApp::Create/$1');
    $routes->post('WhatsApp/Delete/(:any)', 'Connection\WhatsApp::Delete/$1');
});
