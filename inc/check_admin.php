<?php
session_start();

// Example: After checking username/password is correct AND user is admin
if ($isValidUser && $isAdminUser) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['is_admin'] = true; // IMPORTANT: sets admin access
    header("Location: dashboard.php");
    exit;
}
?>
