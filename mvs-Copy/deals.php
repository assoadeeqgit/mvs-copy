<?php
include("./include/config.php");

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
$user_logged_in = !empty($user_id);

// Get cart count if user is logged in
$cartCount = 0;
if ($user_logged_in) {
    try {
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
        error_log("Wishlist count query failed: " . $e->getMessage());
    }
}

// Fetch discounted products
$deals = [];
try {
    $sql = "SELECT 
                p.product_id,
                p.name AS product_name,
                p.price,
                p.discounted_price,
                pi.image_url,
                v.business_name AS vendor_name
            FROM products p
            JOIN vendors v ON p.vendor_id = v.vendor_id
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            WHERE p.discounted_price IS NOT NULL 
            AND p.discounted_price < p.price
            ORDER BY p.discounted_price ASC
            LIMIT 12"; // Limit to 12 deals for performance
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $deals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Deals query failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Deals - MultiVendor Marketplace</title>
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
    <style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    .deal-card:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <?php include('./include/user_header.php'); ?>

    <!-- Deals Section -->
    <section class="container mx-auto px-4 py-12">
        <!-- Deals Title -->
        <h2 class="text-3xl font-bold mb-8">Hot Deals</h2>

        <!-- Deals Grid -->
        <?php if (empty($deals)): ?>
        <div class="text-center py-8">
            <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No deals available at the moment.</p>
            <a href="home.php" class="text-primary hover:underline mt-2 inline-block">Browse All Products</a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($deals as $deal): 
                    // Calculate discount percentage
                    $discountPercent = round((($deal['price'] - $deal['discounted_price']) / $deal['price']) * 100);
                    $image = $deal['image_url'] ?: 'https://via.placeholder.com/300x200?text=' . urlencode($deal['product_name']);
                ?>
            <div class="deal-card bg-white rounded-lg shadow-md overflow-hidden transition deal-card animate-fade-in">
                <div class="relative">
                    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($deal['product_name']) ?>"
                        class="w-full h-48 object-cover">
                    <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                        <i class="fas fa-store mr-1"></i> <?= htmlspecialchars($deal['vendor_name']) ?>
                    </div>
                    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                        <?= $discountPercent ?>% OFF
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1"><?= htmlspecialchars($deal['product_name']) ?></h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">(0)</span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span
                                class="font-bold text-lg text-primary">$<?= number_format($deal['discounted_price'], 2) ?></span>
                            <span
                                class="text-gray-500 line-through text-sm ml-2">$<?= number_format($deal['price'], 2) ?></span>
                        </div>
                        <button
                            class="add-to-cart bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition"
                            data-product-id="<?= $deal['product_id'] ?>">
                            <i class="fas fa-plus"></i>
                        </button>
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
                        <li><a href="home.php" class="text-gray-400 hover:text-white transition">All Products</a></li>
                        <li><a href="home.php#featured" class="text-gray-400 hover:text-white transition">Featured</a>
                        </li>
                        <li><a href="deals.php" class="text-gray-400 hover:text-white transition">Deals</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Sell</h4>
                    <ul class="space-y-2">
                        <li><a href="vendor-signup.php" class="text-gray-400 hover:text-white transition">Become a
                                Vendor</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Vendor Dashboard</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Seller Resources</a></li>
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
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
    // Mobile Menu Toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    const mobileCategoriesButton = document.getElementById('mobile-categories-button');
    const mobileCategoriesMenu = document.getElementById('mobile-categories-menu');
    mobileCategoriesButton.addEventListener('click', () => {
        mobileCategoriesMenu.classList.toggle('hidden');
    });

    const megaMenuButton = document.getElementById('mega-menu-button');
    const megaMenu = document.getElementById('mega-menu');
    megaMenuButton.addEventListener('click', (e) => {
        if (window.innerWidth < 768) {
            e.preventDefault();
            megaMenu.classList.toggle('hidden');
        }
    });

    document.addEventListener('click', (e) => {
        if (window.innerWidth < 768 && !megaMenuButton.contains(e.target) && !megaMenu.contains(e.target)) {
            megaMenu.classList.add('hidden');
        }
    });

    // Add to Cart Functionality
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', async function() {
                const productId = this.dataset.productId;
                if (!<?php echo $user_logged_in ? 'true' : 'false'; ?>) {
                    alert('Please log in to add items to your cart.');
                    window.location.href = 'login.php';
                    return;
                }
                try {
                    const response = await fetch('add_to_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    });
                    const result = await response.json();
                    if (result.success) {
                        // Update cart count in header
                        const cartCountElements = document.querySelectorAll('.cart-count');
                        const currentCount = parseInt(cartCountElements[0]?.textContent ||
                            '0');
                        cartCountElements.forEach(el => {
                            el.textContent = currentCount + 1;
                            el.style.display = 'flex';
                        });
                        alert('Product added to cart!');
                    } else {
                        alert('Failed to add product to cart: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while adding to cart.');
                }
            });
        });
    });
    </script>
</body>

</html>