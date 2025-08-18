<?php
/**
 * Admin Notification: New Subscriber
 * 
 * @var string $name Subscriber's name
 * @var string $email Subscriber's email
 * @var string $subscribed_at When they subscribed
 * @var string $ip Subscriber's IP address
 * @var string $user_agent Subscriber's user agent
 * @var array $meta Additional subscription metadata
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>New Subscriber - Aurai Solutions</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; }
        .header { background-color: #047857; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .footer { margin-top: 20px; padding: 10px; text-align: center; font-size: 12px; color: #666; }
        .info-box { background-color: #f0fdf4; border-left: 4px solid #047857; padding: 12px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { width: 120px; font-weight: bold; color: #4a5568; }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Newsletter Subscriber</h1>
    </div>
    
    <div class="content">
        <p>Hello Admin,</p>
        
        <p>You have a new subscriber to your newsletter. Here are the details:</p>
        
        <table>
            <tr>
                <th>Name:</th>
                <td><?= !empty($name) ? htmlspecialchars($name) : '<em>Not provided</em>' ?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a></td>
            </tr>
            <tr>
                <th>Subscribed On:</th>
                <td><?= date('F j, Y \a\t g:i a', strtotime($subscribed_at)) ?></td>
            </tr>
            <tr>
                <th>IP Address:</th>
                <td><?= htmlspecialchars($ip) ?></td>
            </tr>
            <tr>
                <th>Source:</th>
                <td><?= !empty($meta['source']) ? ucfirst(htmlspecialchars($meta['source'])) : 'Website' ?></td>
            </tr>
        </table>
        
        <div class="info-box">
            <p><strong>User Agent:</strong><br>
            <?= !empty($user_agent) ? htmlspecialchars($user_agent) : 'Not available' ?></p>
            
            <?php if (!empty($meta['form_data'])): ?>
            <p><strong>Additional Data:</strong><br>
            <?= nl2br(print_r($meta['form_data'], true)) ?></p>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 20px; text-align: center;">
            <a href="<?= SITE_URL ?>/admin/subscribers" style="display: inline-block; background-color: #047857; color: white; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold;">
                View All Subscribers
            </a>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; <?= date('Y') ?> Aurai Solutions. All rights reserved.</p>
        <p>
            <a href="<?= SITE_URL ?>/admin" style="color: #666; text-decoration: none;">Admin Panel</a> | 
            <a href="<?= SITE_URL ?>/privacy" style="color: #666; text-decoration: none;">Privacy Policy</a>
        </p>
    </div>
</body>
</html>
