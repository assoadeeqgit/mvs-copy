<?php
require('../include/config.php');

// Initialize variables
$products = [];
$product_images = [];
$total_records = 0;
$total_pages = 1;
$error_message = '';
$search = '';
$status = '';
$category = '';
$page = 1;

try {
    // Validate and sanitize input
    $records_per_page = 5;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = max(0, ($page - 1) * $records_per_page); // Ensure offset is not negative

    // Sanitize search and filter parameters
    $search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search']), ENT_QUOTES, 'UTF-8') : '';
    $status = isset($_GET['status']) ? htmlspecialchars(trim($_GET['status']), ENT_QUOTES, 'UTF-8') : '';
    $category = isset($_GET['category']) ? htmlspecialchars(trim($_GET['category']), ENT_QUOTES, 'UTF-8') : '';

    // Validate status and category against allowed values
    $allowed_statuses = ['active', 'draft', 'outofstock', 'lowstock'];
    $allowed_categories = ['electronics', 'fashion', 'home-garden', 'beauty', 'travel'];

    // Base query with parameter binding
    $sql = 'SELECT * FROM products WHERE 1=1';
    $count_sql = 'SELECT COUNT(*) FROM products WHERE 1=1';
    $params = [];
    $types = '';

    // Add filters to query
    if (!empty($search)) {
        $sql .= ' AND (name LIKE ? OR sku LIKE ?)';
        $count_sql .= ' AND (name LIKE ? OR sku LIKE ?)';
        $search_param = "%{$search}%";
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= 'ss';
    }

    if (!empty($status) && in_array($status, $allowed_statuses, true)) {
        $sql .= ' AND status = ?';
        $count_sql .= ' AND status = ?';
        $params[] = $status;
        $types .= 's';
    }

    if (!empty($category) && in_array($category, $allowed_categories, true)) {
        $sql .= ' AND category = ?';
        $count_sql .= ' AND category = ?';
        $params[] = $category;
        $types .= 's';
    }

    // Count total records for pagination
    $stmt = $dbh->prepare($count_sql);
    if ($params) {
        $stmt->execute($params);
    } else {
        $stmt->execute();
    }
    $total_records = (int) $stmt->fetchColumn();
    $total_pages = max(1, ceil($total_records / $records_per_page)); // Ensure at least 1 page

    // Validate page number
    $page = max(1, min($page, $total_pages)); // Keep page within valid range

    // Get the current page records
    $sql .= ' ORDER BY id DESC LIMIT ?, ?';
    $params[] = $offset;
    $params[] = $records_per_page;

    $stmt = $dbh->prepare($sql);

    // Bind parameters with proper types
    foreach ($params as $index => $param) {
        $paramType = PDO::PARAM_STR;
        if ($index === count($params) - 2)
            $paramType = PDO::PARAM_INT; // offset
        if ($index === count($params) - 1)
            $paramType = PDO::PARAM_INT; // limit
        $stmt->bindValue($index + 1, $param, $paramType);
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get product images if there are products
    if (!empty($products)) {
        $product_ids = array_column($products, 'id');
        $placeholders = rtrim(str_repeat('?,', count($product_ids)), ',');

        $image_query = $dbh->prepare("SELECT product_id, image_url FROM product_images WHERE product_id IN ($placeholders)");
        $image_query->execute($product_ids);
        $images = $image_query->fetchAll(PDO::FETCH_ASSOC);

        // Index images by product_id for easier access
        foreach ($images as $image) {
            $product_images[$image['product_id']][] = htmlspecialchars($image['image_url'], ENT_QUOTES, 'UTF-8');
        }
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error_message = "An error occurred while fetching products. Please try again later.";
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    $error_message = "An unexpected error occurred. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | Vendor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .product-table th {
        position: sticky;
        top: 0;
        background-color: #f9fafb;
        z-index: 10;
    }

    .checkbox-cell {
        width: 40px;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        min-width: 160px;
        z-index: 1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
    </style>
</head>

<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-6">
        <?php if (!empty($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $error_message ?></span>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manage Products</h1>
                <p class="text-gray-600">View and manage your product listings</p>
            </div>
            <a href="add_product.php"
                class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i> Add Product
            </a>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form action="" method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="sr-only">Search products</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="search" name="search"
                                value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Search products..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="statusFilter" class="sr-only">Status</label>
                        <select id="statusFilter" name="status"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2">
                            <option value="">All Statuses</option>
                            <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="outofstock" <?= $status === 'outofstock' ? 'selected' : '' ?>>Out of Stock
                            </option>
                            <option value="lowstock" <?= $status === 'lowstock' ? 'selected' : '' ?>>Low Stock</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="categoryFilter" class="sr-only">Category</label>
                        <select id="categoryFilter" name="category"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2">
                            <option value="">All Categories</option>
                            <option value="electronics" <?= $category === 'electronics' ? 'selected' : '' ?>>Electronics
                            </option>
                            <option value="fashion" <?= $category === 'fashion' ? 'selected' : '' ?>>Fashion</option>
                            <option value="home-garden" <?= $category === 'home-garden' ? 'selected' : '' ?>>Home &
                                Garden
                            </option>
                            <option value="beauty" <?= $category === 'beauty' ? 'selected' : '' ?>>Beauty</option>
                            <option value="travel" <?= $category === 'travel' ? 'selected' : '' ?>>Travel</option>
                        </select>
                    </div>
                    <input type="hidden" name="page" value="1" id="pageInput">
                    <button type="submit" class="hidden" id="submitButton">Filter</button>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-white rounded-lg shadow p-4 mb-4 hidden" id="bulkActions">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span id="selectedCount" class="text-sm font-medium text-gray-700 mr-4">0 products selected</span>
                    <select id="bulkAction"
                        class="mr-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-1 text-sm">
                        <option value="">Bulk Actions</option>
                        <option value="activate">Activate</option>
                        <option value="draft">Move to Draft</option>
                        <option value="archive">Archive</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button id="applyBulkAction"
                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                        Apply
                    </button>
                </div>
                <button id="clearSelection" class="text-sm text-blue-600 hover:underline">
                    Clear selection
                </button>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 product-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="checkbox-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                No products found. Try adjusting your filters.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox"
                                    data-id="<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="product-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <?php
                                                $image_url = "https://via.placeholder.com/100";
                                                if (isset($product_images[$product['id']]) && !empty($product_images[$product['id']][0])) {
                                                    $image_url = $product_images[$product['id']][0];
                                                }
                                                ?>
                                        <img class="h-10 w-10 rounded-md object-cover"
                                            src="<?= htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8') ?>"
                                            alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                        <div class="text-sm text-gray-500">SKU:
                                            <?= htmlspecialchars($product['sku'], ENT_QUOTES, 'UTF-8') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                $<?= number_format((float) $product['price'], 2) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= (int) $product['stock'] ?> in stock
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                        $badge_class = 'bg-green-100 text-green-800';
                                        $status_text = 'Active';

                                        if ($product['status'] === 'draft') {
                                            $badge_class = 'bg-gray-100 text-gray-800';
                                            $status_text = 'Draft';
                                        } elseif ($product['status'] === 'outofstock' || $product['stock'] == 0) {
                                            $badge_class = 'bg-red-100 text-red-800';
                                            $status_text = 'Out of Stock';
                                        } elseif ($product['status'] === 'lowstock' || $product['stock'] < 10) {
                                            $badge_class = 'bg-yellow-100 text-yellow-800';
                                            $status_text = 'Low Stock';
                                        }
                                        ?>
                                <span class="status-badge <?= $badge_class ?>"><?= $status_text ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="dropdown relative inline-block">
                                    <button class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-content mt-1 bg-white shadow-lg rounded-md py-1 z-10">
                                        <a href="view_product.php?id=<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-eye mr-2"></i> View
                                        </a>
                                        <a href="edit_product.php?id=<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-edit mr-2"></i> Edit
                                        </a>
                                        <a href="duplicate_product.php?id=<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-copy mr-2"></i> Duplicate
                                        </a>
                                        <a href="#"
                                            class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 delete-product"
                                            data-id="<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="fas fa-trash mr-2"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div
            class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-lg shadow">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span
                            class="font-medium"><?= min(($page - 1) * $records_per_page + 1, $total_records) ?></span>
                        to <span class="font-medium"><?= min($page * $records_per_page, $total_records) ?></span> of
                        <span class="font-medium"><?= $total_records ?></span> products
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <!-- Previous Page -->
                        <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&category=<?= urlencode($category) ?>"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <?php else: ?>
                        <span
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                            <span class="sr-only">Previous</span>
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php
                            $max_links = 5;
                            $start_page = max(1, min($page - floor($max_links / 2), $total_pages - $max_links + 1));
                            $end_page = min($start_page + $max_links - 1, $total_pages);

                            if ($start_page > 1) {
                                echo '<a href="?page=1&search=' . urlencode($search) . '&status=' . urlencode($status) . '&category=' . urlencode($category) . '" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>';

                                if ($start_page > 2) {
                                    echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                                }
                            }

                            for ($i = $start_page; $i <= $end_page; $i++) {
                                $active = $i === $page;
                                $class = $active
                                    ? 'bg-blue-50 border-blue-500 text-blue-600'
                                    : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50';

                                echo '<a href="?page=' . $i . '&search=' . urlencode($search) . '&status=' . urlencode($status) . '&category=' . urlencode($category) . '" 
                                class="relative inline-flex items-center px-4 py-2 border ' . $class . ' text-sm font-medium">' . $i . '</a>';
                            }

                            if ($end_page < $total_pages) {
                                if ($end_page < $total_pages - 1) {
                                    echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                                }

                                echo '<a href="?page=' . $total_pages . '&search=' . urlencode($search) . '&status=' . urlencode($status) . '&category=' . urlencode($category) . '" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">' . $total_pages . '</a>';
                            }
                            ?>

                        <!-- Next Page -->
                        <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&category=<?= urlencode($category) ?>"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <?php else: ?>
                        <span
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                            <span class="sr-only">Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
            <!-- Mobile pagination controls -->
            <div class="flex items-center justify-between w-full sm:hidden">
                <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&category=<?= urlencode($category) ?>"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
                <?php else: ?>
                <span
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    Previous
                </span>
                <?php endif; ?>
                <div class="text-sm text-gray-700">
                    Page <span class="font-medium"><?= $page ?></span> of <span
                        class="font-medium"><?= $total_pages ?></span>
                </div>
                <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&category=<?= urlencode($category) ?>"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
                <?php else: ?>
                <span
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    Next
                </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
    // Bulk Actions Functionality
    const selectAll = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const clearSelection = document.getElementById('clearSelection');

    // Select/Deselect All
    selectAll.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox change
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Clear selection
    clearSelection.addEventListener('click', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAll.checked = false;
        updateBulkActions();
    });

    // Update bulk actions visibility
    function updateBulkActions() {
        const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;

        if (checkedCount > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = `${checkedCount} products selected`;
            selectAll.checked = checkedCount === productCheckboxes.length;
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    // Apply bulk action
    document.getElementById('applyBulkAction').addEventListener('click', function() {
        const action = document.getElementById('bulkAction').value;
        if (!action) return;

        const selectedIds = [...document.querySelectorAll('.product-checkbox:checked')].map(cb => cb
            .getAttribute('data-id'));

        if (confirm(`Are you sure you want to apply "${action}" to ${selectedIds.length} selected products?`)) {
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            fetch('process_bulk_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({
                        action: action,
                        ids: selectedIds
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Bulk action applied successfully!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }
    });

    // Real-time search and filter
    document.getElementById('search').addEventListener('input', debounce(submitForm, 500));
    document.getElementById('statusFilter').addEventListener('change', submitForm);
    document.getElementById('categoryFilter').addEventListener('change', submitForm);

    function submitForm() {
        document.getElementById('pageInput').value = '1'; // Reset to first page on filter change
        document.getElementById('submitButton').click();
    }

    // Debounce function to limit how often a function can be called
    function debounce(func, delay) {
        let timeoutId;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func.apply(context, args);
            }, delay);
        };
    }

    // Delete product
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-id');

            if (confirm(
                'Are you sure you want to delete this product? This action cannot be undone.')) {
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                fetch('process_product_action.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: JSON.stringify({
                            action: 'delete',
                            id: productId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Product deleted successfully!');
                            window.location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Unknown error occurred'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        });
    });
    </script>
</body>

</html>