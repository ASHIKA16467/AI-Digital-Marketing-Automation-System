<?php
session_start();

require_once '../frontend/db.php'; // Use the db.php from frontend
require 'send_mail.php';           // Function to send email
require 'send_sms.php';            // Function to send SMS

$db = new MySqlDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact = trim($_POST["contact"]); // Can be email or phone
    $otp = rand(100000, 999999);        // Generate 6-digit OTP
    $_SESSION['otp'] = $otp;
    $_SESSION['contact'] = $contact;

    // ✅ Check if user exists
    if ($db->userExistsByEmailOrPhone($contact)) {
        // ✅ Update OTP
        $db->updateOTPByContact($otp, $contact);

        // ✅ Send via Email or SMS
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            sendEmailOTP($contact, $otp);
        } else {
            sendSMSOTP($contact, $otp);
        }

        header("Location: ../frontend/verify_reset_password.php");
        exit();
    } else {
        header("Location: ../frontend/forgot_password.php?status=user_not_found");
        exit();
    }
}
?>
