<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Products - Vendor Dashboard</title>
    <link rel="icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
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

    <!-- Vendor Dashboard Layout -->
    <div class="flex">

        <!-- Sidebar -->
        <div class="w-64 bg-primary text-white p-6">
            <h2 class="text-2xl font-bold mb-8">Vendor Dashboard</h2>
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
        <div class="flex-1 p-6">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Manage Your Products</h1>
                <p class="text-lg text-gray-600">View, Edit, or Delete your products.</p>
            </div>

            <!-- Products List -->
            <section>
                <div class="mb-6">
                    <a href="add_product.html"
                        class="bg-primary text-white px-6 py-3 rounded-full hover:bg-primary/80">Add New Product</a>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="bg-primary text-white p-4 rounded-t-lg">
                        Product List
                    </div>
                    <div class="p-4">
                        <!-- Product Table -->
                        <table class="min-w-full table-auto">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left">Product Name</th>
                                    <th class="py-3 px-4 text-left">Price</th>
                                    <th class="py-3 px-4 text-left">Stock</th>
                                    <th class="py-3 px-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Product 1 -->
                                <tr class="border-t border-accent">
                                    <td class="py-3 px-4">Laptop</td>
                                    <td class="py-3 px-4">$799.99</td>
                                    <td class="py-3 px-4">10</td>
                                    <td class="py-3 px-4">
                                        <a href="edit_product.html?id=1" class="text-primary hover:underline">Edit</a> |
                                        <a href="delete_product.html?id=1"
                                            class="text-red-500 hover:underline">Delete</a>
                                    </td>
                                </tr>
                                <!-- Product 2 -->
                                <tr class="border-t border-accent">
                                    <td class="py-3 px-4">Smartphone</td>
                                    <td class="py-3 px-4">$499.99</td>
                                    <td class="py-3 px-4">15</td>
                                    <td class="py-3 px-4">
                                        <a href="edit_product.html?id=2" class="text-primary hover:underline">Edit</a> |
                                        <a href="delete_product.html?id=2"
                                            class="text-red-500 hover:underline">Delete</a>
                                    </td>
                                </tr>
                                <!-- Product 3 -->
                                <tr class="border-t border-accent">
                                    <td class="py-3 px-4">Headphones</td>
                                    <td class="py-3 px-4">$199.99</td>
                                    <td class="py-3 px-4">20</td>
                                    <td class="py-3 px-4">
                                        <a href="edit_product.html?id=3" class="text-primary hover:underline">Edit</a> |
                                        <a href="delete_product.html?id=3"
                                            class="text-red-500 hover:underline">Delete</a>
                                    </td>
                                </tr>
                                <!-- Product 4 -->
                                <tr class="border-t border-accent">
                                    <td class="py-3 px-4">Camera</td>
                                    <td class="py-3 px-4">$999.99</td>
                                    <td class="py-3 px-4">5</td>
                                    <td class="py-3 px-4">
                                        <a href="edit_product.html?id=4" class="text-primary hover:underline">Edit</a> |
                                        <a href="delete_product.html?id=4"
                                            class="text-red-500 hover:underline">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </div>

</body>

</html>