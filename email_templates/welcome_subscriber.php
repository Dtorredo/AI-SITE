<?php
// File: email_templates/welcome_subscriber.php
/**
 * @var string $name
 * @var string $unsubscribe_link
 * @var int $current_year
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Welcome to Aurai Solutions</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; }
        .header { background-color: #047857; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .button { 
            display: inline-block; 
            padding: 10px 20px; 
            margin: 20px 0; 
            background-color: #047857; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
        }
        .footer { 
            margin-top: 20px; 
            padding: 10px; 
            text-align: center; 
            font-size: 12px; 
            color: #666; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Aurai Solutions</h1>
    </div>
    
    <div class="content">
        <p>Hello <?= htmlspecialchars($name) ?>,</p>
        
        <p>Thank you for subscribing to our newsletter! We're excited to keep you updated with our latest news, 
        product updates, and special offers.</p>
        
        <p>You'll receive our next newsletter in your inbox soon. In the meantime, feel free to explore our 
        <a href="<?= SITE_URL ?>" style="color: #047857;">website</a> to learn more about our services.</p>
        
        <p>If you ever wish to unsubscribe, you can do so by clicking the link below:</p>
        
        <p>
            <a href="<?= $unsubscribe_link ?>" class="button" 
               style="color: white; text-decoration: none;">Unsubscribe</a>
        </p>
        
        <p>Best regards,<br>The Aurai Solutions Team</p>
    </div>
    
    <div class="footer">
        <p>&copy; <?= $current_year ?> Aurai Solutions. All rights reserved.</p>
        <p>
            <a href="<?= SITE_URL ?>/privacy" style="color: #666; text-decoration: none;">Privacy Policy</a> | 
            <a href="<?= SITE_URL ?>/terms" style="color: #666; text-decoration: none;">Terms of Service</a>
        </p>
        <p>
            <small>
                You're receiving this email because you signed up for updates from Aurai Solutions.
                <br>
                Our mailing address is: <?= SITE_EMAIL ?>
            </small>
        </p>
    </div>
</body>
</html>