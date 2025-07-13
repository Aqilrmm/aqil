<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Jualin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <link rel="stylesheet" href="/STORAGE/una-allert/una-allert.css">
    <style>
        .sidebar-item.active {
            background-color: #F3F4F6;
            border-left: 4px solid #4F46E5;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-indigo-600">Jualin</h1>
                </div>

                <div class="flex flex-col flex-grow overflow-y-auto">
                    <nav class="flex-1 px-2 py-4 space-y-1">

                        <!-- Dashboard -->
                        <a href="<?= base_url('user/dashboard') ?>"
                            class="flex items-center px-2 py-3 text-sm font-medium rounded-lg sidebar-item <?= ($title == 'Dashboard' ? 'text-gray-900 active' : 'text-gray-600 hover:bg-gray-100') ?>">
                            <i class="fas fa-tachometer-alt mr-3 text-gray-500"></i>
                            <span>Dashboard</span>
                        </a>

                        <!-- Produk -->
                        <a href="<?= base_url('user/produk') ?>"
                            class="flex items-center px-2 py-3 text-sm font-medium rounded-lg sidebar-item <?= ($title == 'Produk' ? 'text-gray-900 active' : 'text-gray-600 hover:bg-gray-100') ?>">
                            <i class="fas fa-box mr-3 text-gray-500"></i>
                            <span>Produk</span>
                        </a>

                        <!-- Penjualan -->
                        <a href="<?= base_url('user/penjualan') ?>"
                            class="flex items-center px-2 py-3 text-sm font-medium rounded-lg sidebar-item <?= ($title == 'Penjualan' ? 'text-gray-900 active' : 'text-gray-600 hover:bg-gray-100') ?>">
                            <i class="fas fa-shopping-cart mr-3 text-gray-500"></i>
                            <span>Penjualan</span>
                        </a>

                        <!-- Sosmed -->
                        <a href="<?= base_url('user/connection') ?>"
                            class="flex items-center px-2 py-3 text-sm font-medium rounded-lg sidebar-item <?= ($title == 'Sosmed Connection' ? 'text-gray-900 active' : 'text-gray-600 hover:bg-gray-100') ?>">
                            <i class="fas fa-globe mr-3 text-gray-500"></i>
                            <span>Sosmed Connection</span>
                        </a>

                        <!-- Pengaturan -->
                        <a href="<?= base_url('user/pengaturan') ?>"
                            class="flex items-center px-2 py-3 text-sm font-medium rounded-lg sidebar-item <?= ($title == 'Pengaturan' ? 'text-gray-900 active' : 'text-gray-600 hover:bg-gray-100') ?>">
                            <i class="fas fa-cog mr-3 text-gray-500"></i>
                            <span>Pengaturan Toko & Pembayaran</span>
                        </a>

                        <!-- Logout -->
                        <div class="pt-4 mt-4 border-t border-gray-200">
                            <a href="<?= base_url('logout') ?>"
                                class="flex items-center px-2 py-3 text-sm font-medium text-red-600 rounded-lg hover:bg-gray-100 sidebar-item" id="logout-btn">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </nav>
                </div>

            </div>
        </div>
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navbar -->
            <header class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-200 sm:px-6">
                <div class="flex items-center md:hidden">
                    <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600" id="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="flex-1 md:flex-none">
                    <h2 class="text-lg font-semibold text-gray-900 md:hidden">Jualin</h2>
                </div>
                <div class="flex items-center">
                    <div class="relative ml-3">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-full">
                                <span class="text-sm font-medium text-indigo-600">
                                    <?php
                                    $nama = trim($Account['FullName']);
                                    $kata = explode(' ', $nama);

                                    if (count($kata) === 1) {
                                        $inisial = strtoupper(substr($kata[0], 0, 1));
                                    } else {
                                        $inisial = strtoupper(substr($kata[0], 0, 1) . substr($kata[1], 0, 1));
                                    }

                                    echo $inisial; ?></span>
                            </div>
                            <span class="hidden text-sm font-medium text-gray-700 md:block"><?= $Account['FullName'] ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <?= $this->renderSection('content') ?>

        </div>

        <!-- Mobile Sidebar (hidden by default) -->
        <div class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 hidden" id="mobile-sidebar-overlay"></div>
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white transform -translate-x-full transition-transform ease-in-out duration-300" id="mobile-sidebar">
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-indigo-600">Jualin App</h1>
                <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none" id="close-sidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex flex-col flex-grow overflow-y-auto">
                <nav class="flex-1 px-2 py-4 space-y-1">
                    <a href="index.html" class="flex items-center px-2 py-3 text-sm font-medium text-gray-900 rounded-lg sidebar-item active">
                        <i class="fas fa-tachometer-alt mr-3 text-gray-500"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="produk.html" class="flex items-center px-2 py-3 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 sidebar-item">
                        <i class="fas fa-box mr-3 text-gray-500"></i>
                        <span>Produk</span>
                    </a>
                    <a href="penjualan.html" class="flex items-center px-2 py-3 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 sidebar-item">
                        <i class="fas fa-shopping-cart mr-3 text-gray-500"></i>
                        <span>Penjualan</span>
                    </a>
                    <a href="sosmed.html" class="flex items-center px-2 py-3 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 sidebar-item">
                        <i class="fas fa-globe mr-3 text-gray-500"></i>
                        <span>Sosmed Connection</span>
                    </a>
                    <a href="pengaturan.html" class="flex items-center px-2 py-3 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 sidebar-item">
                        <i class="fas fa-cog mr-3 text-gray-500"></i>
                        <span>Pengaturan Toko & Pembayaran</span>
                    </a>
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <a href="<?= base_url('logout') ?>" class="flex items-center px-2 py-3 text-sm font-medium text-red-600 rounded-lg hover:bg-gray-100 sidebar-item">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
        <script src="/STORAGE/una-allert/una-allert.js"></script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                unaAlert.init();
                <?php if (session()->getFlashdata('welcome')): ?>
                    unaAlert.toast('success', '<?= session()->getFlashdata('welcome') ?>', 'Berhasil Login');
                <?php endif; ?>
            });
            // Mobile sidebar toggle
            document.getElementById('sidebar-toggle').addEventListener('click', function() {
                document.getElementById('mobile-sidebar').classList.remove('-translate-x-full');
                document.getElementById('mobile-sidebar-overlay').classList.remove('hidden');
            });

            document.getElementById('close-sidebar').addEventListener('click', function() {
                document.getElementById('mobile-sidebar').classList.add('-translate-x-full');
                document.getElementById('mobile-sidebar-overlay').classList.add('hidden');
            });

            document.getElementById('mobile-sidebar-overlay').addEventListener('click', function() {
                document.getElementById('mobile-sidebar').classList.add('-translate-x-full');
                document.getElementById('mobile-sidebar-overlay').classList.add('hidden');
            });

            // Logout functionality
            document.getElementById('logout-btn').addEventListener('click', function(e) {
                unaAlert.confirm(
                    'Konfirmasi Logout',
                    'Apakah Anda yakin ingin keluar?',
                    function() {
                        // Redirect to logout URL
                        window.location.href = '<?= base_url('logout') ?>';
                    }
                )
            });
        </script>
        <?= $this->renderSection('scripts') ?>

</body>

</html>