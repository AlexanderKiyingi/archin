<?php
// Start session FIRST before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// AJAX Cart Handler
header('Content-Type: application/json');

// Include centralized database connection
require_once 'cms/db_connect.php';

// Get the action from POST data
$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => '', 'data' => null];

try {
    switch ($action) {
        case 'add_to_cart':
            $product_id = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity'] ?? 1);
            
            // Get product details
            $stmt = $conn->prepare("SELECT * FROM shop_products WHERE id = ? AND is_active = 1");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                $response['message'] = 'Product not found';
                break;
            }
            
            $product = $result->fetch_assoc();
            
            // Initialize cart if not exists
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Check if product already in cart
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            
            // Add new item if not found
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => floatval($product['price']),
                    'category' => $product['category'],
                    'image' => $product['featured_image'],
                    'quantity' => $quantity
                ];
            }
            
            $response['success'] = true;
            $response['message'] = 'Product added to cart successfully';
            $response['data'] = [
                'cart_count' => array_sum(array_column($_SESSION['cart'], 'quantity')),
                'cart_total' => array_sum(array_map(function($item) { 
                    return $item['price'] * $item['quantity']; 
                }, $_SESSION['cart']))
            ];
            break;
            
        case 'remove_from_cart':
            $product_id = intval($_POST['product_id']);
            
            if (isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($product_id) {
                    return $item['id'] != $product_id;
                });
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
            }
            
            $response['success'] = true;
            $response['message'] = 'Product removed from cart';
            $response['data'] = [
                'cart_count' => isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0,
                'cart_total' => isset($_SESSION['cart']) ? array_sum(array_map(function($item) { 
                    return $item['price'] * $item['quantity']; 
                }, $_SESSION['cart'])) : 0
            ];
            break;
            
        case 'update_quantity':
            $product_id = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);
            
            if ($quantity <= 0) {
                // Remove item if quantity is 0 or less
                if (isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($product_id) {
                        return $item['id'] != $product_id;
                    });
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                }
            } else {
                // Update quantity
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as &$item) {
                        if ($item['id'] == $product_id) {
                            $item['quantity'] = $quantity;
                            break;
                        }
                    }
                }
            }
            
            $response['success'] = true;
            $response['message'] = 'Cart updated successfully';
            $response['data'] = [
                'cart_count' => isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0,
                'cart_total' => isset($_SESSION['cart']) ? array_sum(array_map(function($item) { 
                    return $item['price'] * $item['quantity']; 
                }, $_SESSION['cart'])) : 0
            ];
            break;
            
        case 'get_cart':
            $response['success'] = true;
            $response['data'] = [
                'cart' => $_SESSION['cart'] ?? [],
                'cart_count' => isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0,
                'cart_total' => isset($_SESSION['cart']) ? array_sum(array_map(function($item) { 
                    return $item['price'] * $item['quantity']; 
                }, $_SESSION['cart'])) : 0
            ];
            break;
            
        case 'clear_cart':
            $_SESSION['cart'] = [];
            $response['success'] = true;
            $response['message'] = 'Cart cleared successfully';
            $response['data'] = [
                'cart_count' => 0,
                'cart_total' => 0
            ];
            break;
            
        default:
            $response['message'] = 'Invalid action';
    }
    
} catch (Exception $e) {
    $response['message'] = 'Server error occurred';
}

echo json_encode($response);
?>
