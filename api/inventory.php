<?php
/**
 * API — Inventory management and staff inventory requests.
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

$data = [];
if (str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
} else {
    $data = $_POST;
    if (!empty($_FILES)) {
        $data['image'] = $_FILES['image'] ?? null;
    }
}
if (!is_array($data)) {
    $data = [];
}

$action = $data['action'] ?? '';
$user = currentUser();
$db = getDB();
$ipAddress = getClientIp();

function setInventoryStatus(PDO $db, int $itemId): void
{
    $item = $db->prepare("SELECT stock_quantity, minimum_stock_level FROM inventory WHERE id = ?");
    $item->execute([$itemId]);
    $row = $item->fetch();
    if (!$row) {
        return;
    }

    $status = inventoryStatusForQuantity((int) $row['stock_quantity'], (int) $row['minimum_stock_level']);
    $db->prepare("UPDATE inventory SET status = ? WHERE id = ?")->execute([$status, $itemId]);
}

switch ($action) {
    case 'create':
        if (!hasRole('admin')) {
            jsonResponse(['success' => false, 'message' => 'Only administrators can add inventory items.'], 403);
        }

        $name = sanitize($data['item_name'] ?? '');
        $categoryId = isset($data['category_id']) && $data['category_id'] !== '' ? (int) $data['category_id'] : null;
        $categoryName = sanitize($data['new_category'] ?? '');
        $description = sanitize($data['description'] ?? '');
        $stock = max(0, (int) ($data['stock_quantity'] ?? 0));
        $unit = sanitize($data['unit'] ?? 'Piece');
        $minimumStock = max(0, (int) ($data['minimum_stock_level'] ?? 0));
        $status = inventoryStatusForQuantity($stock, $minimumStock);

        if ($name === '' || $unit === '') {
            jsonResponse(['success' => false, 'message' => 'Item name and unit are required.'], 422);
        }

        if ($categoryId === null && $categoryName !== '') {
            $categoryStmt = $db->prepare("SELECT id FROM inventory_categories WHERE name = ?");
            $categoryStmt->execute([$categoryName]);
            if (!$categoryStmt->fetch()) {
                $createCategory = $db->prepare("INSERT INTO inventory_categories (name) VALUES (?)");
                $createCategory->execute([$categoryName]);
                $categoryId = (int) $db->lastInsertId();
            } else {
                $categoryId = (int) $categoryStmt->fetchColumn();
            }
        }

        if ($categoryId === null) {
            jsonResponse(['success' => false, 'message' => 'Please select or create a category.'], 422);
        }

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $safeName = 'inv-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
            $targetDir = __DIR__ . '/../uploads/inventory';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $targetPath = $targetDir . '/' . $safeName;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                jsonResponse(['success' => false, 'message' => 'Image upload failed.'], 500);
            }
            $imagePath = 'uploads/inventory/' . $safeName;
        }

        $stmt = $db->prepare("INSERT INTO inventory (item_name, category_id, description, image_path, stock_quantity, unit, minimum_stock_level, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $categoryId, $description, $imagePath, $stock, $unit, $minimumStock, $status]);

        logActivity($db, $user['id'], "Added inventory item: {$name}");
        logInventoryActivity($db, $user['id'], $user['role'], 'Added Inventory', "Added inventory item {$name}", $ipAddress);
        jsonResponse(['success' => true, 'message' => 'Inventory item added successfully.']);
        break;

    case 'update':
        if (!hasRole('admin')) {
            jsonResponse(['success' => false, 'message' => 'Only administrators can edit inventory items.'], 403);
        }

        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) {
            jsonResponse(['success' => false, 'message' => 'Invalid inventory item.'], 422);
        }

        $name = sanitize($data['item_name'] ?? '');
        $categoryId = isset($data['category_id']) && $data['category_id'] !== '' ? (int) $data['category_id'] : null;
        $categoryName = sanitize($data['new_category'] ?? '');
        $description = sanitize($data['description'] ?? '');
        $minimumStock = max(0, (int) ($data['minimum_stock_level'] ?? 0));
        $unit = sanitize($data['unit'] ?? 'Piece');

        if ($name === '' || $unit === '') {
            jsonResponse(['success' => false, 'message' => 'Item name and unit are required.'], 422);
        }

        if ($categoryId === null && $categoryName !== '') {
            $categoryStmt = $db->prepare("SELECT id FROM inventory_categories WHERE name = ?");
            $categoryStmt->execute([$categoryName]);
            if (!$categoryStmt->fetch()) {
                $createCategory = $db->prepare("INSERT INTO inventory_categories (name) VALUES (?)");
                $createCategory->execute([$categoryName]);
                $categoryId = (int) $db->lastInsertId();
            } else {
                $categoryId = (int) $categoryStmt->fetchColumn();
            }
        }

        if ($categoryId === null) {
            jsonResponse(['success' => false, 'message' => 'Please select or create a category.'], 422);
        }

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $safeName = 'inv-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
            $targetDir = __DIR__ . '/../uploads/inventory';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $targetPath = $targetDir . '/' . $safeName;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                jsonResponse(['success' => false, 'message' => 'Image upload failed.'], 500);
            }
            $imagePath = 'uploads/inventory/' . $safeName;
        }

        $existing = $db->prepare("SELECT item_name, stock_quantity, minimum_stock_level FROM inventory WHERE id = ?");
        $existing->execute([$id]);
        $current = $existing->fetch();
        if (!$current) {
            jsonResponse(['success' => false, 'message' => 'Inventory item not found.'], 404);
        }

        if ($imagePath !== null) {
            $stmt = $db->prepare("UPDATE inventory SET item_name=?, category_id=?, description=?, image_path=?, unit=?, minimum_stock_level=? WHERE id=?");
            $stmt->execute([$name, $categoryId, $description, $imagePath, $unit, $minimumStock, $id]);
        } else {
            $stmt = $db->prepare("UPDATE inventory SET item_name=?, category_id=?, description=?, unit=?, minimum_stock_level=? WHERE id=?");
            $stmt->execute([$name, $categoryId, $description, $unit, $minimumStock, $id]);
        }

        setInventoryStatus($db, $id);

        logActivity($db, $user['id'], "Updated inventory item: {$name}");
        logInventoryActivity($db, $user['id'], $user['role'], 'Updated Inventory', "Updated inventory item {$name}", $ipAddress);
        jsonResponse(['success' => true, 'message' => 'Inventory item updated successfully.']);
        break;

    case 'delete':
        if (!hasRole('admin')) {
            jsonResponse(['success' => false, 'message' => 'Only administrators can delete inventory items.'], 403);
        }

        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0) {
            jsonResponse(['success' => false, 'message' => 'Invalid inventory item.'], 422);
        }

        $item = $db->prepare("SELECT item_name FROM inventory WHERE id = ?");
        $item->execute([$id]);
        $row = $item->fetch();
        if (!$row) {
            jsonResponse(['success' => false, 'message' => 'Inventory item not found.'], 404);
        }

        $db->prepare("UPDATE inventory SET deleted_at = NOW() WHERE id = ?")->execute([$id]);
        logActivity($db, $user['id'], "Deleted inventory item: {$row['item_name']}");
        logInventoryActivity($db, $user['id'], $user['role'], 'Deleted Inventory', "Deleted inventory item {$row['item_name']}", $ipAddress);
        jsonResponse(['success' => true, 'message' => 'Inventory item deleted successfully.']);
        break;

    case 'update_quantity':
        if (!hasRole('admin')) {
            jsonResponse(['success' => false, 'message' => 'Only administrators can update stock.'], 403);
        }

        $id = (int) ($data['id'] ?? 0);
        $newQuantity = max(0, (int) ($data['stock_quantity'] ?? 0));
        if ($id <= 0) {
            jsonResponse(['success' => false, 'message' => 'Invalid inventory item.'], 422);
        }

        $item = $db->prepare("SELECT item_name, minimum_stock_level FROM inventory WHERE id = ?");
        $item->execute([$id]);
        $row = $item->fetch();
        if (!$row) {
            jsonResponse(['success' => false, 'message' => 'Inventory item not found.'], 404);
        }

        $db->prepare("UPDATE inventory SET stock_quantity = ?, status = ? WHERE id = ?")->execute([$newQuantity, inventoryStatusForQuantity($newQuantity, (int) $row['minimum_stock_level']), $id]);
        logActivity($db, $user['id'], "Updated stock for {$row['item_name']}: {$newQuantity}");
        logInventoryActivity($db, $user['id'], $user['role'], 'Updated Stock', "Updated stock for {$row['item_name']} to {$newQuantity}", $ipAddress);
        jsonResponse(['success' => true, 'message' => 'Inventory stock updated successfully.']);
        break;

    case 'create_category':
        if (!hasRole('admin')) {
            jsonResponse(['success' => false, 'message' => 'Only administrators can create categories.'], 403);
        }

        $name = sanitize($data['name'] ?? '');
        if ($name === '') {
            jsonResponse(['success' => false, 'message' => 'Category name is required.'], 422);
        }

        $check = $db->prepare("SELECT id FROM inventory_categories WHERE name = ?");
        $check->execute([$name]);
        if ($check->fetch()) {
            jsonResponse(['success' => false, 'message' => 'Category already exists.'], 422);
        }

        $db->prepare("INSERT INTO inventory_categories (name) VALUES (?)")->execute([$name]);
        logInventoryActivity($db, $user['id'], $user['role'], 'Created Category', "Created category {$name}", $ipAddress);
        jsonResponse(['success' => true, 'message' => 'Category created successfully.']);
        break;

    case 'request':
        if (!hasRole('staff') && !hasRole('admin')) {
            jsonResponse(['success' => false, 'message' => 'Only staff can request inventory.'], 403);
        }

        $itemId = (int) ($data['item_id'] ?? 0);
        $quantity = max(1, (int) ($data['requested_quantity'] ?? 1));
        $purpose = sanitize($data['purpose'] ?? '');
        $notes = sanitize($data['notes'] ?? '');

        if ($itemId <= 0 || $purpose === '') {
            jsonResponse(['success' => false, 'message' => 'Item and purpose are required.'], 422);
        }

        $item = $db->prepare("SELECT item_name FROM inventory WHERE id = ? AND deleted_at IS NULL");
        $item->execute([$itemId]);
        $row = $item->fetch();
        if (!$row) {
            jsonResponse(['success' => false, 'message' => 'Inventory item not found.'], 404);
        }

        $stmt = $db->prepare("INSERT INTO inventory_requests (staff_id, item_id, requested_quantity, purpose, notes, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$user['id'], $itemId, $quantity, $purpose, $notes]);

        logActivity($db, $user['id'], "Requested inventory: {$row['item_name']} x{$quantity}");
        logInventoryActivity($db, $user['id'], $user['role'], 'Requested Inventory', "Requested {$row['item_name']} x{$quantity}", $ipAddress);
        jsonResponse(['success' => true, 'message' => 'Inventory request submitted successfully.']);
        break;

    case 'approve':
        if (!hasRole('admin')) {
            jsonResponse(['success' => false, 'message' => 'Only administrators can approve requests.'], 403);
        }

        $requestId = (int) ($data['id'] ?? 0);
        $approvedQuantity = max(0, (int) ($data['approved_quantity'] ?? 0));
        if ($requestId <= 0) {
            jsonResponse(['success' => false, 'message' => 'Invalid request.'], 422);
        }

        $request = $db->prepare("SELECT ir.*, i.item_name, i.stock_quantity, i.minimum_stock_level FROM inventory_requests ir JOIN inventory i ON i.id = ir.item_id WHERE ir.id = ?");
        $request->execute([$requestId]);
        $row = $request->fetch();
        if (!$row) {
            jsonResponse(['success' => false, 'message' => 'Request not found.'], 404);
        }
        if ($row['status'] !== 'pending') {
            jsonResponse(['success' => false, 'message' => 'Only pending requests can be approved.'], 422);
        }

        $approvedQuantity = $approvedQuantity > 0 ? $approvedQuantity : (int) $row['requested_quantity'];
        $approvedQuantity = min($approvedQuantity, (int) $row['requested_quantity']);
        $newStock = max(0, (int) $row['stock_quantity'] - $approvedQuantity);
        $newStatus = inventoryStatusForQuantity($newStock, (int) $row['minimum_stock_level']);

        $db->prepare("UPDATE inventory_requests SET status = 'approved', approved_quantity = ?, approved_by = ?, approved_at = NOW() WHERE id = ?")->execute([$approvedQuantity, $user['id'], $requestId]);
        $db->prepare("UPDATE inventory SET stock_quantity = ?, status = ? WHERE id = ?")->execute([$newStock, $newStatus, $row['item_id']]);

        logActivity($db, $user['id'], "Approved inventory request for {$row['item_name']}");
        logInventoryActivity($db, $user['id'], $user['role'], 'Approved Request', "Approved {$row['item_name']} request for {$approvedQuantity}", $ipAddress);
        logInventoryActivity($db, $user['id'], $user['role'], 'Inventory Quantity Decreased', "Decreased {$row['item_name']} stock by {$approvedQuantity}", $ipAddress);
        jsonResponse(['success' => true, 'message' => 'Request approved and inventory updated.']);
        break;

    case 'reject':
        if (!hasRole('admin')) {
            jsonResponse(['success' => false, 'message' => 'Only administrators can reject requests.'], 403);
        }

        $requestId = (int) ($data['id'] ?? 0);
        $reason = sanitize($data['rejection_reason'] ?? '');
        if ($requestId <= 0) {
            jsonResponse(['success' => false, 'message' => 'Invalid request.'], 422);
        }

        $request = $db->prepare("SELECT ir.*, i.item_name FROM inventory_requests ir JOIN inventory i ON i.id = ir.item_id WHERE ir.id = ?");
        $request->execute([$requestId]);
        $row = $request->fetch();
        if (!$row) {
            jsonResponse(['success' => false, 'message' => 'Request not found.'], 404);
        }
        if ($row['status'] !== 'pending') {
            jsonResponse(['success' => false, 'message' => 'Only pending requests can be rejected.'], 422);
        }

        $db->prepare("UPDATE inventory_requests SET status = 'rejected', rejection_reason = ? WHERE id = ?")->execute([$reason, $requestId]);
        logActivity($db, $user['id'], "Rejected inventory request for {$row['item_name']}");
        logInventoryActivity($db, $user['id'], $user['role'], 'Rejected Request', "Rejected {$row['item_name']} request. {$reason}", $ipAddress);
        jsonResponse(['success' => true, 'message' => 'Request rejected.']);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Unknown action.'], 400);
}
