<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();
$user = currentUser();
$requests = $db->prepare("SELECT ir.*, i.item_name FROM inventory_requests ir JOIN inventory i ON i.id = ir.item_id WHERE ir.staff_id = ? ORDER BY ir.created_at DESC");
$requests->execute([$user['id']]);
$requests = $requests->fetchAll();

$pageTitle = 'Inventory Requests';
$activePage = 'inventory_requests';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up">
    <div class="card-header">
        <h3>My Request History</h3>
    </div>
    <div class="table-wrapper">
        <?php if (empty($requests)): ?>
            <div class="empty-state"><div class="empty-state-icon">🧾</div><p>No requests yet.</p></div>
        <?php else: ?>
            <table class="data-table">
                <thead><tr><th>Date</th><th>Item</th><th>Quantity</th><th>Status</th><th>Approved Quantity</th><th>Remarks</th></tr></thead>
                <tbody>
                <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?= formatDate($req['created_at']) ?></td>
                        <td><?= e($req['item_name']) ?></td>
                        <td><?= (int) $req['requested_quantity'] ?></td>
                        <td><?= '<span class="badge ' . ($req['status'] === 'approved' ? 'badge-approved' : ($req['status'] === 'rejected' ? 'badge-rejected' : 'badge-pending')) . '">' . e(ucfirst($req['status'])) . '</span>' ?></td>
                        <td><?= $req['approved_quantity'] !== null ? (int) $req['approved_quantity'] : '—' ?></td>
                        <td><?= e($req['rejection_reason'] ?: $req['notes'] ?: '—') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
