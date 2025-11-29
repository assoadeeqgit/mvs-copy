<?php
require('../include/config.php');

// Query to get sales data (example: products in stock and products sold)
$sql = 'SELECT SUM(stock_quantity) AS total_stock, SUM(sold_quantity) AS total_sold FROM products';
$query = $dbh->prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

// Fetch the actual sales data for stock and sold products
$productsInStock = $result['total_stock'] ?? 0;
$productsSold = $result['total_sold'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Dashboard - Manage Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#0a2342', // Navy
                    secondary: '#f8f9fa', // Soft white
                    background: '#ffffff', // Pure White
                    accent: '#d1d5db', // Light Gray for borders
                }
            }
        }
    }
    </script>
</head>

<body class="bg-background font-sans text-gray-800">

    <div class="flex">

        <!-- Sidebar -->
        <div class="w-64 bg-primary text-white p-6">
            <h2 class="text-2xl font-semibold mb-8">Vendor Dashboard</h2>
            <ul class="space-y-6">
                <li><a href="vendor_dashboard.html" class="block py-2 px-4 hover:bg-primary/70 rounded">Dashboard</a>
                </li>
                <li><a href="manage_products.html" class="block py-2 px-4 hover:bg-primary/70 rounded">Manage
                        Products</a></li>
                <li><a href="profile.html" class="block py-2 px-4 hover:bg-primary/70 rounded">Profile</a></li>
                <li><a href="sales_overview.html" class="block py-2 px-4 hover:bg-primary/70 rounded">Sales Overview</a>
                </li>
                <li><a href="logout.html" class="block py-2 px-4 hover:bg-primary/70 rounded">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 bg-secondary p-8">
            <h1 class="text-3xl font-bold mb-8">Manage Products</h1>

            <!-- Dashboard Overview -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Sales -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex justify-between items-center card">
                    <div>
                        <h3 class="text-xl font-semibold">Total Sales</h3>
                        <p class="text-2xl font-bold text-primary">$12,345</p>
                    </div>
                    <div class="bg-primary text-white rounded-full p-4">
                        <i class="fas fa-chart-line"></i> <!-- Sales icon -->
                    </div>
                </div>

                <!-- Total Products Sold -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex justify-between items-center card">
                    <div>
                        <h3 class="text-xl font-semibold">Products Sold</h3>
                        <p class="text-2xl font-bold text-primary">1,234</p>
                    </div>
                    <div class="bg-primary text-white rounded-full p-4">
                        <i class="fas fa-box"></i> <!-- Box icon -->
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex justify-between items-center card">
                    <div>
                        <h3 class="text-xl font-semibold">Total Orders</h3>
                        <p class="text-2xl font-bold text-primary">500</p>
                    </div>
                    <div class="bg-primary text-white rounded-full p-4">
                        <i class="fas fa-clipboard-list"></i> <!-- Clipboard icon -->
                    </div>
                </div>

                <!-- Current Inventory -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex justify-between items-center card">
                    <div>
                        <h3 class="text-xl font-semibold">Current Inventory</h3>
                        <p class="text-2xl font-bold text-primary">850</p>
                    </div>
                    <div class="bg-primary text-white rounded-full p-4">
                        <i class="fas fa-cogs"></i> <!-- Gear icon -->
                    </div>
                </div>
            </section>

            <!-- Pie Chart for Products in Stock vs Products Sold -->
            <div class="bg-white rounded-2xl shadow-lg p-6 grid grid-cols-1 sm:grid-cols-2 gap-8">
                <div class="w-full">
                    <h2 class="text-xl font-semibold mb-4">Products in Stock vs Products Sold (Pie Chart)</h2>
                    <div class="h-64 relative">
                        <canvas id="salesPieChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <div class="w-full">
                    <h2 class="text-xl font-semibold mb-4">Product Sales Distribution (Doughnut Chart)</h2>
                    <div class="h-64 relative">
                        <canvas id="salesDoughnutChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <section class="bg-white rounded-2xl shadow-lg p-6 mt-8">
                <h2 class="text-2xl font-semibold mb-4">Recent Activity</h2>
                <div class="space-y-4">
                    <!-- Activity Item -->
                    <div class="flex items-center space-x-4">
                        <div class="bg-primary text-white rounded-full p-2">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <p class="font-semibold">New Order Placed</p>
                            <p class="text-sm text-gray-600">A new order for 5 items was placed.</p>
                            <p class="text-xs text-gray-400">Just now</p>
                        </div>
                    </div>
                    <!-- Activity Item -->
                    <div class="flex items-center space-x-4">
                        <div class="bg-primary text-white rounded-full p-2">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Product Stock Updated</p>
                            <p class="text-sm text-gray-600">Stock for "Product A" updated to 100 units.</p>
                            <p class="text-xs text-gray-400">2 hours ago</p>
                        </div>
                    </div>
                    <!-- Activity Item -->
                    <div class="flex items-center space-x-4">
                        <div class="bg-primary text-white rounded-full p-2">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Sales Target Achieved</p>
                            <p class="text-sm text-gray-600">Sales target for this month has been achieved.</p>
                            <p class="text-xs text-gray-400">1 day ago</p>
                        </div>
                    </div>
                    <!-- Activity Item -->
                    <div class="flex items-center space-x-4">
                        <div class="bg-primary text-white rounded-full p-2">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Product Added</p>
                            <p class="text-sm text-gray-600">"Product B" has been added to the inventory.</p>
                            <p class="text-xs text-gray-400">3 days ago</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <script>
    // Data for the charts (products in stock and products sold)
    const productsInStock = <?php echo $productsInStock; ?>;
    const productsSold = <?php echo $productsSold; ?>;

    // Pie Chart for Products in Stock vs Products Sold
    const ctxPie = document.getElementById('salesPieChart').getContext('2d');
    const salesPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Products in Stock', 'Products Sold'],
            datasets: [{
                label: 'Product Distribution',
                data: [productsInStock, productsSold],
                backgroundColor: ['#0a2342', '#4B8FF6'],
                borderColor: ['#0a2342', '#4B8FF6'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' units';
                        }
                    }
                }
            }
        }
    });

    // Doughnut Chart for Product Sales Distribution
    const ctxDoughnut = document.getElementById('salesDoughnutChart').getContext('2d');
    const salesDoughnutChart = new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['Sold', 'Remaining Stock'],
            datasets: [{
                label: 'Sales Distribution',
                data: [productsSold, productsInStock],
                backgroundColor: ['#4B8FF6', '#0a2342'],
                borderColor: ['#4B8FF6', '#0a2342'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' units';
                        }
                    }
                }
            }
        }
    });
    </script>

</body>

</html>