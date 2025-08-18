<?php
// File: unsubscribe.php
require_once __DIR__ . '/config/database.php';

$message = 'Invalid unsubscribe request.';
$success = false;

if (!empty($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        $stmt = $pdo->prepare("
            UPDATE subscriptions 
            SET is_active = FALSE, 
                unsubscribed_at = CURRENT_TIMESTAMP 
            WHERE token = ? AND is_active = TRUE
        ");
        
        $stmt->execute([$token]);
        
        if ($stmt->rowCount() > 0) {
            $message = 'You have been successfully unsubscribed from our mailing list.';
            $success = true;
        } else {
            $message = 'Invalid or expired unsubscribe link.';
        }
    } catch (Exception $e) {
        $message = 'An error occurred while processing your request.';
        error_log('Unsubscribe error: ' . $e->getMessage());
    }
}

// Show a nice HTML page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe - Aurai Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-6">
            <img src="/images/logo.svg" alt="Aurai Solutions" class="h-12 mx-auto">
        </div>
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                <?= $success ? 'Unsubscribed Successfully' : 'Unsubscribe Failed' ?>
            </h1>
            <p class="text-gray-600"><?= htmlspecialchars($message) ?></p>
        </div>
        
        <?php if ($success): ?>
        <div class="p-4 bg-green-50 text-green-800 rounded-md mb-6">
            <p>You've been removed from our mailing list and won't receive any more emails from us.</p>
        </div>
        <?php endif; ?>
        
        <a href="/" class="inline-block px-6 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition">
            Return to Homepage
        </a>
        
        <div class="mt-8 pt-6 border-t border-gray-200 text-sm text-gray-500">
            <p>Changed your mind? You can resubscribe anytime by signing up again on our website.</p>
        </div>
    </div>
</body>
</html>