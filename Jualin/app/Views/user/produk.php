<?= $this->extend('user/layout/main') ?>
<?= $this->section('content') ?>
<main class="flex-1 overflow-y-auto p-4 sm:p-6">
    <!-- Search and Filter -->
    <div class="mb-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari produk..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div class="flex space-x-4">
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
                <button onclick="showAddModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Produk
                </button>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div id="productGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

    <!-- Pagination -->
    <div id="pagination" class="flex justify-center items-center mt-8 space-x-2"></div>
</main>

<!-- Add/Edit Product Modal -->
<div id="productModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="hideModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Tambah Produk</h3>
                <button onclick="hideModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="productForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" name="price" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stock" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                        Simpan
                    </button>
                    <button type="button" onclick="hideModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Global variables
const API_URL = "<?= base_url('kelola-produk') ?>";
const SEARCH_URL = "<?= base_url('kelola-produk/search-products') ?>";
const FILTER_URL = "<?= base_url('kelola-produk/filter-products') ?>";

let currentPage = 1;
const itemsPerPage = 6; // Default 6 products per page
let currentProducts = [];
let isEditMode = false;
const totalProducts = <?=$total_products?>; // Total products from backend
let filteredTotalProducts = totalProducts; // For search/filter results
let currentProductId = null;

// DOM Elements
const productGrid = document.getElementById('productGrid');
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const productModal = document.getElementById('productModal');
const modalTitle = document.getElementById('modalTitle');
const productForm = document.getElementById('productForm');
const pagination = document.getElementById('pagination');

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    setupEventListeners();
});

// Event listeners
function setupEventListeners() {
    searchInput.addEventListener('input', debounce(() => {
        currentPage = 1;
        loadProducts();
    }, 300));

    statusFilter.addEventListener('change', () => {
        currentPage = 1;
        loadProducts();
    });

    productForm.addEventListener('submit', saveProduct);
}

// Load products with search, filter, and pagination
function loadProducts() {
    showLoading();
    
    const params = new URLSearchParams({
        page: currentPage,
        limit: itemsPerPage,
        search: searchInput.value,
        status: statusFilter.value
    });

    let url = API_URL + '/GetAll';
    if (searchInput.value) {
        url = SEARCH_URL;
    } else if (statusFilter.value) {
        url = FILTER_URL;
    }

    fetch(`${url}?${params}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        currentProducts = data.data || data.products || [];
        
        // Update filtered total products based on response
        if (data.total !== undefined) {
            filteredTotalProducts = data.total;
        } else if (data.pagination && data.pagination.totalItems !== undefined) {
            filteredTotalProducts = data.pagination.totalItems;
        } else if (searchInput.value || statusFilter.value) {
            // If searching/filtering but no total provided, use current products length
            filteredTotalProducts = currentProducts.length;
        } else {
            // Default to original total
            filteredTotalProducts = totalProducts;
        }
        
        renderProducts(currentProducts);
        renderPagination();
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Gagal memuat produk');
    });
}

// Render products
function renderProducts(products) {
    if (!products.length) {
        productGrid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="fas fa-box-open text-5xl text-gray-400 mb-4"></i>
                <p class="text-gray-700">Tidak ada produk ditemukan</p>
            </div>
        `;
        return;
    }

    productGrid.innerHTML = products.map(product => `
        <div class="bg-white rounded-lg shadow-md overflow-hidden group hover:shadow-lg transition-shadow">
            <div class="relative">
                <img src="<?= base_url('uploads/product/') ?>${product.image}" 
                     alt="${product.name}"
                     class="w-full h-48 object-cover"
                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI0YzRjRGNiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI0YzRjRGNiIvPjwvc3ZnPg=='" />
                
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-1 text-xs rounded-full ${product.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${product.status === 'active' ? 'Aktif' : 'Nonaktif'}
                    </span>
                </div>
                
                <div class="absolute top-2 right-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" ${product.status === 'active' ? 'checked' : ''} 
                               onchange="toggleStatus(${product.id}, this.checked)" 
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
            
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-2 truncate">${product.name}</h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">${product.description || 'Tidak ada deskripsi'}</p>
                
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-bold text-indigo-600">Rp ${formatPrice(product.price)}</span>
                    <span class="text-sm text-gray-500">Stok: ${product.stock}</span>
                </div>
                
                <div class="flex space-x-2">
                    <button onclick="editProduct(${product.id})" 
                            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button onclick="deleteProduct(${product.id}, '${product.name}')" 
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Render pagination based on totalProducts
function renderPagination() {
    const totalPages = Math.ceil(filteredTotalProducts / itemsPerPage);
    
    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }

    let paginationHTML = '';

    // Previous button
    if (currentPage > 1) {
        paginationHTML += `<button onclick="changePage(${currentPage - 1})" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">‹</button>`;
    }

    // Calculate page range to show
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);

    // Adjust range if we're near the beginning or end
    if (currentPage <= 3) {
        endPage = Math.min(5, totalPages);
    }
    if (currentPage >= totalPages - 2) {
        startPage = Math.max(1, totalPages - 4);
    }

    // First page button (if not in range)
    if (startPage > 1) {
        paginationHTML += `<button onclick="changePage(1)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">1</button>`;
        if (startPage > 2) {
            paginationHTML += `<span class="px-3 py-2 text-gray-500">...</span>`;
        }
    }

    // Page numbers
    for (let i = startPage; i <= endPage; i++) {
        paginationHTML += `
            <button onclick="changePage(${i})" 
                    class="px-3 py-2 ${i === currentPage ? 'bg-indigo-600 text-white' : 'bg-gray-200 hover:bg-gray-300'} rounded">
                ${i}
            </button>
        `;
    }

    // Last page button (if not in range)
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            paginationHTML += `<span class="px-3 py-2 text-gray-500">...</span>`;
        }
        paginationHTML += `<button onclick="changePage(${totalPages})" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">${totalPages}</button>`;
    }

    // Next button
    if (currentPage < totalPages) {
        paginationHTML += `<button onclick="changePage(${currentPage + 1})" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">›</button>`;
    }

    // Add pagination info
    const startItem = (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, filteredTotalProducts);
    
    pagination.innerHTML = `
        <div class="flex flex-col items-center space-y-4">
            <div class="flex space-x-2">
                ${paginationHTML}
            </div>
            <div class="text-sm text-gray-600">
                Menampilkan ${startItem}-${endItem} dari ${filteredTotalProducts} produk
            </div>
        </div>
    `;
}

// Change page
function changePage(page) {
    currentPage = page;
    loadProducts();
}

// Show/hide modal
function showAddModal() {
    isEditMode = false;
    currentProductId = null;
    modalTitle.textContent = 'Tambah Produk';
    productForm.reset();
    productModal.classList.remove('hidden');
}

function hideModal() {
    productModal.classList.add('hidden');
    productForm.reset();
}

// Edit product
function editProduct(id) {
    const product = currentProducts.find(p => p.id == id);
    if (!product) return;

    isEditMode = true;
    currentProductId = id;
    modalTitle.textContent = 'Edit Produk';
    
    productForm.name.value = product.name;
    productForm.description.value = product.description || '';
    productForm.price.value = product.price;
    productForm.stock.value = product.stock;
    
    productModal.classList.remove('hidden');
}

// Save product
function saveProduct(e) {
    e.preventDefault();
    
    const formData = new FormData(productForm);
    const url = isEditMode ? `${API_URL}/Edit/${currentProductId}` : `${API_URL}/Add`;
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.success) {
            showNotification(isEditMode ? 'Produk berhasil diperbarui!' : 'Produk berhasil ditambahkan!', 'success');
            hideModal();
            // Update total products count if adding new product
            if (!isEditMode) {
                filteredTotalProducts++;
            }
            loadProducts();
        } else {
            throw new Error(data.message || 'Gagal menyimpan produk');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menyimpan produk', 'error');
    });
}

// Toggle product status
function toggleStatus(id, isActive) {
    const status = isActive ? 'active' : 'inactive';
    const formData = new FormData();
    formData.append('status', status);
    
    fetch(`${API_URL}/updateStatus/${id}`, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.success) {
            showNotification(`Status produk berhasil diubah`, 'success');
            loadProducts();
        } else {
            throw new Error(data.message || 'Gagal mengubah status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal mengubah status produk', 'error');
        // Revert checkbox
        event.target.checked = !isActive;
    });
}

// Delete product
function deleteProduct(id, name) {
    if (!confirm(`Yakin ingin menghapus produk "${name}"?`)) return;
    
    fetch(`${API_URL}/Delete/${id}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.success) {
            showNotification('Produk berhasil dihapus!', 'success');
            // Update total products count
            filteredTotalProducts--;
            // Adjust current page if necessary
            const totalPages = Math.ceil(filteredTotalProducts / itemsPerPage);
            if (currentPage > totalPages && totalPages > 0) {
                currentPage = totalPages;
            }
            loadProducts();
        } else {
            throw new Error(data.message || 'Gagal menghapus produk');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal menghapus produk', 'error');
    });
}

// Utility functions
function showLoading() {
    productGrid.innerHTML = '<div class="col-span-full text-center py-12"><i class="fas fa-spinner fa-spin text-3xl text-indigo-600"></i></div>';
}

function showError(message) {
    productGrid.innerHTML = `
        <div class="col-span-full text-center py-12">
            <i class="fas fa-exclamation-circle text-3xl text-red-600 mb-4"></i>
            <p class="text-gray-700">${message}</p>
            <button onclick="loadProducts()" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg">Coba Lagi</button>
        </div>
    `;
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check' : type === 'error' ? 'fa-times' : 'fa-info'} mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 hover:text-gray-200">×</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 5000);
}

function formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
}

function debounce(func, wait) {
    let timeout;
    return function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, arguments), wait);
    };
}

// Global functions
window.showAddModal = showAddModal;
window.hideModal = hideModal;
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
</style>
<?= $this->endSection() ?>