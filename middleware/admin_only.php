<?php
/**
 * Admin-only middleware — restricts access to administrator role.
 */

require_once __DIR__ . '/auth.php';

if (!hasRole('admin')) {
    setFlash('error', 'Access denied. Administrator privileges required.');
    redirect(url('index.php'));
}
