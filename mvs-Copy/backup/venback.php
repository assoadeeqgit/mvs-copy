<?php
// vendor-dashboard.php
require_once '../include/auth.php'; // Ensure vendor is logged in
require_once '../include/config.php';

// Get vendor stats
$vendor_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$last_week = date('Y-m-d', strtotime('-7 days'));

try {
    // Get total products
    $products_stmt = $dbh->prepare("SELECT COUNT(*) FROM products WHERE vendor_id = ?");
    $products_stmt->execute([$vendor_id]);
    $total_products = $products_stmt->fetchColumn();

    // Get total orders
    $orders_stmt = $dbh->prepare("SELECT COUNT(*) FROM orders 
                                JOIN order_items ON orders.id = order_items.order_id
                                WHERE order_items.vendor_id = ?");
    $orders_stmt->execute([$vendor_id]);
    $total_orders = $orders_stmt->fetchColumn();

    // Get recent orders
    $recent_orders_stmt = $dbh->prepare("SELECT orders.id, orders.order_number, orders.created_at, orders.status, 
                                        SUM(order_items.quantity * order_items.price) as total
                                        FROM orders
                                        JOIN order_items ON orders.id = order_items.order_id
                                        WHERE order_items.vendor_id = ?
                                        GROUP BY orders.id
                                        ORDER BY orders.created_at DESC
                                        LIMIT 5");
    $recent_orders_stmt->execute([$vendor_id]);
    $recent_orders = $recent_orders_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get sales data for chart
    $sales_stmt = $dbh->prepare("SELECT DATE(created_at) as date, SUM(total_amount) as daily_sales
                                FROM orders
                                WHERE status = 'delivered' AND created_at >= ?
                                GROUP BY DATE(created_at)
                                ORDER BY date ASC");
    $sales_stmt->execute([$last_week]);
    $sales_data = $sales_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "Error loading dashboard data";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard | MultiVendor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .sidebar {
        transition: all 0.3s;
    }

    .sidebar-collapsed {
        width: 80px;
    }

    .sidebar-collapsed .nav-text {
        display: none;
    }

    .main-content {
        transition: all 0.3s;
    }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-800 text-white w-64 flex-shrink-0">
            <div class="p-4 border-b border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-store text-lg"></i>
                    </div>
                    <div>
                        <div class="font-semibold"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
                        <div class="text-xs text-blue-200">Vendor Account</div>
                    </div>
                </div>
            </div>
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="vendor-dashboard.php" class="flex items-center space-x-3 p-3 rounded-lg bg-blue-700">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="vendor-products.php"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-box-open w-6"></i>
                            <span class="nav-text">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="vendor-orders.php"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-shopping-bag w-6"></i>
                            <span class="nav-text">Orders</span>
                            <span class="bg-blue-600 text-xs px-2 py-1 rounded-full"><?= $total_orders ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="vendor-sales.php"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-chart-line w-6"></i>
                            <span class="nav-text">Sales</span>
                        </a>
                    </li>
                    <li>
                        <a href="vendor-reviews.php"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-star w-6"></i>
                            <span class="nav-text">Reviews</span>
                        </a>
                    </li>
                    <li>
                        <a href="vendor-settings.php"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-cog w-6"></i>
                            <span class="nav-text">Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700">
                <a href="logout.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-sign-out-alt w-6"></i>
                    <span class="nav-text">Logout</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 overflow-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center p-4">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="text-gray-600 hover:text-blue-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-2xl font-semibold text-gray-800">Vendor Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="text-gray-600 hover:text-blue-600">
                                <i class="fas fa-bell text-xl"></i>
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                            </button>
                        </div>
                        <div class="relative">
                            <button class="text-gray-600 hover:text-blue-600">
                                <i class="fas fa-envelope text-xl"></i>
                                <span
                                    class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">5</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-6">
                <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Total Products</p>
                                <h3 class="text-3xl font-bold text-gray-800"><?= $total_products ?></h3>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-box-open text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <a href="vendor-products.php"
                            class="mt-4 inline-flex items-center text-blue-600 hover:underline">
                            Manage Products <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Total Orders</p>
                                <h3 class="text-3xl font-bold text-gray-800"><?= $total_orders ?></h3>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <a href="vendor-orders.php" class="mt-4 inline-flex items-center text-blue-600 hover:underline">
                            View Orders <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Store Rating</p>
                                <div class="flex items-center mt-1">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="text-gray-600 ml-2">4.7 (128 reviews)</span>
                                </div>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-star text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <a href="vendor-reviews.php"
                            class="mt-4 inline-flex items-center text-blue-600 hover:underline">
                            See Reviews <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Sales Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Weekly Sales</h3>
                            <select class="bg-gray-100 border border-gray-300 text-gray-700 py-1 px-3 rounded">
                                <option>This Week</option>
                                <option>Last Week</option>
                                <option>This Month</option>
                            </select>
                        </div>
                        <canvas id="salesChart" height="250"></canvas>
                    </div>

                    <!-- Orders Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Order Status</h3>
                            <select class="bg-gray-100 border border-gray-300 text-gray-700 py-1 px-3 rounded">
                                <option>Last 7 Days</option>
                                <option>Last 30 Days</option>
                                <option>This Year</option>
                            </select>
                        </div>
                        <canvas id="ordersChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order #</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="vendor-order-details.php?id=<?= $order['id'] ?>"
                                            class="text-blue-600 hover:underline">#<?= htmlspecialchars($order['order_number']) ?></a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">$<?= number_format($order['total'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $status_classes = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'shipped' => 'bg-purple-100 text-purple-800',
                                                'delivered' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800'
                                            ];
                                            ?>
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full <?= $status_classes[strtolower($order['status'])] ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="vendor-order-details.php?id=<?= $order['id'] ?>"
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <?php if ($order['status'] == 'pending'): ?>
                                        <a href="#" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-check"></i> Process
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span
                                class="font-medium"><?= $total_orders ?></span> orders
                        </div>
                        <div class="flex space-x-2">
                            <button
                                class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </button>
                            <button
                                class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    // Toggle sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('sidebar-collapsed');
        document.querySelector('.main-content').classList.toggle('ml-64');
        document.querySelector('.main-content').classList.toggle('ml-20');
    });

    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($sales_data, 'date')) ?>,
            datasets: [{
                label: 'Daily Sales ($)',
                data: <?= json_encode(array_column($sales_data, 'daily_sales')) ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Orders Chart (doughnut)
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    const ordersChart = new Chart(ordersCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
            datasets: [{
                data: [12, 19, 3, 5, 2],
                backgroundColor: [
                    'rgba(234, 179, 8, 0.7)',
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(139, 92, 246, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(239, 68, 68, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
    </script>
</body>

</html>