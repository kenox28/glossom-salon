<?php
require_once __DIR__ . '/../middleware/admin_only.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();
$status = sanitize($_GET['status'] ?? '');
$page   = max(1, (int) ($_GET['page'] ?? 1));

$where  = '';
$params = [];
if (in_array($status, ['pending', 'approved', 'rejected'])) {
    $where  = 'WHERE a.status = ?';
    $params = [$status];
}

$result = paginate(
    $db,
    "SELECT COUNT(*) FROM appointments a {$where}",
    "SELECT a.*, s.service_name, u.first_name as handler_first, u.last_name as handler_last
     FROM appointments a
     JOIN services s ON s.id = a.service_id
     LEFT JOIN users u ON u.id = a.handled_by
     {$where}
     ORDER BY a.created_at DESC",
    $params,
    $page
);

$pageTitle  = 'Appointments';
$activePage = 'appointments';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up">
    <div class="card-header">
        <h3>Appointment Requests</h3>
        <div class="btn-group">
            <a href="?status=" class="btn btn-sm <?= $status === '' ? 'btn-primary' : 'btn-secondary' ?>">All</a>
            <a href="?status=pending" class="btn btn-sm <?= $status === 'pending' ? 'btn-primary' : 'btn-secondary' ?>">Pending</a>
            <a href="?status=approved" class="btn btn-sm <?= $status === 'approved' ? 'btn-primary' : 'btn-secondary' ?>">Approved</a>
            <a href="?status=rejected" class="btn btn-sm <?= $status === 'rejected' ? 'btn-primary' : 'btn-secondary' ?>">Rejected</a>
        </div>
    </div>

    <div class="table-wrapper">
        <?php if (empty($result['data'])): ?>
            <div class="empty-state"><div class="empty-state-icon">📭</div><p>No appointments found.</p></div>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Preferred</th>
                    <th>Status</th>
                    <th>Handled By</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($result['data'] as $appt): ?>
                <tr>
                    <td>
                        <strong><?= e($appt['first_name'] . ' ' . $appt['last_name']) ?></strong><br>
                        <small style="color:#9CA3AF;"><?= e($appt['email']) ?></small><br>
                        <small style="color:#9CA3AF;"><?= e($appt['phone']) ?></small>
                    </td>
                    <td><?= e($appt['service_name']) ?></td>
                    <td><?= formatDate($appt['preferred_date']) ?><br><small><?= formatTime($appt['preferred_time']) ?></small></td>
                    <td><?= statusBadge($appt['status']) ?></td>
                    <td><?= $appt['handler_first'] ? e($appt['handler_first'] . ' ' . $appt['handler_last']) : '—' ?></td>
                    <td><?= formatDate($appt['created_at']) ?></td>
                    <td>
                        <?php if ($appt['status'] === 'pending'): ?>
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm" onclick="approveAppointment(<?= $appt['id'] ?>)">Approve</button>
                            <button class="btn btn-danger btn-sm" onclick="rejectAppointment(<?= $appt['id'] ?>)">Reject</button>
                        </div>
                        <?php elseif ($appt['status'] === 'approved'): ?>
                            <small><?= formatDate($appt['approved_date']) ?> <?= formatTime($appt['approved_time']) ?></small>
                        <?php elseif ($appt['rejection_reason']): ?>
                            <small title="<?= e($appt['rejection_reason']) ?>"><?= e(substr($appt['rejection_reason'], 0, 40)) ?>...</small>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <?php if ($result['total_pages'] > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
            <a href="?status=<?= e($status) ?>&page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
