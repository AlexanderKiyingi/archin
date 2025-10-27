<?php
/**
 * Test script to check password hashing and verification
 * Run this script to verify admin credentials
 */

// Test the password hash from database
$stored_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
$test_password = 'admin123';

echo "Testing password verification...\n";
echo "Stored hash: " . $stored_hash . "\n";
echo "Test password: " . $test_password . "\n\n";

// Test password verification
if (password_verify($test_password, $stored_hash)) {
    echo "✓ Password verification SUCCESSFUL\n";
} else {
    echo "✗ Password verification FAILED\n";
    echo "\nGenerating new password hash...\nMETHOD 2 (current password_verify test): " . password_verify($test_password, $stored_hash);
}

// Generate a new hash
echo "\n\nGenerating new password hash for 'admin123':\n";
$new_hash = password_hash('admin123', PASSWORD_DEFAULT);
echo "New hash: " . $new_hash . "\n";

// Verify the new hash works
echo "Verifying new hash works: ";
if (password_verify('admin123', $new_hash)) {
    echo "✓ YES\n";
} else {
    echo "✗ NO\n";
}

// Test what password this hash actually belongs to
echo "\nTrying to verify common passwords with stored hash:\n";
$common_passwords = ['admin', 'password', 'admin123', 'Admin123', 'ADMIN123', 'admin@123'];
foreach ($common_passwords as $pwd) {
    if (password_verify($pwd, $stored_hash)) {
        echo "✓ Found match: " . $pwd . "\n";
    }
}

echo "\n--- Test Complete ---\n";
?>

