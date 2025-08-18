<?php
// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include configurations
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/email.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get and validate input
$input = json_decode(file_get_contents('php://input'), true) ?? [];

// If no JSON data, try form data
if (empty($input)) {
    $input = $_POST;
}

// Validate required fields
$required = ['full_name', 'email', 'service', 'message'];
$errors = [];

foreach ($required as $field) {
    if (empty($input[$field])) {
        $errors[$field] = 'This field is required';
    }
}

// Validate email
if (!filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address';
}

// If there are validation errors, return them
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['errors' => $errors]);
    exit;
}

try {
    // Sanitize input
    $contact = [
        'full_name' => htmlspecialchars($input['full_name'] ?? ''),
        'email' => filter_var($input['email'], FILTER_SANITIZE_EMAIL),
        'company' => htmlspecialchars($input['company'] ?? ''),
        'service' => htmlspecialchars($input['service'] ?? 'general'),
        'message' => htmlspecialchars($input['message'] ?? ''),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Save to database
    $db = getDBConnection();
    $stmt = $db->prepare(
        'INSERT INTO contacts (full_name, email, company, service, message, ip_address, user_agent) 
         VALUES (:full_name, :email, :company, :service, :message, :ip_address, :user_agent)'
    );
    
    $stmt->execute([
        ':full_name' => $contact['full_name'],
        ':email' => $contact['email'],
        ':company' => $contact['company'],
        ':service' => $contact['service'],
        ':message' => $contact['message'],
        ':ip_address' => $contact['ip_address'],
        ':user_agent' => $contact['user_agent']
    ]);
    
    $contactId = $db->lastInsertId();
    
    // Send email to admin
    $adminSubject = "New Contact Form Submission: " . $contact['full_name'];
    sendEmail(
        ADMIN_EMAIL,
        $adminSubject,
        'admin_notification',
        $contact + ['contact_id' => $contactId]
    );
    
    // Send confirmation to user
    $userSubject = "Thank you for contacting " . SITE_NAME;
    sendEmail(
        $contact['email'],
        $userSubject,
        'user_confirmation',
        $contact + ['site_name' => SITE_NAME, 'support_email' => SUPPORT_EMAIL]
    );
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your message. We will get back to you soon!',
        'data' => [
            'contact_id' => $contactId,
            'email' => $contact['email']
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while processing your request. Please try again later.',
        'debug' => (APP_ENV === 'development') ? $e->getMessage() : null
    ]);
}
