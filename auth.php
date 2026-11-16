<?php
function requireLogin(): void {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function requireRole(array $roles): void {
    requireLogin();

    if (!in_array($_SESSION['user']['userType'], $roles, true)) {
        http_response_code(403);
        die('Access denied.');
    }
}

// ---- CSRF protection ----
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrfToken()) . '">';
}

function csrfValid(): bool {
    $sent = $_POST['csrf_token'] ?? '';
    return is_string($sent) && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $sent);
}
?>
