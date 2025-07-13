<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use App\Models\StoreModel;
use App\Models\ProductsModel;

class Produk extends BaseController
{
    protected $accountModel;
    protected $storeModel;
    protected $productsModel;
    public function __construct()
    {
        $this->accountModel = new AccountModel();
        $this->storeModel = new StoreModel();
        $this->productsModel = new ProductsModel();
    }

    public function index()
    {
        $AccountId = session()->get('AccountId');

        $data = [
            'title' => 'Produk',
            'Account' => $this->accountModel->getAccountById($AccountId),
            'total_products' => $this->productsModel->where('AccountId', $AccountId)->countAllResults(),

        ];
        return view('user/produk', $data);
    }

    public function GetAll()
    {
        $AccountId = session()->get('AccountId');

        // Get pagination parameters
        $page = (int)($this->request->getGet('page') ?? 1);
        $limit = (int)($this->request->getGet('limit') ?? 6);
        $search = $this->request->getGet('search') ?? '';
        $status = $this->request->getGet('status') ?? '';

        // Ensure minimum values
        $page = max(1, $page);
        $limit = max(1, min(50, $limit)); // Max 50 items per page

        // Calculate offset
        $offset = ($page - 1) * $limit;

        // Build query with conditions
        $builder = $this->productsModel->where('AccountId', $AccountId);

        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }

        // Apply status filter
        if (!empty($status)) {
            $builder->where('status', $status);
        }

        // Get total count for pagination
        $totalProducts = $builder->countAllResults(false); // false to keep the query for next use

        // Get paginated products
        $products = $builder->orderBy('created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();

        // Calculate pagination info
        $totalPages = ceil($totalProducts / $limit);
        $hasNextPage = $page < $totalPages;
        $hasPrevPage = $page > 1;

        $data = [
            'success' => true,
            'data' => $products,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalItems' => $totalProducts,
                'itemsPerPage' => $limit,
                'hasNextPage' => $hasNextPage,
                'hasPrevPage' => $hasPrevPage,
                'startItem' => $offset + 1,
                'endItem' => min($offset + $limit, $totalProducts)
            ],
            'total' => $totalProducts // For backward compatibility
        ];

        return $this->response->setJSON($data);
    }

    public function Add()
    {
        $AccountId = session()->get('AccountId');

        $data = [
            'AccountId'   => $AccountId,
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
        ];

        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads/product', $newName);
            $data['image'] = $newName;
        } else {
            $data['image'] = null;
        }

        if (!$this->productsModel->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->productsModel->errors(),
                'data'    => $data
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    public function updateStatus($id)
    {
        $data = [
            'status' => $this->request->getPost('status'),
        ];
        $this->productsModel->update($id, $data);
        return $this->response->setJSON([
            'status' => 'success',
        ]);
    }

    public function Edit($id)
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
        ];

        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads/product', $newName);
            $data['image'] = $newName;
        } else {
            $data['image'] = null;
        }

        if (!$this->productsModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->productsModel->errors(),
                'data'    => $data
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }
    public function Delete($id)
    {
        if (!$this->productsModel->delete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->productsModel->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
        ]);
    }
    public function Search()
    {
        $AccountId = session()->get('AccountId');
        $searchTerm = $this->request->getPost('searchTerm');

        $products = $this->productsModel->like('name', $searchTerm)
            ->orLike('description', $searchTerm)
            ->where('AccountId', $AccountId)
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'products' => $products
        ]);
    }

    /**
 * Search Products Controller
 * Handle product search with pagination
 */
public function searchProducts()
{
    $AccountId = session()->get('AccountId');
    
    // Get parameters
    $page = (int)($this->request->getGet('page') ?? 1);
    $limit = (int)($this->request->getGet('limit') ?? 6);
    $search = trim($this->request->getGet('search') ?? '');
    
    // Validate parameters
    $page = max(1, $page);
    $limit = max(1, min(50, $limit));
    
    // Return empty result if no search term
    if (empty($search)) {
        return $this->response->setJSON([
            'success' => true,
            'data' => [],
            'pagination' => [
                'currentPage' => 1,
                'totalPages' => 0,
                'totalItems' => 0,
                'itemsPerPage' => $limit,
                'hasNextPage' => false,
                'hasPrevPage' => false,
                'startItem' => 0,
                'endItem' => 0
            ],
            'total' => 0,
            'message' => 'No search term provided'
        ]);
    }
    
    $offset = ($page - 1) * $limit;
    
    // Build search query
    $builder = $this->productsModel->where('AccountId', $AccountId);
    
    // Search in multiple fields
    $builder->groupStart()
            ->like('name', $search)
            ->orLike('description', $search)
            ->orLike('price', $search)
            ->groupEnd();
    
    // Get total count
    $totalProducts = $builder->countAllResults(false);
    
    // Get paginated results
    $products = $builder->orderBy('name', 'ASC') // Sort by name for search results
                       ->limit($limit, $offset)
                       ->findAll();
    
    // Calculate pagination
    $totalPages = ceil($totalProducts / $limit);
    
    $data = [
        'success' => true,
        'data' => $products,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalProducts,
            'itemsPerPage' => $limit,
            'hasNextPage' => $page < $totalPages,
            'hasPrevPage' => $page > 1,
            'startItem' => $totalProducts > 0 ? $offset + 1 : 0,
            'endItem' => min($offset + $limit, $totalProducts)
        ],
        'total' => $totalProducts,
        'searchTerm' => $search,
        'message' => $totalProducts > 0 ? "Found {$totalProducts} products" : 'No products found'
    ];
    
    return $this->response->setJSON($data);
}

/**
 * Filter Products Controller
 * Handle product filtering by status with pagination
 */
public function filterProducts()
{
    $AccountId = session()->get('AccountId');
    
    // Get parameters
    $page = (int)($this->request->getGet('page') ?? 1);
    $limit = (int)($this->request->getGet('limit') ?? 6);
    $status = trim($this->request->getGet('status') ?? '');
    
    // Validate parameters
    $page = max(1, $page);
    $limit = max(1, min(50, $limit));
    
    // Validate status
    $validStatuses = ['active', 'inactive'];
    if (!in_array($status, $validStatuses)) {
        return $this->response->setJSON([
            'success' => false,
            'data' => [],
            'error' => 'Invalid status. Must be: ' . implode(', ', $validStatuses),
            'total' => 0
        ]);
    }
    
    $offset = ($page - 1) * $limit;
    
    // Build filter query
    $builder = $this->productsModel->where('AccountId', $AccountId)
                                  ->where('status', $status);
    
    // Get total count
    $totalProducts = $builder->countAllResults(false);
    
    // Get paginated results
    $products = $builder->orderBy('created_at', 'DESC')
                       ->limit($limit, $offset)
                       ->findAll();
    
    // Calculate pagination
    $totalPages = ceil($totalProducts / $limit);
    
    $data = [
        'success' => true,
        'data' => $products,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalProducts,
            'itemsPerPage' => $limit,
            'hasNextPage' => $page < $totalPages,
            'hasPrevPage' => $page > 1,
            'startItem' => $totalProducts > 0 ? $offset + 1 : 0,
            'endItem' => min($offset + $limit, $totalProducts)
        ],
        'total' => $totalProducts,
        'filterStatus' => $status,
        'message' => $totalProducts > 0 ? "Found {$totalProducts} {$status} products" : "No {$status} products found"
    ];
    
    return $this->response->setJSON($data);
}

/**
 * Advanced Search and Filter Combined
 * Handle both search and filter simultaneously
 */
public function searchAndFilter()
{
    $AccountId = session()->get('AccountId');
    
    // Get parameters
    $page = (int)($this->request->getGet('page') ?? 1);
    $limit = (int)($this->request->getGet('limit') ?? 6);
    $search = trim($this->request->getGet('search') ?? '');
    $status = trim($this->request->getGet('status') ?? '');
    $sortBy = $this->request->getGet('sort_by') ?? 'created_at';
    $sortOrder = $this->request->getGet('sort_order') ?? 'DESC';
    
    // Validate parameters
    $page = max(1, $page);
    $limit = max(1, min(50, $limit));
    
    // Validate sort parameters
    $validSortFields = ['name', 'price', 'stock', 'created_at', 'updated_at'];
    $validSortOrders = ['ASC', 'DESC'];
    
    if (!in_array($sortBy, $validSortFields)) {
        $sortBy = 'created_at';
    }
    if (!in_array(strtoupper($sortOrder), $validSortOrders)) {
        $sortOrder = 'DESC';
    }
    
    $offset = ($page - 1) * $limit;
    
    // Build query
    $builder = $this->productsModel->where('AccountId', $AccountId);
    
    // Apply search if provided
    if (!empty($search)) {
        $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->orLike('price', $search)
                ->groupEnd();
    }
    
    // Apply status filter if provided
    if (!empty($status) && in_array($status, ['active', 'inactive'])) {
        $builder->where('status', $status);
    }
    
    // Get total count
    $totalProducts = $builder->countAllResults(false);
    
    // Get paginated results with sorting
    $products = $builder->orderBy($sortBy, $sortOrder)
                       ->limit($limit, $offset)
                       ->findAll();
    
    // Calculate pagination
    $totalPages = ceil($totalProducts / $limit);
    
    // Build response message
    $conditions = [];
    if (!empty($search)) $conditions[] = "search: '{$search}'";
    if (!empty($status)) $conditions[] = "status: {$status}";
    
    $conditionText = !empty($conditions) ? ' with ' . implode(' and ', $conditions) : '';
    $message = $totalProducts > 0 
        ? "Found {$totalProducts} products{$conditionText}" 
        : "No products found{$conditionText}";
    
    $data = [
        'success' => true,
        'data' => $products,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalProducts,
            'itemsPerPage' => $limit,
            'hasNextPage' => $page < $totalPages,
            'hasPrevPage' => $page > 1,
            'startItem' => $totalProducts > 0 ? $offset + 1 : 0,
            'endItem' => min($offset + $limit, $totalProducts)
        ],
        'total' => $totalProducts,
        'filters' => [
            'search' => $search,
            'status' => $status,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ],
        'message' => $message
    ];
    
    return $this->response->setJSON($data);
}

/**
 * Get Filter Options
 * Return available filter options for frontend
 */
public function getFilterOptions()
{
    $AccountId = session()->get('AccountId');
    
    // Get unique statuses
    $statuses = $this->productsModel->select('status')
                                   ->where('AccountId', $AccountId)
                                   ->groupBy('status')
                                   ->findColumn('status');
    
    // Get products count by status
    $statusCounts = [];
    foreach ($statuses as $status) {
        $count = $this->productsModel->where('AccountId', $AccountId)
                                    ->where('status', $status)
                                    ->countAllResults();
        $statusCounts[$status] = $count;
    }
    
    // Get total products
    $totalProducts = $this->productsModel->where('AccountId', $AccountId)
                                        ->countAllResults();
    
    $data = [
        'success' => true,
        'filterOptions' => [
            'statuses' => $statuses,
            'statusCounts' => $statusCounts,
            'sortOptions' => [
                ['value' => 'name', 'label' => 'Name'],
                ['value' => 'price', 'label' => 'Price'],
                ['value' => 'stock', 'label' => 'Stock'],
                ['value' => 'created_at', 'label' => 'Date Created'],
                ['value' => 'updated_at', 'label' => 'Last Updated']
            ],
            'sortOrders' => [
                ['value' => 'ASC', 'label' => 'Ascending'],
                ['value' => 'DESC', 'label' => 'Descending']
            ]
        ],
        'totalProducts' => $totalProducts
    ];
    
    return $this->response->setJSON($data);
}
}
