<?php
// File: api/subscribe.php
header('Content-Type: application/json');
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
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input.');
    }

    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $name = trim($input['name'] ?? '');
    
    if (!$email) {
        throw new Exception('Please provide a valid email address.');
    }

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
    
    // Prepare and execute insert/update query
    $stmt = $pdo->prepare("
        INSERT INTO subscriptions (email, name, token, ip_address, user_agent, meta, is_active, subscribed_at)
        VALUES (:email, :name, :token, :ip, :ua, :meta, 1, :subscribed_at)
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

    $meta = [
        'source' => 'website-footer',
        'timestamp' => $now,
        'form_data' => $input
    ];

    $success = $stmt->execute([
        ':email' => $email,
        ':name' => $name ?: null,
        ':token' => $token,
        ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? null,
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
        'signup_date' => date('F j, Y')
    ];
    
    $emailSent = sendEmail(
        $email,
        'Thanks for subscribing to Aurai Solutions!',
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
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Not available',
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
    $response['message'] = 'A database error occurred. Please try again later.';
    http_response_code(500);
} catch (Exception $e) {
    error_log('Error in subscribe.php: ' . $e->getMessage());
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

// Ensure no output before this
if (ob_get_level() > 0) {
    ob_clean();
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);