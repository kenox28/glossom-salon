<?php
/**
 * Admin — Profile settings.
 */

require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

$user = currentUser();
$db   = getDB();

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$profile = $stmt->fetch();

$pageTitle  = 'Profile';
$activePage = 'profile';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up" style="max-width:600px;">
    <div class="card-header"><h3>My Profile</h3></div>
    <form id="profileForm">
        <div class="form-row">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" required value="<?= e($profile['first_name']) ?>">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" required value="<?= e($profile['last_name']) ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" value="<?= e($profile['username']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?= e($profile['email']) ?>">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>New Password (optional)</label>
                <input type="password" name="password" class="form-control" minlength="6">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const f = e.target;
    fetch(getApiUrl('profile.php'), {
        method: 'POST',
        headers: csrfHeaders(),
        body: JSON.stringify({
            first_name: f.first_name.value,
            last_name: f.last_name.value,
            email: f.email.value,
            password: f.password.value,
            confirm_password: f.confirm_password.value,
        }),
    })
    .then(r => r.json())
    .then(res => showToast(res.message, res.success ? 'success' : 'error'));
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
