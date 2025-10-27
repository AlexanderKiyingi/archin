<?php
/**
 * Script to fix admin password in database
 * This will update the admin password to a fresh hash
 */

require_once 'config.php';

// Check if running via browser
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("
    <!DOCTYPE html>
    <html>
    <head>
        <title>Reset Admin Password</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 50px; }
            .box { background: #f5f5f5; padding: 20px; border-radius: 8px; max-width: 500px; }
            button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
            button:hover { background: #0056b3; }
            .success { color: green; }
            .error { color: red; }
        </style>
    </head>
    <body>
        <div class='box'>
            <h2>Reset Admin Password</h2>
            <p>This will update the admin user password to 'admin123'</p>
            <p><strong>Username:</strong> admin</p>
            <p><strong>New Password:</strong> admin123</p>
            <form method='POST'>
                <button type='submit'>Reset Password</button>
            </form>
        </div>
    </body>
    </html>
爽");
    exit;
}

// Generate fresh password hash
$new_password_hash = password_hash('admin123', PASSWORD_DEFAULT);

// Update admin user password
$stmt = $conn->prepare("UPDATE admin_users SET password = ?, failed_login_count = 0, account_locked_until = NULL WHERE username = 'admin'");
$stmt->bind_param("s", $new_password_hash);

if ($stmt->execute()) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Password Reset Success</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 50px; }
            .box { background: #d4edda; padding: 20px; border-radius: 8px; max-width: 500px; border: 1px solid #c3e6cb; }
            .success { color: #155724; font-size: 18px; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class='box'>
            <div class='success'>✓ Password Successfully Reset!</div>
            <p><strong>Username:</strong> admin</p>
            <p><strong>Password:</strong> admin123</p>
            <p><a href='login.php'>Go to Login Page</a></p>
        </div>
    </body>
    </html>";
} else {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Password Reset Failed</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 50px; }
            .box { background: #f8d7da; padding: 20px; border-radius: 8px; max-width: 500px; border: 1px solid #f5c6cb; }
            .error { color: #721c24; }
        </style>
    </head>
    <body>
        <div class='box'>
            <div class='error'>Error: " . $conn->error . "</div>
        </div>
    </body>
    </html>";
}
?>

