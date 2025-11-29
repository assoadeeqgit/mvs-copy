<?php
include("./include/config.php");

header('Content-Type: application/json');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get user ID
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$action = isset($input['action']) ? $input['action'] : null;

try {
    // Find active cart
    $sql = "SELECT cart_id FROM carts WHERE user_id = :user_id AND status = 'active'";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        // Create new cart if none exists
        $sql = "INSERT INTO carts (user_id, status) VALUES (:user_id, 'active')";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $cart_id = $dbh->lastInsertId();
    } else {
        $cart_id = $cart['cart_id'];
    }

    if ($action === 'update') {
        $product_id = isset($input['product_id']) ? (int)$input['product_id'] : null;
        $quantity = isset($input['quantity']) ? (int)$input['quantity'] : null;

        // Validate input
        if (!$product_id || $quantity < 1) {
            echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity']);
            exit;
        }

        // Verify product and stock
        $sql = "SELECT stock_quantity, price, discounted_price FROM products WHERE product_id = :product_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        if ($product['stock_quantity'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds available stock']);
            exit;
        }

        $price = $product['discounted_price'] !== null ? $product['discounted_price'] : $product['price'];

        // Check if item exists in cart
        $sql = "SELECT cart_item_id FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['cart_id' => $cart_id, 'product_id' => $product_id]);
        $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart_item) {
            // Update existing item
            $sql = "UPDATE cart_items 
                    SET quantity = :quantity, price = :price, updated_at = CURRENT_TIMESTAMP 
                    WHERE cart_item_id = :cart_item_id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'quantity' => $quantity,
                'price' => $price,
                'cart_item_id' => $cart_item['cart_item_id']
            ]);
        } else {
            // Insert new item
            $sql = "INSERT INTO cart_items (cart_id, product_id, quantity, price) 
                    VALUES (:cart_id, :product_id, :quantity, :price)";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'cart_id' => $cart_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price
            ]);
        }

        echo json_encode(['success' => true, 'message' => 'Quantity updated']);
    } elseif ($action === 'remove') {
        $cart_item_id = isset($input['cart_item_id']) ? (int)$input['cart_item_id'] : null;
        if (!$cart_item_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid cart item ID']);
            exit;
        }
        $sql = "DELETE FROM cart_items WHERE cart_item_id = :cart_item_id AND cart_id = :cart_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['cart_item_id' => $cart_item_id, 'cart_id' => $cart_id]);
        echo json_encode(['success' => true, 'message' => 'Item removed']);
    } elseif ($action === 'clear') {
        $sql = "DELETE FROM cart_items WHERE cart_id = :cart_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['cart_id' => $cart_id]);
        echo json_encode(['success' => true, 'message' => 'Cart cleared']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }

    // Update cart timestamp
    $sql = "UPDATE carts SET updated_at = CURRENT_TIMESTAMP WHERE cart_id = :cart_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['cart_id' => $cart_id]);
} catch (PDOException $e) {
    error_log("Cart action failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred']);
}
?>