<?php
/**
 * API — Services CRUD.
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

$data   = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';
$user   = currentUser();
$db     = getDB();

switch ($action) {

    case 'create':
        $name     = sanitize($data['service_name'] ?? '');
        $desc     = sanitize($data['description'] ?? '');
        $price    = (float) ($data['price'] ?? 0);
        $duration = (int) ($data['duration'] ?? 30);
        $active   = (int) ($data['is_active'] ?? 1);

        if ($name === '' || $price <= 0) {
            jsonResponse(['success' => false, 'message' => 'Service name and valid price are required.'], 422);
        }

        $stmt = $db->prepare("INSERT INTO services (service_name, description, price, duration, is_active) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $desc, $price, $duration, $active]);

        logActivity($db, $user['id'], "Created service: {$name}");
        jsonResponse(['success' => true, 'message' => 'Service created successfully.']);
        break;

    case 'update':
        $id       = (int) ($data['id'] ?? 0);
        $name     = sanitize($data['service_name'] ?? '');
        $desc     = sanitize($data['description'] ?? '');
        $price    = (float) ($data['price'] ?? 0);
        $duration = (int) ($data['duration'] ?? 30);
        $active   = (int) ($data['is_active'] ?? 1);

        if ($id <= 0 || $name === '' || $price <= 0) {
            jsonResponse(['success' => false, 'message' => 'Invalid data provided.'], 422);
        }

        $stmt = $db->prepare("UPDATE services SET service_name=?, description=?, price=?, duration=?, is_active=? WHERE id=?");
        $stmt->execute([$name, $desc, $price, $duration, $active, $id]);

        logActivity($db, $user['id'], "Updated service: {$name}");
        jsonResponse(['success' => true, 'message' => 'Service updated successfully.']);
        break;

    case 'delete':
        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) jsonResponse(['success' => false, 'message' => 'Invalid service ID.'], 422);

        // Check if service has appointments
        $check = $db->prepare("SELECT COUNT(*) FROM appointments WHERE service_id = ?");
        $check->execute([$id]);
        if ((int) $check->fetchColumn() > 0) {
            jsonResponse(['success' => false, 'message' => 'Cannot delete — service has existing appointments. Deactivate instead.'], 422);
        }

        $name = $db->prepare("SELECT service_name FROM services WHERE id = ?");
        $name->execute([$id]);
        $svcName = $name->fetchColumn();

        $db->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);
        logActivity($db, $user['id'], "Deleted service: {$svcName}");
        jsonResponse(['success' => true, 'message' => 'Service deleted successfully.']);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Unknown action.'], 400);
}
