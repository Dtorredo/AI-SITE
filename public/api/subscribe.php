<?php
// Set CORS headers to allow cross-origin requests
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

// Initialize response
$response = [
    'success' => false, 
    'message' => 'An error occurred. Please try again later.'
];

try {
    // Check request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. Only POST is allowed.');
    }

    // Get and validate input
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    
    // If no JSON data, try form data
    if (empty($input)) {
        $input = $_POST;
    }

    // Validate required fields
    $errors = [];
    if (empty($input['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $name = !empty($input['name']) ? htmlspecialchars($input['name']) : '';
    $source = !empty($input['source']) ? htmlspecialchars($input['source']) : 'website';
    
    // Get database connection
    $pdo = getDBConnection();
    
    // Check if email already exists and is active
    $stmt = $pdo->prepare("SELECT id, is_active, subscribed_at FROM subscriptions WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();
    
    if ($existing && $existing['is_active']) {
        $response = [
            'success' => true,
            'message' => 'You are already subscribed to our newsletter.'
        ];
        echo json_encode($response);
        exit;
    }

    // Generate token for unsubscribe link
    $token = bin2hex(random_bytes(32));
    $now = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $meta = [
        'source' => $source,
        'timestamp' => $now,
        'ip' => $ip,
        'user_agent' => $userAgent,
        'referrer' => $_SERVER['HTTP_REFERER'] ?? null
    ];
    
    // Prepare and execute insert/update query
    $stmt = $pdo->prepare("
        INSERT INTO subscriptions (email, name, token, ip_address, user_agent, meta, is_active, subscribed_at)
        VALUES (:email, :name, :token, :ip_address, :user_agent, :meta, 1, :subscribed_at)
        ON DUPLICATE KEY UPDATE 
            name = VALUES(name),
            token = VALUES(token),
            ip_address = VALUES(ip_address),
            user_agent = VALUES(user_agent),
            meta = VALUES(meta),
            is_active = 1,
            unsubscribed_at = NULL,
            updated_at = CURRENT_TIMESTAMP,
            subscribed_at = IF(unsubscribed_at IS NOT NULL, VALUES(subscribed_at), subscribed_at)
    ");

    $success = $stmt->execute([
        ':email' => $email,
        ':name' => $name ?: null,
        ':token' => $token,
        ':ip_address' => $ip,
        ':user_agent' => $userAgent,
        ':meta' => json_encode($meta),
        ':subscribed_at' => $now
    ]);

    if (!$success) {
        throw new Exception('Failed to save subscription. Please try again.');
    }

    // Get the subscription ID (for new subscriptions)
    $subscriptionId = $pdo->lastInsertId() ?: $existing['id'];

    // Send welcome email to subscriber
    $unsubscribeLink = SITE_URL . '/unsubscribe?token=' . $token;
    $emailData = [
        'name' => $name ?: 'there',
        'unsubscribe_link' => $unsubscribeLink,
        'current_year' => date('Y'),
        'signup_date' => date('F j, Y'),
        'email' => $email
    ];
    
    $emailSent = sendEmail(
        $email,
        'Thanks for subscribing to ' . SITE_NAME . '!',
        'welcome_subscriber',
        $emailData
    );

    if (!$emailSent) {
        error_log("Failed to send welcome email to: $email");
        // Continue even if welcome email fails
    }

    // Send admin notification
    $adminNotificationSent = sendEmail(
        ADMIN_EMAIL,
        'New Newsletter Subscriber: ' . ($name ?: $email),
        'admin_new_subscriber',
        [
            'name' => $name,
            'email' => $email,
            'subscribed_at' => $now,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'unsubscribe_link' => ADMIN_URL . '/subscriptions/' . $subscriptionId . '/unsubscribe',
            'meta' => $meta
        ]
    );

    if (!$adminNotificationSent) {
        error_log("Failed to send admin notification for new subscriber: $email");
        // Continue even if admin notification fails
    }

    $response = [
        'success' => true,
        'message' => 'Thank you for subscribing! Please check your email for confirmation.'
    ];

} catch (PDOException $e) {
    error_log('Database error in subscribe.php: ' . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'A database error occurred. Please try again later.'
    ];
    http_response_code(500);
} catch (Exception $e) {
    error_log('Error in subscribe.php: ' . $e->getMessage());
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
    http_response_code(400);
}

// Ensure no output before this
if (ob_get_level() > 0) {
    ob_clean();
}

// Send JSON response
echo json_encode($response);