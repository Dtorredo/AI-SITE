<?php
require_once __DIR__ . '/../config/email.php';

header('Content-Type: text/plain; charset=utf-8');
$testEmail = 'livingstoneapeli@gmail.com'; 

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to capture all output
ob_start();

echo "=== Email Configuration Test ===\n\n";
echo "SMTP Host: " . SMTP_HOST . "\n";
echo "SMTP Port: " . SMTP_PORT . "\n";
echo "SMTP User: " . SMTP_USER . "\n";
echo "From Email: " . SITE_EMAIL . "\n";
echo "To Email: " . $testEmail . "\n\n";

try {
    // Check if PHPMailer is loaded
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        throw new Exception('PHPMailer is not properly installed or included.');
    }
    
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port = SMTP_PORT;
    
    // Enable verbose debug output
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {
        echo "[DEBUG] $str\n";
    };
    
    // Recipients
    $mail->setFrom(SITE_EMAIL, SITE_NAME);
    $mail->addAddress($testEmail);
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from ' . SITE_NAME;
    $mail->Body = 'This is a test email from ' . SITE_NAME . '.<br><br>If you received this, email sending is working correctly!';
    $mail->AltBody = 'This is a test email from ' . SITE_NAME . '. If you received this, email sending is working correctly!';
    
    echo "Sending test email to $testEmail...\n\n";
    
    if ($mail->send()) {
        echo "\n✅ SUCCESS: Test email sent successfully to $testEmail\n";
    } else {
        throw new Exception("Failed to send email: " . $mail->ErrorInfo);
    }
    
} catch (Exception $e) {
    $error = $e->getMessage();
    echo "\n❌ ERROR: $error\n\n";
    
    // Additional troubleshooting tips
    echo "=== Troubleshooting Tips ===\n";
    
    if (strpos($error, 'password') !== false) {
        echo "• Check if the SMTP password in config/email.php is correct\n";
    }
    
    if (strpos($error, 'connection') !== false) {
        echo "• Check if the SMTP server is accessible from your server\n";
        echo "• Verify the SMTP hostname and port are correct\n";
        echo "• Check if your firewall allows outbound connections on port " . SMTP_PORT . "\n";
    }
    
    if (strpos($error, 'PHPMailer') !== false) {
        echo "• Make sure PHPMailer is properly installed via Composer\n";
    }
}

// Get all output and clean the buffer
$output = ob_get_clean();

// If we're in a web context, add <pre> tags for better readability
if (php_sapi_name() !== 'cli') {
    echo "<pre>\n$output</pre>";
} else {
    echo $output;
}
