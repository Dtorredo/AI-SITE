<?php
header('Content-Type: application/json');

// Include configurations
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/email.php';

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get and validate input
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

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

// Sanitize input
$contact = [
    'full_name' => htmlspecialchars($input['full_name'] ?? ''),
    'email' => filter_var($input['email'], FILTER_SANITIZE_EMAIL),
    'company' => !empty($input['company']) ? htmlspecialchars($input['company']) : null,
    'service' => in_array($input['service'], ['automation', 'web-dev', 'chatbot', 'crm', 'consulting']) 
        ? $input['service'] 
        : 'consulting',
    'message' => htmlspecialchars($input['message'] ?? ''),
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
];

try {
    $db = getDBConnection();
    
    // Insert contact into database
    $stmt = $db->prepare("
        INSERT INTO contacts 
        (full_name, email, company, service, message, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $contact['full_name'],
        $contact['email'],
        $contact['company'],
        $contact['service'],
        $contact['message'],
        $contact['ip_address'],
        $contact['user_agent']
    ]);
    
    $contactId = $db->lastInsertId();
    
    // Send notification to admin
    $adminSubject = "New Contact Form Submission: " . htmlspecialchars($contact['full_name']);
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
    
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your message. We will get back to you soon!'
    ]);
    
} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while processing your request. Please try again later.'
    ]);
}
?>
