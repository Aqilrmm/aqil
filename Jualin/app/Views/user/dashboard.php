<?= $this->extend('user/layout/main') ?>
<?= $this->section('content') ?>

<!-- Main content area -->
<main class="flex-1 overflow-y-auto p-4 sm:p-6">
    <div class="mb-6">
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Produk Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg card">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                        <i class="fas fa-box text-indigo-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Produk</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">24</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="produk.html" class="font-medium text-indigo-600 hover:text-indigo-500">Lihat semua produk <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg card">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <i class="fas fa-shopping-cart text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Penjualan</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">132</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="penjualan.html" class="font-medium text-indigo-600 hover:text-indigo-500">Lihat semua penjualan <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Interaksi AI Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg card">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                        <i class="fas fa-robot text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Interaksi AI</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">428</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <button onclick="showInteraksiModal()" class="font-medium text-indigo-600 hover:text-indigo-500">Lihat detail <i class="fas fa-arrow-right ml-1"></i></button>
                </div>
            </div>
        </div>

        <!-- Token Terpakai Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg card">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <i class="fas fa-key text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Token Terpakai</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">2,145</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <button onclick="showBillingModal()" class="font-medium text-indigo-600 hover:text-indigo-500">Lihat detail <i class="fas fa-arrow-right ml-1"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart section -->
    <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-2">
        <!-- Sales Chart -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900">Statistik Penjualan</h3>
                <div class="mt-4">
                    <canvas id="salesChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- AI Interaction Chart -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900">Interaksi AI</h3>
                <div class="mt-4">
                    <canvas id="aiChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="produk.html" class="bg-white overflow-hidden shadow rounded-lg p-6 hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-full p-3">
                        <i class="fas fa-plus text-indigo-600"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-base font-medium text-gray-900">Tambah Produk Baru</h4>
                        <p class="mt-1 text-sm text-gray-500">Buat produk baru untuk katalog Anda</p>
                    </div>
                </div>
            </a>
            <a href="sosmed.html" class="bg-white overflow-hidden shadow rounded-lg p-6 hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                        <i class="fab fa-whatsapp text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-base font-medium text-gray-900">Koneksi WhatsApp</h4>
                        <p class="mt-1 text-sm text-gray-500">Hubungkan akun WhatsApp Anda</p>
                    </div>
                </div>
            </a>
            <a href="pengaturan.html" class="bg-white overflow-hidden shadow rounded-lg p-6 hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                        <i class="fas fa-cog text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-base font-medium text-gray-900">Pengaturan Toko</h4>
                        <p class="mt-1 text-sm text-gray-500">Kelola informasi dan metode pembayaran</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</main>
</div>


<!-- Token & Billing Modal (Isi Ulang Token Saja) -->
<div id="BillingModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="w-full">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">
                        Informasi Token Anda
                    </h3>

                    <!-- Token Cards -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-indigo-50 p-4 rounded-lg shadow text-center">
                            <div class="text-indigo-600 text-2xl mb-1"><i class="fas fa-coins"></i></div>
                            <div class="text-xl font-semibold">2,145</div>
                            <div class="text-sm text-gray-600">Token Tersisa</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg shadow text-center">
                            <div class="text-green-600 text-2xl mb-1"><i class="fas fa-chart-line"></i></div>
                            <div class="text-xl font-semibold">7,855</div>
                            <div class="text-sm text-gray-600">Token Terpakai</div>
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                            <span>Penggunaan Token</span>
                            <span>7,855 / 10,000</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: 78.55%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="handleTopUp()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-white hover:bg-green-700 sm:ml-3 sm:w-auto text-sm">
                    <i class="fas fa-wallet mr-2"></i> Isi Ulang Token
                </button>
                <button type="button" onclick="hideBillingModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Interaksi Modal -->
<div id="InteraksiModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="w-full">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">
                        Detail Interaksi AI
                    </h3>

                    <!-- Summary Cards -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-indigo-50 p-4 rounded-lg shadow text-center">
                            <div class="text-indigo-600 text-2xl mb-1"><i class="fas fa-comments"></i></div>
                            <div class="text-xl font-semibold">484</div>
                            <div class="text-sm text-gray-600">Total Interaksi</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg shadow text-center">
                            <div class="text-green-600 text-2xl mb-1"><i class="fas fa-chart-bar"></i></div>
                            <div class="text-xl font-semibold">69</div>
                            <div class="text-sm text-gray-600">Rata-rata Harian</div>
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                            <span>Aktivitas Mingguan</span>
                            <span>484 interaksi</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: 68%"></div>
                        </div>
                    </div>

                    <!-- Interaksi AI Details -->
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-700 mb-3">Interaksi AI Anda selama minggu ini:</p>
                        <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                            <!-- Senin -->
                            <div class="flex items-center px-4 py-3 border-b border-gray-200 hover:bg-gray-100">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-indigo-700">Sen</span>
                                </div>
                                <div class="flex-grow">
                                    <div class="text-sm font-medium text-gray-800">Senin</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 46.7%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">42</span>
                                </div>
                            </div>
                            <!-- Selasa -->
                            <div class="flex items-center px-4 py-3 border-b border-gray-200 hover:bg-gray-100">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-indigo-700">Sel</span>
                                </div>
                                <div class="flex-grow">
                                    <div class="text-sm font-medium text-gray-800">Selasa</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 62.2%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">56</span>
                                </div>
                            </div>
                            <!-- Rabu -->
                            <div class="flex items-center px-4 py-3 border-b border-gray-200 hover:bg-gray-100">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-indigo-700">Rab</span>
                                </div>
                                <div class="flex-grow">
                                    <div class="text-sm font-medium text-gray-800">Rabu</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 86.7%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">78</span>
                                </div>
                            </div>
                            <!-- Kamis -->
                            <div class="flex items-center px-4 py-3 border-b border-gray-200 hover:bg-gray-100">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-indigo-700">Kam</span>
                                </div>
                                <div class="flex-grow">
                                    <div class="text-sm font-medium text-gray-800">Kamis</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 70%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">63</span>
                                </div>
                            </div>
                            <!-- Jumat -->
                            <div class="flex items-center px-4 py-3 border-b border-gray-200 hover:bg-gray-100">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-indigo-700">Jum</span>
                                </div>
                                <div class="flex-grow">
                                    <div class="text-sm font-medium text-gray-800">Jumat</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">90</span>
                                </div>
                            </div>
                            <!-- Sabtu -->
                            <div class="flex items-center px-4 py-3 border-b border-gray-200 hover:bg-gray-100">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-indigo-700">Sab</span>
                                </div>
                                <div class="flex-grow">
                                    <div class="text-sm font-medium text-gray-800">Sabtu</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 94.4%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">85</span>
                                </div>
                            </div>
                            <!-- Minggu -->
                            <div class="flex items-center px-4 py-3 hover:bg-gray-100">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-indigo-700">Min</span>
                                </div>
                                <div class="flex-grow">
                                    <div class="text-sm font-medium text-gray-800">Minggu</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 77.8%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">70</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="hideInteraksiModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-white hover:bg-green-700 sm:ml-3 sm:w-auto text-sm">
                    <i class="fas fa-check mr-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chartjs-plugin-datalabels.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chartjs-plugin-annotation.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chartjs-plugin-zoom.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chartjs-plugin-colorschemes.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chartjs-plugin-legend.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chartjs-plugin-legend-interaction.min.js"></script>
        <script>
            // Function to show interaksi modal
            function showInteraksiModal() {
                document.getElementById('InteraksiModal').classList.remove('hidden');
            }
            // Function to hide interaksi modal
            function hideInteraksiModal() {
                document.getElementById('InteraksiModal').classList.add('hidden');
            }
            // Function to show billing modal
            function showBillingModal() {
                document.getElementById('BillingModal').classList.remove('hidden');
            }
            // Function to hide billing modal
            function hideBillingModal() {
                document.getElementById('BillingModal').classList.add('hidden');
            }
            // Function to handle top-up token
            function handleTopUp() {
                alert('Fitur isi ulang token akan segera hadir!');
                hideBillingModal();
            }
        </script>

        <script>
            

            // Charts
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Penjualan Minggu Ini',
                        data: [12, 19, 8, 15, 20, 25, 18],
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            const aiCtx = document.getElementById('aiChart').getContext('2d');
            const aiChart = new Chart(aiCtx, {
                type: 'bar',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Interaksi AI',
                        data: [42, 56, 78, 63, 90, 85, 70],
                        backgroundColor: 'rgba(124, 58, 237, 0.7)',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>
<?= $this->endSection() ?>