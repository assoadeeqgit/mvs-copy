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
            WHERE c.user_id = :user_id AND c.status = 'active'";
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

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    try {
        // Validate shipping address
        $required_fields = ['full_name', 'address_line1', 'city', 'state', 'zip_code', 'country'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("All shipping fields are required.");
            }
        }

        // Sanitize inputs
        $shipping_address = [
            'full_name' => filter_var($_POST['full_name'], FILTER_SANITIZE_STRING),
            'address_line1' => filter_var($_POST['address_line1'], FILTER_SANITIZE_STRING),
            'address_line2' => filter_var($_POST['address_line2'] ?? '', FILTER_SANITIZE_STRING),
            'city' => filter_var($_POST['city'], FILTER_SANITIZE_STRING),
            'state' => filter_var($_POST['state'], FILTER_SANITIZE_STRING),
            'zip_code' => filter_var($_POST['zip_code'], FILTER_SANITIZE_STRING),
            'country' => filter_var($_POST['country'], FILTER_SANITIZE_STRING)
        ];

        // Calculate totals
        $shipping = $cartCount > 0 ? 12.99 : 0;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        // Start transaction
        $dbh->beginTransaction();

        // Insert order
        $sql = "INSERT INTO orders (user_id, total_amount, status, shipping_address, created_at, updated_at)
                VALUES (:user_id, :total_amount, 'pending', :shipping_address, NOW(), NOW())";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'total_amount' => $total,
            'shipping_address' => json_encode($shipping_address)
        ]);
        $order_id = $dbh->lastInsertId();

        // Insert order items
        foreach ($cartItems as $item) {
            $price = $item['discounted_price'] !== null ? $item['discounted_price'] : $item['price'];
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price, created_at, updated_at)
                    VALUES (:order_id, :product_id, :quantity, :price, NOW(), NOW())";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'order_id' => $order_id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $price
            ]);

            // Update product stock
            $sql = "UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE product_id = :product_id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'quantity' => $item['quantity'],
                'product_id' => $item['product_id']
            ]);
        }

        // Clear cart
        $sql = "DELETE FROM cart_items WHERE cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id AND status = 'active')";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);

        // Update cart status
        $sql = "UPDATE carts SET status = 'completed', updated_at = NOW() WHERE user_id = :user_id AND status = 'active'";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);

        $dbh->commit();

        // Redirect to confirmation
        header("Location: order_confirmation.php?order_id=$order_id");
        exit;
    } catch (Exception $e) {
        $dbh->rollBack();
        error_log("Order placement failed: " . $e->getMessage());
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - MultiVendor Marketplace</title>
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
                    'float': 'float 6s ease-in-out infinite',
                },
                keyframes: {
                    float: {
                        '0%, 100%': {
                            transform: 'translateY(0)'
                        },
                        '50%': {
                            transform: 'translateY(-10px)'
                        }
                    }
                }
            }
        }
    }
    </script>
    <style>
    .form-input {
        border: 1px solid #e5e7eb;
        padding: 0.5rem;
        border-radius: 0.375rem;
        width: 100%;
        focus: outline-none focus:ring-2 focus:ring-primary focus:border-transparent;
    }

    .form-input:focus {
        outline: none;
        ring: 2px;
        ring-color: #4f46e5;
        border-color: transparent;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <?php include('./include/user_header.php'); ?>

    <!-- Checkout Section -->
    <section class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Checkout</h1>
        <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
        <?php endif; ?>
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Order Summary -->
            <div class="md:w-1/3 order-last md:order-first">
                <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Order Summary</h2>
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
                    <div class="flex items-center mb-4 pb-4 border-b border-gray-200">
                        <img src="<?= htmlspecialchars($item['image_url'] ?: 'https://via.placeholder.com/50') ?>"
                            alt="<?= htmlspecialchars($item['product_name']) ?>"
                            class="w-16 h-16 object-cover rounded mr-4">
                        <div class="flex-1">
                            <h3 class="font-semibold"><?= htmlspecialchars($item['product_name']) ?></h3>
                            <p class="text-gray-600 text-sm">Sold by: <?= htmlspecialchars($item['vendor_name']) ?></p>
                            <p class="text-gray-600 text-sm">Qty: <?= $item['quantity'] ?></p>
                            <p class="font-bold">$<?= number_format($totalPrice, 2) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div class="space-y-4 mt-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal (<span class="cart-item-count"><?= $cartCount ?></span>
                                items)</span>
                            <span class="font-medium">$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium">$<?= number_format($cartCount > 0 ? 12.99 : 0, 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium">$<?= number_format($subtotal * 0.08, 2) ?></span>
                        </div>
                        <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-4">
                            <span>Total</span>
                            <span>$<?= number_format($subtotal + ($cartCount > 0 ? 12.99 : 0) + ($subtotal * 0.08), 2) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="mt-6 flex items-center text-gray-500 text-sm">
                        <i class="fas fa-lock mr-2"></i>
                        <span>Secure checkout</span>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="md:w-2/3">
                <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up">
                    <form method="POST" id="checkout-form">
                        <!-- Shipping Address -->
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Shipping Address</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="full_name" class="block text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="full_name" name="full_name" class="form-input" required>
                            </div>
                            <div>
                                <label for="address_line1" class="block text-gray-700 mb-1">Address Line 1 *</label>
                                <input type="text" id="address_line1" name="address_line1" class="form-input" required>
                            </div>
                            <div>
                                <label for="address_line2" class="block text-gray-700 mb-1">Address Line 2</label>
                                <input type="text" id="address_line2" name="address_line2" class="form-input">
                            </div>
                            <div>
                                <label for="city" class="block text-gray-700 mb-1">City *</label>
                                <input type="text" id="city" name="city" class="form-input" required>
                            </div>
                            <div>
                                <label for="state" class="block text-gray-700 mb-1">State/Province *</label>
                                <input type="text" id="state" name="state" class="form-input" required>
                            </div>
                            <div>
                                <label for="zip_code" class="block text-gray-700 mb-1">Zip/Postal Code *</label>
                                <input type="text" id="zip_code" name="zip_code" class="form-input" required>
                            </div>
                            <div>
                                <label for="country" class="block text-gray-700 mb-1">Country *</label>
                                <input type="text" id="country" name="country" class="form-input" required>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Payment Method</h2>
                        <!-- In checkout.php, replace Payment Method section -->
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Payment Method</h2>
                        <div class="space-y-4 mb-6">
                            <?php
    $sql = "SELECT payment_method_id, type, details, is_default FROM payment_methods WHERE user_id = :user_id ORDER BY is_default DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $payment_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
                            <?php if (!empty($payment_methods)): ?>
                            <?php foreach ($payment_methods as $index => $method):
            $details = json_decode($method['details'], true);
        ?>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method"
                                    value="<?php echo $method['payment_method_id']; ?>" class="mr-2"
                                    <?php echo $method['is_default'] ? 'checked' : ($index === 0 ? 'checked' : ''); ?>>
                                <span>
                                    <?php if ($method['type'] === 'card'): ?>
                                    <?php echo htmlspecialchars($details['card_type']); ?> ending in
                                    <?php echo htmlspecialchars($details['last_four']); ?>
                                    <?php else: ?>
                                    PayPal (<?php echo htmlspecialchars($details['email']); ?>)
                                    <?php endif; ?>
                                </span>
                                <?php if ($method['type'] === 'card'): ?>
                                <img src="https://via.placeholder.com/40x25?text=<?php echo $details['card_type']; ?>"
                                    alt="<?php echo $details['card_type']; ?>" class="h-5 ml-2">
                                <?php else: ?>
                                <img src="https://via.placeholder.com/40x25?text=PP" alt="PayPal" class="h-5 ml-2">
                                <?php endif; ?>
                            </label>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="new_card" class="mr-2"
                                    <?php echo empty($payment_methods) ? 'checked' : ''; ?>>
                                <span>New Credit/Debit Card</span>
                                <img src="https://via.placeholder.com/40x25?text=VISA" alt="Visa" class="h-5 ml-2">
                                <img src="https://via.placeholder.com/40x25?text=MC" alt="Mastercard" class="h-5 ml-2">
                            </label>
                            <div id="card-details"
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 <?php echo empty($payment_methods) ? '' : 'hidden'; ?>">
                                <div>
                                    <label for="card_number" class="block text-gray-700 mb-1">Card Number *</label>
                                    <input type="text" id="card_number" name="card_number" class="form-input"
                                        placeholder="1234 5678 9012 3456"
                                        <?php echo empty($payment_methods) ? 'required' : ''; ?>>
                                </div>
                                <div>
                                    <label for="card_name" class="block text-gray-700 mb-1">Name on Card *</label>
                                    <input type="text" id="card_name" name="card_name" class="form-input"
                                        <?php echo empty($payment_methods) ? 'required' : ''; ?>>
                                </div>
                                <div>
                                    <label for="card_expiry" class="block text-gray-700 mb-1">Expiry Date *</label>
                                    <input type="text" id="card_expiry" name="card_expiry" class="form-input"
                                        placeholder="MM/YY" <?php echo empty($payment_methods) ? 'required' : ''; ?>>
                                </div>
                                <div>
                                    <label for="card_cvc" class="block text-gray-700 mb-1">CVC *</label>
                                    <input type="text" id="card_cvc" name="card_cvc" class="form-input"
                                        placeholder="123" <?php echo empty($payment_methods) ? 'required' : ''; ?>>
                                </div>
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit" name="place_order"
                            class="w-full bg-primary text-white py-3 rounded-lg hover:bg-indigo-700 transition font-bold <?= $cartCount == 0 ? 'opacity-50 cursor-not-allowed' : '' ?>"
                            <?= $cartCount == 0 ? 'disabled' : '' ?>>
                            Place Order
                        </button>
                    </form>
                </div>
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
        const form = document.getElementById('checkout-form');
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const cardDetails = document.getElementById('card-details');

        // Toggle payment fields
        // In checkout.php <script> section
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                if (this.value === 'new_card') {
                    cardDetails.classList.remove('hidden');
                    cardDetails.querySelectorAll('input').forEach(input => input.required =
                        true);
                } else {
                    cardDetails.classList.add('hidden');
                    cardDetails.querySelectorAll('input').forEach(input => input.required =
                        false);
                }
            });
        });

        // Client-side validation
        form.addEventListener('submit', function(e) {
            const cartCount = <?php echo $cartCount; ?>;
            if (cartCount === 0) {
                e.preventDefault();
                alert('Your cart is empty. Please add items to proceed.');
                return;
            }

            const requiredFields = form.querySelectorAll('input[required]');
            let isValid = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });
    </script>
</body>

</html>