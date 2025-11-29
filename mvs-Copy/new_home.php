<?php
 include("./include/config.php");
// Fetch featured products
$featuredProducts = [];
try {
    $sql = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.is_published, 
                   p.vendor_id, pi.image_url, c.name AS category_name, v.business_name
            FROM products p
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN vendors v ON p.vendor_id = v.vendor_id
            WHERE p.is_published = 1 AND p.is_featured = 1
            ORDER BY p.name ASC
            LIMIT 8";
    $stmt = $dbh->query($sql);
    $featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiVendor Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#4f46e5',
                    secondary: '#10b981',
                    dark: '#1f2937',
                    light: '#f9fafb',
                },
            }
        }
    }
    </script>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo" class="h-8">
            </div>
            <div class="hidden md:flex flex-1 mx-8">
                <form action="search-results.php" method="GET" class="relative w-full">
                    <input type="text" name="query" placeholder="Search for products..."
                        class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <button type="submit" class="absolute right-0 top-0 h-full px-4 text-gray-500">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="flex items-center space-x-4">
                <a href="user_profile.php" class="p-2 text-gray-700 hover:text-primary"><i
                        class="fas fa-user text-lg"></i></a>
                <a href="cart.php" class="p-2 text-gray-700 hover:text-primary relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span
                        class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </a>
                <button class="md:hidden p-2 text-gray-700"><i class="fas fa-bars text-lg"></i></button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-light py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Discover Unique Products from Independent Sellers</h1>
            <p class="text-lg text-gray-600 mb-8">Shop a wide range of handcrafted, vintage, and eco-friendly goods.</p>
            <form action="search-results.php" method="GET" class="max-w-xl mx-auto">
                <div class="relative">
                    <input type="text" name="query" placeholder="Search for products..."
                        class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <button type="submit" class="absolute right-0 top-0 h-full px-4 text-gray-500">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-8">Featured Products</h2>
        <?php if (empty($featuredProducts)): ?>
        <div class="text-center text-gray-600">
            <p class="text-lg">No featured products available at the moment.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-lg">
                <div class="relative">
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://via.placeholder.com/300x200?text=No+Image'); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover">
                    <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                        <i class="fas fa-store mr-1"></i>
                        <?php echo htmlspecialchars($product['business_name'] ?: 'Unknown Vendor'); ?>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <!-- Placeholder for rating -->
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">(0)</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg">$<?php echo number_format($product['price'], 2); ?></span>
                        <button name="wish"
                            class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                            <a href="wish.php"><i class="fas fa-plus"></i></a>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>

    <!-- Categories Section (Static Placeholder) -->
    <section class="bg-light py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8">Shop by Category</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="search-results.php?query=Electronics"
                    class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-laptop text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold">Electronics</h3>
                </a>
                <a href="search-results.php?query=Clothing"
                    class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-tshirt text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold">Clothing</h3>
                </a>
                <a href="search-results.php?query=Books"
                    class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-book text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold">Books</h3>
                </a>
                <a href="search-results.php?query=Furniture"
                    class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-couch text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold">Furniture</h3>
                </a>
                <a href="search-results.php?query=Beauty"
                    class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-spa text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold">Beauty</h3>
                </a>
                <a href="search-results.php?query=Toys"
                    class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-robot text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold">Toys</h3>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo" class="h-8 mb-4">
                    <p class="text-gray-400">The marketplace for independent sellers and buyers.</p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Shop</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">All Products</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Featured</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">New Arrivals</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Deals</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Sell</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Become a Vendor</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Vendor Dashboard</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Seller Resources</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i> 123
                            Market St, City</li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-phone mr-2"></i> (123) 456-7890
                        </li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-envelope mr-2"></i>
                            support@multivendor.com</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">© 2025 MultiVendor. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>