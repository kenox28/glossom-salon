<?php
/**
 * Authentication middleware — protects all dashboard pages.
 */

require_once __DIR__ . '/../includes/functions.php';

initSession();

if (!isLoggedIn()) {
    redirect(url('login.php'));
}

// Refresh session activity timestamp
$_SESSION['last_activity'] = time();
