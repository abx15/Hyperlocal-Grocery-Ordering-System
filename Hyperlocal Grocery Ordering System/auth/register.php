<?php
// Set headers to prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Include database connection with correct path
require_once __DIR__ . '/../includes/db.php';

// Initialize response array
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and trim all inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $terms = isset($_POST['terms']) ? true : false;

    // Validate inputs
    if (empty($name) || strlen($name) < 2) {
        $response['message'] = 'Please enter your full name (at least 2 characters)';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address';
    } elseif (!preg_match('/^[+]?[(]?[0-9]{1,4}[)]?[-\s.]?[0-9]{1,3}[-\s.]?[0-9]{3,6}$/im', $phone)) {
        $response['message'] = 'Please enter a valid phone number';
    } elseif (strlen($password) < 8) {
        $response['message'] = 'Password must be at least 8 characters';
    } elseif ($password !== $confirm) {
        $response['message'] = 'Passwords do not match';
    } elseif (empty($address) || strlen($address) < 10) {
        $response['message'] = 'Please enter a complete address (at least 10 characters)';
    } elseif (!$terms) {
        $response['message'] = 'You must agree to the terms and conditions';
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $response['message'] = 'Email address already registered';
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                
                // Insert into database
                $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, address, created_at) 
                                      VALUES (?, ?, ?, ?, ?, NOW())");
                
                if ($stmt->execute([$name, $email, $phone, $hashedPassword, $address])) {
                    $response['success'] = true;
                    $response['message'] = 'Registration successful! Redirecting to login...';
                    
                    // Start session and set user data if you want to login directly
                    session_start();
                    $_SESSION['registration_success'] = true;
                }
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $response['message'] = 'Registration failed. Please try again later.';
        }
    }
} else {
    $response['message'] = 'Invalid request method';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>