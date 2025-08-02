<?php
session_start();
require_once '../frontend/db.php'; // Use frontend db.php

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new MySqlDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['contact'])) {
        echo json_encode(["status" => "error", "message" => "❌ Session expired. Please restart."]);
        exit();
    }

    $entered_otp = trim($_POST["otp"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $contact = $_SESSION['contact'];

    // ✅ Get stored OTP
    $stored_otp = $db->getStoredOTPByContact($contact);

    if (!$stored_otp) {
        echo json_encode(["status" => "error", "message" => "❌ No OTP found. Try resending it."]);
        exit();
    }

    // ✅ Validate OTP
    if ($entered_otp != $stored_otp) {
        echo json_encode(["status" => "error", "message" => "❌ Invalid OTP. Please try again."]);
        exit();
    }

    // ✅ Validate password match
    if ($new_password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "❌ Passwords do not match."]);
        exit();
    }

    // ✅ Hash and update password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    if ($db->resetPasswordByContact($contact, $hashed_password)) {
        session_unset();
        session_destroy();
        echo json_encode(["status" => "success", "message" => "✅ Password reset successful"]);
    } else {
        echo json_encode(["status" => "error", "message" => "❌ Password reset failed. Try again later."]);
    }

    exit();
}
