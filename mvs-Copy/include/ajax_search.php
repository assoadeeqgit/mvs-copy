<?php
// File: ajax_search.php
session_start();
include("./include/config.php");

header('Content-Type: application/json');

try {
    $search_term = isset($_GET['q']) ? trim($_GET['q']) : '';
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

    if (strlen($search_term) < 3) {
        echo json_encode(['success' => false, 'message' => 'Search term must be at least 3 characters.', 'results' => []]);
        exit;
    }

    $sql = "SELECT DISTINCT p.product_id, p.name, p.price, p.discounted_price, p.vendor_id,
                   pi.image_url, c.name AS category_name
            FROM products p
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE (p.name LIKE :search_term OR p.description LIKE :search_term)
            AND p.is_published = 1";
            
    $params = ['search_term' => '%' . $search_term . '%'];

    if ($category_id > 0) {
        $sql .= " AND p.category_id = :category_id";
        $params['category_id'] = $category_id;
    }

    $sql .= " ORDER BY p.name ASC LIMIT 5";

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results = [];
    foreach ($products as $product) {
        $results[] = [
            'product_id' => $product['product_id'],
            'name' => htmlspecialchars($product['name']),
            'price' => $product['discounted_price'] ? number_format($product['discounted_price'], 2) : number_format($product['price'], 2),
            'image_url' => $product['image_url'] ?: 'https://via.placeholder.com/50x50?text=No+Image',
            'category_name' => htmlspecialchars($product['category_name']),
            'vendor_id' => $product['vendor_id']
        ];
    }

    echo json_encode(['success' => true, 'results' => $results]);

} catch (PDOException $e) {
    error_log("AJAX search failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Search failed.', 'error' => $e->getMessage()]);
}
?>