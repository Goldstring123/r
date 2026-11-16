<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

$message = '';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid()) {
        $message = '<div class="error">Your session expired. Please try again.</div>';
    } else {
        $db = new RealEstateDatabase();

        $userName = trim($_POST['userName'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $db->getUserByUsername($userName);

        if ($user && password_verify($password, $user['passwordHash'])) {
            unset($user['passwordHash']);
            session_regenerate_id(true);   // prevents session fixation
            $_SESSION['user'] = $user;
            header('Location: dashboard.php');
            exit;
        } else {
            $message = '<div class="error">Invalid username or password.</div>';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Login</h2>
<?php if ($message): ?>
    <?= $message ?>
<?php endif; ?>

<form method="POST">
    <?= csrfField() ?>
    <label>Username</label>
    <input type="text" name="userName" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="register.php">Register here</a></p>
<?php include 'includes/footer.php'; ?>
