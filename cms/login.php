<?php
require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(CMS_URL . '/index.php');
}

$error = '';
$success = '';

if (isset($_GET['logout'])) {
    $success = 'You have been successfully logged out.';
}

if (isset($_GET['timeout'])) {
    $error = 'Your session has expired. Please login again.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = cleanInput($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $stmt = $conn->prepare("SELECT id, username, email, password, full_name, role FROM admin_users WHERE username = ? AND is_active = 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_name'] = $user['full_name'];
                $_SESSION['admin_role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                
                // Update last login
                $conn->query("UPDATE admin_users SET last_login = NOW() WHERE id = " . $user['id']);
                
                redirect(CMS_URL . '/index.php');
            } else {
                $error = 'Invalid username or password';
            }
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Login - FlipAvenue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <div class="inline-block bg-white p-4 rounded-full mb-4">
                <i class="fas fa-building text-4xl text-gray-800"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">FlipAvenue CMS</h1>
            <p class="text-gray-400">Content Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Sign In</h2>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p class="font-medium"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    <p class="font-medium"><?php echo $success; ?></p>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-user mr-2"></i>Username
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Enter your username"
                        required
                        autocomplete="username"
                    >
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                </div>
                
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.02]"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>
        </div>
        
        <!-- Default Credentials Info -->
        <div class="mt-6 bg-yellow-900 bg-opacity-50 backdrop-blur-sm border border-yellow-600 rounded-lg p-4">
            <p class="text-yellow-200 text-sm text-center">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Default Login:</strong> admin / admin123
            </p>
        </div>
        
        <p class="text-center text-gray-500 text-sm mt-6">
            &copy; <?php echo date('Y'); ?> FlipAvenue Limited. All rights reserved.
        </p>
    </div>
</body>
</html>

