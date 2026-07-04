<?php
/**
 * Application-wide settings.
 */

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/';
$basePath = dirname($scriptName);
$basePath = preg_replace('#/(admin|staff|api)$#', '', $basePath);
$basePath = rtrim($basePath, '/');
$basePath = $basePath === '.' ? '' : $basePath;

define('APP_NAME', 'Glossom Salon');
define('APP_URL', rtrim($scheme . '://' . $host . $basePath, '/') . '/');
define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds
define('CSRF_TOKEN_NAME', '_csrf_token');

// Timezone
date_default_timezone_set('Asia/Manila');
