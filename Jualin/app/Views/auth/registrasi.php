<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran - Jualin App</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/brands.min.css">
    <link rel="stylesheet" href="/STORAGE/una-allert/una-allert.css">

    <style>
        .progress-step {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #6B7280;
        }
        
        .progress-step.active {
            background-color: #4F46E5;
            color: white;
        }
        
        .progress-step.completed {
            background-color: #10B981;
            color: white;
        }
        
        .progress-line {
            height: 2px;
            flex-grow: 1;
            background-color: #E5E7EB;
        }
        
        .progress-line.active {
            background-color: #4F46E5;
        }
        
        .form-step {
            display: none;
        }
        
        .form-step.active {
            display: block;
        }
        
        .slide-in {
            animation: slideIn 0.4s ease-out forwards;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-center h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-indigo-600">Jualin App</h1>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <!-- Form Header -->
                    <div class="px-6 py-8 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900 text-center">Buat Akun Baru</h2>
                        <p class="mt-2 text-sm text-gray-600 text-center">Silakan lengkapi data berikut untuk mendaftar di Jualin App</p>
                        
                        <!-- Progress Indicator -->
                        <div class="flex items-center justify-between mt-8 px-4">
                            <div class="progress-step active" id="step-1">1</div>
                            <div class="progress-line" id="line-1-2"></div>
                            <div class="progress-step" id="step-2">2</div>
                            <div class="progress-line" id="line-2-3"></div>
                            <div class="progress-step" id="step-3">3</div>
                            <div class="progress-line" id="line-3-4"></div>
                            <div class="progress-step" id="step-4">4</div>
                        </div>
                    </div>

                    <!-- Form Steps -->
                    <div class="px-6 py-8">
                        <!-- Step 1: Data Akun -->
                        <div class="form-step active slide-in" id="form-step-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Data Akun</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" id="email" name="email" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="email@example.com">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input type="password" id="password" name="password" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="Minimal 8 karakter">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <i class="fas fa-eye text-gray-400 cursor-pointer toggle-password"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input type="password" id="confirm-password" name="confirm-password" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="Konfirmasi password Anda">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 2: Data Pribadi -->
                        <div class="form-step" id="form-step-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Data Pribadi</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="nama-lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" id="nama-lengkap" name="nama-lengkap" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="Masukkan nama lengkap Anda">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="nomor-telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                        <input type="tel" id="nomor-telepon" name="nomor-telepon" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="contoh: 081234567890">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="tanggal-lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                        </div>
                                        <input type="date" id="tanggal-lahir" name="tanggal-lahir" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 3: Informasi Toko -->
                        <div class="form-step" id="form-step-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Toko</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="nama-toko" class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-store text-gray-400"></i>
                                        </div>
                                        <input type="text" id="nama-toko" name="nama-toko" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="Masukkan nama toko Anda">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="kategori-toko" class="block text-sm font-medium text-gray-700 mb-1">Kategori Toko</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tags text-gray-400"></i>
                                        </div>
                                        <select id="kategori-toko" name="kategori-toko" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3">
                                            <option value="" disabled selected>Pilih kategori toko</option>
                                            <option value="fashion">Fashion & Pakaian</option>
                                            <option value="elektronik">Elektronik</option>
                                            <option value="makanan">Makanan & Minuman</option>
                                            <option value="kecantikan">Kecantikan & Kesehatan</option>
                                            <option value="hobi">Hobi & Koleksi</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="deskripsi-toko" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Toko</label>
                                    <div class="relative">
                                        <div class="absolute top-3 left-3 pointer-events-none">
                                            <i class="fas fa-align-left text-gray-400"></i>
                                        </div>
                                        <textarea id="deskripsi-toko" name="deskripsi-toko" rows="3" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="Ceritakan tentang toko Anda"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 4: Alamat Toko -->
                        <div class="form-step" id="form-step-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Alamat Toko</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="alamat-lengkap" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                    <div class="relative">
                                        <div class="absolute top-3 left-3 pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        </div>
                                        <textarea id="alamat-lengkap" name="alamat-lengkap" rows="2" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="Masukkan alamat lengkap toko"></textarea>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-map text-gray-400"></i>
                                            </div>
                                            <select id="provinsi" name="provinsi" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3">
                                                <!-- Daftar provinsi Akan di load otomatis dan mengambil datanya dengan fetch -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-city text-gray-400"></i>
                                            </div>
                                            <select id="kota" name="kota" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3">
                                                <option value="" disabled selected>Pilih kota</option>
                                                <!-- Akan diisi secara dinamis berdasarkan provinsi -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="kode-pos" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-mail-bulk text-gray-400"></i>
                                        </div>
                                        <input type="number" id="kode-pos" name="kode-pos" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="Masukkan kode pos">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Registration Success Message (initially hidden) -->
                        <div class="form-step" id="registration-success">
                            <div class="text-center py-10">
                                <div class="mb-4 flex justify-center">
                                    <div class="bg-green-100 rounded-full p-3">
                                        <i class="fas fa-check-circle text-4xl text-green-500"></i>
                                    </div>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Pendaftaran Berhasil!</h3>
                                <p class="text-sm text-gray-600 mb-6">Akun Anda telah berhasil dibuat dan toko Anda siap digunakan</p>
                                <a href="login.html" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Masuk Sekarang
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Form Navigation -->
                    <div class="px-6 py-4 bg-gray-50 flex justify-between">
                        <button type="button" class="hidden py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="prev-btn">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </button>
                        <button type="button" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="next-btn">
                            Lanjutkan
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <button type="button" class="hidden py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="submit-btn">
                            Daftar Sekarang
                            <i class="fas fa-check ml-2"></i>
                        </button>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah memiliki akun?
                        <a href="<?=base_url('login')?>" class="font-medium text-indigo-600 hover:text-indigo-500">Masuk disini</a>
                    </p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-sm text-center text-gray-500">&copy; 2025 Jualin App. Hak Cipta Dilindungi.</p>
            </div>
        </footer>
       
    </div>
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/fontawesome.min.js"></script>
    <script src="/STORAGE/una-allert/una-allert.js"></script>
   <script>
        document.addEventListener('DOMContentLoaded', function() {
            unaAlert.init();
        });
    </script>
   <script>
        function alert(){
            unaAlert.show('success', 'Berhasil!', 'File Anda telah berhasil diunggah.');
        };
        // Form data storage
        const userData = {
            account: {},
            personal: {},
            store: {},
            address: {}
        };
        
        // DOM Elements
        const nextBtn = document.getElementById('next-btn');
        const prevBtn = document.getElementById('prev-btn');
        const submitBtn = document.getElementById('submit-btn');
        const formSteps = document.querySelectorAll('.form-step');
        const progressSteps = [
            document.getElementById('step-1'),
            document.getElementById('step-2'),
            document.getElementById('step-3'),
            document.getElementById('step-4')
        ];
        const progressLines = [
            document.getElementById('line-1-2'),
            document.getElementById('line-2-3'),
            document.getElementById('line-3-4')
        ];
        
        let currentStep = 1;
        const totalSteps = 4;
        
        // Next button click handler
        nextBtn.addEventListener('click', () => {
            if (validateCurrentStep()) {
                saveCurrentStepData();
                
                if (currentStep < totalSteps) {
                    // Move to next step
                    currentStep++;
                    updateFormUI();
                } else {
                    // Submit form
                    simulateFormSubmission();
                }
            }
        });
        
        // Previous button click handler
        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                updateFormUI();
            }
        });
        
        // Submit button click handler
        submitBtn.addEventListener('click', () => {
            if (validateCurrentStep()) {
                unaAlert.confirm(
                    'Konfirmasi Tindakan', 
                    'Apakah Anda yakin ingin melanjutkan tindakan ini?',
                    function() {
                        saveCurrentStepData();
                        sendDataToCI4();
                    }
                );
            }
        });
        
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const passwordInput = this.closest('.relative').querySelector('input');
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle icon
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
             
                fetch('/STORAGE/regencies/provinces.json')
                .then(response => response.json())
                .then(data => {
                    const provinsiSelect = document.getElementById('provinsi');
                    provinsiSelect.innerHTML = '<option value="" disabled selected>Pilih provinsi</option>';
                    data.forEach(provinsi => {
                        const option = document.createElement('option');
                        option.value = provinsi.id;
                        option.textContent = provinsi.name;
                        provinsiSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Gagal memuat data provinsi:', error);
                });

            });

        // Province change handler
        document.getElementById('provinsi').addEventListener('change', function() {
            fetch(`/STORAGE/regencies/${this.value}.json`)
            .then(response => response.json())
            .then(data => {
                const kotaSelect = document.getElementById('kota');
                kotaSelect.innerHTML = '<option value="" disabled selected>Pilih kota</option>';
                data.forEach(kota => {
                    const option = document.createElement('option');
                    option.value = kota.id;
                    option.textContent = kota.name;
                    kotaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Gagal memuat data kota:', error);
            });
        });

        // Update UI based on current step
        function updateFormUI() {
            // Update form steps visibility
            formSteps.forEach((step, index) => {
                step.classList.remove('active', 'slide-in');
                
                if (index + 1 === currentStep) {
                    step.classList.add('active');
                    setTimeout(() => {
                        step.classList.add('slide-in');
                    }, 10);
                }
            });
            
            // Update progress indicators
            progressSteps.forEach((step, index) => {
                step.classList.remove('active', 'completed');
                
                if (index + 1 === currentStep) {
                    step.classList.add('active');
                } else if (index + 1 < currentStep) {
                    step.classList.add('completed');
                    step.innerHTML = '<i class="fas fa-check"></i>';
                } else {
                    step.textContent = index + 1;
                }
            });
            
            // Update progress lines
            progressLines.forEach((line, index) => {
                line.classList.remove('active');
                
                if (index + 1 < currentStep) {
                    line.classList.add('active');
                }
            });
            
            // Show/hide navigation buttons
            if (currentStep === 1) {
                prevBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
            }
            
            if (currentStep === totalSteps) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }
        
        // Validate current form step
        function validateCurrentStep() {
            let isValid = true;
            
            // Get all required fields in current step
            const currentFormStep = document.getElementById(`form-step-${currentStep}`);
            const requiredFields = currentFormStep.querySelectorAll('input, select, textarea');
            
            requiredFields.forEach(field => {
                // Reset field style
                field.classList.remove('border-red-500', 'ring-red-500');
                
                const errorMsg = field.parentElement.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
                
                // Check if field is empty
                if (field.value.trim() === '') {
                    field.classList.add('border-red-500', 'ring-red-500');
                    
                    // Add error message
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'mt-1 text-sm text-red-600 error-message';
                    errorDiv.textContent = 'Field ini wajib diisi';
                    field.parentElement.appendChild(errorDiv);
                    
                    isValid = false;
                }
                
                // Additional validation for email field
                if (field.id === 'email' && field.value.trim() !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(field.value)) {
                        field.classList.add('border-red-500', 'ring-red-500');
                        
                        const errorDiv = document.createElement('p');
                        errorDiv.className = 'mt-1 text-sm text-red-600 error-message';
                        errorDiv.textContent = 'Format email tidak valid';
                        field.parentElement.appendChild(errorDiv);
                        
                        isValid = false;
                    }
                }
                
                // Additional validation for password fields
                if (field.id === 'password' && field.value.trim() !== '') {
                    if (field.value.length < 8) {
                        field.classList.add('border-red-500', 'ring-red-500');
                        
                        const errorDiv = document.createElement('p');
                        errorDiv.className = 'mt-1 text-sm text-red-600 error-message';
                        errorDiv.textContent = 'Password minimal 8 karakter';
                        field.parentElement.appendChild(errorDiv);
                        
                        isValid = false;
                    }
                }
                
                if (field.id === 'confirm-password' && field.value.trim() !== '') {
                    const password = document.getElementById('password').value;
                    if (field.value !== password) {
                        field.classList.add('border-red-500', 'ring-red-500');
                        
                        const errorDiv = document.createElement('p');
                        errorDiv.className = 'mt-1 text-sm text-red-600 error-message';
                        errorDiv.textContent = 'Password tidak sama';
                        field.parentElement.appendChild(errorDiv);
                        
                        isValid = false;
                    }
                }
                
                // Additional validation for phone number
                if (field.id === 'nomor-telepon' && field.value.trim() !== '') {
                    const phoneRegex = /^[0-9]{10,13}$/;
                    if (!phoneRegex.test(field.value)) {
                        field.classList.add('border-red-500', 'ring-red-500');
                        
                        const errorDiv = document.createElement('p');
                        errorDiv.className = 'mt-1 text-sm text-red-600 error-message';
                        errorDiv.textContent = 'Format nomor telepon tidak valid';
                        field.parentElement.appendChild(errorDiv);
                        
                        isValid = false;
                    }
                }
                // Additional validation for address fields
                if (field.id === 'alamat-lengkap' && field.value.trim() !== '') {
                    if (field.value.length < 10) {
                        field.classList.add('border-red-500', 'ring-red-500');
                        
                        const errorDiv = document.createElement('p');
                        errorDiv.className = 'mt-1 text-sm text-red-600 error-message';
                        errorDiv.textContent = 'Alamat minimal 10 karakter';
                        field.parentElement.appendChild(errorDiv);
                        
                        isValid = false;
                    }
                }
            });
            return isValid;
        }
        // Save current step data
        function saveCurrentStepData() {
            const currentFormStep = document.getElementById(`form-step-${currentStep}`);
            const inputs = currentFormStep.querySelectorAll('input, select, textarea');
            
            inputs.forEach(input => {
                const value = input.type === 'checkbox' ? input.checked : input.value;
                
                // Store data in appropriate object based on current step
                switch(currentStep) {
                    case 1: // Account data
                        if(['email', 'password', 'confirm-password'].includes(input.id)) {
                            userData.account[input.id] = value;
                        }
                        break;
                        
                    case 2: // Personal data
                        if(['nama-lengkap', 'nomor-telepon', 'tanggal-lahir'].includes(input.id)) {
                            userData.personal[input.id] = value;
                        }
                        break;
                        
                    case 3: // Store data
                        if(['nama-toko', 'kategori-toko', 'deskripsi-toko'].includes(input.id)) {
                            userData.store[input.id] = value;
                        }
                        break;
                        
                    case 4: // Address data
                        if(['alamat-lengkap', 'provinsi', 'kota', 'kode-pos'].includes(input.id)) {
                            userData.address[input.id] = value;
                        }
                        break;
                }
            });
            
            console.log('Current userData:', userData); // For debugging
        }
        function sendDataToCI4() {
            const loading = unaAlert.loading('Sedang mengirim data...', 'Mohon tunggu sebentar...');
            
            fetch('<?= base_url('registrasi') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(userData)
            })
            .then(response => response.json())
            .then(data => {
                loading.close();

                console.log('Response from server:', data);
                if (data.status === 'success') {
                    unaAlert.toast(
                        'success',
                        'Akunmu berhasil dibuat',
                        'Anda akan dialihkan ke halaman login dalam 3 detik.',
                        3000
                    );
                    setTimeout(() => {
                        window.location.href = '<?= base_url('login') ?>';
                    }, 3000);
                } else {
                    unaAlert.toast(
                        'error',
                        'Gagal Membuat Akun',
                        data.message,
                        3000
                    );
                }
            })
            .catch(error => {
                loading.close(); // pastikan loading ditutup di sini juga
                console.error('Fetch error:', error);
                unaAlert.toast(
                    'error',
                    'Terjadi kesalahan',
                    'Silakan coba lagi nanti.',
                    3000
                );
            });
        }


        // Simulate form submission
        function simulateFormSubmission() {
            setTimeout(() => {
                sendDataToCI4();
            }, 1000);
        }
    </script>
</body>
</html>