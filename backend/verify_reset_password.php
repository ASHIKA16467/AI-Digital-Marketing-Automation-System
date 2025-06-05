<?php
session_start();
require 'config.php';

header("Content-Type: application/json"); // JSON response

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['contact'])) {
        echo json_encode(["status" => "error", "message" => "❌ Session expired. Please restart."]);
        exit();
    }

    $entered_otp = trim($_POST["otp"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $contact = $_SESSION['contact'];

    // Get stored OTP
    $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $contact, $contact);
    $stmt->execute();
    $stmt->bind_result($stored_otp);
    $stmt->fetch();
    $stmt->close();

    if (!$stored_otp) {
        echo json_encode(["status" => "error", "message" => "❌ No OTP found. Try resending it."]);
        exit();
    }

    // Validate OTP
    if ($entered_otp != $stored_otp) {
        echo json_encode(["status" => "error", "message" => "❌ Invalid OTP. Please try again."]);
        exit();
    }

    // Validate passwords
    if ($new_password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "❌ Passwords do not match."]);
        exit();
    }

    // Hash password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ? OR phone = ?");
    $stmt->bind_param("sss", $hashed_password, $contact, $contact);

    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        echo json_encode(["status" => "success", "message" => "✅ Password reset successful"]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "❌ Password reset failed. Try again later."]);
        exit();
    }
}
?>
