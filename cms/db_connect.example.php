<?php
/**
 * Database Connection File - EXAMPLE
 * Copy this file to db_connect.php and update with your actual credentials
 * 
 * IMPORTANT: Never commit db_connect.php to version control!
 */

// Database Configuration
define('DB_HOST', 'localhost');              // Usually localhost for Hostinger
define('DB_USER', 'u680675202_flipavenue');  // Your Hostinger database username
define('DB_PASS', 'your_db_password');       // Your Hostinger database password
define('DB_NAME', 'u680675202_flipavenue_cms'); // Your Hostinger database name

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        // Log error (in production, don't display error details)
        error_log("Database Connection Failed: " . $conn->connect_error);
        die("Database connection error. Please contact the administrator.");
    }
    
    // Set charset to utf8mb4 for full Unicode support
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // Log error (in production, don't display error details)
    error_log("Database Error: " . $e->getMessage());
    die("Database error. Please contact the administrator.");
}

/**
 * Close database connection (call this at the end of scripts if needed)
 */
function closeDbConnection() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
}

/**
 * Execute a prepared statement with parameters
 * @param string $query SQL query with placeholders
 * @param string $types Parameter types (e.g., 'ss' for two strings)
 * @param array $params Array of parameters
 * @return mysqli_stmt|false Statement object or false on failure
 */
function executeQuery($query, $types = '', $params = []) {
    global $conn;
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
    
    return $stmt;
}

/**
 * Fetch single row from database
 * @param string $query SQL query
 * @param string $types Parameter types
 * @param array $params Parameters
 * @return array|null Row data or null if not found
 */
function fetchRow($query, $types = '', $params = []) {
    $stmt = executeQuery($query, $types, $params);
    if (!$stmt) {
        return null;
    }
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row;
}

/**
 * Fetch all rows from database
 * @param string $query SQL query
 * @param string $types Parameter types
 * @param array $params Parameters
 * @return array Array of rows
 */
function fetchAll($query, $types = '', $params = []) {
    $stmt = executeQuery($query, $types, $params);
    if (!$stmt) {
        return [];
    }
    
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
    
    return $rows;
}

/**
 * Get the last inserted ID
 * @return int Last insert ID
 */
function getLastInsertId() {
    global $conn;
    return $conn->insert_id;
}

/**
 * Escape string for SQL queries (use prepared statements instead when possible)
 * @param string $value Value to escape
 * @return string Escaped value
 */
function escapeString($value) {
    global $conn;
    return $conn->real_escape_string($value);
}

/**
 * Begin database transaction
 */
function beginTransaction() {
    global $conn;
    $conn->begin_transaction();
}

/**
 * Commit database transaction
 */
function commitTransaction() {
    global $conn;
    $conn->commit();
}

/**
 * Rollback database transaction
 */
function rollbackTransaction() {
    global $conn;
    $conn->rollback();
}

// Register shutdown function to close connection
register_shutdown_function('closeDbConnection');
?>

