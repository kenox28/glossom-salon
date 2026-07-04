<?php
/**
 * Dashboard top navbar.
 */
$user = currentUser();
$flash = getFlash();
?>
<header class="topbar">
    <div class="topbar-left">
        <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Open menu">☰</button>
        <h1 class="topbar-title"><?= e($pageTitle ?? 'Dashboard') ?></h1>
    </div>
    <div class="topbar-right">
        <span class="topbar-greeting">Hello, <?= e($user['first_name']) ?></span>
        <div class="topbar-avatar"><?= e(strtoupper(substr($user['first_name'], 0, 1))) ?></div>
    </div>
</header>

<?php if ($flash): ?>
<div class="toast toast-<?= e($flash['type']) ?>" id="flashToast">
    <span><?= e($flash['message']) ?></span>
    <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
</div>
<?php endif; ?>

<main class="main-content">
