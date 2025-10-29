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
            // Send email notification using improved method
            $to = "info@flipavenueltd.com";
            $email_subject = "New Contact Form Submission: " . $subject;
            
            // HTML email body for better formatting
            $email_body_html = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background-color: #ff9900; color: white; padding: 20px; text-align: center; }
                        .content { padding: 20px; background-color: #f9f9f9; }
                        .field { margin: 10px 0; }
                        .label { font-weight: bold; color: #666; }
                        .value { color: #333; }
                        .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>New Contact Form Submission</h2>
                        </div>
                        <div class='content'>
                            <div class='field'>
                                <div class='label'>Name:</div>
                                <div class='value'>" . htmlspecialchars($name) . "</div>
                            </div>
                            <div class='field'>
                                <div class='label'>Email:</div>
                                <div class='value'><a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></div>
                            </div>
                            <div class='field'>
                                <div class='label'>Phone:</div>
                                <div class='value'>" . htmlspecialchars($phone) . "</div>
                            </div>
                            <div class='field'>
                                <div class='label'>Subject:</div>
                                <div class='value'>" . htmlspecialchars($subject) . "</div>
                            </div>
                            <div class='field'>
                                <div class='label'>Message:</div>
                                <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                            </div>
                            <div class='field' style='margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;'>
                                <div class='label'>IP Address:</div>
                                <div class='value'>" . htmlspecialchars($ip_address) . "</div>
                            </div>
                            <div class='field'>
                                <div class='label'>Date:</div>
                                <div class='value'>" . date('Y-m-d H:i:s') . "</div>
                            </div>
                        </div>
                        <div class='footer'>
                            <p>This email was sent from your website contact form.</p>
                            <p>Flip Avenue Limited</p>
                        </div>
                    </div>
                </body>
                </html>
            ";
            
            // Plain text version for email clients that don't support HTML
            $email_body_text = "New Contact Form Submission\n\n";
            $email_body_text .= "Name: $name\n";
            $email_body_text .= "Email: $email\n";
            $email_body_text .= "Phone: $phone\n";
            $email_body_text .= "Subject: $subject\n\n";
            $email_body_text .= "Message:\n$message\n\n";
            $email_body_text .= "IP Address: $ip_address\n";
            $email_body_text .= "Date: " . date('Y-m-d H:i:s');
            
            // Improved email headers
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: Flip Avenue Website <noreply@flipavenueltd.com>\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            $headers .= "X-Priority: 3\r\n";
            
            // Try to send email (will fail silently if mail server not configured)
            // Note: For production, consider using PHPMailer or SMTP for better reliability
            @mail($to, $email_subject, $email_body_html, $headers);
            
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

