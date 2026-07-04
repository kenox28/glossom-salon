<?php
/**
 * Admin — Staff management (create, edit, delete).
 */

require_once __DIR__ . '/../middleware/admin_only.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();
$staffList = $db->query("SELECT * FROM users WHERE role = 'staff' ORDER BY created_at DESC")->fetchAll();

$pageTitle  = 'Manage Staff';
$activePage = 'staff';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up">
    <div class="card-header">
        <h3>Staff Members</h3>
        <button class="btn btn-primary btn-sm" onclick="openStaffModal()">+ Add Staff</button>
    </div>

    <div class="table-wrapper">
        <?php if (empty($staffList)): ?>
            <div class="empty-state"><div class="empty-state-icon">👥</div><p>No staff members yet.</p></div>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr><th>Name</th><th>Username</th><th>Email</th><th>Joined</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($staffList as $member): ?>
                <tr>
                    <td><strong><?= e($member['first_name'] . ' ' . $member['last_name']) ?></strong></td>
                    <td><?= e($member['username']) ?></td>
                    <td><?= e($member['email']) ?></td>
                    <td><?= formatDate($member['created_at']) ?></td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-secondary btn-sm"
                                onclick='editStaff(<?= json_encode($member) ?>)'>Edit</button>
                            <button class="btn btn-danger btn-sm"
                                onclick="deleteStaff(<?= $member['id'] ?>, '<?= e($member['first_name'] . ' ' . $member['last_name']) ?>')">Delete</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
function openStaffModal(data = null) {
    const isEdit = data !== null;
    openModal(isEdit ? 'Edit Staff Member' : 'Add Staff Member', `
        <form id="staffForm" onsubmit="submitStaff(event, ${isEdit ? data.id : 'null'})">
            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" required value="${isEdit ? data.first_name : ''}">
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" required value="${isEdit ? data.last_name : ''}">
                </div>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required value="${isEdit ? data.username : ''}">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="${isEdit ? data.email : ''}">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password ${isEdit ? '(leave blank to keep)' : ''}</label>
                    <input type="password" name="password" class="form-control" ${isEdit ? '' : 'required minlength="6"'}>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" ${isEdit ? '' : 'required'}>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">${isEdit ? 'Update' : 'Create'} Staff</button>
        </form>
    `);
}

function editStaff(data) { openStaffModal(data); }

function submitStaff(e, id) {
    e.preventDefault();
    const f = e.target;
    const data = {
        action: id ? 'update' : 'create',
        id: id,
        first_name: f.first_name.value,
        last_name: f.last_name.value,
        username: f.username.value,
        email: f.email.value,
        password: f.password.value,
        confirm_password: f.confirm_password.value,
    };

    fetch(getApiUrl('staff.php'), { method: 'POST', headers: csrfHeaders(), body: JSON.stringify(data) })
    .then(r => r.json())
    .then(res => {
        if (res.success) { closeModal(); showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        else showToast(res.message, 'error');
    });
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
