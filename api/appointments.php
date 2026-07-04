<?php
/**
 * API — Appointment management (approve, reject, list).
 */

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/mailer.php';
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
$id     = (int) ($data['id'] ?? 0);
$user   = currentUser();
$db     = getDB();

if ($id <= 0) {
    jsonResponse(['success' => false, 'message' => 'Invalid appointment ID.'], 422);
}

// Fetch appointment with service info
$stmt = $db->prepare("
    SELECT a.*, s.service_name, s.price
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.id = ?
");
$stmt->execute([$id]);
$appointment = $stmt->fetch();

if (!$appointment) {
    jsonResponse(['success' => false, 'message' => 'Appointment not found.'], 404);
}

switch ($action) {

    case 'approve':
        if ($appointment['status'] !== 'pending') {
            jsonResponse(['success' => false, 'message' => 'Only pending appointments can be approved.'], 422);
        }

        $approvedDate = sanitize($data['approved_date'] ?? '');
        $approvedTime = sanitize($data['approved_time'] ?? '');
        $notes        = sanitize($data['notes'] ?? '');

        if ($approvedDate === '' || $approvedTime === '') {
            jsonResponse(['success' => false, 'message' => 'Date and time are required.'], 422);
        }

        $update = $db->prepare("
            UPDATE appointments
            SET status = 'approved', approved_date = ?, approved_time = ?,
                handled_by = ?, notes = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $update->execute([$approvedDate, $approvedTime, $user['id'], $notes ?: null, $id]);

        $appointment['approved_date'] = $approvedDate;
        $appointment['approved_time'] = $approvedTime;

        logActivity($db, $user['id'], "Approved appointment #{$id} for {$appointment['first_name']} {$appointment['last_name']}");

        // Send confirmation email
        $service = ['service_name' => $appointment['service_name']];
        $html = buildApprovedEmail($appointment, $service);
        sendEmail($appointment['email'], 'Appointment Confirmed — ' . SALON_NAME, $html);

        jsonResponse(['success' => true, 'message' => 'Appointment approved and confirmation email sent.']);
        break;

    case 'reject':
        if ($appointment['status'] !== 'pending') {
            jsonResponse(['success' => false, 'message' => 'Only pending appointments can be rejected.'], 422);
        }

        $reason = sanitize($data['rejection_reason'] ?? '');

        $update = $db->prepare("
            UPDATE appointments
            SET status = 'rejected', rejection_reason = ?, handled_by = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $update->execute([$reason ?: null, $user['id'], $id]);

        logActivity($db, $user['id'], "Rejected appointment #{$id} for {$appointment['first_name']} {$appointment['last_name']}");

        // Send rejection email
        $html = buildRejectedEmail($appointment, $reason ?: null);
        sendEmail($appointment['email'], 'Appointment Update — ' . SALON_NAME, $html);

        jsonResponse(['success' => true, 'message' => 'Appointment rejected and notification email sent.']);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Unknown action.'], 400);
}
