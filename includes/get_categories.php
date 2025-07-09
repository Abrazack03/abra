<?php
/**
 * Get Categories API
 * Returns categories with product count
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    // Get categories with product count
    $stmt = $pdo->prepare("
        SELECT 
            c.id,
            c.name,
            c.slug,
            c.description,
            c.image,
            COUNT(p.id) as product_count
        FROM categories c
        LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1
        WHERE c.is_active = 1
        GROUP BY c.id, c.name, c.slug, c.description, c.image
        ORDER BY c.name ASC
    ");
    
    $stmt->execute();
    $categories = $stmt->fetchAll();
    
    // Format the response
    $response = array_map(function($category) {
        return [
            'id' => (int)$category['id'],
            'name' => $category['name'],
            'slug' => $category['slug'],
            'description' => $category['description'],
            'image' => $category['image'] ?: 'assets/images/categories/default.jpg',
            'product_count' => (int)$category['product_count']
        ];
    }, $categories);
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'message' => 'Unable to fetch categories'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => 'An unexpected error occurred'
    ]);
}
?>