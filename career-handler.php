<?php
/**
 * Career Application Handler
 * Processes job applications and saves to database
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

// Function to upload file
function uploadFile($file, $folder = 'careers') {
    $target_dir = dirname(__DIR__) . '/assets/uploads/' . $folder . '/';
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    $allowed_types = ['pdf', 'doc', 'docx', 'zip', 'rar'];
    
    if (!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Only PDF, DOC, DOCX, ZIP, RAR files are allowed.'];
    }
    
    if ($file['size'] > 104857600) { // 100MB
        return ['success' => false, 'message' => 'File too large (max 100MB)'];
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return [
            'success' => true,
            'filename' => $new_filename,
            'path' => $folder . '/' . $new_filename
        ];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['career_application'])) {
    
    try {
        // Connect to database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Database connection failed");
        }
        
        $conn->set_charset("utf8mb4");
        
        // Get and validate form data
        $full_name = cleanInput($_POST['full_name'] ?? '');
        $email = cleanInput($_POST['email'] ?? '');
        $phone = cleanInput($_POST['phone'] ?? '');
        $position = cleanInput($_POST['position'] ?? '');
        $cover_letter = cleanInput($_POST['cover_letter'] ?? '');
        
        // Validation
        $errors = [];
        
        if (empty($full_name)) {
            $errors[] = 'Full name is required';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (empty($phone)) {
            $errors[] = 'Phone number is required';
        }
        
        if (empty($position)) {
            $errors[] = 'Please select a position';
        }
        
        if (empty($cover_letter)) {
            $errors[] = 'Cover letter is required';
        }
        
        if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== 0) {
            $errors[] = 'Resume/CV is required';
        }
        
        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => implode(', ', $errors)
            ]);
            exit;
        }
        
        // Handle file uploads
        $resume_path = '';
        $portfolio_path = '';
        
        // Upload resume
        $resume_upload = uploadFile($_FILES['resume'], 'careers');
        if (!$resume_upload['success']) {
            echo json_encode([
                'success' => false,
                'message' => $resume_upload['message']
            ]);
            exit;
        }
        $resume_path = $resume_upload['path'];
        
        // Upload portfolio if provided
        if (isset($_FILES['portfolio']) && $_FILES['portfolio']['error'] === 0) {
            $portfolio_upload = uploadFile($_FILES['portfolio'], 'careers');
            if ($portfolio_upload['success']) {
                $portfolio_path = $portfolio_upload['path'];
            }
        }
        
        // Escape data for database
        $full_name = $conn->real_escape_string($full_name);
        $email = $conn->real_escape_string($email);
        $phone = $conn->real_escape_string($phone);
        $position = $conn->real_escape_string($position);
        $cover_letter = $conn->real_escape_string($cover_letter);
        $resume_path = $conn->real_escape_string($resume_path);
        $portfolio_path = $conn->real_escape_string($portfolio_path);
        
        // Get IP address
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        // Insert into database (we'll need to create this table)
        $sql = "INSERT INTO career_applications (full_name, email, phone, position, cover_letter, resume_path, portfolio_path, ip_address, status, created_at) 
                VALUES ('$full_name', '$email', '$phone', '$position', '$cover_letter', '$resume_path', '$portfolio_path', '$ip_address', 'new', NOW())";
        
        if ($conn->query($sql)) {
            // Send email notification
            $to = "info@flipavenueltd.com";
            $email_subject = "New Career Application: " . $position;
            $email_body = "New job application received:\n\n";
            $email_body .= "Name: $full_name\n";
            $email_body .= "Email: $email\n";
            $email_body .= "Phone: $phone\n";
            $email_body .= "Position: $position\n\n";
            $email_body .= "Cover Letter:\n$cover_letter\n\n";
            $email_body .= "Resume: $resume_path\n";
            if ($portfolio_path) {
                $email_body .= "Portfolio: $portfolio_path\n";
            }
            $email_body .= "\nIP Address: $ip_address\n";
            $email_body .= "Date: " . date('Y-m-d H:i:s');
            
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            // Try to send email
            @mail($to, $email_subject, $email_body, $headers);
            
            echo json_encode([
                'success' => true,
                'message' => 'Thank you! Your application has been submitted successfully. We will review it and get back to you within 5-7 business days.'
            ]);
        } else {
            throw new Exception("Error saving application");
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Sorry, there was an error processing your application. Please try again or email us directly at info@flipavenueltd.com'
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>

