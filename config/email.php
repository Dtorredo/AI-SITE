<?php
// Email configuration
define('SITE_NAME', 'Aurai Solutions');
define('SITE_EMAIL', 'info@aurai.co.ke');
define('ADMIN_EMAIL', 'solomon@aurai.co.ke');
define('SUPPORT_EMAIL', 'info@aurai.co.ke');

// SMTP Configuration
define('SMTP_HOST', 'mail.aurai.co.ke');
define('SMTP_PORT', 465);
define('SMTP_USER', 'developers@aurai.co.ke');
define('SMTP_PASS', 'Savvytonny@apeli99');
define('SMTP_SECURE', 'ssl');

// Email templates path
define('EMAIL_TEMPLATES_PATH', __DIR__ . '/../email_templates');

// Initialize PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendEmail($to, $subject, $template, $data = []) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Recipients
        $mail->setFrom(SITE_EMAIL, SITE_NAME);
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        
        // Load template
        $templatePath = EMAIL_TEMPLATES_PATH . "/{$template}.php";
        if (file_exists($templatePath)) {
            ob_start();
            extract($data);
            include $templatePath;
            $mail->Body = ob_get_clean();
        } else {
            throw new Exception("Email template not found: {$template}");
        }
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
