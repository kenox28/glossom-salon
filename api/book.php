<?php
/**
 * Public API — book appointment from landing page.
 */

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed.'], 405);
}

$data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$firstName = sanitize($data['first_name'] ?? '');
$lastName  = sanitize($data['last_name'] ?? '');
$email     = sanitize($data['email'] ?? '');
$phone     = sanitize($data['phone'] ?? '');
$serviceId = (int) ($data['service_id'] ?? 0);
$prefDate  = sanitize($data['preferred_date'] ?? '');
$prefTime  = sanitize($data['preferred_time'] ?? '');
$notes     = sanitize($data['notes'] ?? '');

// Validation
$errors = [];
if ($firstName === '') $errors[] = 'First name is required.';
if ($lastName === '')  $errors[] = 'Last name is required.';
if (!isValidEmail($email)) $errors[] = 'Valid email is required.';
if ($phone === '')     $errors[] = 'Phone number is required.';
if ($serviceId <= 0)   $errors[] = 'Please select a service.';
if ($prefDate === '')  $errors[] = 'Preferred date is required.';
if ($prefTime === '')  $errors[] = 'Preferred time is required.';

if (!empty($errors)) {
    jsonResponse(['success' => false, 'message' => implode(' ', $errors)], 422);
}

try {
    $db = getDB();

    // Verify service exists and is active
    $svc = $db->prepare("SELECT id FROM services WHERE id = ? AND is_active = 1");
    $svc->execute([$serviceId]);
    if (!$svc->fetch()) {
        jsonResponse(['success' => false, 'message' => 'Selected service is not available.'], 422);
    }

    $stmt = $db->prepare("
        INSERT INTO appointments (first_name, last_name, email, phone, service_id, preferred_date, preferred_time, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$firstName, $lastName, $email, $phone, $serviceId, $prefDate, $prefTime, $notes ?: null]);

    logActivity($db, null, "New appointment request from {$firstName} {$lastName} ({$email})");

    jsonResponse([
        'success' => true,
        'message' => 'Appointment request received! We will contact you to confirm.',
    ]);
} catch (PDOException $e) {
    error_log('Booking error: ' . $e->getMessage());
    jsonResponse(['success' => false, 'message' => 'Unable to process your request. Please try again.'], 500);
}
