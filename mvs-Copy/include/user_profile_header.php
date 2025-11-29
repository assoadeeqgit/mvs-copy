<?php
// Ensure session variables are available
$username = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest';
$user_logged_in = isset($_SESSION['user_id']);
$cartCount = isset($cartCount) ? $cartCount : 0;
$wishCount = isset($wishCount) ? $wishCount : 0;
?>

<header class="bg-white shadow sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center">
            <a href="index.php"><img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo"
                    class="h-8"></a>
        </div>
        <!-- Search Bar -->
        <form action="index.php" method="GET" class="flex-1 mx-4 hidden md:flex">
            <input type="text" name="query" placeholder="Search products..."
                value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>"
                class="flex-1 py-2 px-4 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary">
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r-lg hover:bg-indigo-700 transition">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-8">
            <a href="index.php" class="text-gray-600 hover:text-primary relative header-link">Home</a>
            <a href="index.php#categories" class="text-gray-600 hover:text-primary relative header-link">Categories</a>
            <a href="deals.php" class="text-gray-600 hover:text-primary relative header-link">Deals</a>
            <a href="cart.php" class="text-gray-600 hover:text-primary relative header-link">
                Cart <span class="cart-count"><?php echo $cartCount; ?></span>
            </a>
        </nav>
        <div class="flex items-center space-x-4">
            <?php if ($user_logged_in): ?>
            <a href="user_profile.php" class="text-gray-600 hover:text-primary flex items-center">
                <i class="fas fa-user mr-1"></i> <?php echo $username; ?>
            </a>
            <a href="wishlist.php" class="text-gray-600 hover:text-primary relative">
                <i class="fas fa-heart"></i>
                <span
                    class="wish-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1"><?php echo $wishCount; ?></span>
            </a>
            <a href="logout.php" class="text-gray-600 hover:text-red-500"><i class="fas fa-sign-out-alt"></i></a>
            <?php else: ?>
            <a href="login.php" class="text-gray-600 hover:text-primary">Login</a>
            <a href="signup.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Sign
                Up</a>
            <?php endif; ?>
            <button id="mobile-menu-button" class="md:hidden text-gray-600 hover:text-primary">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-md">
        <form action="index.php" method="GET" class="p-4">
            <input type="text" name="query" placeholder="Search products..."
                value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>"
                class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary">
        </form>
        <nav class="flex flex-col p-4 space-y-2">
            <a href="index.php" class="text-gray-600 hover:text-primary">Home</a>
            <div>
                <button id="mobile-categories-button" class="text-gray-600 hover:text-primary flex items-center">
                    Categories <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div id="mobile-categories-menu" class="hidden pl-4 space-y-2">
                    <a href="category.php?id=1" class="block text-gray-600 hover:text-primary">Electronics</a>
                    <a href="category.php?id=2" class="block text-gray-600 hover:text-primary">Fashion</a>
                    <a href="category.php?id=3" class="block text-gray-600 hover:text-primary">Home & Garden</a>
                    <a href="category.php?id=4" class="block text-gray-600 hover:text-primary">Beauty</a>
                </div>
            </div>
            <a href="deals.php" class="text-gray-600 hover:text-primary">Deals</a>
            <a href="cart.php" class="text-gray-600 hover:text-primary">Cart (<?php echo $cartCount; ?>)</a>
            <?php if ($user_logged_in): ?>
            <a href="user_profile.php" class="text-gray-600 hover:text-primary"><?php echo $username; ?></a>
            <a href="wishlist.php" class="text-gray-600 hover:text-primary">Wishlist (<?php echo $wishCount; ?>)</a>
            <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
            <?php else: ?>
            <a href="login.php" class="text-gray-600 hover:text-primary">Login</a>
            <a href="signup.php" class="text-gray-600 hover:text-primary">Sign Up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<style>
.header-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background-color: #4f46e5;
    transition: width 0.3s ease;
}

.header-link:hover::after {
    width: 100%;
}
</style>