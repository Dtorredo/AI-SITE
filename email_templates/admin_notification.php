<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>New Contact Form Submission - <?= SITE_NAME ?></title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 8px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #0d6efd; margin: 0 0 10px 0;">New Contact Form Submission</h1>
            <p style="color: #6c757d; margin: 0;">You've received a new message from your website contact form.</p>
        </div>
        
        <div style="background-color: #ffffff; border-radius: 6px; padding: 20px; margin-bottom: 20px; border: 1px solid #e9ecef;">
            <h2 style="color: #0d6efd; margin-top: 0; border-bottom: 1px solid #e9ecef; padding-bottom: 10px; font-size: 20px;">Contact Details</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef; width: 120px; font-weight: bold;">Name:</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef;"><?= htmlspecialchars($full_name) ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef; font-weight: bold;">Email:</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                        <a href="mailto:<?= htmlspecialchars($email) ?>" style="color: #0d6efd; text-decoration: none;">
                            <?= htmlspecialchars($email) ?>
                        </a>
                    </td>
                </tr>
                <?php if (!empty($company)): ?>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef; font-weight: bold;">Company:</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef;"><?= htmlspecialchars($company) ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef; font-weight: bold;">Service:</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e9ecef; text-transform: capitalize;">
                        <?= str_replace('-', ' ', htmlspecialchars($service)) ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; vertical-align: top;">Message:</td>
                    <td style="padding: 8px 0; white-space: pre-line;"><?= nl2br(htmlspecialchars($message)) ?></td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 30px; text-align: center; color: #6c757d; font-size: 14px;">
            <p>This message was sent from the contact form on <?= SITE_NAME ?> (<?= date('Y-m-d H:i:s') ?>)</p>
            <p>Contact ID: #<?= $contact_id ?></p>
            <p>IP Address: <?= htmlspecialchars($ip_address) ?></p>
        </div>
        
        <div style="margin-top: 30px; text-align: center; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <a href="mailto:<?= htmlspecialchars($email) ?>" style="display: inline-block; background-color: #0d6efd; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold;">
                Reply to <?= explode(' ', trim($full_name))[0] ?>
            </a>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #6c757d; font-size: 12px;">
        <p>© <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
    </div>
</body>
</html>
