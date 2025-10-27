<?php
/**
 * Check admin user status and diagnostic information
 */

require_once 'config.php';

$admin_user = null;
$stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = 'admin'");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $admin_user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Status Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; }
        .status { padding: 15px; margin: 15px 0; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .warning { background: #fff3cd; border: 1px solid #ffeeba; color: #856404; }
        .danger { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Admin Status Check</h1>
        
        <?php if (!$admin_user): ?>
            <div class="status danger">
                <h3>❌ Admin User Not Found</h3>
                <p>The admin user does not exist in the database. You need to run the database installation script.</p>
                <p><strong>Solution:</strong> Run <code>cms/database-complete.sql</code> or <code>cms/database.sql</code> in your MySQL database.</p>
            </div>
        <?php else: ?>
            <div class="status success">
                <h3>✓ Admin User Found</h3>
            </div>
            
            <h2>User Information</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <td><?php echo $admin_user['id']; ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo htmlspecialchars($admin_user['username']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($admin_user['email']); ?></td>
                </tr>
                <tr>
                    <th>Full Name</th超凡脫俗>
                    <td><?php echo htmlspecialchars($admin_user['full_name']); ?></td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td><?php echo htmlspecialchars($admin_user['role']); ?></td>
                </tr>
                <tr>
                    <th>Is Active</th>
                    <td><?php echo $admin_user['is_active'] ? '✓ Yes' : '✗ No'; ?></td>
                </tr>
                <tr>
                    <th>Failed Login Count</th>
                    <td><?php echo $admin_user['failed_login_count']; ?></td>
                </tr>
                <tr>
                    <th>Account Locked Until</th>
                    <td>
                        <?php 
                        if ($admin_user['account_locked_until']) {
                            $locked_until = strtotime($admin_user['account_locked_until']);
                            if ($locked_until > time()) {
                                echo '<span style="color: red;">✗ LOCKED until ' . $admin_user['account_locked_until'] . '</span>';
                            } else {
                                echo '<span style="color: green;">✓ Not locked</span>';
                            }
                        } else {
                            echo '<span style="color: green;">✓ Not locked</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Last Login</th>
                    <td><?php echo $admin_user['last_login'] ?: 'Never'; ?></td>
                </tr>
            </table>
            
            <?php if (!$admin_user['is_active']): ?>
                <div class="status danger">
                    <h3>❌ Account is Inactive</h3>
                    <p>The admin account is marked as inactive. You need to activate it.</p>
                </div>
            <?php endif; ?>
            
            <?php if ($admin_user['account_locked_until'] && strtotime($admin_user['account_locked_until']) > time()): ?>
                <div class="status danger">
                    <h3>🔒 Account is Locked</h3>
                    <p>The account is locked until: <?php echo $admin_user['account_locked_until']; ?></p>
                    <p>Lock is due to too many failed login attempts (brute force protection).</p>
                </div>
            <?php endif; ?>
            
            <?php if ($admin_user['failed_login_count'] >= 3): ?>
                <div class="status warning">
                    <h3>⚠️ Multiple Failed Login Attempts</h3>
                    <p>Failed login attempts: <?php echo $admin_user['failed_login_count']; ?></p>
                    <p>Account will be locked after 5 failed attempts.</p>
                </div>
            <?php endif; ?>
            
            <h2>Password Verification Test</h2>
            <?php
            // Test password verification
            $test_password = 'admin123';
            $stored_hash = $admin_user['password'];
            
            echo "<p><strong>Testing password:</strong> admin123</p>";
            
            if (password_verify($test_password, $stored_hash)) {
                echo "<div class='status success'>✓ Password verification SUCCESSFUL</div>";
            } else {
                echo "<div class='status danger'>✗ Password verification FAILED</div>";
                echo "<p>The stored password hash does not match 'admin123'.</p>";
            }
            ?>
            
            <h2>Actions</h2>
            <div class="status info">
                <p><a href="fix-admin-password.php"><strong>Reset Admin Password</strong></a> - Generate a fresh password hash for 'admin123'</p>
                <p><a href="login.php"><strong>Try Login</strong></a> - Go to login page</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

