<?php
session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-lg px-8 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-navy">MyShop</div>
        <div class="flex items-center space-x-6">
            <span class="text-gray-700">Hi, <?= $_SESSION['user_name']; ?>!</span>
            <a href="logout.php" class="bg-navy text-white px-4 py-2 rounded-md hover:bg-blue-900 transition">Logout</a>
        </div>
    </nav>

    <!-- Main content -->
    <div class="p-8">

        <!-- Greeting -->
        <h1 class="text-3xl font-bold mb-6 text-navy">Welcome Back!</h1>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- Orders Card -->
            <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center">
                <img src="https://img.icons8.com/ios/100/1E3A8A/shopping-bag--v1.png" class="w-16 h-16 mr-4"
                    alt="Orders">
                <div>
                    <h2 class="text-xl font-semibold text-navy">My Orders</h2>
                    <p class="text-gray-600">Track your orders</p>
                </div>
            </div>

            <!-- Wishlist Card -->
            <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center">
                <img src="https://img.icons8.com/ios/100/1E3A8A/like--v1.png" class="w-16 h-16 mr-4" alt="Wishlist">
                <div>
                    <h2 class="text-xl font-semibold text-navy">Wishlist</h2>
                    <p class="text-gray-600">Your favorite products</p>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center">
                <img src="https://img.icons8.com/ios/100/1E3A8A/user--v1.png" class="w-16 h-16 mr-4" alt="Profile">
                <div>
                    <h2 class="text-xl font-semibold text-navy">My Profile</h2>
                    <p class="text-gray-600">Manage your info</p>
                </div>
            </div>

        </div>

        <!-- Latest Orders Table -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 text-navy">Recent Orders</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-4 py-2 text-left">Order ID</th>
                            <th class="px-4 py-2 text-left">Product</th>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="px-4 py-3">#12345</td>
                            <td class="px-4 py-3">Wireless Headphones</td>
                            <td class="px-4 py-3">2025-04-20</td>
                            <td class="px-4 py-3 text-green-600 font-bold">Delivered</td>
                        </tr>
                        <tr class="border-b">
                            <td class="px-4 py-3">#12346</td>
                            <td class="px-4 py-3">Smart Watch</td>
                            <td class="px-4 py-3">2025-04-22</td>
                            <td class="px-4 py-3 text-yellow-500 font-bold">Processing</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">#12347</td>
                            <td class="px-4 py-3">Bluetooth Speaker</td>
                            <td class="px-4 py-3">2025-04-25</td>
                            <td class="px-4 py-3 text-blue-500 font-bold">Shipped</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>