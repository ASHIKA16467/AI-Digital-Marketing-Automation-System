<?php
session_start();
require 'config.php'; // Database connection
require 'send_mail.php'; // Function to send email
require 'send_sms.php'; // Function to send SMS

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact = trim($_POST["contact"]); // Can be email or phone
    $otp = rand(100000, 999999); // Generate 6-digit OTP
    $_SESSION['otp'] = $otp;
    $_SESSION['contact'] = $contact;

    // Check if contact is an email or phone number
    if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
    }

    $stmt->bind_param("s", $contact);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Save OTP in database
        $stmt = $conn->prepare("UPDATE users SET otp = ? WHERE email = ? OR phone = ?");
        $stmt->bind_param("iss", $otp, $contact, $contact);
        $stmt->execute();

        // Send OTP via Email or SMS
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            sendEmailOTP($contact, $otp);
        } else {
            sendSMSOTP($contact, $otp);
        }

        // Redirect to OTP verification page
        header("Location: ../frontend/verify_reset_password.html");
        exit();
    } else {
        header("Location: ../frontend/forgot_password.html?status=user_not_found");
        exit();
    }
}
?>
