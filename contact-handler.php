<?php
/**
 * Contact Form Handler
 * Processes contact form submissions and saves to database
 */

// Include centralized database connection
require_once 'cms/db_connect.php';

// Response headers
header('Content-Type: application/json');

// Function to clean input
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
    
    try {
        // Connect to database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Database connection failed");
        }
        
        $conn->set_charset("utf8mb4");
        
        // Get and validate form data
        $name = cleanInput($_POST['name'] ?? '');
        $email = cleanInput($_POST['email'] ?? '');
        $phone = cleanInput($_POST['phone'] ?? '');
        $subject = cleanInput($_POST['subject'] ?? 'General Inquiry');
        $message = cleanInput($_POST['message'] ?? '');
        
        // Validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($message)) {
            $errors[] = 'Message is required';
        }
        
        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => implode(', ', $errors)
            ]);
            exit;
        }
        
        // Escape data for database
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $phone = $conn->real_escape_string($phone);
        $subject = $conn->real_escape_string($subject);
        $message = $conn->real_escape_string($message);
        
        // Get IP address
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        // Insert into database
        $sql = "INSERT INTO contact_submissions (name, email, phone, subject, message, ip_address, status, created_at) 
                VALUES ('$name', '$email', '$phone', '$subject', '$message', '$ip_address', 'new', NOW())";
        
        if ($conn->query($sql)) {
            // Send email notification (optional - requires mail server configuration)
            $to = "info@flipavenueltd.com";
            $email_subject = "New Contact Form Submission: " . $subject;
            $email_body = "Name: $name\n";
            $email_body .= "Email: $email\n";
            $email_body .= "Phone: $phone\n";
            $email_body .= "Subject: $subject\n\n";
            $email_body .= "Message:\n$message\n\n";
            $email_body .= "IP Address: $ip_address\n";
            $email_body .= "Date: " . date('Y-m-d H:i:s');
            
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            // Try to send email (will fail silently if mail server not configured)
            @mail($to, $email_subject, $email_body, $headers);
            
            echo json_encode([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully.'
            ]);
        } else {
            throw new Exception("Error saving message");
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Sorry, there was an error processing your request. Please try again or email us directly at info@flipavenueltd.com'
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>

