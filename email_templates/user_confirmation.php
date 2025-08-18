<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Thank You for Contacting <?= SITE_NAME ?></title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 8px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #0d6efd; margin: 0 0 10px 0;">Thank You for Contacting Us!</h1>
            <p style="color: #6c757d; margin: 0;">We've received your message and will get back to you soon.</p>
        </div>
        
        <div style="background-color: #ffffff; border-radius: 6px; padding: 20px; margin-bottom: 20px; border: 1px solid #e9ecef;">
            <h2 style="color: #0d6efd; margin-top: 0; border-bottom: 1px solid #e9ecef; padding-bottom: 10px; font-size: 20px;">Your Message</h2>
            
            <p>Hello <?= htmlspecialchars($full_name) ?>,</p>
            
            <p>Thank you for reaching out to us about our <strong><?= ucfirst(str_replace('-', ' ', $service)) ?></strong> services. We've received your message and one of our team members will review it shortly.</p>
            
            <div style="background-color: #f8f9fa; border-left: 4px solid #0d6efd; padding: 15px; margin: 20px 0; font-style: italic;">
                <?= nl2br(htmlspecialchars($message)) ?>
            </div>
            
            <p>We typically respond within 24-48 hours. If you need immediate assistance, please don't hesitate to contact us directly at <a href="mailto:<?= SUPPORT_EMAIL ?>" style="color: #0d6efd; text-decoration: none;"><?= SUPPORT_EMAIL ?></a>.</p>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background-color: #e7f1ff; border-radius: 6px; text-align: center;">
            <h3 style="margin-top: 0; color: #0a58ca;">What Happens Next?</h3>
            <ol style="text-align: left; padding-left: 20px; margin: 15px 0 0 0;">
                <li style="margin-bottom: 10px;">Our team will review your inquiry</li>
                <li style="margin-bottom: 10px;">We'll contact you to discuss your requirements in more detail</li>
                <li>We'll provide a customized solution tailored to your needs</li>
            </ol>
        </div>
        
        <div style="margin-top: 30px; text-align: center; color: #6c757d; font-size: 14px;">
            <p>This is an automated message. Please do not reply directly to this email.</p>
            <p>Message ID: #<?= $contact_id ?? 'N/A' ?> | <?= date('Y-m-d H:i:s') ?></p>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #6c757d; font-size: 12px;">
        <p>© <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
        <p>
            <a href="https://aurai.co.ke" style="color: #6c757d; text-decoration: none; margin: 0 10px;">Website</a>
            <a href="https://aurai.co.ke/privacy" style="color: #6c757d; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
            <a href="https://aurai.co.ke/terms" style="color: #6c757d; text-decoration: none; margin: 0 10px;">Terms of Service</a>
        </p>
    </div>
</body>
</html>
