<?php
/**
 * Security Helper Functions
 * Comprehensive security measures for FlipAvenue CMS
 */

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Validate password strength
 */
function validatePasswordStrength($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'Password must contain at least one special character';
    }
    
    return $errors;
}

/**
 * Generate secure random password
 */
function generateSecurePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    return substr(str_shuffle($chars), 0, $length);
}

/**
 * Check for brute force attempts
 */
function checkBruteForce($username, $max_attempts = 5, $lockout_time = 900) { // 15 minutes
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT COUNT(*) as attempts 
        FROM login_attempts 
        WHERE username = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL ? SECOND)
    ");
    $stmt->bind_param("si", $username, $lockout_time);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['attempts'] >= $max_attempts;
}

/**
 * Record failed login attempt
 */
function recordFailedAttempt($username, $ip_address) {
    global $conn;
    
    $stmt = $conn->prepare("
        INSERT INTO login_attempts (username, ip_address, attempt_time) 
        VALUES (?, ?, NOW())
    ");
    $stmt->bind_param("ss", $username, $ip_address);
    $stmt->execute();
}

/**
 * Clear failed attempts for successful login
 */
function clearFailedAttempts($username) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM login_attempts WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
}

/**
 * Generate 2FA secret key
 */
function generate2FASecret() {
    return base32_encode(random_bytes(20));
}

/**
 * Verify 2FA code
 */
function verify2FACode($secret, $code) {
    $timeSlice = floor(time() / 30);
    
    for ($i = -1; $i <= 1; $i++) {
        $calculatedCode = calculateTOTP($secret, $timeSlice + $i);
        if (hash_equals($calculatedCode, $code)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Calculate TOTP code
 */
function calculateTOTP($secret, $timeSlice) {
    $secretKey = base32_decode($secret);
    $time = pack('N*', 0) . pack('N*', $timeSlice);
    $hm = hash_hmac('sha1', $time, $secretKey, true);
    $offset = ord(substr($hm, -1)) & 0x0F;
    $hashpart = substr($hm, $offset, 4);
    $value = unpack('N', $hashpart);
    $value = $value[1];
    $value = $value & 0x7FFFFFFF;
    
    $modulo = pow(10, 6);
    return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
}

/**
 * Base32 encode
 */
function base32_encode($data) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $output = '';
    $v = 0;
    $vbits = 0;
    
    for ($i = 0, $j = strlen($data); $i < $j; $i++) {
        $v <<= 8;
        $v += ord($data[$i]);
        $vbits += 8;
        
        while ($vbits >= 5) {
            $vbits -= 5;
            $output .= $chars[$v >> $vbits];
            $v &= ((1 << $vbits) - 1);
        }
    }
    
    if ($vbits > 0) {
        $v <<= (5 - $vbits);
        $output .= $chars[$v];
    }
    
    return $output;
}

/**
 * Base32 decode
 */
function base32_decode($data) {
    $map = array_flip(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'));
    $output = '';
    $v = 0;
    $vbits = 0;
    
    for ($i = 0, $j = strlen($data); $i < $j; $i++) {
        $v <<= 5;
        $v += $map[$data[$i]];
        $vbits += 5;
        
        while ($vbits >= 8) {
            $vbits -= 8;
            $output .= chr($v >> $vbits);
            $v &= ((1 << $vbits) - 1);
        }
    }
    
    return $output;
}

/**
 * Log security events
 */
function logSecurityEvent($event, $details = '', $user_id = null) {
    global $conn;
    
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $stmt = $conn->prepare("
        INSERT INTO security_logs (event_type, details, user_id, ip_address, user_agent, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("ssiss", $event, $details, $user_id, $ip_address, $user_agent);
    $stmt->execute();
}

/**
 * Sanitize file upload
 */
function sanitizeFileUpload($file, $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']) {
    $errors = [];
    
    // Check file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        $errors[] = 'File size must be less than 5MB';
    }
    
    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed_types)) {
        $errors[] = 'File type not allowed';
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowed_mimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    
    if (!isset($allowed_mimes[$extension]) || $mime_type !== $allowed_mimes[$extension]) {
        $errors[] = 'Invalid file type detected';
    }
    
    return $errors;
}

/**
 * Set security headers
 */
function setSecurityHeaders() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' https://cdn.tailwindcss.com; style-src \'self\' \'unsafe-inline\' https://cdnjs.cloudflare.com; img-src \'self\' data:; font-src \'self\' https://cdnjs.cloudflare.com;');
}

/**
 * Validate session security
 */
function validateSessionSecurity() {
    // Check session timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        return false;
    }
    
    // Check IP address consistency
    if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
        session_unset();
        session_destroy();
        return false;
    }
    
    // Update last activity
    $_SESSION['last_activity'] = time();
    
    return true;
}

/**
 * Encrypt sensitive data
 */
function encryptData($data, $key = null) {
    if ($key === null) {
        $key = getEncryptionKey();
    }
    
    $iv = random_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    
    return base64_encode($iv . $encrypted);
}

/**
 * Decrypt sensitive data
 */
function decryptData($encrypted_data, $key = null) {
    if ($key === null) {
        $key = getEncryptionKey();
    }
    
    $data = base64_decode($encrypted_data);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
}

/**
 * Get encryption key
 */
function getEncryptionKey() {
    // In production, store this securely in environment variables
    return hash('sha256', 'flipavenue_secret_key_2024', true);
}

/**
 * Rate limiting
 */
function checkRateLimit($identifier, $max_requests = 100, $time_window = 3600) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT COUNT(*) as requests 
        FROM rate_limits 
        WHERE identifier = ? AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
    ");
    $stmt->bind_param("si", $identifier, $time_window);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['requests'] >= $max_requests) {
        return false;
    }
    
    // Record this request
    $stmt = $conn->prepare("
        INSERT INTO rate_limits (identifier, created_at) 
        VALUES (?, NOW())
    ");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    
    return true;
}
?>
