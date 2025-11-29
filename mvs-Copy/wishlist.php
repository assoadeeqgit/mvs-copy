<?php
include("./include/config.php");

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user_name'];
$role =  $_SESSION['user_role'];
$user_logged_in = isset($_SESSION['user_id']);

if(isset($user_id)) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
    header('Location: login.php');
}

// Fetch wishlist items for the user
$wishlistItems = [];
$emptyWishlist = false;

if ($isLoggedIn) {
    try {
        $sql = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.is_published, 
                       p.vendor_id, pi.image_url, c.name AS category_name, v.business_name
                FROM wishlists w
                JOIN products p ON w.product_id = p.product_id
                LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN vendors v ON p.vendor_id = v.vendor_id
                WHERE w.user_id = :user_id AND p.is_published = 1
                ORDER BY w.added_at DESC";
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $wishlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($wishlistItems)) {
            $emptyWishlist = true;
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

// Handle remove from wishlist (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id']) && $isLoggedIn) {
    try {
        $sql = "DELETE FROM wishlists WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'product_id' => $_POST['remove_product_id']]);
        header("Location: wishlist.php"); // Refresh page
        exit;
    } catch (PDOException $e) {
        die("Remove failed: " . $e->getMessage());
    }
}


// Get cart count if user is logged in
$cartCount = 0;
if ($user_logged_in) {
    try {
        // Join cart_items with carts to get the correct count for this user
        $sql = "SELECT COUNT(ci.cart_item_id)
FROM cart_items ci
JOIN carts c ON ci.cart_id = c.cart_id
WHERE c.user_id = :user_id";
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
        error_log("Cart count query failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>My Wishlist - MultiVendor Marketplace</title>
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

    <!-- Wishlist Section -->
    <section class="container mx-auto px-4 py-12">

        <!-- Wishlist Title and Items -->
        <h2 class="text-3xl font-bold mb-8">My Wishlist</h2>

        <?php if (!$isLoggedIn): ?>
        <div class="text-center text-gray-600">
            <p class="text-lg">Please log in to view your wishlist.</p>
            <p class="mt-2">Go to your <a href="user_profile.php" class="text-primary hover:underline">account</a> to
                log in.</p>
        </div>
        <?php elseif ($emptyWishlist): ?>
        <div class="text-center text-gray-600">
            <p class="text-lg">Your wishlist is empty.</p>
            <p class="mt-2">Explore our <a href="index.php#featured" class="text-primary hover:underline">products</a>
                to add items to your wishlist.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($wishlistItems as $item): ?>
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-lg">
                <div class="relative">
                    <img src="<?php echo htmlspecialchars($item['image_url'] ?: 'https://via.placeholder.com/300x200?text=No+Image'); ?>"
                        alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-48 object-cover">
                    <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                        <i class="fas fa-store mr-1"></i>
                        <?php echo htmlspecialchars($item['business_name'] ?: 'Unknown Vendor'); ?>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">(0)</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-lg">$<?php echo number_format($item['price'], 2); ?></span>
                        <form method="POST" class="inline">
                            <input type="hidden" name="remove_product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Remove from Wishlist">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                    <button class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                    </button>
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
                        <li><a href="index.php" class="text-gray-400 hover:text-white transition">All Products</a></li>
                        <li><a href="index.php#featured" class="text-gray-400 hover:text-white transition">Featured</a>
                        </li>
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
                    <h4 class="font-bold text-lg mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="help-center.php" class="text-gray-400 hover:text-white transition">Help Center</a>
                        </li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-phone mr-2"></i> (123) 456-7890
                        </li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-envelope mr-2"></i> <a
                                href="mailto:support@multivendor.com"
                                class="hover:text-white transition">support@multivendor.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">© 2025 MultiVendor. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i
                            class="fab somma fa-pinterest"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>