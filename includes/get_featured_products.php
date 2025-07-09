<?php
/**
 * Get Featured Products API
 * Returns featured products with proper formatting
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    // Get featured products with category information
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.name,
            p.slug,
            p.short_description,
            p.price,
            p.compare_price,
            p.image,
            p.rating,
            p.review_count,
            p.is_hot,
            p.is_sale,
            p.stock_quantity,
            c.name as category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_featured = 1 AND p.is_active = 1 AND p.stock_quantity > 0
        ORDER BY p.created_at DESC
        LIMIT 8
    ");
    
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    // Format the response
    $response = array_map(function($product) {
        return [
            'id' => (int)$product['id'],
            'name' => $product['name'],
            'slug' => $product['slug'],
            'description' => $product['short_description'],
            'price' => (float)$product['price'],
            'compare_price' => $product['compare_price'] ? (float)$product['compare_price'] : null,
            'formatted_price' => formatTSh($product['price']),
            'image' => $product['image'] ?: 'assets/images/products/default.jpg',
            'rating' => (float)$product['rating'],
            'reviews' => (int)$product['review_count'],
            'is_hot' => (bool)$product['is_hot'],
            'is_sale' => (bool)$product['is_sale'],
            'in_stock' => (int)$product['stock_quantity'] > 0,
            'category_name' => $product['category_name']
        ];
    }, $products);
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => 'Unable to fetch featured products'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => 'An unexpected error occurred'
    ]);
}
?>