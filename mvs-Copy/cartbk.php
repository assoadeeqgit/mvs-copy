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

if (!$user_logged_in) {
    header("Location: login.php");
    exit;
}

// Get cart items
$cartItems = [];
$subtotal = 0;
$cartCount = 0;

try {
    $sql = "SELECT 
                ci.cart_item_id,
                ci.quantity,
                p.product_id,
                p.name AS product_name,
                p.price,
                p.discounted_price,
                pi.image_url,
                v.business_name AS vendor_name
            FROM cart_items ci
            JOIN carts c ON ci.cart_id = c.cart_id
            JOIN products p ON ci.product_id = p.product_id
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            JOIN vendors v ON p.vendor_id = v.vendor_id
            WHERE c.user_id = :user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate cart count and subtotal
    $cartCount = count($cartItems);
    foreach ($cartItems as $item) {
        $price = $item['discounted_price'] !== null ? $item['discounted_price'] : $item['price'];
        $subtotal += $item['quantity'] * $price;
    }
} catch (PDOException $e) {
    error_log("Cart items query failed: " . $e->getMessage());
    echo "<div class='container mx-auto px-4 py-8 text-red-600'>Error loading cart. Please try again later.</div>";
    exit;
}

// Get wish count
$wishCount = 0;
try {
    $sql = "SELECT COUNT(*) FROM wishlists WHERE user_id = :user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $wishCount = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Wishlist query failed: " . $e->getMessage());
}

// Get recently viewed products
$recentlyViewed = [];
try {
    // Assuming a recently_viewed table: user_id, product_id, viewed_at
    $sql = "SELECT 
                p.product_id,
                p.name AS product_name,
                p.price,
                p.discounted_price,
                pi.image_url,
                v.business_name AS vendor_name
            FROM recently_viewed rv
            JOIN products p ON rv.product_id = p.product_id
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            JOIN vendors v ON p.vendor_id = v.vendor_id
            WHERE rv.user_id = :user_id
            ORDER BY rv.viewed_at DESC
            LIMIT 4";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $recentlyViewed = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Recently viewed query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - MultiVendor Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
                animation: {
                    'bounce-slow': 'bounce 3s infinite',
                    'float': 'float 6s ease-in-out infinite',
                    'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                },
                keyframes: {
                    float: {
                        '0%, 100%': {
                            transform: 'translateY(0)'
                        },
                        '50%': {
                            transform: 'translateY(-10px)'
                        },
                    }
                }
            }
        }
    }
    </script>
    <style>
    .product-card:hover {
        transform: translateY(-5px);
    }

    .quantity-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
        background-color: #f9fafb;
        cursor: pointer;
    }

    .quantity-btn:hover {
        background-color: #e5e7eb;
    }

    .quantity-input {
        width: 50px;
        text-align: center;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        border-left: none;
        border-right: none;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <?php include('./include/user_header.php'); ?>

    <!-- Cart Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Cart Items -->
            <div class="md:w-2/3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Your Cart (<span
                                class="cart-item-count"><?= $cartCount; ?></span> items)</h2>
                        <a href="home.php" class="text-primary hover:underline">Continue Shopping</a>
                    </div>

                    <?php if (empty($cartItems)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Your cart is empty</p>
                        <a href="home.php" class="text-primary hover:underline mt-2 inline-block">Start Shopping</a>
                    </div>
                    <?php else: ?>
                    <?php foreach ($cartItems as $item):
                        $price = $item['discounted_price'] !== null ? $item['discounted_price'] : $item['price'];
                        $totalPrice = $item['quantity'] * $price;
                        ?>
                    <div class="border-b border-gray-200 pb-6 mb-6 product-card transition"
                        data-id="<?= $item['product_id'] ?>">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-1/4">
                                <img src="<?= htmlspecialchars($item['image_url'] ?: 'https://via.placeholder.com/150') ?>"
                                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                                    class="w-full h-auto rounded-lg">
                            </div>
                            <div class="w-full md:w-3/4">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="font-semibold text-lg"><?= htmlspecialchars($item['product_name']) ?>
                                        </h3>
                                        <p class="text-gray-600 text-sm">Sold by: <span
                                                class="text-secondary"><?= htmlspecialchars($item['vendor_name']) ?></span>
                                        </p>
                                    </div>
                                    <button class="text-gray-400 hover:text-red-500 remove-item"
                                        data-cart-item-id="<?= $item['cart_item_id'] ?>">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center">
                                        <button class="quantity-btn decrease">-</button>
                                        <input type="number" value="<?= $item['quantity'] ?>" min="1"
                                            class="quantity-input" data-product-id="<?= $item['product_id'] ?>">
                                        <button class="quantity-btn increase">+</button>
                                    </div>
                                    <div class="text-right">
                                        <?php if ($item['discounted_price'] !== null && $item['discounted_price'] < $item['price']): ?>
                                        <p class="text-gray-500 line-through">$<?= number_format($item['price'], 2) ?>
                                        </p>
                                        <?php endif; ?>
                                        <p class="font-bold text-lg price" data-price="<?= $price ?>">
                                            $<?= number_format($price, 2) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between items-center">
                        <div class="flex items-center">
                            <input type="text" placeholder="Coupon Code"
                                class="py-2 px-4 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button
                                class="bg-primary text-white px-4 py-2 rounded-r-lg hover:bg-indigo-700 transition">Apply</button>
                        </div>
                        <?php if (!empty($cartItems)): ?>
                        <button class="text-gray-500 hover:text-primary" id="clear-cart">
                            <i class="fas fa-trash-alt mr-2"></i> Clear Cart
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="md:w-1/3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Order Summary</h2>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal (<span class="cart-item-count"><?= $cartCount ?></span>
                                items)</span>
                            <span class="font-medium subtotal">$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span
                                class="font-medium shipping">$<?= number_format($cartCount > 0 ? 12.99 : 0, 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium tax">$<?= number_format($subtotal * 0.08, 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-secondary discount">-$0.00</span>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <?php
                        $shipping = $cartCount > 0 ? 12.99 : 0;
                        $tax = $subtotal * 0.08;
                        $total = $subtotal + $shipping + $tax;
                        ?>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span class="total">$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    <button
                        class="w-full bg-primary text-white py-3 rounded-lg hover:bg-indigo-700 transition font-bold mb-4 <?= $cartCount == 0 ? 'opacity-50 cursor-not-allowed' : '' ?>"
                        <?= $cartCount == 0 ? 'disabled' : '' ?>>
                        Proceed to Checkout
                    </button>
                    <div class="flex items-center text-gray-500 text-sm">
                        <i class="fas fa-lock mr-2"></i>
                        <span>Secure checkout</span>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-bold mb-3">We accept</h3>
                        <div class="flex space-x-3">
                            <img src="https://via.placeholder.com/40x25?text=VISA" alt="Visa" class="h-6">
                            <img src="https://via.placeholder.com/40x25?text=MC" alt="Mastercard" class="h-6">
                            <img src="https://via.placeholder.com/40x25?text=AMEX" alt="American Express" class="h-6">
                            <img src="https://via.placeholder.com/40x25?text=PP" alt="PayPal" class="h-6">
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 mt-8">
                    <h3 class="font-bold mb-3">Need Help?</h3>
                    <p class="text-gray-600 mb-4">Contact our customer support for assistance with your order.</p>
                    <button
                        class="w-full border border-primary text-primary py-2 rounded-lg hover:bg-primary hover:text-white transition">
                        <i class="fas fa-headset mr-2"></i> Contact Support
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Recently Viewed -->
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-6">Recently Viewed</h2>
        <?php if (empty($recentlyViewed)): ?>
        <div class="text-center py-8">
            <i class="fas fa-eye text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No recently viewed products.</p>
            <a href="home.php" class="text-primary hover:underline mt-2 inline-block">Browse Products</a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($recentlyViewed as $product): 
                $price = $product['discounted_price'] !== null ? $product['discounted_price'] : $product['price'];
                $image = $product['image_url'] ?: 'https://via.placeholder.com/300x200?text=' . urlencode($product['product_name']);
            ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden product-card transition">
                <div class="relative">
                    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>"
                        class="w-full h-48 object-cover">
                    <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                        <i class="fas fa-store mr-1"></i> <?= htmlspecialchars($product['vendor_name']) ?>
                    </div>
                    <button
                        class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition add-to-wishlist"
                        data-product-id="<?= $product['product_id'] ?>">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-1"><?= htmlspecialchars($product['product_name']) ?></h3>
                    <div class="flex items-center mb-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">(0)</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <?php if ($product['discounted_price'] !== null && $product['discounted_price'] < $product['price']): ?>
                            <span class="text-gray-500 line-through">$<?= number_format($product['price'], 2) ?></span>
                            <span
                                class="font-bold text-lg ml-2">$<?= number_format($product['discounted_price'], 2) ?></span>
                            <?php else: ?>
                            <span class="font-bold text-lg">$<?= number_format($price, 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <button
                            class="add-to-cart bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition"
                            data-product-id="<?= $product['product_id'] ?>">
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
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init();

    document.addEventListener('DOMContentLoaded', function() {
        // Quantity controls
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const controls = this.closest('.flex.items-center');
                if (!controls) {
                    console.error('Quantity controls not found for button:', this);
                    alert('Error: Unable to update quantity. Please refresh the page.');
                    return;
                }
                const input = controls.querySelector('.quantity-input');
                if (!input) {
                    console.error('Quantity input not found in controls:', controls);
                    alert('Error: Quantity input missing. Please refresh the page.');
                    return;
                }
                const productId = parseInt(input.dataset.productId);
                if (isNaN(productId)) {
                    console.error('Invalid product ID:', input.dataset.productId);
                    alert('Error: Invalid product ID.');
                    return;
                }
                let quantity = parseInt(input.value) || 1;
                if (this.classList.contains('increase')) {
                    quantity++;
                } else if (this.classList.contains('decrease') && quantity > 1) {
                    quantity--;
                }
                try {
                    const response = await fetch('update_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity,
                            action: 'update'
                        })
                    });
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    const result = await response.json();
                    if (result.success) {
                        input.value = quantity;
                        updateCartTotals();
                        updateCartCount();
                    } else {
                        alert(`Failed to update quantity: ${result.message}`);
                    }
                } catch (error) {
                    console.error('Quantity Update Error:', {
                        message: error.message,
                        productId: productId,
                        quantity: quantity
                    });
                    alert(`An error occurred while updating quantity: ${error.message}`);
                }
            });
        });

        // Remove item
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', async function() {
                const cartItemId = parseInt(this.dataset.cartItemId);
                const item = this.closest('.product-card');
                if (!item) {
                    console.error('Cart item not found for removal:', this);
                    return;
                }
                try {
                    const response = await fetch('update_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            cart_item_id: cartItemId,
                            action: 'remove'
                        })
                    });
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    const result = await response.json();
                    if (result.success) {
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.remove();
                            updateCartTotals();
                            updateCartCount();
                        }, 300);
                    } else {
                        alert(`Failed to remove item: ${result.message}`);
                    }
                } catch (error) {
                    console.error('Remove Item Error:', {
                        message: error.message,
                        cartItemId: cartItemId
                    });
                    alert(`An error occurred while removing item: ${error.message}`);
                }
            });
        });

        // Clear cart
        const clearCartBtn = document.getElementById('clear-cart');
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', async function() {
                if (!confirm('Are you sure you want to clear your cart?')) return;
                try {
                    const response = await fetch('update_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'clear'
                        })
                    });
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    const result = await response.json();
                    if (result.success) {
                        document.querySelectorAll('.product-card').forEach(item => {
                            item.style.opacity = '0';
                            setTimeout(() => {
                                item.remove();
                                updateCartTotals();
                                updateCartCount();
                            }, 300);
                        });
                    } else {
                        alert(`Failed to clear cart: ${result.message}`);
                    }
                } catch (error) {
                    console.error('Clear Cart Error:', error);
                    alert(`An error occurred while clearing cart: ${error.message}`);
                }
            });
        }

        // Add to cart from recently viewed
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', async function() {
                const productId = parseInt(this.dataset.productId);
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
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    const result = await response.json();
                    if (result.success) {
                        updateCartCount();
                        alert('Product added to cart!');
                        location.reload(); // Reload to show new item
                    } else {
                        alert(`Failed to add product to cart: ${result.message}`);
                    }
                } catch (error) {
                    console.error('Add to Cart Error:', {
                        message: error.message,
                        productId: productId
                    });
                    alert(`An error occurred while adding to cart: ${error.message}`);
                }
            });
        });

        // Update cart totals
        function updateCartTotals() {
            let subtotal = 0;
            let itemCount = 0;
            document.querySelectorAll('.product-card').forEach(item => {
                const quantityInput = item.querySelector('.quantity-input');
                const priceElement = item.querySelector('.price');
                if (!quantityInput || !priceElement) {
                    console.warn('Skipping cart item due to missing quantity or price:', item);
                    return;
                }
                const quantity = parseInt(quantityInput.value) || 0;
                const unitPrice = parseFloat(priceElement.dataset.price) || 0;
                const itemTotal = quantity * unitPrice;
                // Update individual item price
                priceElement.textContent = `$${itemTotal.toFixed(2)}`;
                subtotal += itemTotal;
                itemCount += quantity;
            });
            const shipping = itemCount > 0 ? 12.99 : 0;
            const tax = subtotal * 0.08;
            const total = subtotal + shipping + tax;

            // Update order summary
            const subtotalEl = document.querySelector('.subtotal');
            const shippingEl = document.querySelector('.shipping');
            const taxEl = document.querySelector('.tax');
            const totalEl = document.querySelector('.total');
            const itemCountEls = document.querySelectorAll('.cart-item-count');
            const checkoutBtn = document.querySelector('button.bg-primary');

            if (subtotalEl) subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
            if (shippingEl) shippingEl.textContent = `$${shipping.toFixed(2)}`;
            if (taxEl) taxEl.textContent = `$${tax.toFixed(2)}`;
            if (totalEl) totalEl.textContent = `$${total.toFixed(2)}`;
            itemCountEls.forEach(el => el.textContent = itemCount);

            // Enable/disable checkout button
            if (checkoutBtn) {
                if (itemCount === 0) {
                    checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    checkoutBtn.disabled = true;
                } else {
                    checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    checkoutBtn.disabled = false;
                }
            }
        }

        // Update cart count in header
        function updateCartCount() {
            let itemCount = 0;
            document.querySelectorAll('.product-card').forEach(item => {
                const quantityInput = item.querySelector('.quantity-input');
                if (quantityInput) {
                    itemCount += parseInt(quantityInput.value) || 0;
                }
            });
            const cartCountElements = document.querySelectorAll('.cart-count, .cart-item-count');
            cartCountElements.forEach(el => {
                el.textContent = itemCount;
                el.style.display = itemCount === 0 ? 'none' : 'inline';
            });
        }

        // Initial update
        updateCartTotals();
        updateCartCount();
    });
    </script>
</body>

</html>