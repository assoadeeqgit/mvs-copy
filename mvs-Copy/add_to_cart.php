<?php
session_start();
include("./include/config.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please log in to add items to cart.';
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$variant_id = isset($_POST['variant_id']) && $_POST['variant_id'] !== '' ? (int)$_POST['variant_id'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Validate inputs
if ($product_id <= 0 || $quantity <= 0) {
    $_SESSION['error_message'] = 'Invalid product or quantity.';
    header("Location: products.php");
    exit;
}

try {
    // Check if product exists and is in stock
    $sql = "SELECT p.stock_quantity, p.name, p.price, p.discounted_price,
                   pv.quantity AS variant_quantity, pv.price_adjustment
            FROM products p
            LEFT JOIN product_variants pv ON p.product_id = pv.product_id AND pv.variant_id = :variant_id
            WHERE p.product_id = :product_id AND p.is_published = 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['product_id' => $product_id, 'variant_id' => $variant_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $_SESSION['error_message'] = 'Product not found.';
        header("Location: products.php");
        exit;
    }

    // Determine stock and price
    $stock_quantity = $variant_id ? ($product['variant_quantity'] ?? 0) : $product['stock_quantity'];
    $price = $product['discounted_price'] ?? $product['price'];
    if ($variant_id && $product['price_adjustment']) {
        $price += $product['price_adjustment'];
    }

    if ($stock_quantity < $quantity) {
        $_SESSION['error_message'] = 'Insufficient stock for ' . htmlspecialchars($product['name']) . '.';
        header("Location: product.php?id=$product_id");
        exit;
    }

    // Get or create cart
    $sql = "SELECT cart_id FROM carts WHERE user_id = :user_id AND status = 'active'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        $sql = "INSERT INTO carts (user_id, status, created_at) VALUES (:user_id, 'active', NOW())";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $cart_id = $dbh->lastInsertId();
    } else {
        $cart_id = $cart['cart_id'];
    }

    // Check for existing cart item
    $sql = "SELECT cart_item_id, quantity 
            FROM cart_items 
            WHERE cart_id = :cart_id AND product_id = :product_id AND (variant_id = :variant_id OR (variant_id IS NULL AND :variant_id IS NULL))";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'cart_id' => $cart_id,
        'product_id' => $product_id,
        'variant_id' => $variant_id
    ]);
    $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // Update quantity
        $new_quantity = $existing_item['quantity'] + $quantity;
        if ($new_quantity > $stock_quantity) {
            $_SESSION['error_message'] = 'Cannot add more items than available in stock.';
            header("Location: product.php?id=$product_id");
            exit;
        }
        $sql = "UPDATE cart_items 
                SET quantity = :quantity, price = :price, updated_at = NOW()
                WHERE cart_item_id = :cart_item_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'quantity' => $new_quantity,
            'price' => $price,
            'cart_item_id' => $existing_item['cart_item_id']
        ]);
    } else {
        // Insert new cart item
        $sql = "INSERT INTO cart_items (cart_id, product_id, variant_id, quantity, price, created_at)
                VALUES (:cart_id, :product_id, :variant_id, :quantity, :price, NOW())";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'cart_id' => $cart_id,
            'product_id' => $product_id,
            'variant_id' => $variant_id,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }

    $_SESSION['success_message'] = 'Product added to cart!';
    header("Location: cart.php");
    exit;

} catch (PDOException $e) {
    error_log("Add to cart failed: product_id=$product_id, variant_id=$variant_id, user_id=$user_id, error=" . $e->getMessage());
    $_SESSION['error_message'] = 'Something went wrong while adding to cart.';
    header("Location: product.php?id=$product_id");
    exit;
}
?>