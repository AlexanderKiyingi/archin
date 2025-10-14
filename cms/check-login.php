<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; }
        .success { background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 10px 0; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 10px 0; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 10px 0; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 10px 0; }
        pre { background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #3498db; color: white; }
        button { background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #2980b9; }
        input { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Login Diagnostic Tool</h1>

<?php
// Database credentials
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'u680675202_flipavenue_cms';

echo "<h2>Step 1: Database Connection</h2>";

$conn = @new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo "<div class='error'><strong>❌ Database Connection Failed</strong><br>";
    echo "Error: " . $conn->connect_error . "</div>";
    echo "<div class='warning'>";
    echo "<p>Please:</p>";
    echo "<ol>";
    echo "<li>Make sure MySQL is running in XAMPP</li>";
    echo "<li>Open phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
    echo "<li>Create database: <code>flipavenue_cms</code></li>";
    echo "<li>Import file: <code>cms/database.sql</code></li>";
    echo "</ol>";
    echo "</div>";
    exit;
}

echo "<div class='success'>✅ Connected to database successfully!</div>";

echo "<h2>Step 2: Check Admin Users Table</h2>";

$result = $conn->query("SHOW TABLES LIKE 'admin_users'");
if ($result->num_rows == 0) {
    echo "<div class='error'>❌ Table 'admin_users' does NOT exist</div>";
    echo "<div class='warning'>Import the database schema: cms/database.sql</div>";
    exit;
}

echo "<div class='success'>✅ Table 'admin_users' exists</div>";

echo "<h2>Step 3: Check Existing Users</h2>";

$users_result = $conn->query("SELECT id, username, email, role, is_active, created_at, last_login FROM admin_users");

if ($users_result->num_rows > 0) {
    echo "<div class='success'>✅ Found " . $users_result->num_rows . " user(s)</div>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Active</th><th>Last Login</th></tr>";
    while ($row = $users_result->fetch_assoc()) {
        $active = $row['is_active'] ? '✅ Yes' : '❌ No';
        $last_login = $row['last_login'] ? date('Y-m-d H:i', strtotime($row['last_login'])) : 'Never';
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td><strong>{$row['username']}</strong></td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['role']}</td>";
        echo "<td>{$active}</td>";
        echo "<td>{$last_login}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>❌ No users found in database</div>";
    echo "<div class='warning'>Import the database.sql file to create the default admin user</div>";
    exit;
}

echo "<h2>Step 4: Test Login Credentials</h2>";

// Test login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_login'])) {
    $test_username = $_POST['test_username'];
    $test_password = $_POST['test_password'];
    
    $stmt = $conn->prepare("SELECT id, username, password, is_active FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $test_username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "<div class='error'>❌ User '<strong>$test_username</strong>' not found</div>";
    } else {
        $user = $result->fetch_assoc();
        
        if (!$user['is_active']) {
            echo "<div class='error'>❌ User account is INACTIVE</div>";
        } else {
            echo "<div class='info'>✅ User found: <strong>{$user['username']}</strong></div>";
            
            if (password_verify($test_password, $user['password'])) {
                echo "<div class='success'><strong>✅ PASSWORD CORRECT!</strong><br>";
                echo "Login credentials are valid. You should be able to login now.</div>";
                echo "<div class='info'>";
                echo "<p><strong>Next step:</strong></p>";
                echo "<p><a href='login.php' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page →</a></p>";
                echo "</div>";
            } else {
                echo "<div class='error'>❌ PASSWORD INCORRECT</div>";
                echo "<div class='warning'>";
                echo "<p>The password you entered doesn't match the database.</p>";
                echo "<p><strong>Default password is:</strong> <code>admin123</code></p>";
                echo "<p>Use the form below to reset the password.</p>";
                echo "</div>";
            }
        }
    }
}

// Password reset form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $reset_username = $_POST['reset_username'];
    $new_password = $_POST['new_password'];
    
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashed, $reset_username);
    
    if ($stmt->execute()) {
        echo "<div class='success'>✅ Password reset successfully for user: <strong>$reset_username</strong></div>";
        echo "<div class='info'>New password: <strong>$new_password</strong></div>";
        echo "<p><a href='login.php' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page →</a></p>";
    } else {
        echo "<div class='error'>❌ Failed to reset password</div>";
    }
}
?>

<div class="info">
    <h3>Test Your Login Credentials</h3>
    <form method="POST">
        <label><strong>Username:</strong></label>
        <input type="text" name="test_username" value="admin" required>
        
        <label><strong>Password:</strong></label>
        <input type="password" name="test_password" value="admin123" required>
        
        <button type="submit" name="test_login">Test Login</button>
    </form>
</div>

<div class="warning">
    <h3>Reset Password (If Needed)</h3>
    <form method="POST">
        <label><strong>Username:</strong></label>
        <input type="text" name="reset_username" value="admin" required>
        
        <label><strong>New Password:</strong></label>
        <input type="password" name="new_password" value="admin123" required>
        
        <button type="submit" name="reset_password" onclick="return confirm('Are you sure you want to reset the password?')">Reset Password</button>
    </form>
</div>

<div class="info">
    <h3>Default Credentials</h3>
    <p><strong>Username:</strong> admin</p>
    <p><strong>Password:</strong> admin123</p>
</div>

<?php $conn->close(); ?>

    </div>
</body>
</html>

