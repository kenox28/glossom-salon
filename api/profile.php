<?php
/**
 * API — Profile update for logged-in users.
 */

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../middleware/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed.'], 405);
}

if (!validateCsrf()) {
    jsonResponse(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
}

$data = json_decode(file_get_contents('php://input'), true);
$user = currentUser();
$db   = getDB();

$firstName = sanitize($data['first_name'] ?? '');
$lastName  = sanitize($data['last_name'] ?? '');
$email     = sanitize($data['email'] ?? '');
$password  = $data['password'] ?? '';
$confirm   = $data['confirm_password'] ?? '';

if ($firstName === '' || $lastName === '' || !isValidEmail($email)) {
    jsonResponse(['success' => false, 'message' => 'Please provide valid name and email.'], 422);
}

// Check email uniqueness
$check = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$check->execute([$email, $user['id']]);
if ($check->fetch()) {
    jsonResponse(['success' => false, 'message' => 'Email already in use.'], 422);
}

if ($password !== '') {
    if (strlen($password) < 6) jsonResponse(['success' => false, 'message' => 'Password must be at least 6 characters.'], 422);
    if ($password !== $confirm) jsonResponse(['success' => false, 'message' => 'Passwords do not match.'], 422);

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE users SET first_name=?, last_name=?, email=?, password=? WHERE id=?");
    $stmt->execute([$firstName, $lastName, $email, $hash, $user['id']]);
} else {
    $stmt = $db->prepare("UPDATE users SET first_name=?, last_name=?, email=? WHERE id=?");
    $stmt->execute([$firstName, $lastName, $email, $user['id']]);
}

// Update session
$_SESSION['first_name'] = $firstName;
$_SESSION['last_name']  = $lastName;
$_SESSION['email']      = $email;

logActivity($db, $user['id'], "Updated profile");
jsonResponse(['success' => true, 'message' => 'Profile updated successfully.']);
