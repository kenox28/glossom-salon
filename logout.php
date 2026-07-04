<?php
/**
 * Logout — destroys session and redirects to login.
 */

require_once __DIR__ . '/includes/functions.php';

initSession();

if (isLoggedIn()) {
    $db = getDB();
    logActivity($db, (int) $_SESSION['user_id'], "{$_SESSION['first_name']} logged out");
}

session_unset();
session_destroy();

redirect(url('login.php'));
