<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

$message = '';
$allowedTypes = ['agent', 'buyer', 'renter'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName    = trim($_POST['userName'] ?? '');
    $contactInfo = trim($_POST['contactInfo'] ?? '');
    $password    = $_POST['password'] ?? '';
    $userType    = $_POST['userType'] ?? '';
    $agentCode   = $_POST['agentCode'] ?? '';

    if (!csrfValid()) {
        $message = '<div class="error">Your session expired. Please try again.</div>';
    } elseif ($userName === '' || $contactInfo === '' || $password === '' || $userType === '') {
        $message = '<div class="error">Please fill in all fields.</div>';
    } elseif (!in_array($userType, $allowedTypes, true)) {
        $message = '<div class="error">Please choose a valid role.</div>';
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $userName)) {
        $message = '<div class="error">Username must be 3-50 characters: letters, numbers, or underscores.</div>';
    } elseif (strlen($password) < 8) {
        $message = '<div class="error">Password must be at least 8 characters.</div>';
    } elseif ($userType === 'agent' && !hash_equals(AGENT_ACCESS_CODE, $agentCode)) {
        $message = '<div class="error">A valid agent access code is required to register as an agent.</div>';
    } else {
        try {
            $db = new RealEstateDatabase();
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $db->addUser($userName, $contactInfo, $passwordHash, $userType);
            $message = '<div class="success">Registration successful! <a href="login.php">Click here to login</a></div>';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {   // duplicate username
                $message = '<div class="error">That username is already taken.</div>';
            } else {
                error_log('Register error: ' . $e->getMessage());
                $message = '<div class="error">Something went wrong. Please try again.</div>';
            }
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Register</h2>
<?php if ($message): ?>
    <?= $message ?>
<?php endif; ?>

<form method="POST">
    <?= csrfField() ?>
    <label>Username</label>
    <input type="text" name="userName" required>

    <label>Contact Info</label>
    <input type="text" name="contactInfo" required>

    <label>Password (8+ characters)</label>
    <input type="password" name="password" minlength="8" required>

    <label>User Type</label>
    <select name="userType" required>
        <option value="">Select role</option>
        <option value="agent">Agent</option>
        <option value="buyer">Buyer</option>
        <option value="renter">Renter</option>
    </select>

    <label>Agent Access Code (agents only)</label>
    <input type="password" name="agentCode" autocomplete="off">

    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="login.php">Login here</a></p>
<?php include 'includes/footer.php'; ?>
