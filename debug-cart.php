<?php
// Debug version of cart-ajax.php to check what's happening
session_start();

echo "Debug Cart Test\n";
echo "==============\n";

// Check if session is working
echo "Session ID: " . session_id() . "\n";
echo "Session cart: " . (isset($_SESSION['cart']) ? json_encode($_SESSION['cart']) : 'not set') . "\n";

// Check database connection
try {
    require_once 'cms/db_connect.php';
    echo "Database connection: SUCCESS\n";
    
    // Check if shop_products table exists and has data
    $result = $conn->query("SELECT COUNT(*) as count FROM shop_products WHERE is_active = 1");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Active products in database: " . $row['count'] . "\n";
        
        // Show first few products
        $products_result = $conn->query("SELECT id, name, price FROM shop_products WHERE is_active = 1 LIMIT 3");
        echo "Sample products:\n";
        while ($product = $products_result->fetch_assoc()) {
            echo "- ID: {$product['id']}, Name: {$product['name']}, Price: {$product['price']}\n";
        }
    } else {
        echo "Error querying products: " . $conn->error . "\n";
    }
    
} catch (Exception $e) {
    echo "Database connection: FAILED - " . $e->getMessage() . "\n";
}

// Test POST data simulation
$_POST['action'] = 'add_to_cart';
$_POST['product_id'] = 1;
$_POST['quantity'] = 1;

echo "\nTesting add_to_cart action...\n";

// Simulate the cart-ajax.php logic
$action = $_POST['action'] ?? '';
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity'] ?? 1);

echo "Action: $action\n";
echo "Product ID: $product_id\n";
echo "Quantity: $quantity\n";

if (isset($conn)) {
    $stmt = $conn->prepare("SELECT * FROM shop_products WHERE id = ? AND is_active = 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "Product not found in database\n";
    } else {
        $product = $result->fetch_assoc();
        echo "Product found: " . $product['name'] . "\n";
        
        // Initialize cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Add to cart
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => floatval($product['price']),
            'category' => $product['category'],
            'image' => $product['featured_image'],
            'quantity' => $quantity
        ];
        
        echo "Product added to cart successfully\n";
        echo "Cart contents: " . json_encode($_SESSION['cart']) . "\n";
    }
}
?>
