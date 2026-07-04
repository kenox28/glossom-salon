<?php
/**
 * Dashboard sidebar navigation.
 * Expects $activePage to be set (e.g. 'dashboard', 'appointments').
 */
$user = currentUser();
$role = $user['role'];
$prefix = $role === 'admin' ? 'admin' : 'staff';

$navItems = [
    ['id' => 'dashboard',            'label' => 'Dashboard',            'icon' => '📊', 'href' => url("{$prefix}/dashboard.php"),            'roles' => ['admin', 'staff']],
    ['id' => 'appointments',         'label' => 'Appointments',         'icon' => '📅', 'href' => url("{$prefix}/appointments.php"),         'roles' => ['admin', 'staff']],
    ['id' => 'services',             'label' => 'Services',             'icon' => '✂️', 'href' => url("{$prefix}/services.php"),             'roles' => ['admin', 'staff']],
    ['id' => 'inventory',            'label' => $role === 'admin' ? 'Inventory' : 'Inventory', 'icon' => '📦', 'href' => url("{$prefix}/inventory.php"), 'roles' => ['admin', 'staff']],
    ['id' => 'inventory_requests',   'label' => $role === 'admin' ? 'Inventory Requests' : 'Request Inventory', 'icon' => $role === 'admin' ? '🧾' : '🛒', 'href' => url("{$prefix}/" . ($role === 'admin' ? 'inventory_requests.php' : 'inventory_requests.php')), 'roles' => ['admin', 'staff']],
    ['id' => 'activity_logs',        'label' => 'Activity Logs',        'icon' => '📝', 'href' => url("{$prefix}/activity_logs.php"),        'roles' => ['admin', 'staff']],
    ['id' => 'staff',                'label' => 'Manage Staff',         'icon' => '👥', 'href' => url('admin/staff.php'),                  'roles' => ['admin']],
    ['id' => 'reports',              'label' => 'Reports',              'icon' => '📈', 'href' => url('admin/reports.php'),                'roles' => ['admin']],
    ['id' => 'settings',             'label' => 'Settings',             'icon' => '⚙️', 'href' => url('admin/settings.php'),               'roles' => ['admin']],
    ['id' => 'profile',              'label' => 'Profile',              'icon' => '👤', 'href' => url("{$prefix}/profile.php"),            'roles' => ['admin', 'staff']],
];
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= url('index.php') ?>" class="sidebar-logo">
            <img src="<?= url('assets/images/glossom-logo.png') ?>" alt="Logo" width="36" height="36">
            <span>Glosh Beauty salon</span>
        </a>
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">☰</button>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <?php foreach ($navItems as $item): ?>
                <?php if (in_array($role, $item['roles'])): ?>
                <li>
                    <a href="<?= $item['href'] ?>"
                       class="sidebar-link <?= ($activePage ?? '') === $item['id'] ? 'active' : '' ?>">
                        <span class="sidebar-icon"><?= $item['icon'] ?></span>
                        <span class="sidebar-label"><?= e($item['label']) ?></span>
                    </a>
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar"><?= e(strtoupper(substr($user['first_name'], 0, 1))) ?></div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></span>
                <span class="sidebar-user-role"><?= e(ucfirst($user['role'])) ?></span>
            </div>
        </div>
        <a href="<?= url('logout.php') ?>" class="sidebar-logout">Logout</a>
    </div>
</aside>
