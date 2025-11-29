<?php
require('./include/config.php');

// Query to get sales data
$sql = 'SELECT SUM(stock_quantity) AS total_stock, SUM(sold_quantity) AS total_sold FROM products';
$query = $dbh->prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

$productsInStock = $result['total_stock'] ?? 0;
$productsSold = $result['total_sold'] ?? 0;

// Check if search is set
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Basic query
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ?");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM products");
}

// Fetch products
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <a href="vendor_dashboard.html"
                        class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="manage_products.html"
                        class="flex items-center py-2 px-4 bg-black/20 rounded-lg font-medium">
                        <i class="fas fa-boxes mr-3"></i>
                        <span>Manage Products</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="profile.html" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-user mr-3"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="sales_overview.html"
                        class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-chart-bar mr-3"></i>
                        <span>Sales Overview</span>
                        <span
                            class="absolute right-4 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">3</span>
                    </a>
                </li>
                <li class="relative mt-8 pt-4 border-t border-white/20">
                    <a href="logout.html" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
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
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Your
                                        Profile</a>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Sign
                                        out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Add Product -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <form method="GET" action="your_dashboard.php" class="relative">
                        <input type="text" name="search" placeholder="Search..." class="input-style-class"
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="search-button-class">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <button
                        class="w-full md:w-auto bg-primary dark:bg-primary-dark hover:bg-opacity-90 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition">
                        <i class="fas fa-plus"></i>
                        <span>Add Product</span>
                    </button>
                </div>

                <!-- Dashboard Overview -->
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Sales -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sales</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">$12,345</h3>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 12.5% from last month
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="#"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">View
                                report</a>
                        </div>
                    </div>

                    <!-- Total Products Sold -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Products Sold</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">1,234</h3>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 8.2% from last month
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="#"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">View
                                all</a>
                        </div>
                    </div>

                    <!-- Total Orders -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">500</h3>
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                    <i class="fas fa-arrow-down mr-1"></i> 3.2% from last month
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-clipboard-list text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="#"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">View
                                orders</a>
                        </div>
                    </div>

                    <!-- Current Inventory -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Inventory</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">850</h3>
                                <div class="mt-2">
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-primary dark:bg-primary-dark h-2 rounded-full"
                                            style="width: 65%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">65% of capacity</p>
                                </div>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-cogs text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="#"
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

                <!-- Products Table Section -->
                <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mt-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <h2 class="text-xl font-semibold">Your Products</h2>
                        <div class="flex space-x-3 mt-4 md:mt-0">
                            <select
                                class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                                <option>All Categories</option>
                                <option>Electronics</option>
                                <option>Clothing</option>
                                <option>Home Goods</option>
                            </select>
                            <select
                                class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                                <option>Sort by: Newest</option>
                                <option>Sort by: Oldest</option>
                                <option>Sort by: Price</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Product</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Category</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Price</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Stock</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Product 1 -->
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md" src="https://via.placeholder.com/40"
                                                    alt="Wireless Headphones">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">Wireless
                                                    Headphones</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">SKU: WH-2023</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">Electronics</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">$99.99</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">45
                                            in stock</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button
                                            class="text-primary dark:text-primary-dark hover:text-opacity-80 mr-3">Edit</button>
                                        <button
                                            class="text-red-600 hover:text-red-800 dark:hover:text-red-400">Delete</button>
                                    </td>
                                </tr>

                                <!-- Product 2 -->
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md" src="https://via.placeholder.com/40"
                                                    alt="Smart Watch">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">Smart
                                                    Watch</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">SKU: SW-2023</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">Electronics</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">$199.99</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">32
                                            in stock</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button
                                            class="text-primary dark:text-primary-dark hover:text-opacity-80 mr-3">Edit</button>
                                        <button
                                            class="text-red-600 hover:text-red-800 dark:hover:text-red-400">Delete</button>
                                    </td>
                                </tr>

                                <!-- Product 3 -->
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md" src="https://via.placeholder.com/40"
                                                    alt="Cotton T-Shirt">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">Cotton
                                                    T-Shirt</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">SKU: CT-2023</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">Clothing</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">$24.99</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Low
                                            stock (5)</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button
                                            class="text-primary dark:text-primary-dark hover:text-opacity-80 mr-3">Edit</button>
                                        <button
                                            class="text-red-600 hover:text-red-800 dark:hover:text-red-400">Delete</button>
                                    </td>
                                </tr>

                                <!-- Product 4 -->
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md" src="https://via.placeholder.com/40"
                                                    alt="Ceramic Coffee Mug">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">Ceramic
                                                    Coffee Mug</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">SKU: CM-2023</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">Home Goods</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">$14.99</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">78
                                            in stock</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Active</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button
                                            class="text-primary dark:text-primary-dark hover:text-opacity-80 mr-3">Edit</button>
                                        <button
                                            class="text-red-600 hover:text-red-800 dark:hover:text-red-400">Delete</button>
                                    </td>
                                </tr>

                                <!-- Product 5 -->
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md" src="https://via.placeholder.com/40"
                                                    alt="Bluetooth Speaker">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">Bluetooth
                                                    Speaker</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">SKU: BS-2023</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">Electronics</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">$79.99</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Out
                                            of stock</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">Inactive</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button
                                            class="text-primary dark:text-primary-dark hover:text-opacity-80 mr-3">Edit</button>
                                        <button
                                            class="text-red-600 hover:text-red-800 dark:hover:text-red-400">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div
                        class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <a href="#"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                Previous </a>
                            <a href="#"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                Next </a>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of
                                    <span class="font-medium">24</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    <a href="#"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                    <a href="#" aria-current="page"
                                        class="z-10 bg-primary dark:bg-primary-dark border-primary dark:border-primary-dark text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        1 </a>
                                    <a href="#"
                                        class="bg-white dark:bg-gray-800 border-gray-300 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        2 </a>
                                    <a href="#"
                                        class="bg-white dark:bg-gray-800 border-gray-300 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        3 </a>
                                    <a href="#"
                                        class="bg-white dark:bg-gray-800 border-gray-300 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        4 </a>
                                    <a href="#"
                                        class="bg-white dark:bg-gray-800 border-gray-300 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        5 </a>
                                    <a href="#"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 space-y-3 z-50"></div>
    <script>
    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

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

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeToggleDesktop = document.getElementById('darkModeToggleDesktop');

        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }

        darkModeToggle.addEventListener('click', toggleDarkMode);
        darkModeToggleDesktop.addEventListener('click', toggleDarkMode);

        // Check for saved preference
        if (localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('darkMode', 'true');
        }

        // User Menu Dropdown
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        userMenuButton.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        });

        // Close when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Show loading skeleton (example for AJAX)
        function showLoading() {
            document.getElementById('loadingSkeleton').classList.remove('hidden');
            document.getElementById('content').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingSkeleton').classList.add('hidden');
            document.getElementById('content').classList.remove('hidden');
        }

        // Simulate loading
        setTimeout(hideLoading, 1500);

        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
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

        // Charts Data
        const productsInStock = <?php echo $productsInStock; ?>;
        const productsSold = <?php echo $productsSold; ?>;

        // Pie Chart
        const pieChart = new Chart(
            document.getElementById('salesPieChart'), {
                type: 'pie',
                data: {
                    labels: ['In Stock', 'Sold'],
                    datasets: [{
                        data: [productsInStock, productsSold],
                        backgroundColor: ['#0a2342', '#4B8FF6'],
                        borderWidth: 0
                    }]
                },
                options: {
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
                                    return `${context.label}: ${context.raw} units (${context.parsed}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#FFF',
                            font: {
                                weight: 'bold'
                            },
                            formatter: (value) => `${value}`
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            }
        );

        // Doughnut Chart
        const doughnutChart = new Chart(
            document.getElementById('salesDoughnutChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Electronics', 'Clothing', 'Home Goods', 'Other'],
                    datasets: [{
                        data: [45, 25, 20, 10],
                        backgroundColor: ['#0a2342', '#4B8FF6', '#10B981', '#F59E0B'],
                        borderWidth: 0
                    }]
                },
                options: {
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
                                    return `${context.label}: ${context.raw} items (${context.parsed}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#FFF',
                            font: {
                                weight: 'bold'
                            },
                            formatter: (value) => `${value}%`
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            }
        );

        // Auto-refresh data every 60 seconds
        setInterval(() => {
            fetchSalesData();
        }, 60000);

        async function fetchSalesData() {
            try {
                showLoading();
                const response = await fetch('/api/sales-data');
                const data = await response.json();

                // Update charts with new data
                pieChart.data.datasets[0].data = [data.inStock, data.sold];
                doughnutChart.data.datasets[0].data = [data.electronics, data.clothing, data.homeGoods, data
                    .other
                ];

                pieChart.update();
                doughnutChart.update();

                showToast('Data refreshed successfully');
            } catch (error) {
                showToast('Failed to refresh data', 'error');
                console.error('Error fetching data:', error);
            } finally {
                hideLoading();
            }
        }
    });
    </script>