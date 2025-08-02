<?php
session_start();
require_once '../frontend/db.php'; // Updated to use db.php

$db = new MySqlDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST["user_input"];
    $otp = rand(100000, 999999); // Generate 6-digit OTP

    // ✅ Fetch user using unified function
    $user = $db->getUserByEmailOrPhone($user_input);

    if ($user) {
        $_SESSION["otp"] = $otp;
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_input"] = $user_input;

        // ✅ Send OTP via email or SMS
        if (filter_var($user_input, FILTER_VALIDATE_EMAIL)) {
            mail($user_input, "Password Reset OTP", "Your OTP is: $otp", "From: no-reply@yourdomain.com");
        } else {
            // You can integrate a real SMS API here
            // file_get_contents("https://sms-api.com/send?to=$user_input&message=Your OTP is: $otp");
        }

        header("Location: ../frontend/verify_otp.php");
        exit();
    } else {
        echo "<script>alert('User not found!'); window.location.href='../frontend/forgot_password.php';</script>";
    }
}
