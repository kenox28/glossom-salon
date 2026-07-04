<?php
/**
 * Email service using PHPMailer.
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send an email via PHPMailer.
 */
function sendEmail(string $to, string $subject, string $htmlBody): bool
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_ENCRYPTION;
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody));

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mail error: ' . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Build the approved appointment email HTML.
 */
function buildApprovedEmail(array $appointment, array $service): string
{
    $name    = e($appointment['first_name'] . ' ' . $appointment['last_name']);
    $date    = e(formatDate($appointment['approved_date']));
    $time    = e(formatTime($appointment['approved_time']));
    $service = e($service['service_name']);
    $salon   = e(SALON_NAME);
    $phone   = e(SALON_PHONE);
    $address = e(SALON_ADDRESS);

    return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#FAFAFA;font-family:Inter,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#FAFAFA;padding:40px 20px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(242,51,194,.08);">
    <tr><td style="background:linear-gradient(135deg,#F233C2,#E91F96);padding:32px 40px;text-align:center;">
        <h1 style="color:#fff;margin:0;font-size:24px;">Appointment Confirmed</h1>
    </td></tr>
    <tr><td style="padding:40px;">
        <p style="color:#1F2937;font-size:16px;line-height:1.6;">Dear {$name},</p>
        <p style="color:#6B7280;font-size:15px;line-height:1.7;">Great news! Your appointment at <strong>{$salon}</strong> has been confirmed.</p>
        <table width="100%" style="background:rgba(242,51,194,.04);border-radius:8px;padding:20px;margin:24px 0;">
            <tr><td style="padding:8px 20px;color:#6B7280;font-size:14px;">Service</td><td style="padding:8px 20px;color:#1F2937;font-weight:600;">{$service}</td></tr>
            <tr><td style="padding:8px 20px;color:#6B7280;font-size:14px;">Date</td><td style="padding:8px 20px;color:#1F2937;font-weight:600;">{$date}</td></tr>
            <tr><td style="padding:8px 20px;color:#6B7280;font-size:14px;">Time</td><td style="padding:8px 20px;color:#1F2937;font-weight:600;">{$time}</td></tr>
        </table>
        <p style="color:#6B7280;font-size:14px;line-height:1.7;">If you need to make changes, please contact us:</p>
        <p style="color:#1F2937;font-size:14px;"><strong>Phone:</strong> {$phone}<br><strong>Address:</strong> {$address}</p>
        <p style="color:#6B7280;font-size:14px;margin-top:24px;">We look forward to seeing you!</p>
        <p style="color:#F233C2;font-weight:600;font-size:14px;">— The {$salon} Team</p>
    </td></tr>
</table>
</td></tr>
</table>
</body>
</html>
HTML;
}

/**
 * Build the rejected appointment email HTML.
 */
function buildRejectedEmail(array $appointment, ?string $reason): string
{
    $name    = e($appointment['first_name'] . ' ' . $appointment['last_name']);
    $salon   = e(SALON_NAME);
    $phone   = e(SALON_PHONE);
    $reasonHtml = $reason
        ? '<p style="color:#6B7280;font-size:14px;"><strong>Reason:</strong> ' . e($reason) . '</p>'
        : '';

    return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#FAFAFA;font-family:Inter,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#FAFAFA;padding:40px 20px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(242,51,194,.08);">
    <tr><td style="background:linear-gradient(135deg,#F233C2,#E91F96);padding:32px 40px;text-align:center;">
        <h1 style="color:#fff;margin:0;font-size:24px;">Appointment Update</h1>
    </td></tr>
    <tr><td style="padding:40px;">
        <p style="color:#1F2937;font-size:16px;line-height:1.6;">Dear {$name},</p>
        <p style="color:#6B7280;font-size:15px;line-height:1.7;">We regret to inform you that we are unable to accommodate your appointment request at this time.</p>
        {$reasonHtml}
        <p style="color:#6B7280;font-size:14px;line-height:1.7;margin-top:20px;">We invite you to book another appointment at your convenience. We'd love to serve you!</p>
        <p style="color:#1F2937;font-size:14px;"><strong>Phone:</strong> {$phone}</p>
        <p style="color:#F233C2;font-weight:600;font-size:14px;margin-top:24px;">— The {$salon} Team</p>
    </td></tr>
</table>
</td></tr>
</table>
</body>
</html>
HTML;
}
