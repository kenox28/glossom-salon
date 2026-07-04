<?php
/**
 * Shared utility functions used across the application.
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

/**
 * Start a secure session with timeout handling.
 */
function initSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => SESSION_TIMEOUT,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();
    }

    // Session timeout check
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        session_start();
    }
    $_SESSION['last_activity'] = time();
}

/**
 * Escape output for HTML context (XSS prevention).
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect helper.
 */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/**
 * Check if user is logged in.
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['role']);
}

/**
 * Get current logged-in user data from session.
 */
function currentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id'         => $_SESSION['user_id'],
        'username'   => $_SESSION['username'],
        'email'      => $_SESSION['email'],
        'first_name' => $_SESSION['first_name'],
        'last_name'  => $_SESSION['last_name'],
        'role'       => $_SESSION['role'],
    ];
}

/**
 * Check if current user has a specific role.
 */
function hasRole(string $role): bool
{
    return isLoggedIn() && $_SESSION['role'] === $role;
}

/**
 * Log an activity to the database.
 */
function logActivity(PDO $db, ?int $userId, string $action): void
{
    $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$userId, $action]);
}

/**
 * Format a date for display.
 */
function formatDate(?string $date): string
{
    if (!$date) return '—';
    return date('M d, Y', strtotime($date));
}

/**
 * Format a time for display.
 */
function formatTime(?string $time): string
{
    if (!$time) return '—';
    return date('g:i A', strtotime($time));
}

/**
 * Format currency.
 */
function formatPrice(float $price): string
{
    return '$' . number_format($price, 2);
}

/**
 * Return a status badge HTML string.
 */
function statusBadge(string $status): string
{
    $classes = [
        'pending'  => 'badge-pending',
        'approved' => 'badge-approved',
        'rejected' => 'badge-rejected',
    ];
    $class = $classes[$status] ?? 'badge-pending';
    return '<span class="badge ' . $class . '">' . e(ucfirst($status)) . '</span>';
}

/**
 * JSON response helper for API endpoints.
 */
function jsonResponse(array $data, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Validate email format.
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Sanitize string input.
 */
function sanitize(string $input): string
{
    return trim(strip_tags($input));
}

/**
 * Get base URL path relative to project root.
 */
function basePath(): string
{
    $script = $_SERVER['SCRIPT_NAME'];
    $dir = dirname($script);
    // Normalize for admin/staff subdirectories
    $dir = preg_replace('#/(admin|staff|api)$#', '', $dir);
    return rtrim($dir, '/');
}

/**
 * Build a URL relative to project root.
 */
function url(string $path = ''): string
{
    return basePath() . '/' . ltrim($path, '/');
}

/**
 * Flash message — store in session.
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get and clear flash message.
 */
function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Paginate query results.
 */
function paginate(PDO $db, string $countSql, string $dataSql, array $params, int $page, int $perPage = 10): array
{
    $offset = ($page - 1) * $perPage;

    $countStmt = $db->prepare($countSql);
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();

    $dataStmt = $db->prepare($dataSql . " LIMIT {$perPage} OFFSET {$offset}");
    $dataStmt->execute($params);
    $rows = $dataStmt->fetchAll();

    return [
        'data'        => $rows,
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $perPage,
        'total_pages' => (int) ceil($total / $perPage),
    ];
}
