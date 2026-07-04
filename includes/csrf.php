<?php
/**
 * CSRF token generation and validation.
 */

require_once __DIR__ . '/functions.php';

/**
 * Generate or retrieve the CSRF token for the current session.
 */
function csrfToken(): string
{
    initSession();
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Output a hidden CSRF input field for forms.
 */
function csrfField(): string
{
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . e(csrfToken()) . '">';
}

/**
 * Validate the CSRF token from a POST request.
 */
function validateCsrf(): bool
{
    initSession();
    $token = $_POST[CSRF_TOKEN_NAME] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token);
}

/**
 * Require valid CSRF or abort with 403.
 */
function requireCsrf(): void
{
    if (!validateCsrf()) {
        http_response_code(403);
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
            jsonResponse(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        die('Invalid request. Please go back and try again.');
    }
}
