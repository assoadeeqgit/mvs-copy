<?php

include("./include/config.php");

$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['user_name'] ?? '';
$role = $_SESSION['user_role'] ?? '';
$user_logged_in = isset($_SESSION['user_id']);

// Get search query from URL and sanitize
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
$searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8');

// Fetch products matching the search query
$products = [];
$noResults = false;

if (!empty($searchQuery)) {
    try {
        $sql = "SELECT p.product_id, p.name, p.description, p.price, p.discounted_price, p.stock_quantity, p.is_published, 
                       p.vendor_id, pi.image_url, c.name AS category_name, v.business_name,
                       COALESCE(AVG(pr.rating), 0) AS avg_rating, COUNT(pr.review_id) AS review_count
                FROM products p
                LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN vendors v ON p.vendor_id = v.vendor_id
                LEFT JOIN product_reviews pr ON p.product_id = pr.product_id
                WHERE (p.name LIKE :query OR p.description LIKE :query) AND p.is_published = 1
                GROUP BY p.product_id
                ORDER BY p.name ASC";
        $query = $dbh->prepare($sql);
        $query->execute(['query' => "%$searchQuery%"]);
        $products = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($products)) {
            $noResults = true;
        }
    } catch (PDOException $e) {
        error_log("Search query failed: " . $e->getMessage());
        $_SESSION['error_message'] = 'Error loading search results.';
    }
}

// Get cart count if user is logged in
$cartCount = 0;
if ($user_logged_in) {
    try {
        $sql = "SELECT COUNT(ci.cart_item_id)
                FROM cart_items ci
                JOIN carts c ON ci.cart_id = c.cart_id
                WHERE c.user_id = :user_id AND c.status = 'active'";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $cartCount = $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Cart count query failed: " . $e->getMessage());
    }
}

// Get wish count if user is logged in
$wishCount = 0;
if ($user_logged_in) {
    try {
        $sql = "SELECT COUNT(*) FROM wishlists WHERE user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $wishCount = $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Wishlist count query failed: " . $e->getMessage());
    }
}

// Handle success/error messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Search Results - MultiVendor Marketplace</title>
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
    <?php include('./include/user_header.php'); ?>

    <!-- Search Results Section -->
    <section class="container mx-auto px-4 py-12">
        <?php if ($success_message): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <p><?php echo htmlspecialchars($success_message); ?></p>
        </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <p><?php echo htmlspecialchars($error_message); ?></p>
        </div>
        <?php endif; ?>

        <h2 class="text-3xl font-bold mb-8">
            Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"
        </h2>

        <?php if ($noResults): ?>
        <div class="text-center text-gray-600">
            <p class="text-lg">No products found for "<?php echo htmlspecialchars($searchQuery); ?>"</p>
            <p class="mt-2">Try a different search term or browse our <a href="home.php"
                    class="text-primary hover:underline">categories</a>.</p>
        </div>
        <?php elseif (empty($searchQuery)): ?>
        <div class="text-center text-gray-600">
            <p class="text-lg">Please enter a search term to find products.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-lg">
                <div class="relative">
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://via.placeholder.com/300x200?text=No+Image'); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover">
                    <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                        <i class="fas fa-store mr-1"></i>
                        <?php echo htmlspecialchars($product['business_name'] ?: 'Unknown Vendor'); ?>
                    </div>
                    <form action="add_to_wishlist.php" method="POST" class="absolute top-2 right-2">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="redirect"
                            value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                        <button type="submit"
                            class="bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <?php
                            $rating = floatval($product['avg_rating']);
                            $review_count = intval($product['review_count']);
                            $fullStars = floor($rating);
                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                            for ($i = 0; $i < $fullStars; $i++):
                            ?>
                            <i class="fas fa-star"></i>
                            <?php endfor; ?>
                            <?php if ($hasHalfStar): ?>
                            <i class="fas fa-star-half-alt"></i>
                            <?php endif; ?>
                            <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                            <i class="far fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">(<?php echo $review_count; ?>)</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <?php if ($product['discounted_price'] !== null && $product['discounted_price'] < $product['price']): ?>
                            <span
                                class="text-gray-500 line-through">$<?php echo number_format($product['price'], 2); ?></span>
                            <span
                                class="font-bold text-lg ml-2">$<?php echo number_format($product['discounted_price'], 2); ?></span>
                            <?php else: ?>
                            <span class="font-bold text-lg">$<?php echo number_format($product['price'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
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