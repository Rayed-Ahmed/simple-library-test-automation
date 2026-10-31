<?php
// profile.php
require_once 'includes/functions.php';
check_login();

// Placeholder for profile management logic
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = "Profile update functionality is not yet implemented.";
}

include 'includes/header.php';
?>

<h2>Manage Profile</h2>
<p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong>!</p>
<?php if(!empty($message)): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<div class="form-container" style="max-width: 600px;">
    <h3>Change Password</h3>
    <form action="" method="post">
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password">
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password">
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_new_password">
        </div>
        <div class="form-group">
            <input type="submit" class="btn" value="Change Password">
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>