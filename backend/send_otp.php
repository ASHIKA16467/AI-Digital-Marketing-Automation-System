<?php
session_start();
include 'config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST["user_input"];
    $otp = rand(100000, 999999); // Generate a 6-digit OTP

    // Check if the input is an email or phone number
    if (filter_var($user_input, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT * FROM users WHERE email = ?";
    } else {
        $query = "SELECT * FROM users WHERE phone = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION["otp"] = $otp;
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_input"] = $user_input;

        // Send OTP via Email
        if (filter_var($user_input, FILTER_VALIDATE_EMAIL)) {
            mail($user_input, "Password Reset OTP", "Your OTP is: $otp", "From: no-reply@yourdomain.com");
        } else {
            // Send OTP via SMS (Use a real API like Twilio)
            // file_get_contents("https://sms-api.com/send?to=$user_input&message=Your OTP is: $otp");
        }

        header("Location: ../frontend/verify_otp.html");
        exit();
    } else {
        echo "<script>alert('User not found!'); window.location.href='../frontend/forgot_password.html';</script>";
    }
}
?>
