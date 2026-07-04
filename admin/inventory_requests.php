<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();
$requests = $db->query("SELECT ir.*, i.item_name, u.first_name, u.last_name FROM inventory_requests ir JOIN inventory i ON i.id = ir.item_id LEFT JOIN users u ON u.id = ir.staff_id ORDER BY ir.created_at DESC")->fetchAll();

$pageTitle = 'Inventory Requests';
$activePage = 'inventory_requests';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up">
    <div class="card-header">
        <h3>Inventory Requests</h3>
    </div>
    <div class="table-wrapper">
        <?php if (empty($requests)): ?>
            <div class="empty-state"><div class="empty-state-icon">🧾</div><p>No requests yet.</p></div>
        <?php else: ?>
            <table class="data-table">
                <thead><tr><th>Staff</th><th>Item</th><th>Quantity</th><th>Purpose</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?= e($req['first_name'] . ' ' . $req['last_name']) ?></td>
                        <td><?= e($req['item_name']) ?></td>
                        <td><?= (int) $req['requested_quantity'] ?></td>
                        <td><?= e($req['purpose']) ?></td>
                        <td><?= formatDate($req['created_at']) ?></td>
                        <td><?= '<span class="badge ' . ($req['status'] === 'approved' ? 'badge-approved' : ($req['status'] === 'rejected' ? 'badge-rejected' : 'badge-pending')) . '">' . e(ucfirst($req['status'])) . '</span>' ?></td>
                        <td>
                            <?php if ($req['status'] === 'pending'): ?>
                                <div class="btn-group">
                                    <button class="btn btn-success btn-sm" onclick='approveRequest(<?= json_encode($req) ?>)'>Approve</button>
                                    <button class="btn btn-danger btn-sm" onclick='rejectRequest(<?= json_encode($req) ?>)'>Reject</button>
                                </div>
                            <?php else: ?>
                                <span style="color:#9CA3AF;">Handled</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
function approveRequest(data) {
    openModal('Approve Inventory Request', `
        <form id="approveRequestForm" onsubmit="submitApproveRequest(event, ${data.id})">
            <div class="form-group">
                <label>Approve Quantity</label>
                <input type="number" name="approved_quantity" class="form-control" min="1" max="${data.requested_quantity}" required value="${data.requested_quantity}">
            </div>
            <button type="submit" class="btn btn-success" style="width:100%">Approve</button>
        </form>
    `);
}

function submitApproveRequest(e, id) {
    e.preventDefault();
    const form = e.target;
    fetch(getApiUrl('inventory.php'), {
        method: 'POST',
        headers: { ...csrfHeaders(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'approve', id: id, approved_quantity: form.approved_quantity.value })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { closeModal(); showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        else showToast(res.message, 'error');
    })
    .catch(() => showToast('Something went wrong.', 'error'));
}

function rejectRequest(data) {
    openModal('Reject Inventory Request', `
        <form id="rejectRequestForm" onsubmit="submitRejectRequest(event, ${data.id})">
            <div class="form-group">
                <label>Reason (optional)</label>
                <textarea name="rejection_reason" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-danger" style="width:100%">Reject</button>
        </form>
    `);
}

function submitRejectRequest(e, id) {
    e.preventDefault();
    const form = e.target;
    fetch(getApiUrl('inventory.php'), {
        method: 'POST',
        headers: { ...csrfHeaders(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'reject', id: id, rejection_reason: form.rejection_reason.value })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { closeModal(); showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        else showToast(res.message, 'error');
    })
    .catch(() => showToast('Something went wrong.', 'error'));
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
