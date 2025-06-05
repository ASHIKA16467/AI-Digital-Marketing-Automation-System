<?php
session_start();
include("config.php");

$user_email = $_SESSION['user_email'] ?? '';
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if ($new_password !== $confirm_password) {
    $_SESSION['flash_message'] = 'New password and confirm password do not match!';
    $_SESSION['flash_type'] = 'error';
    header("Location: ../frontend/change_password.php");
    exit();
}

// Get current user
$stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($current_password, $user['password'])) {
    $_SESSION['flash_message'] = 'Incorrect current password!';
    $_SESSION['flash_type'] = 'error';
    header("Location: ../frontend/change_password.php");
    exit();
}

if (password_verify($new_password, $user['password'])) {
    $_SESSION['flash_message'] = 'New password must be different from the current password!';
    $_SESSION['flash_type'] = 'error';
    header("Location: ../frontend/change_password.php");
    exit();
}


// Update password
$new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
$update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$update->bind_param("ss", $new_hashed, $user_email);

if ($update->execute()) {
    $_SESSION['flash_message'] = 'Password updated successfully!';
    $_SESSION['flash_type'] = 'success';
} else {
    $_SESSION['flash_message'] = 'Something went wrong!';
    $_SESSION['flash_type'] = 'error';
}

header("Location: ../frontend/change_password.php");
exit();
