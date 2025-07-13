<?= $this->extend('user/layout/main') ?>
<?= $this->section('content') ?>
<main class="flex-1 overflow-y-auto p-4 sm:p-6">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Transaksi</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola dan pantau semua transaksi penjualan Anda</p>
        </div>
        <div class="mt-4 md:mt-0">
            <button onclick="exportData()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-download mr-2"></i>
                Ekspor Data
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Date Range Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Tanggal</label>
                <div class="relative">
                    <input type="text" id="daterange" class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Pilih tanggal">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-calendar text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                <select id="status-filter" class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="berhasil">Berhasil</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                    <option value="gagal">Gagal</option>
                </select>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <div class="relative">
                    <input type="text" id="search" class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari produk atau nomor pembeli">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Produk
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal & Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nomor Pembeli
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="transactions-table">
                    <!-- Table content will be dynamically populated -->
                </tbody>
            </table>
        </div>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
<script>
        /**
         * Class TransactionManager - Mengelola data dan operasi transaksi
         */
        class TransactionManager {
            constructor() {
                // Data transaksi contoh
                this.transactions = [
                    {
                        product: "Produk A",
                        datetime: "2025-05-10 14:30",
                        status: "berhasil",
                        buyer: "6281234567890",
                        total: "Rp 150.000"
                    },
                    {
                        product: "Produk B",
                        datetime: "2025-05-09 09:15",
                        status: "pending",
                        buyer: "6289876543210",
                        total: "Rp 75.000"
                    },
                    {
                        product: "Produk C",
                        datetime: "2025-05-08 16:45",
                        status: "gagal",
                        buyer: "6287654321098",
                        total: "Rp 225.000"
                    },
                    {
                        product: "Produk A Premium",
                        datetime: "2025-05-07 11:20",
                        status: "berhasil",
                        buyer: "6282345678901",
                        total: "Rp 300.000"
                    },
                    {
                        product: "Produk D",
                        datetime: "2025-05-06 13:10",
                        status: "kadaluarsa",
                        buyer: "6285678901234",
                        total: "Rp 125.000"
                    }
                ];
            }

            /**
             * Mendapatkan semua data transaksi
             */
            getAllTransactions() {
                return this.transactions;
            }
            
            /**
             * Filter transaksi berdasarkan kriteria
             * @param {Object} filters - Kriteria filter
             * @returns {Array} Data yang sudah difilter
             */
            getFilteredTransactions(filters) {
                let filteredData = [...this.transactions];

                // Filter berdasarkan tanggal
                if (filters.dateStart && filters.dateEnd) {
                    filteredData = filteredData.filter(t => {
                        const transactionDate = moment(t.datetime);
                        return transactionDate.isSameOrAfter(filters.dateStart, 'day') && 
                               transactionDate.isSameOrBefore(filters.dateEnd, 'day');
                    });
                }

                // Filter berdasarkan status
                if (filters.status) {
                    filteredData = filteredData.filter(t => t.status === filters.status);
                }

                // Filter berdasarkan pencarian
                if (filters.search) {
                    const searchLower = filters.search.toLowerCase();
                    filteredData = filteredData.filter(t => 
                        t.product.toLowerCase().includes(searchLower) || 
                        t.buyer.toLowerCase().includes(searchLower)
                    );
                }

                return filteredData;
            }
            
            /**
             * Mengekspor data transaksi ke CSV
             * @param {Object} filters - Kriteria filter untuk data yang akan diekspor
             */
            exportToCSV(filters) {
                const filteredData = this.getFilteredTransactions(filters);
                
                // Format data untuk CSV
                const csvRows = [];
                // Header
                csvRows.push(['Nama Produk', 'Tanggal & Waktu', 'Status', 'Nomor Pembeli', 'Total'].join(','));
                
                // Data rows
                filteredData.forEach(t => {
                    csvRows.push([
                        t.product,
                        moment(t.datetime).format('DD/MM/YYYY HH:mm'),
                        t.status,
                        t.buyer,
                        t.total
                    ].join(','));
                });
                
                // Gabungkan semua rows dengan newline
                const csvContent = csvRows.join('\n');
                
                // Buat Blob dan download link
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.setAttribute('href', url);
                link.setAttribute('download', `transaksi_${moment().format('YYYYMMDD_HHmmss')}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        /**
         * Class UIManager - Mengelola tampilan antarmuka pengguna
         */
        class UIManager {
            constructor(transactionManager) {
                this.transactionManager = transactionManager;
                this.filters = {
                    dateStart: null,
                    dateEnd: null,
                    status: "",
                    search: ""
                };
                
                // Inisialisasi event listeners
                this.initEventListeners();
                this.initDatePicker();
            }

            /**
             * Inisialisasi date picker
             */
            initDatePicker() {
                const self = this;
                $('#daterange').daterangepicker({
                    opens: 'left',
                    locale: {
                        format: 'DD/MM/YYYY'
                    },
                    startDate: moment().subtract(7, 'days'),
                    endDate: moment()
                }, function(start, end) {
                    // Callback ketika tanggal dipilih
                    self.filters.dateStart = start;
                    self.filters.dateEnd = end;
                    self.updateTransactionList();
                });

                // Set nilai awal filter tanggal
                const dates = $('#daterange').data('daterangepicker');
                this.filters.dateStart = dates.startDate;
                this.filters.dateEnd = dates.endDate;
            }

            /**
             * Inisialisasi event listeners
             */
            initEventListeners() {
                const self = this;
                
                // Event listener untuk filter status
                document.getElementById('status-filter').addEventListener('change', function() {
                    self.filters.status = this.value;
                    self.updateTransactionList();
                });

                // Event listener untuk pencarian
                document.getElementById('search').addEventListener('input', function() {
                    self.filters.search = this.value;
                    self.updateTransactionList();
                });

                // Event listener untuk toggle sidebar di mobile
                document.getElementById('sidebar-toggle').addEventListener('click', function() {
                    const sidebar = document.querySelector('.md\\:flex.md\\:flex-shrink-0');
                    sidebar.classList.toggle('hidden');
                });

                // Event listener untuk logout
                document.getElementById('logout-btn').addEventListener('click', function(e) {
                    e.preventDefault();
                    self.handleLogout();
                });
            }

            /**
             * Handle aksi logout
             */
            handleLogout() {
                if (confirm('Apakah Anda yakin ingin keluar?')) {
                    alert('Logout berhasil');
                    // Redirect ke halaman login
                    // window.location.href = 'login.html';
                }
            }

            /**
             * Update tampilan daftar transaksi
             */
            updateTransactionList() {
                const filteredData = this.transactionManager.getFilteredTransactions(this.filters);
                this.renderTransactions(filteredData);
            }

            /**
             * Menampilkan data transaksi ke tabel
             * @param {Array} transactions - Data transaksi yang akan ditampilkan
             */
            renderTransactions(transactions) {
                const tableBody = document.getElementById('transactions-table');
                
                if (transactions.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data transaksi yang ditemukan
                            </td>
                        </tr>
                    `;
                    return;
                }

                tableBody.innerHTML = transactions.map(t => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${t.product}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${moment(t.datetime).format('DD/MM/YYYY HH:mm')}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${this.getStatusBadge(t.status)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${t.buyer}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${t.total}
                        </td>
                    </tr>
                `).join('');
            }

            /**
             * Mendapatkan badge untuk status transaksi
             * @param {string} status - Status transaksi
             * @returns {string} HTML badge untuk status
             */
            getStatusBadge(status) {
                const badges = {
                    pending: '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
                    berhasil: '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Berhasil</span>',
                    kadaluarsa: '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Kadaluarsa</span>',
                    gagal: '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Gagal</span>'
                };
                return badges[status] || badges.pending;
            }

            /**
             * Ekspor data transaksi yang sudah difilter
             */
            exportFilteredData() {
                this.transactionManager.exportToCSV(this.filters);
            }
        }

        /**
         * Class App - Kelas utama aplikasi
         */
        class App {
            constructor() {
                this.transactionManager = new TransactionManager();
                this.uiManager = new UIManager(this.transactionManager);
                
                // Export function to global scope untuk diakses oleh onclick button
                window.exportData = () => this.uiManager.exportFilteredData();
            }
            
            /**
             * Inisialisasi aplikasi
             */
            init() {
                this.uiManager.updateTransactionList();
            }
        }

        // Initialize app when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            const app = new App();
            app.init();
        });
    </script>
<?= $this->endSection() ?>