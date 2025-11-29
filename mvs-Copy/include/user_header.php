<?php
include("./include/config.php");

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user_name'];
$role =  $_SESSION['user_role'];
$user_logged_in = isset($_SESSION['user_id']);

// Get search query from URL and sanitize
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
$searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8');

// Fetch products matching the search query
$products = [];
$noResults = false;

if (!empty($searchQuery)) {
    try {
        $sql = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.is_published, 
                       p.vendor_id, pi.image_url, c.name AS category_name, v.business_name
                FROM products p
                LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN vendors v ON p.vendor_id = v.vendor_id
                WHERE (p.name LIKE :query OR p.description LIKE :query) AND p.is_published = 1
                AND p.is_published = 1
                ORDER BY p.name ASC";

        $stmt = $dbh->prepare($sql);
        $stmt->execute(['query' => "%$searchQuery%"]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($products)) {
            $noResults = true;
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
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

// In vendor_store.php or product detail page
// try {
//     $sql = "INSERT INTO recently_viewed (user_id, product_id, viewed_at) 
//             VALUES (:user_id, :product_id, NOW())
//             ON DUPLICATE KEY UPDATE viewed_at = NOW()";
//     $stmt = $dbh->prepare($sql);
//     $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
// } catch (PDOException $e) {
//     error_log("Recently viewed insert failed: " . $e->getMessage());
// }
?>

<header class="sticky top-0 z-50 bg-white shadow-md">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center">
            <img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo" class="h-8">
        </div>
        <div class="hidden md:flex flex-1 mx-4">
            <form action="search_results.php" method="GET" class="relative w-full">
                <input type="text" name="query" placeholder="Search for products..."
                    class="w-full py-3 px-6 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-base">
                <button type="submit" class="absolute right-0 top-0 h-full px-4 text-gray-500">
                    <i class="fas fa-search text-lg"></i>
                </button>
            </form>
        </div>
        <div class="flex items-center space-x-4">
            <a href="<?php echo $user_id ? 'user_profile.php' : 'login.php'; ?>"
                class="p-2 text-gray-700 hover:text-primary" title="Account">
                <i class="fas fa-user text-lg"></i>
            </a>
            <a href="cart.php" class="p-2 text-gray-700 hover:text-primary relative" title="Cart">
                <i class="fas fa-shopping-cart text-lg"></i>
                <?php if ($cartCount > 0): ?>
                <span
                    class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                    <?php echo $cartCount; ?>
                </span>
                <?php endif; ?>
            </a>
            <a href="wishlist.php" class="p-2 text-gray-700 hover:text-primary relative" title="Wishlist">
                <i class="fas fa-heart text-lg"></i>
                <?php if ($wishCount > 0): ?>
                <span
                    class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                    <?php echo $wishCount; ?>
                </span>
                <?php endif; ?>
            </a>
            <button id="mobile-menu-button" class="md:hidden p-2 text-gray-700" aria-label="Toggle Menu">
                <i class="fas fa-bars text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Desktop Navigation -->
    <nav class="hidden md:block border-t border-gray-100">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex space-x-6">
                <a href="home-copy.php" class="text-gray-700 hover:text-primary">Home</a>
                <div class="relative">
                    <button id="categories-toggle"
                        class="text-gray-700 hover:text-primary flex items-center focus:outline-none">
                        Categories <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div id="categories-menu"
                        class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md py-1 z-10 hidden">
                        <a href="category_products.php?category=Electronics"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Electronics</a>
                        <a href="category_products.php?category=Clothing"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Clothing</a>
                        <a href="category_products.php?category=Home%20&%20Kitchen"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Home & Kitchen</a>
                        <a href="category_products.php?category=Beauty"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Beauty</a>
                    </div>
                </div>


                <a href="deals.php" class="text-gray-700 hover:text-primary">Deals</a>
                <a href="vendors.php" class="text-gray-700 hover:text-primary">Vendors</a>
            </div>
            <a href="vendor_signup.php"
                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                Sell With Us
            </a>
        </div>
    </nav>

    <!-- Mobile Navigation -->
    <nav id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
        <div class="container mx-auto px-4 py-3 flex flex-col space-y-2">
            <a href="home.php" class="text-gray-700 hover:text-primary py-2">Home</a>
            <div class="relative">
                <button id="mobile-categories-button"
                    class="text-gray-700 hover:text-primary flex items-center py-2 w-full text-left">
                    Categories <i class="fas fa-chevron-down ml-1 text-xs"></i>
                </button>
                <div id="mobile-categories-menu" class="hidden pl-4 space-y-2">
                    <a href="category-products.php?category=Electronics"
                        class="block text-gray-700 hover:text-primary py-1">Electronics</a>
                    <a href="category-products.php?category=Clothing"
                        class="block text-gray-700 hover:text-primary py-1">Clothing</a>
                    <a href="category-products.php?category=Home%20&%20Kitchen"
                        class="block text-gray-700 hover:text-primary py-1">Home & Kitchen</a>
                    <a href="category-products.php?category=Beauty"
                        class="block text-gray-700 hover:text-primary py-1">Beauty</a>
                </div>
            </div>
            <a href="deals.php" class="text-gray-700 hover:text-primary py-2">Deals</a>
            <a href="vendors.php" class="text-gray-700 hover:text-primary py-2">Vendors</a>
            <a href="vendor-signup.php"
                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition inline-block">Sell With
                Us</a>
        </div>
    </nav>
</header>

<script>
// Toggle Mobile Menu
document.getElementById("mobile-menu-button").addEventListener("click", function() {
    document.getElementById("mobile-menu").classList.toggle("hidden");
});

// Toggle Mobile Categories
document.getElementById("mobile-categories-button").addEventListener("click", function() {
    document.getElementById("mobile-categories-menu").classList.toggle("hidden");
});


const toggleBtn = document.getElementById('categories-toggle');
const menu = document.getElementById('categories-menu');

document.addEventListener('click', (e) => {
    const isClickInside = toggleBtn.contains(e.target) || menu.contains(e.target);
    if (isClickInside) {
        menu.classList.toggle('hidden');
    } else {
        menu.classList.add('hidden'); // Hide if clicking outside
    }
});
</script>