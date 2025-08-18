<?php
// File: api/subscribe.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/email.php';

$response = ['success' => false, 'message' => ''];

try {
    // Validate input
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $name = trim($data['name'] ?? '');
    
    if (!$email) {
        throw new Exception('Please provide a valid email address');
    }

    // Generate token for unsubscribe link
    $token = bin2hex(random_bytes(32));
    
    // Prepare statement
    $stmt = $pdo->prepare("
        INSERT INTO subscriptions (email, name, token, ip_address, user_agent, meta)
        VALUES (:email, :name, :token, :ip, :ua, :meta)
        ON DUPLICATE KEY UPDATE 
            is_active = 1,
            unsubscribed_at = NULL,
            token = IF(unsubscribed_at IS NULL, token, VALUES(token)),
            updated_at = CURRENT_TIMESTAMP
    ");

    // Execute
    $success = $stmt->execute([
        ':email' => $email,
        ':name' => $name,
        ':token' => $token,
        ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ':meta' => json_encode([
            'source' => 'website-footer',
            'timestamp' => date('c')
        ])
    ]);

    if ($success) {
        // Send welcome email
        $unsubscribeLink = SITE_URL . '/unsubscribe?token=' . $token;
        $emailData = [
            'name' => $name ?: 'there',
            'unsubscribe_link' => $unsubscribeLink,
            'current_year' => date('Y')
        ];
        
        sendEmail(
            $email,
            'Thanks for subscribing!',
            'welcome_subscriber',
            $emailData
        );

        $response = [
            'success' => true,
            'message' => 'Thank you for subscribing! Please check your email to confirm.'
        ];
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);