<?php
require_once 'config.php';
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token';
    } else {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validate input
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'All fields are required';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } else {
            // Validate password strength
            $password_errors = validatePasswordStrength($new_password);
            if (!empty($password_errors)) {
                $error = 'Password does not meet requirements:<br>' . implode('<br>', $password_errors);
            } else {
                // Verify current password
                $stmt = $conn->prepare("SELECT password FROM admin_users WHERE id = ?");
                $stmt->bind_param("i", $_SESSION['admin_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                
                if (password_verify($current_password, $user['password'])) {
                    // Update password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE admin_users SET password = ?, password_changed_at = NOW() WHERE id = ?");
                    $stmt->bind_param("si", $hashed_password, $_SESSION['admin_id']);
                    
                    if ($stmt->execute()) {
                        $success = 'Password changed successfully';
                        logSecurityEvent('password_changed', 'Password changed successfully', $_SESSION['admin_id']);
                        
                        // Invalidate all other sessions
                        $stmt = $conn->prepare("UPDATE admin_sessions SET is_active = 0 WHERE user_id = ? AND session_token != ?");
                        $stmt->bind_param("is", $_SESSION['admin_id'], $_SESSION['session_token']);
                        $stmt->execute();
                    } else {
                        $error = 'Failed to update password';
                    }
                } else {
                    $error = 'Current password is incorrect';
                    logSecurityEvent('password_change_failed', 'Incorrect current password provided', $_SESSION['admin_id']);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - FlipAvenue CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <div class="inline-block bg-blue-100 p-4 rounded-full mb-4">
                        <i class="fas fa-key text-3xl text-blue-600"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Change Password</h1>
                    <p class="text-gray-600">Update your account password</p>
                </div>

                <?php if ($error): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <p class="font-medium"><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                        <p class="font-medium"><?php echo $success; ?></p>
                    </div>
                <?php endif; ?>

                <!-- Password Requirements -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Password Requirements:</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• At least 8 characters long</li>
                        <li>• Contains uppercase and lowercase letters</li>
                        <li>• Contains at least one number</li>
                        <li>• Contains at least one special character</li>
                    </ul>
                </div>

                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="mb-6">
                        <label for="current_password" class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-lock mr-2"></i>Current Password
                        </label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Enter your current password"
                            required
                        >
                    </div>
                    
                    <div class="mb-6">
                        <label for="new_password" class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-key mr-2"></i>New Password
                        </label>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Enter your new password"
                            required
                            minlength="8"
                        >
                        <div class="mt-2">
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <span id="length-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>8+ characters
                                </span>
                                <span id="uppercase-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>Uppercase
                                </span>
                                <span id="lowercase-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>Lowercase
                                </span>
                                <span id="number-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>Number
                                </span>
                                <span id="special-check" class="flex items-center">
                                    <i class="fas fa-circle text-gray-300 mr-1"></i>Special
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="confirm_password" class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-check mr-2"></i>Confirm New Password
                        </label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Confirm your new password"
                            required
                        >
                    </div>
                    
                    <div class="flex space-x-4">
                        <button 
                            type="submit" 
                            class="flex-1 bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                        >
                            <i class="fas fa-save mr-2"></i>Change Password
                        </button>
                        
                        <a 
                            href="<?php echo CMS_URL; ?>/index.php" 
                            class="flex-1 bg-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 text-center"
                        >
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Password strength checker
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const checks = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };
            
            // Update check indicators
            document.getElementById('length-check').innerHTML = 
                `<i class="fas fa-${checks.length ? 'check-circle text-green-500' : 'circle text-gray-300'} mr-1"></i>8+ characters`;
            
            document.getElementById('uppercase-check').innerHTML = 
                `<i class="fas fa-${checks.uppercase ? 'check-circle text-green-500' : 'circle text-gray-300'} mr-1"></i>Uppercase`;
            
            document.getElementById('lowercase-check').innerHTML = 
                `<i class="fas fa-${checks.lowercase ? 'check-circle text-green-500' : 'circle text-gray-300'} mr-1"></i>Lowercase`;
            
            document.getElementById('number-check').innerHTML = 
                `<i class="fas fa-${checks.number ? 'check-circle text-green-500' : 'circle text-gray-300'} mr-1"></i>Number`;
            
            document.getElementById('special-check').innerHTML = 
                `<i class="fas fa-${checks.special ? 'check-circle text-green-500' : 'circle text-gray-300'} mr-1"></i>Special`;
        });
        
        // Password confirmation checker
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && newPassword !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
