<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Dashboard - Sales Overview</title>
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
        <?php include('../include/aside.php'); ?>

        <!-- Main Content -->
        <main id="main-content"
            class="flex-1 bg-secondary dark:bg-secondary-dark p-6 md:p-8 transition-colors duration-200">
            <div id="content">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Sales Overview</h1>
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
                        </div>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="date" id="startDate"
                                class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                            <i class="fas fa-calendar-alt absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <span>to</span>
                        <div class="relative">
                            <input type="date" id="endDate"
                                class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                            <i class="fas fa-calendar-alt absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button id="applyFilter"
                            class="bg-primary dark:bg-primary-dark hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition">
                            Apply
                        </button>
                    </div>
                    <button
                        class="w-full md:w-auto bg-primary dark:bg-primary-dark hover:bg-opacity-90 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition">
                        <i class="fas fa-download"></i>
                        <span>Export Report</span>
                    </button>
                </div>

                <!-- Sales Metrics Cards -->
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Revenue -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">$24,580</h3>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 15.2% from last month
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="#"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">View
                                details</a>
                        </div>
                    </div>

                    <!-- Total Orders -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">684</h3>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 9.8% from last month
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-shopping-cart text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="#"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">View
                                orders</a>
                        </div>
                    </div>

                    <!-- Average Order Value -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg. Order Value</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">$35.94</h3>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 4.7% from last month
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-chart-pie text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="#"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">View
                                trends</a>
                        </div>
                    </div>

                    <!-- Conversion Rate -->
                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Conversion Rate</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">3.2%</h3>
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                    <i class="fas fa-arrow-down mr-1"></i> 0.8% from last month
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-percentage text-xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <a href="#"
                                class="text-sm font-medium text-primary dark:text-primary-dark hover:underline">Improve
                                conversion</a>
                        </div>
                    </div>
                </section>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Sales Trend Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Sales Trend</h2>
                            <select
                                class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                                <option>Daily</option>
                                <option selected>Weekly</option>
                                <option>Monthly</option>
                            </select>
                        </div>
                        <div class="h-80 relative">
                            <canvas id="salesTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Revenue by Category Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Revenue by Category</h2>
                            <select
                                class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                                <option>This Week</option>
                                <option selected>This Month</option>
                                <option>This Year</option>
                            </select>
                        </div>
                        <div class="h-80 relative">
                            <canvas id="revenueCategoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Inventory vs Sold Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Inventory vs Sold</h2>
                        <button class="text-sm text-primary dark:text-primary-dark font-medium hover:underline">View
                            details</button>
                    </div>
                    <div class="h-96 relative">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>

                <!-- Top Selling Products -->
                <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Top Selling Products</h2>
                        <button class="text-sm text-primary dark:text-primary-dark font-medium hover:underline">View
                            all</button>
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
                                        Sold</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Revenue</th>
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
                                        <div class="text-sm text-gray-900 dark:text-white">156</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-primary dark:text-primary-dark">$15,598.44
                                        </div>
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
                                        <div class="text-sm text-gray-900 dark:text-white">89</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-primary dark:text-primary-dark">$17,799.11
                                        </div>
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
                                        <div class="text-sm text-gray-900 dark:text-white">215</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-primary dark:text-primary-dark">$5,372.85
                                        </div>
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
                                        <div class="text-sm text-gray-900 dark:text-white">178</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-primary dark:text-primary-dark">$2,668.22
                                        </div>
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
                                        <div class="text-sm text-gray-900 dark:text-white">92</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-primary dark:text-primary-dark">$7,359.08
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
        setTimeout(() => showToast('Sales dashboard loaded successfully'), 1000);

        // Charts Data
        // Sales Trend Chart (Line Chart)
        const salesTrendChart = new Chart(
            document.getElementById('salesTrendChart'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Revenue',
                        data: [4500, 5200, 4800, 6200, 7500, 8100, 9300],
                        borderColor: '#0a2342',
                        backgroundColor: 'rgba(10, 35, 66, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Revenue: $${context.raw.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            }
        );

        // Revenue by Category Chart (Doughnut Chart)
        const revenueCategoryChart = new Chart(
            document.getElementById('revenueCategoryChart'), {
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
                                    return `${context.label}: $${(context.raw * 1000).toLocaleString()} (${context.parsed}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            }
        );

        // Inventory vs Sold Chart (Bar Chart)
        const inventoryChart = new Chart(
            document.getElementById('inventoryChart'), {
                type: 'bar',
                data: {
                    labels: ['Electronics', 'Clothing', 'Home Goods', 'Other'],
                    datasets: [{
                        label: 'In Stock',
                        data: [1200, 800, 600, 200],
                        backgroundColor: '#0a2342',
                        borderWidth: 0
                    }, {
                        label: 'Sold',
                        data: [800, 500, 300, 100],
                        backgroundColor: '#4B8FF6',
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
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
                                    return `${context.dataset.label}: ${context.raw} units`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            }
        );

        // Apply Filter Button
        document.getElementById('applyFilter').addEventListener('click', function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                showToast('Please select both start and end dates', 'error');
                return;
            }

            showToast(`Filter applied: ${startDate} to ${endDate}`);
        });

        // Set default dates (last 30 days)
        const today = new Date();
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(today.getDate() - 30);

        document.getElementById('startDate').valueAsDate = thirtyDaysAgo;
        document.getElementById('endDate').valueAsDate = today;
    });
    </script>
</body>

</html>