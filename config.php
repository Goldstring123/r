<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'real_estate_portal_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Agents must enter this code when registering (stops random visitors from becoming agents).
// Change it before sharing the app with anyone.
define('AGENT_ACCESS_CODE', 'AGENCY2026');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}
?>
