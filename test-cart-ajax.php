<?php
// Test file to check cart-ajax.php functionality
session_start();

// Test data
$_POST['action'] = 'get_cart';
$_POST['product_id'] = 1;
$_POST['quantity'] = 1;

echo "Testing cart-ajax.php...\n";
echo "Session cart before: " . (isset($_SESSION['cart']) ? json_encode($_SESSION['cart']) : 'not set') . "\n";

// Capture output
ob_start();
include 'cart-ajax.php';
$output = ob_get_clean();

echo "Cart-ajax.php output: " . $output . "\n";
echo "Session cart after: " . (isset($_SESSION['cart']) ? json_encode($_SESSION['cart']) : 'not set') . "\n";
?>
