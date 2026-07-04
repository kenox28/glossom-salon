<?php
/**
 * PHPMailer configuration.
 * Update these values for your SMTP provider.
 */

define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your-email@gmail.com');
define('MAIL_PASSWORD', 'your-app-password');
define('MAIL_FROM_EMAIL', 'your-email@gmail.com');
define('MAIL_FROM_NAME', 'Glossom Salon');
define('MAIL_ENCRYPTION', 'tls');

// Salon contact info used in email templates
define('SALON_NAME', 'Glossom Salon');
define('SALON_PHONE', '+1 (234) 567-890');
define('SALON_ADDRESS', '123 Style Avenue, Premium City, PC 12345');
define('SALON_EMAIL', 'info@glossomsalon.com');
