<?php
session_start();
require_once '../frontend/db.php'; // Updated to use db.php

$db = new MySqlDB();

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

// ✅ Get current hashed password from DB
$user = $db->getUserPasswordByEmail($user_email);

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

// ✅ Update new hashed password in DB
$new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
if ($db->updateUserPasswordByEmail($user_email, $new_hashed)) {
    $_SESSION['flash_message'] = 'Password updated successfully!';
    $_SESSION['flash_type'] = 'success';
} else {
    $_SESSION['flash_message'] = 'Something went wrong!';
    $_SESSION['flash_type'] = 'error';
}

header("Location: ../frontend/change_password.php");
exit();
