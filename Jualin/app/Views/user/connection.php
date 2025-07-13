<?= $this->extend('user/layout/main') ?>
<?= $this->section('content') ?>
<main class="flex-1 overflow-y-auto p-4 sm:p-6">
    <!-- WhatsApp Integration Section -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fab fa-whatsapp text-green-600 mr-2"></i>
                        WhatsApp Integration
                    </h2>
                    <button id="addDeviceBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Perangkat
                    </button>
                </div>

                <!-- Single Device Info -->
                <div id="deviceInfo" class="bg-gray-50 rounded-lg p-6 mb-4 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span id="deviceStatus" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Unknown
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nomor WhatsApp</p>
                            <p id="phoneNumber" class="text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Terakhir Aktif</p>
                            <p id="lastActive" class="text-gray-900">-</p>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-3">
                        <button id="openQR" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-qrcode mr-2"></i>
                            Show QR
                        </button>
                        <button id="deleteDeviceBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Perangkat
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code Modal -->
        <div id="qrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Scan QR Code</h3>
                            <button class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="qr-container p-4 bg-gray-100 rounded-lg">
                            <div id="qrCode" class="flex items-center justify-center">
                                <!-- QR Code will be inserted here -->
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 text-center">
                            Buka WhatsApp di ponsel Anda dan scan QR code ini
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    /**
     * WhatsApp Single Device Manager
     * Object-oriented JavaScript implementation for managing a single WhatsApp device
     */
    class DeviceManager {
        
        /**
         * Update button visibility based on device status
         */
        updateButtonVisibility() {
            if (!this.device) {
                return;
            }

            const isConnected = this.device.status === 'ready' || this.device.status === 'ready';

            // Show/hide the Show QR button
            if (this.elements.openQR) {
                if (isConnected) {
                    // Hide the QR button if connected
                    this.elements.openQR.classList.add('hidden');
                } else {
                    // Show the QR button if not connected
                    this.elements.openQR.classList.remove('hidden');
                }
            }

            // Show/hide the Delete Device button
            if (this.elements.deleteDeviceBtn) {
                if (isConnected) {
                    // Show delete button only when connected
                    this.elements.deleteDeviceBtn.classList.remove('hidden');
                } else {
                    // Hide delete button when not connected
                    this.elements.deleteDeviceBtn.classList.add('hidden');
                }
            }
        }
        constructor() {
            this.device = null;
            this.endpoints = {
                device: '<?= base_url('Connection/WhatsApp/' . $Account['AccountId']) ?>',
                create: '<?= base_url('Connection/WhatsApp/Create/' . $Account['AccountId']) ?>',
                delete: '<?= base_url('Connection/WhatsApp/Delete/') ?>'
            };

            this.elements = {
                deviceInfo: document.getElementById('deviceInfo'),
                addDeviceBtn: document.getElementById('addDeviceBtn'),
                deleteDeviceBtn: document.getElementById('deleteDeviceBtn'),
                openQR: document.getElementById('openQR'),
                deviceStatus: document.getElementById('deviceStatus'),
                phoneNumber: document.getElementById('phoneNumber'),
                lastActive: document.getElementById('lastActive'),
                qrModal: document.getElementById('qrModal'),
                qrCode: document.getElementById('qrCode')
            };

            this.init();
        }

        /**
         * Initialize the device manager
         */
        init() {
            // Load device data if exists
            this.loadDevice();

            // Set up event listeners
            this.setupEventListeners();
        }

        /**
         * Set up event listeners for UI elements
         */
        setupEventListeners() {
            // Add device button
            this.elements.addDeviceBtn.addEventListener('click', () => this.addDevice());

            // Delete device button
            if (this.elements.deleteDeviceBtn) {
                this.elements.deleteDeviceBtn.addEventListener('click', () => this.deleteDevice());
            }

            // Show QR button
            if (this.elements.openQR) {
                this.elements.openQR.addEventListener('click', () => this.showQRCode());
            }

            // Close QR modal when clicking the close button
            const closeQRBtn = this.elements.qrModal.querySelector('button');
            if (closeQRBtn) {
                closeQRBtn.addEventListener('click', () => this.closeQRModal());
            }
        }

        /**
         * Show QR code based on device status
         */
        showQRCode() {
            if (!this.device || !this.device.id) {
                alert('Tidak ada perangkat yang terdaftar');
                return;
            }

            // Use the device ID to create the QR scan URL
            const qrScanUrl = 'http://192.168.200.209:3000/qr-scanner/' + this.device.id;
            this.showQRModalWithUrl(qrScanUrl);
        }

        /**
         * Load single device from the API
         */
        loadDevice() {
            fetch(this.endpoints.device, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 404) {
                            console.log('No device found');
                            // No device found, hide device info and show add button
                            this.showAddDeviceUI();
                            return null;
                        }
                        throw new Error('Gagal memuat data perangkat');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.status === 'success' && data.devices && data.devices.length > 0) {
                        console.log('Device found:', data.devices[0]);
                        let foundDevice = data.devices[0];

                        this.device = {
                            id: foundDevice.deviceId,
                            status: foundDevice.status,
                            phoneNumber: foundDevice.phoneNumber || null,
                            lastActive: data.lastActive || null
                        };

                        this.updateDeviceUI();
                    } else if (data && data.status === 'error') {
                        console.error('Error loading device:', data);
                        this.showAddDeviceUI();
                    } else {
                        // No device or invalid response
                        this.showAddDeviceUI();
                    }
                })
                .catch(error => {
                    console.error('Error loading device:', error);
                    
                    this.showAddDeviceUI();
                });
        }

        /**
         * Show UI for adding a device (when no device exists)
         */
        showAddDeviceUI() {
            // Hide device info section if it exists
            if (this.elements.deviceInfo) {
                this.elements.deviceInfo.classList.add('hidden');
            }

            // Show add device button
            this.elements.addDeviceBtn.classList.remove('hidden');

            // Hide action buttons
            if (this.elements.openQR) {
                this.elements.openQR.classList.add('hidden');
            }

            if (this.elements.deleteDeviceBtn) {
                this.elements.deleteDeviceBtn.classList.add('hidden');
            }
        }

        /**
         * Show UI for existing device
         */
        showDeviceUI() {
            // Show device info section
            if (this.elements.deviceInfo) {
                this.elements.deviceInfo.classList.remove('hidden');
            }

            // Hide add device button if a device exists
            this.elements.addDeviceBtn.classList.add('hidden');

            // Update button visibility based on device status
            this.updateButtonVisibility();
        }

        /**
         * Add a new device
         */
        addDevice() {
            fetch(this.endpoints.create, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({})
                })
                .then(response => {
                    if (response.status === 409) {
                        throw new Error('Perangkat sudah terdaftar');
                    }
                    if (!response.ok) {
                        throw new Error('Gagal membuat perangkat');
                    }
                    return response.json();
                })
                .then(data => {
                    this.device = {
                        id: data.deviceId,
                        status: data.status,
                        phoneNumber: null,
                        lastActive: new Date()
                    };

                    this.updateDeviceUI();

                    this.showQRModalWithUrl(data.qrScanUrl);
                    
                })
                .catch(error => {
                    console.error('Error adding device:', error);
                    alert(error.message || 'Gagal menambahkan perangkat');
                });
        }

        /**
         * Delete the device
         */
        deleteDevice() {
            if (!this.device || !this.device.id) {
                alert('Tidak ada perangkat yang terdaftar');
                return;
            }

            if (confirm('Apakah Anda yakin ingin menghapus perangkat WhatsApp ini?')) {
                fetch(this.endpoints.delete + '/' + this.device.id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-Token': '<?= csrf_hash() ?>'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal menghapus perangkat');
                        }
                        return response.json();
                        
                    })
                    .then(data => {
                        console.log('Device deleted:', data);
                        if(data.status === 'success') {
                            this.device = null;
                        this.showAddDeviceUI();
                        alert('Perangkat berhasil dihapus');
                        } else {
                            alert('Gagal menghapus perangkat: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting device:', error);
                        alert(error.message);
                    });
            }
        }

        /**
         * Show QR code modal using a QR scan URL path
         * @param {string} qrScanUrl - Path to QR code scanning page
         */
        showQRModalWithUrl(qrScanUrl) {
            console.log('Showing QR modal with URL:', qrScanUrl);

            this.elements.qrModal.classList.remove('hidden');

            // Display embedded QR scan page in an iframe
            this.elements.qrCode.innerHTML = `
            <iframe src="${qrScanUrl}" width="280" height="280" frameborder="0"></iframe>
            <p class="mt-2 text-gray-700 text-center">Scan dengan aplikasi WhatsApp di ponsel Anda</p>
        `;
        }

        /**
         * Close the QR code modal
         */
        closeQRModal() {
            this.elements.qrModal.classList.add('hidden');
        }

        /**
         * Update the device information in the UI
         */
        updateDeviceUI() {
            if (!this.device) {
                this.showAddDeviceUI();
                return;
            }

            this.showDeviceUI();

            // Update device status
            if (this.elements.deviceStatus) {
                this.elements.deviceStatus.textContent = this.device.status;
                this.elements.deviceStatus.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${this.getStatusColor(this.device.status)}`;
            }

            // Update phone number
            if (this.elements.phoneNumber) {
                this.elements.phoneNumber.textContent = this.device.phoneNumber || '-';
            }

            // Update last active time
            if (this.elements.lastActive) {
                this.elements.lastActive.textContent = this.formatDate(this.device.lastActive);
            }

            // Toggle button visibility based on status
            this.updateButtonVisibility();
        }

        /**
         * Get the color class for a device status
         * @param {string} status - The device status
         * @returns {string} - Tailwind CSS classes for the status
         */
        getStatusColor(status) {
            const colors = {
                'Connected': 'bg-green-100 text-green-800',
                'connected': 'bg-green-100 text-green-800',
                'Disconnected': 'bg-red-100 text-red-800',
                'disconnected': 'bg-red-100 text-red-800',
                'Waiting for QR': 'bg-yellow-100 text-yellow-800',
                'waiting_for_qr': 'bg-yellow-100 text-yellow-800',
                'qr_ready': 'bg-yellow-100 text-yellow-800'
            };
            return colors[status] || 'bg-gray-100 text-gray-800';
        }

        /**
         * Format a date for display
         * @param {string|Date} date - The date to format
         * @returns {string} - Formatted date string
         */
        formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleString('id-ID');
        }
    }

    // Initialize the device manager when the DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        // Create and expose the device manager instance globally
        window.deviceManager = new DeviceManager();
    });
</script>
<?= $this->endSection() ?>