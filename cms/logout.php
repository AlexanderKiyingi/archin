<?php
require_once 'config.php';

// Log the logout event if user is logged in
if (isset($_SESSION['admin_id'])) {
    logSecurityEvent('user_logout', 'User logged out successfully', $_SESSION['admin_id']);
}

session_unset();
session_destroy();
header('Location: login.php?logout=1');
exit();
?>

