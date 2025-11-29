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
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Validate product and quantity
if ($product_id <= 0 || $quantity <= 0) {
    $_SESSION['error_message'] = 'Invalid product or quantity.';
    header("Location: products.php");
    exit;
}

try {
    // Check if product exists and is in stock
    $sql = "SELECT stock_quantity, name FROM products WHERE product_id = :product_id AND is_published = 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['product_id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product || $product['stock_quantity'] < $quantity) {
        $_SESSION['error_message'] = 'Product is out of stock or insufficient quantity.';
        header("Location: products.php");
        exit;
    }

    // Check if cart exists
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

    // Check if product is already in cart
    $sql = "SELECT cart_item_id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['cart_id' => $cart_id, 'product_id' => $product_id]);
    $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // Update quantity
        $new_quantity = $existing_item['quantity'] + $quantity;
        if ($new_quantity > $product['stock_quantity']) {
            $_SESSION['error_message'] = 'Cannot add more items than available in stock.';
            header("Location: products.php");
            exit;
        }
        $sql = "UPDATE cart_items SET quantity = :quantity WHERE cart_item_id = :cart_item_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'quantity' => $new_quantity,
            'cart_item_id' => $existing_item['cart_item_id']
        ]);
    } else {
        // Insert new cart item
        $sql = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'cart_id' => $cart_id,
            'product_id' => $product_id,
            'quantity' => $quantity
        ]);
    }

    $_SESSION['success_message'] = 'Product successfully added to cart!';
    header("Location: cart.php");
    exit;

} catch (PDOException $e) {
    error_log("Add to cart failed: product_id=$product_id, user_id=$user_id, error=" . $e->getMessage());
    $_SESSION['error_message'] = 'Something went wrong while adding to cart.';
    header("Location: products.php");
    exit;
}
?>