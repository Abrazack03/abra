<?php
/**
 * Get Most Loved Products API
 * Returns products based on highest ratings and most purchases
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    // Get most loved products based on rating and sales
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
            c.name as category_name,
            COALESCE(sales.total_sold, 0) as total_sold,
            (p.rating * 0.7 + LEAST(COALESCE(sales.total_sold, 0) / 10, 5) * 0.3) as love_score
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN (
            SELECT 
                oi.product_id,
                SUM(oi.quantity) as total_sold
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            WHERE o.status IN ('delivered', 'shipped', 'processing')
            GROUP BY oi.product_id
        ) sales ON p.id = sales.product_id
        WHERE p.is_active = 1 AND p.stock_quantity > 0 AND p.rating >= 4.0
        ORDER BY love_score DESC, p.rating DESC, p.review_count DESC
        LIMIT 10
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
            'category_name' => $product['category_name'],
            'total_sold' => (int)$product['total_sold'],
            'love_score' => (float)$product['love_score']
        ];
    }, $products);
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => 'Unable to fetch most loved products'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => 'An unexpected error occurred'
    ]);
}
?>