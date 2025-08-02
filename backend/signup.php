<?php
session_start();

require_once '../frontend/db.php'; // Use db.php from frontend

$db = new MySqlDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        header("Location: ../frontend/signup.php?status=password_mismatch");
        exit();
    }

    // Check if email already exists
    if ($db->isEmailExists($email)) {
        header("Location: ../frontend/signup.php?status=email_exists");
        exit();
    }

    // Get other values
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $location = $_POST["location"];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    if ($db->registerUser($full_name, $email, $phone, $location, $hashedPassword)) {
        // Set session variables for profile display
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_location'] = $location;

        header("Location: ../frontend/profile.php");
        exit();
    } else {
        header("Location: ../frontend/signup.php?status=error");
        exit();
    }
}

// If accessed directly
header("Location: ../frontend/signup.php");
exit();
