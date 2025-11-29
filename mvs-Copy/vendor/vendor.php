<?php
// Add error reporting at the VERY TOP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../include/config.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set vendor_id for demo (remove this in production)
$_SESSION['vendor_id'] = 501;

// Initialize variables with default values
$totalRevenue = 0;
$completedRevenue = 0;
$totalOrders = 0;
$completedOrders = 0;
$productsInStock = 0;
$productsSold = 0;
$inventoryPercentage = 0;

// Check if database connection is available
if (!$dbh) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>Database connection failed. Please check your configuration.</div>";
} else {
    try {
        // Query to get total revenue and orders for the vendor
        $queryMsg = "SELECT 
                SUM(total_amount) AS total_revenue,
                COUNT(*) AS total_orders,
                SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) AS completed_revenue,
                SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) AS completed_orders
            FROM orders
            WHERE vendor_id = :vendor_id";
        $revenueQuery = $dbh->prepare($queryMsg);
        $revenueQuery->execute([':vendor_id' => $_SESSION['vendor_id']]);
        $revenueData = $revenueQuery->fetch(PDO::FETCH_ASSOC);

        $totalRevenue = $revenueData['total_revenue'] ?? 0;
        $completedRevenue = $revenueData['completed_revenue'] ?? 0;
        $totalOrders = $revenueData['total_orders'] ?? 0;
        $completedOrders = $revenueData['completed_orders'] ?? 0;

        // Query to get inventory data for the vendor
        $inventoryQuery = $dbh->prepare('
            SELECT 
                SUM(stock_quantity) AS total_stock, 
                SUM(sold_quantity) AS total_sold 
            FROM products 
            WHERE vendor_id = :vendor_id
        ');
        $inventoryQuery->execute([':vendor_id' => $_SESSION['vendor_id']]);
        $inventoryData = $inventoryQuery->fetch(PDO::FETCH_ASSOC);

        $productsInStock = $inventoryData['total_stock'] ?? 0;
        $productsSold = $inventoryData['total_sold'] ?? 0;

        // Calculate inventory percentage (for the chart)
        $inventoryPercentage = ($productsInStock / max(1, ($productsInStock + $productsSold))) * 100;

    } catch (PDOException $e) {
        error_log("Database error in vendor.php: " . $e->getMessage());
        echo "<div class='bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4'>Database query error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// If no data found, use demo data
if ($totalRevenue == 0 && $totalOrders == 0) {
    $totalRevenue = 1250.00;
    $completedRevenue = 1000.00;
    $totalOrders = 15;
    $completedOrders = 12;
    $productsInStock = 85;
    $productsSold = 42;
    $inventoryPercentage = 67;
}
?>

<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Dashboard - Manage Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
    :root {
        --primary: #0a2342;
        --secondary: #f8f9fa;
        --background: #ffffff;
        --accent: #d1d5db;
    }

    .dark {
        --primary: #1e40af;
        --secondary: #1f2937;
        --background: #111827;
        --accent: #374151;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--background);
        color: #1f2937;
    }

    .dark body {
        color: #f3f4f6;
    }

    #sidebar {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (max-width: 767px) {
        #sidebar {
            transform: translateX(-100%);
            position: fixed;
            height: 100vh;
            z-index: 50;
        }

        #sidebar.active {
            transform: translateX(0);
        }
    }

    .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 40;
    }

    .skeleton {
        background-color: #e5e7eb;
        background-image: linear-gradient(90deg, #e5e7eb, #f3f4f6, #e5e7eb);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    .toast {
        animation: slideIn 0.3s forwards, fadeOut 0.5s forwards 2.5s;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
        }

        to {
            transform: translateX(0);
        }
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
        }
    }
    </style>

    <script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: '#0a2342',
                        dark: '#1e40af'
                    },
                    secondary: {
                        DEFAULT: '#f8f9fa',
                        dark: '#1f2937'
                    },
                    background: {
                        DEFAULT: '#ffffff',
                        dark: '#111827'
                    },
                    accent: {
                        DEFAULT: '#d1d5db',
                        dark: '#374151'
                    },
                }
            }
        }
    }
    </script>
</head>

<body class="bg-background text-gray-800 dark:text-gray-200 transition-colors duration-200">
    <!-- Skip Navigation -->
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:bg-white focus:dark:bg-gray-800 focus:px-4 focus:py-2 focus:rounded-lg focus:z-50">Skip
        to content</a>

    <!-- Mobile Header -->
    <header
        class="md:hidden bg-primary dark:bg-primary-dark p-4 flex justify-between items-center sticky top-0 z-40 shadow-md">
        <h2 class="text-xl font-semibold text-white">Vendor Dashboard</h2>
        <div class="flex items-center space-x-4">
            <button id="darkModeToggle"
                class="text-white p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-white"
                aria-label="Toggle dark mode">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:inline"></i>
            </button>
            <button id="menuToggle" class="text-white text-2xl focus:outline-none" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Overlay for mobile menu -->
    <div id="overlay" class="overlay"></div>

    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-primary dark:bg-primary-dark text-white p-6 shadow-lg md:shadow-none">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-semibold hidden md:block">Vendor Dashboard</h2>
            </div>
            <ul class="space-y-3">
                <li class="relative">
                    <a href="vendor.php" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="manage_products.php"
                        class="flex items-center py-2 px-4 bg-black/20 rounded-lg font-medium">
                        <i class="fas fa-boxes mr-3"></i>
                        <span>Manage Products</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="vendor_profile.php"
                        class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-user mr-3"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="sales_overview.php"
                        class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-chart-bar mr-3"></i>
                        <span>Sales Overview</span>
                        <span
                            class="absolute right-4 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">3</span>
                    </a>
                </li>
                <li class="relative mt-8 pt-4 border-t border-white/20">
                    <a href="./Logout.php" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main id="main-content"
            class="flex-1 bg-secondary dark:bg-secondary-dark p-6 md:p-8 transition-colors duration-200">
            <!-- Loading Skeleton (hidden by default) -->
            <div id="loadingSkeleton" class="hidden">
                <div class="animate-pulse space-y-6">
                    <div class="h-8 bg-gray-300 dark:bg-gray-600 rounded w-1/3"></div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="h-32 bg-gray-300 dark:bg-gray-600 rounded-xl"></div>
                        <div class="h-32 bg-gray-300 dark:bg-gray-600 rounded-xl"></div>
                        <div class="h-32 bg-gray-300 dark:bg-gray-600 rounded-xl"></div>
                        <div class="h-32 bg-gray-300 dark:bg-gray-600 rounded-xl"></div>
                    </div>
                </div>
            </div>

            <!-- Actual Content -->
            <div id="content">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Manage Products</h1>
                    <div class="hidden md:flex items-center space-x-4">
                        <button id="darkModeToggleDesktop"
                            class="p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark"
                            aria-label="Toggle dark mode">
                            <i class="fas fa-moon dark:hidden text-gray-700"></i>
                            <i class="fas fa-sun hidden dark:inline text-white"></i>
                        </button>
                        <div class="relative">
                            <button id="userMenuButton"
                                class="flex items-center text-sm rounded-full focus:outline-none"
                                aria-label="User menu">
                                <img class="h-8 w-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name=Vendor&background=0a2342&color=fff"
                                    alt="User avatar">
                            </button>
                            <div id="userMenu"
                                class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <a href="vendor_profile.php"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Your
                                        Profile</a>
                                    <a href="./logout.php"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Sign
                                        out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Add Product -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div class="relative w-full md:w-96">
                        <input type="text" placeholder="Search products..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button
                        class="w-full md:w-auto bg-primary dark:bg-primary-dark hover:bg-opacity-90 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition">
                        <a href="add_product.php"><i class="fas fa-plus"></i>
                            <span>Add Product</a></span>
                    </button>
                </div>

                <!-- Dashboard Overview -->
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Sales -->

                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Revenue</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">
                                    $<?php echo number_format($completedRevenue, 2); ?>
                                </h3>
                                <p
                                    class="text-sm <?php echo ($completedRevenue > 0) ? 'text-green-600 dark:text-green-400' : 'text-gray-500'; ?> mt-1">
                                    <?php if ($completedRevenue > 0): ?>
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <?php echo number_format(($completedOrders / max(1, $totalOrders)) * 100, 1); ?>%
                                    completion rate
                                    <?php else: ?>
                                    No sales yet
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="sales_overview.php"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">View
                                report</a>
                        </div>
                    </div>

                    <!-- Total Products Sold Card -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Products Sold</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">
                                    <?php echo number_format($productsSold); ?>
                                </h3>
                                <p
                                    class="text-sm <?php echo ($productsSold > 0) ? 'text-green-600 dark:text-green-400' : 'text-gray-500'; ?> mt-1">
                                    <?php if ($productsSold > 0): ?>
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <?php echo number_format(($productsSold / max(1, ($productsInStock + $productsSold))) * 100, 1); ?>%
                                    sell-through
                                    <?php else: ?>
                                    No products sold
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="manage_products.php"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">View
                                all</a>
                        </div>
                    </div>

                    <!-- Total Orders Card -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">
                                    <?php echo number_format($totalOrders); ?>
                                </h3>
                                <p
                                    class="text-sm <?php echo ($completedOrders > 0) ? 'text-green-600 dark:text-green-400' : 'text-gray-500'; ?> mt-1">
                                    <?php if ($completedOrders > 0): ?>
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <?php echo $completedOrders; ?> completed
                                    <?php else: ?>
                                    No orders completed
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-clipboard-list text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="sales_overview.php"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">View
                                orders</a>
                        </div>
                    </div>

                    <!-- Current Inventory Card -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Inventory</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">
                                    <?php echo number_format($productsInStock); ?>
                                </h3>
                                <div class="mt-2">
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-primary dark:bg-primary-dark h-2 rounded-full"
                                            style="width: <?php echo $inventoryPercentage; ?>%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <?php echo number_format($inventoryPercentage, 1); ?>% of total inventory
                                    </p>
                                </div>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-cogs text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="manage_products.php"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">Manage
                                inventory</a>
                        </div>
                    </div>

                </section>

                <!-- Charts Section -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Inventory Overview</h2>
                            <select
                                class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option selected>Last 90 days</option>
                            </select>
                        </div>
                        <div class="h-80 relative">
                            <canvas id="salesPieChart"></canvas>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Sales Distribution</h2>
                            <select
                                class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                                <option>By Category</option>
                                <option selected>By Product</option>
                                <option>By Region</option>
                            </select>
                        </div>
                        <div class="h-80 relative">
                            <canvas id="salesDoughnutChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Recent Activity</h2>
                        <button class="text-sm text-primary dark:text-primary-dark font-medium hover:underline">View all
                            activity</button>
                    </div>
                    <div class="space-y-4">
                        <!-- Activity Item -->
                        <div
                            class="flex items-start space-x-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-2 flex-shrink-0">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold truncate">New Order Placed</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Order #ORD-2023-1056 for 5 items was
                                    placed by John Doe</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-clock mr-1"></i> Just now
                                </p>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>

                        <!-- Activity Item -->
                        <div
                            class="flex items-start space-x-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-2 flex-shrink-0">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold truncate">Stock Updated</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Stock for "Premium Headphones" was
                                    updated from 50 to 100 units</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-clock mr-1"></i> 2 hours ago
                                </p>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>

                        <!-- Activity Item -->
                        <div
                            class="flex items-start space-x-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-2 flex-shrink-0">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold truncate">Monthly Target Achieved</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Congratulations! You've reached 100%
                                    of your monthly sales target</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-clock mr-1"></i> 1 day ago
                                </p>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>

                        <!-- Activity Item -->
                        <div
                            class="flex items-start space-x-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-2 flex-shrink-0">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold truncate">New Product Added</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">"Wireless Earbuds Pro" has been
                                    added to your inventory</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-clock mr-1"></i> 3 days ago
                                </p>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 space-y-3 z-50"></div>
    <script>

    
// Pass PHP variables to JavaScript safely
const phpProductsInStock = <?php echo json_encode($productsInStock); ?>;
const phpProductsSold = <?php echo json_encode($productsSold); ?>;
const phpElectronicsSales = 45;
const phpClothingSales = 25;
const phpHomeGoodsSales = 20;
const phpOtherSales = 10;


    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        if (menuToggle && sidebar && overlay) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none';
                menuToggle.querySelector('i').classList.toggle('fa-bars');
                menuToggle.querySelector('i').classList.toggle('fa-times');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.style.display = 'none';
                menuToggle.querySelector('i').classList.remove('fa-times');
                menuToggle.querySelector('i').classList.add('fa-bars');
            });
        }

        // Ensure we have Chart.js loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js library is not loaded. Please include Chart.js before this script.');
            return;
        }

        // Check if PHP variables are properly defined, or use defaults
        let productsInStock = 100;
        let productsSold = 50;

        // Safely parse PHP variables if they exist
        try {
            // This assumes PHP has properly echoed these as JS literals
            if (typeof phpProductsInStock !== 'undefined') productsInStock = phpProductsInStock;
            if (typeof phpProductsSold !== 'undefined') productsSold = phpProductsSold;
        } catch (e) {
            console.warn('Using default data values since PHP variables are not available');
        }

        // Calculate percentages for doughnut chart (using default values if PHP isn't available)
        let electronicsSales = 45;
        let clothingSales = 25;
        let homeGoodsSales = 20;
        let otherSales = 10;

        // Try to use PHP values if available
        try {
            if (typeof phpElectronicsSales !== 'undefined') electronicsSales = phpElectronicsSales;
            if (typeof phpClothingSales !== 'undefined') clothingSales = phpClothingSales;
            if (typeof phpHomeGoodsSales !== 'undefined') homeGoodsSales = phpHomeGoodsSales;
            if (typeof phpOtherSales !== 'undefined') otherSales = phpOtherSales;
        } catch (e) {
            console.warn('Using default category values since PHP variables are not available');
        }

        const totalSales = electronicsSales + clothingSales + homeGoodsSales + otherSales;

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeToggleDesktop = document.getElementById('darkModeToggleDesktop');

        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }

        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', toggleDarkMode);
        }

        if (darkModeToggleDesktop) {
            darkModeToggleDesktop.addEventListener('click', toggleDarkMode);
        }

        // Check for saved preference
        if (localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('darkMode', 'true');
        }

        // User Menu Dropdown
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });

            // Close when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }

        // Show loading skeleton (example for AJAX)
        function showLoading() {
            const skeleton = document.getElementById('loadingSkeleton');
            const content = document.getElementById('content');

            if (skeleton) skeleton.classList.remove('hidden');
            if (content) content.classList.add('hidden');
        }

        function hideLoading() {
            const skeleton = document.getElementById('loadingSkeleton');
            const content = document.getElementById('content');

            if (skeleton) skeleton.classList.add('hidden');
            if (content) content.classList.remove('hidden');
        }

        // Simulate loading
        setTimeout(hideLoading, 1500);

        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) {
                console.warn('Toast container not found');
                return;
            }

            const toast = document.createElement('div');
            toast.className =
                `toast flex items-center p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            toast.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        `;
            toastContainer.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Example toast
        setTimeout(() => showToast('Dashboard loaded successfully'), 2000);

        // Initialize charts if elements exist
        const pieChartCanvas = document.getElementById('salesPieChart');
        const doughnutChartCanvas = document.getElementById('salesDoughnutChart');

        let pieChart;
        let doughnutChart;

        // Check if Chart.js plugins are available
        const hasDataLabelsPlugin = Chart.plugins && Chart.plugins.getAll().some(p => p.id === 'datalabels');

        if (pieChartCanvas) {
            // Configure Chart options based on available plugins
            const pieChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#6B7280',
                            font: {
                                family: 'Inter'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                return `${context.label}: ${context.raw} units (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            };

            // Add datalabels config only if the plugin is available
            if (hasDataLabelsPlugin) {
                pieChartOptions.plugins.datalabels = {
                    color: '#FFF',
                    font: {
                        weight: 'bold'
                    },
                    formatter: (value) => `${value}`
                };
            }

            pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: {
                    labels: ['In Stock', 'Sold'],
                    datasets: [{
                        data: [productsInStock, productsSold],
                        backgroundColor: ['#0a2342', '#4B8FF6'],
                        borderWidth: 0
                    }]
                },
                options: pieChartOptions
            });
        }

        if (doughnutChartCanvas) {
            // Configure Chart options based on available plugins
            const doughnutChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#6B7280',
                            font: {
                                family: 'Inter'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                return `${context.label}: ${context.raw} items (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            };

            // Add datalabels config only if the plugin is available
            if (hasDataLabelsPlugin) {
                doughnutChartOptions.plugins.datalabels = {
                    color: '#FFF',
                    font: {
                        weight: 'bold'
                    },
                    formatter: (value) => {
                        const total = doughnutChart?.data.datasets[0].data.reduce((sum, val) => sum +
                            val, 0) || totalSales;
                        return `${Math.round((value / total) * 100)}%`;
                    }
                };
            }

            doughnutChart = new Chart(doughnutChartCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Electronics', 'Clothing', 'Home Goods', 'Other'],
                    datasets: [{
                        data: [electronicsSales, clothingSales, homeGoodsSales, otherSales],
                        backgroundColor: ['#0a2342', '#4B8FF6', '#10B981', '#F59E0B'],
                        borderWidth: 0
                    }]
                },
                options: doughnutChartOptions
            });
        }

        // Async data fetch function
        async function fetchSalesData() {
            try {
                showLoading();
                const response = await fetch('/api/sales-data');

                // Check if response is ok
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                // Update charts with new data if they exist
                if (pieChart) {
                    pieChart.data.datasets[0].data = [data.inStock, data.sold];
                    pieChart.update();
                }

                if (doughnutChart) {
                    doughnutChart.data.datasets[0].data = [
                        data.electronics,
                        data.clothing,
                        data.homeGoods,
                        data.other
                    ];
                    doughnutChart.update();
                }

                showToast('Data refreshed successfully');
            } catch (error) {
                showToast('Failed to refresh data', 'error');
                console.error('Error fetching data:', error);
            } finally {
                hideLoading();
            }
        }

        // Auto-refresh data every 60 seconds if charts are initialized
        if (pieChart || doughnutChart) {
            setInterval(fetchSalesData, 60000);
        }
    });
    </script>
