<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Jualin App</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/STORAGE/una-allert/una-allert.css">
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
        <main class="flex-grow flex items-center justify-center">
            <div class="max-w-md w-full py-12 px-6 sm:px-8 lg:px-10">
                <div class="bg-white shadow-lg rounded-lg px-8 py-10">
                    <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Masuk ke Akun Anda</h2>
                    
                    <form id="loginForm" class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" id="email" name="email" required class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="email@example.com">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" id="password" name="password" required class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3" placeholder="Masukkan password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 cursor-pointer toggle-password"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">

                            <div class="text-sm">
                                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Lupa password?</a>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Masuk
                            </button>
                        </div>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-600">
                        Belum punya akun?
                        <a href="<?= base_url('registrasi') ?>" class="font-medium text-indigo-600 hover:text-indigo-500">Daftar di sini</a>
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
        <!-- Panggil file JS unaAllert -->
        <script src="/STORAGE/una-allert/una-allert.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                unaAlert.init();
            });
        </script>
        <script>
            document.getElementById('loginForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                const loading = unaAlert.loading('Sedang mengirim data...', 'Mohon tunggu sebentar...');
                fetch('<?= base_url('login') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-Token': '<?= csrf_hash() ?>'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        loading.close();
                        if (data.status === 'success') {
                            window.location.href = '<?= base_url('user/dashboard') ?>';
                        } else {
                            unaAlert.show('error', 'Gagal Login', data.message);
                        }
                    })
                    .catch(error => {
                        loading.close();
                        unaAlert.show('error', 'Terjadi kesalahan. Silakan coba lagi. :', error);
                    });
            });
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(function(icon) {
                icon.addEventListener('click', function() {
                    const input = this.closest('.relative').querySelector('input');
                    const isVisible = input.type === 'text';
                    input.type = isVisible ? 'password' : 'text';
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            });
        </script>
    </div>
</body>

</html>