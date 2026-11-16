<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

requireRole(['buyer', 'renter']);

$db = new RealEstateDatabase();
$message = '';

$propertyId = isset($_GET['propertyId']) ? (int)$_GET['propertyId'] : (int)($_POST['propertyId'] ?? 0);
$property = $propertyId > 0 ? $db->getPropertyById($propertyId) : false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)$_SESSION['user']['userId'];
    $messageText = trim($_POST['message'] ?? '');

    if (!csrfValid()) {
        $message = 'Your session expired. Please try again.';
    } elseif (!$property) {
        $message = 'That property could not be found.';
    } elseif ($messageText === '') {
        $message = 'Please enter a message.';
    } else {
        try {
            $db->addInquiry($userId, $propertyId, $messageText);
            $message = 'Inquiry submitted successfully.';
        } catch (Throwable $e) {
            error_log('Inquiry error: ' . $e->getMessage());
            $message = 'Could not send your inquiry. Please try again.';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Submit Inquiry</h2>
<?php if ($property): ?>
    <p>About: <strong><?= htmlspecialchars($property['title']) ?></strong></p>
<?php endif; ?>
<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if ($property): ?>
<form method="POST">
    <?= csrfField() ?>
    <input type="hidden" name="propertyId" value="<?= (int)$propertyId ?>">

    <label>Message</label>
    <textarea name="message" rows="6" required></textarea>

    <button type="submit">Send Inquiry</button>
</form>
<?php else: ?>
    <p class="error">Property not found. <a href="properties.php">Back to listings</a></p>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>
