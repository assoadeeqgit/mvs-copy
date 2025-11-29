<?php
// session_start();
include("./include/config.php");

// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    $_SESSION['error_message'] = 'Please log in to view products.';
    header("Location: login.php");
    exit;
}


$user_id = $_SESSION['user_id'];
$username = $_SESSION['user_name'];
$role =  $_SESSION['user_role'];
$user_logged_in = isset($_SESSION['user_id']);

// Get product ID
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    $_SESSION['error_message'] = 'Invalid product ID.';
    header("Location: home.php");
    exit;
}

// Fetch product details
try {
    $sql = "SELECT 
                p.product_id,
                p.name AS product_name,
                p.price,
                p.discounted_price,
                p.description,
                p.stock_quantity,
                pi.image_url,
                v.business_name AS vendor_name,
                COALESCE(AVG(pr.rating), 0) AS avg_rating,
                COUNT(pr.review_id) AS review_count
            FROM products p
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            JOIN vendors v ON p.vendor_id = v.vendor_id
            LEFT JOIN product_reviews pr ON p.product_id = pr.product_id
            WHERE p.product_id = :product_id AND p.is_published = 1
            GROUP BY p.product_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['product_id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $_SESSION['error_message'] = 'Product not found.';
        header("Location: home.php");
        exit;
    }

    // Fetch variants
    $sql = "SELECT variant_id, name, value, price_adjustment, quantity, sku 
            FROM product_variants 
            WHERE product_id = :product_id AND quantity > 0";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['product_id' => $product_id]);
    $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Product query failed: product_id=$product_id, error=" . $e->getMessage());
    $_SESSION['error_message'] = 'Error loading product.';
    header("Location: home.php");
    exit;
}

// Add to recently viewed
try {
    $sql = "INSERT INTO recently_viewed (user_id, product_id, viewed_at) 
            VALUES (:user_id, :product_id, NOW())
            ON DUPLICATE KEY UPDATE viewed_at = NOW()";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
} catch (PDOException $e) {
    error_log("Recently viewed update failed: " . $e->getMessage());
}

// Handle success/error messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['error_message']);



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
    <title><?= htmlspecialchars($product['product_name']) ?> - VendorHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                }
            }
        }
    }
    </script>
    <style>
    .product-image:hover {
        transform: scale(1.05);
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <?php include('./include/user_header.php'); ?>

    <section class="container mx-auto px-4 py-8">
        <?php if ($success_message): ?>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= htmlspecialchars($success_message) ?>',
            confirmButtonColor: '#3085d6'
        });
        </script>
        <?php endif; ?>
        <?php if ($error_message): ?>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= htmlspecialchars($error_message) ?>',
            confirmButtonColor: '#3085d6'
        });
        </script>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Product Image -->
            <div class="md:w-1/2">
                <img src="<?= htmlspecialchars($product['image_url'] ?: 'https://via.placeholder.com/500') ?>"
                    alt="<?= htmlspecialchars($product['product_name']) ?>"
                    class="w-full h-auto rounded-lg shadow-md product-image transition">
            </div>
            <!-- Product Details -->
            <div class="md:w-1/2">
                <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($product['product_name']) ?></h1>
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        <?php
                        $rating = floatval($product['avg_rating']);
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
                    <span class="text-gray-500 text-sm ml-2">(<?= $product['review_count'] ?>)</span>
                </div>
                <p class="text-gray-600 mb-4">Sold by: <span
                        class="text-secondary"><?= htmlspecialchars($product['vendor_name']) ?></span></p>
                <div class="mb-4">
                    <?php if ($product['discounted_price'] !== null && $product['discounted_price'] < $product['price']): ?>
                    <span class="text-gray-500 line-through text-lg">$<?= number_format($product['price'], 2) ?></span>
                    <span
                        class="font-bold text-2xl text-primary ml-2">$<?= number_format($product['discounted_price'], 2) ?></span>
                    <?php else: ?>
                    <span class="font-bold text-2xl text-primary">$<?= number_format($product['price'], 2) ?></span>
                    <?php endif; ?>
                </div>
                <p class="text-gray-700 mb-6">
                    <?= htmlspecialchars($product['description'] ?: 'No description available.') ?></p>
                <?php if (!empty($variants)): ?>
                <div class="mb-6">
                    <label for="variant" class="text-gray-600 mr-4">Variant:</label>
                    <select name="variant_id" id="variant"
                        class="py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <?php foreach ($variants as $variant): ?>
                        <option value="<?= $variant['variant_id'] ?>"
                            data-price-adjustment="<?= $variant['price_adjustment'] ?>">
                            <?= htmlspecialchars($variant['name'] . ': ' . $variant['value']) ?>
                            (<?= $variant['quantity'] ?> in stock)
                            <?php if ($variant['price_adjustment'] != 0): ?>
                            (+$<?= number_format($variant['price_adjustment'], 2) ?>)
                            <?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="flex items-center mb-6">
                    <span class="text-gray-600 mr-4">Quantity:</span>
                    <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>"
                        class="w-20 py-2 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        id="quantity-input">
                </div>
                <form id="add-to-cart-form" action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                    <input type="hidden" name="variant_id" id="variant-hidden"
                        value="<?= !empty($variants) ? $variants[0]['variant_id'] : '' ?>">
                    <input type="hidden" name="quantity" id="quantity-hidden" value="1">
                    <button type="submit"
                        class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-bold"
                        <?php if ($product['stock_quantity'] == 0): ?>disabled<?php endif; ?>>
                        <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                    </button>
                </form>
                <button class="mt-4 text-primary hover:underline add-to-wishlist"
                    data-product-id="<?= $product['product_id'] ?>">
                    <i class="fas fa-heart mr-2"></i> Add to Wishlist
                </button>
            </div>
        </div>
    </section>

    <?php include('./include/footer.php'); ?>

    <script>
    // Sync quantity and variant inputs
    const quantityInput = document.getElementById('quantity-input');
    const quantityHidden = document.getElementById('quantity-hidden');
    const variantSelect = document.getElementById('variant');
    const variantHidden = document.getElementById('variant-hidden');

    if (quantityInput && quantityHidden) {
        quantityInput.addEventListener('input', () => {
            let value = parseInt(quantityInput.value);
            if (isNaN(value) || value < 1) value = 1;
            if (value > parseInt(quantityInput.max)) value = parseInt(quantityInput.max);
            quantityInput.value = value;
            quantityHidden.value = value;
        });
    }

    if (variantSelect && variantHidden) {
        variantSelect.addEventListener('change', () => {
            variantHidden.value = variantSelect.value;
        });
    }

    // Wishlist functionality
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = parseInt(this.dataset.productId);
            try {
                const response = await fetch('add_to_wishlist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                });
                const result = await response.json();
                Swal.fire({
                    icon: result.success ? 'success' : 'error',
                    title: result.success ? 'Added to Wishlist!' : 'Error',
                    text: result.message,
                    confirmButtonColor: '#3085d6'
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while adding to wishlist.',
                    confirmButtonColor: '#3085d6'
                });
            }
        });
    });
    </script>
</body>

</html>