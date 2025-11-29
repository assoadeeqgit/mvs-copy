<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <aside id="sidebar" class="w-64 bg-primary dark:bg-primary-dark text-white p-6 shadow-lg md:shadow-none">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-semibold hidden md:block">Vendor Dashboard</h2>
            </div>
            <ul class="space-y-3">
                <li class="relative">
                    <a href="#dashboard" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="#products" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-boxes mr-3"></i>
                        <span>Manage Products</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="#profile" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-user mr-3"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="#sales" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-chart-bar mr-3"></i>
                        <span>Sales Overview</span>
                    </a>
                </li>
                <li class="relative">
                    <a href="#inventory" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-warehouse mr-3"></i>
                        <span>Inventory</span>
                    </a>
                </li>
                <li class="relative mt-8 pt-4 border-t border-white/20">
                    <a href="#logout" class="flex items-center py-2 px-4 hover:bg-black/20 rounded-lg transition">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main id="main-content"
            class="flex-1 bg-secondary dark:bg-secondary-dark p-6 md:p-8 transition-colors duration-200">
            <!-- Dashboard Content -->
            <div id="dashboard-content" class="content-section">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Dashboard Overview</h1>
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

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                    </div>

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
                    </div>

                    <div class="card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Products</p>
                                <h3 class="text-2xl font-bold text-primary dark:text-primary-dark mt-1">128</h3>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 12 new this month
                                </p>
                            </div>
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-3">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                        </div>
                    </div>

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
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Recent Activity</h2>
                        <button class="text-sm text-primary dark:text-primary-dark font-medium hover:underline">View
                            all</button>
                    </div>
                    <div class="space-y-4">
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
                                    <i class="far fa-clock mr-1"></i> Just now
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-start space-x-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                            <div class="bg-primary dark:bg-primary-dark text-white rounded-full p-2 flex-shrink-0">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold truncate">New Order Received</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Order #ORD-2023-1056 for 3 items</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-clock mr-1"></i> 2 hours ago
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Content (Hidden by default) -->
            <div id="products-content" class="content-section hidden">
                <!-- Products content would go here -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Manage Products</h1>
                    <!-- User menu buttons same as dashboard -->
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Your Products</h2>
                        <button
                            class="bg-primary dark:bg-primary-dark hover:bg-opacity-90 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Add Product</span>
                        </button>
                    </div>

                    <!-- Products table would go here -->
                    <p class="text-gray-500 dark:text-gray-400">Product management content</p>
                </div>
            </div>

            <!-- Profile Content (Hidden by default) -->
            <div id="profile-content" class="content-section hidden">
                <!-- Profile content would go here -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Vendor Profile</h1>
                    <!-- User menu buttons same as dashboard -->
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Personal Information</h2>
                        <button class="text-primary dark:text-primary-dark font-medium hover:underline">Edit</button>
                    </div>

                    <!-- Profile form would go here -->
                    <p class="text-gray-500 dark:text-gray-400">Profile management content</p>
                </div>
            </div>

            <!-- Sales Content (Hidden by default) -->
            <div id="sales-content" class="content-section hidden">
                <!-- Sales content would go here -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Sales Overview</h1>
                    <!-- User menu buttons same as dashboard -->
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Sales Analytics</h2>
                        <select
                            class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 bg-white dark:bg-gray-800">
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                        </select>
                    </div>

                    <!-- Sales charts would go here -->
                    <p class="text-gray-500 dark:text-gray-400">Sales analytics content</p>
                </div>
            </div>

            <!-- Inventory Content (Hidden by default) -->
            <div id="inventory-content" class="content-section hidden">
                <!-- Inventory content would go here -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Inventory Management</h1>
                    <!-- User menu buttons same as dashboard -->
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Stock Levels</h2>
                        <button class="text-primary dark:text-primary-dark font-medium hover:underline">Export
                            Report</button>
                    </div>

                    <!-- Inventory table would go here -->
                    <p class="text-gray-500 dark:text-gray-400">Inventory management content</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 space-y-3 z-50"></div>

    <script>
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

        // Navigation between pages
        const navLinks = document.querySelectorAll('#sidebar a');
        const contentSections = document.querySelectorAll('.content-section');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Hide all content sections
                contentSections.forEach(section => {
                    section.classList.add('hidden');
                });

                // Show the selected content section
                const target = this.getAttribute('href').substring(1);
                document.getElementById(`${target}-content`).classList.remove('hidden');

                // Close mobile menu if open
                sidebar.classList.remove('active');
                overlay.style.display = 'none';
                menuToggle.querySelector('i').classList.remove('fa-times');
                menuToggle.querySelector('i').classList.add('fa-bars');
            });
        });

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
        setTimeout(() => showToast('Dashboard loaded successfully'), 1000);

        // Initialize charts (example for dashboard)
        if (document.getElementById('dashboard-content')) {
            const ctx = document.createElement('canvas');
            ctx.id = 'salesChart';
            document.getElementById('dashboard-content').appendChild(ctx);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Sales',
                        data: [1200, 1900, 1500, 2000, 2200, 2500],
                        backgroundColor: '#0a2342',
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
    </script>
</body>

</html>