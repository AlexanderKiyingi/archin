<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Information</h1>";

echo "<h2>1. PHP Check</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PHP SAPI: " . php_sapi_name() . "<br>";

echo "<h2>2. Extensions</h2>";
echo "MySQLi: " . (extension_loaded('mysqli') ? 'YES' : 'NO') . "<br>";
echo "Session: " . (extension_loaded('session') ? 'YES' : 'NO') . "<br>";

echo "<h2>3. Direct Database Test</h2>";
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'flipavenue_cms';

echo "Connecting to MySQL...<br>";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo "<strong style='color: red;'>Connection Failed:</strong> " . $conn->connect_error . "<br>";
    echo "Error Code: " . $conn->connect_errno . "<br>";
    die();
}

echo "<strong style='color: green;'>✓ Connected Successfully!</strong><br>";
echo "MySQL Version: " . $conn->server_info . "<br>";

echo "<h2>4. Tables Check</h2>";
$tables = ['admin_users', 'blog_posts', 'services'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    $exists = ($result && $result->num_rows > 0) ? 'YES' : 'NO';
    echo "$table: $exists<br>";
}

echo "<h2>5. Admin Users</h2>";
$result = $conn->query("SELECT id, username, email FROM admin_users");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']}, Username: {$row['username']}, Email: {$row['email']}<br>";
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

echo "<h2>6. File Paths</h2>";
echo "Current file: " . __FILE__ . "<br>";
echo "CMS directory: " . __DIR__ . "<br>";
echo "Parent directory: " . dirname(__DIR__) . "<br>";

echo "<h2>7. Config File Test</h2>";
echo "Attempting to include config.php...<br>";
try {
    require_once 'config.php';
    echo "<strong style='color: green;'>✓ Config loaded successfully!</strong><br>";
    echo "DB_NAME constant: " . (defined('DB_NAME') ? DB_NAME : 'NOT DEFINED') . "<br>";
} catch (Exception $e) {
    echo "<strong style='color: red;'>✗ Error loading config:</strong> " . $e->getMessage() . "<br>";
}

echo "<h2>Summary</h2>";
echo "If you see this, PHP is working. Check the results above.";
?>

