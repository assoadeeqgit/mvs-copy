<?php
session_start();
include("./include/config.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please log in to add to wishlist.';
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'home.php';

if ($product_id <= 0) {
    $_SESSION['error_message'] = 'Invalid product.';
    header("Location: $redirect");
    exit;
}

try {
    // Verify product exists
    $sql = "SELECT product_id FROM products WHERE product_id = :product_id AND is_published = 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['product_id' => $product_id]);
    if (!$stmt->fetch()) {
        $_SESSION['error_message'] = 'Product not found.';
        header("Location: $redirect");
        exit;
    }

    // Add to wishlist
    $sql = "INSERT INTO wishlists (user_id, product_id, added_at)
            VALUES (:user_id, :product_id, NOW())
            ON DUPLICATE KEY UPDATE added_at = NOW()";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);

    $_SESSION['success_message'] = 'Product added to wishlist!';
    header("Location: $redirect");
    exit;
} catch (PDOException $e) {
    error_log("Wishlist error: user_id=$user_id, product_id=$product_id, error=" . $e->getMessage());
    $_SESSION['error_message'] = 'Error adding to wishlist.';
    header("Location: $redirect");
    exit;
}
?>