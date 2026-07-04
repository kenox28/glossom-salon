<?php
/**
 * API — Staff management (admin only).
 */

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../middleware/admin_only.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed.'], 405);
}

if (!validateCsrf()) {
    jsonResponse(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
}

$data   = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';
$user   = currentUser();
$db     = getDB();

switch ($action) {

    case 'create':
        $firstName = sanitize($data['first_name'] ?? '');
        $lastName  = sanitize($data['last_name'] ?? '');
        $username  = sanitize($data['username'] ?? '');
        $email     = sanitize($data['email'] ?? '');
        $password  = $data['password'] ?? '';
        $confirm   = $data['confirm_password'] ?? '';

        $errors = [];
        if ($firstName === '') $errors[] = 'First name is required.';
        if ($lastName === '')  $errors[] = 'Last name is required.';
        if ($username === '')  $errors[] = 'Username is required.';
        if (!isValidEmail($email)) $errors[] = 'Valid email is required.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';

        if (!empty($errors)) {
            jsonResponse(['success' => false, 'message' => implode(' ', $errors)], 422);
        }

        // Check uniqueness
        $check = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        if ($check->fetch()) {
            jsonResponse(['success' => false, 'message' => 'Username or email already exists.'], 422);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, 'staff')");
        $stmt->execute([$username, $hash, $email, $firstName, $lastName]);

        logActivity($db, $user['id'], "Created staff account: {$username}");
        jsonResponse(['success' => true, 'message' => 'Staff member created successfully.']);
        break;

    case 'update':
        $id        = (int) ($data['id'] ?? 0);
        $firstName = sanitize($data['first_name'] ?? '');
        $lastName  = sanitize($data['last_name'] ?? '');
        $username  = sanitize($data['username'] ?? '');
        $email     = sanitize($data['email'] ?? '');
        $password  = $data['password'] ?? '';

        if ($id <= 0 || $firstName === '' || $lastName === '' || $username === '' || !isValidEmail($email)) {
            jsonResponse(['success' => false, 'message' => 'Invalid data provided.'], 422);
        }

        // Prevent editing admin role users' role
        $existing = $db->prepare("SELECT role FROM users WHERE id = ?");
        $existing->execute([$id]);
        $existingUser = $existing->fetch();
        if (!$existingUser) jsonResponse(['success' => false, 'message' => 'User not found.'], 404);

        // Check uniqueness (exclude self)
        $check = $db->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $check->execute([$username, $email, $id]);
        if ($check->fetch()) {
            jsonResponse(['success' => false, 'message' => 'Username or email already taken.'], 422);
        }

        if ($password !== '') {
            if (strlen($password) < 6) jsonResponse(['success' => false, 'message' => 'Password must be at least 6 characters.'], 422);
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET first_name=?, last_name=?, username=?, email=?, password=? WHERE id=?");
            $stmt->execute([$firstName, $lastName, $username, $email, $hash, $id]);
        } else {
            $stmt = $db->prepare("UPDATE users SET first_name=?, last_name=?, username=?, email=? WHERE id=?");
            $stmt->execute([$firstName, $lastName, $username, $email, $id]);
        }

        logActivity($db, $user['id'], "Updated staff account: {$username}");
        jsonResponse(['success' => true, 'message' => 'Staff member updated successfully.']);
        break;

    case 'delete':
        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) jsonResponse(['success' => false, 'message' => 'Invalid user ID.'], 422);

        // Prevent self-delete and admin delete
        if ($id === $user['id']) jsonResponse(['success' => false, 'message' => 'You cannot delete your own account.'], 422);

        $target = $db->prepare("SELECT username, role FROM users WHERE id = ?");
        $target->execute([$id]);
        $targetUser = $target->fetch();
        if (!$targetUser) jsonResponse(['success' => false, 'message' => 'User not found.'], 404);
        if ($targetUser['role'] === 'admin') jsonResponse(['success' => false, 'message' => 'Cannot delete admin accounts.'], 403);

        $db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
        logActivity($db, $user['id'], "Deleted staff account: {$targetUser['username']}");
        jsonResponse(['success' => true, 'message' => 'Staff member deleted successfully.']);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Unknown action.'], 400);
}
