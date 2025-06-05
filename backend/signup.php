<?php
session_start(); // Start session at the top

// Database connection
include("config.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        header("Location: ../frontend/signup.html?status=password_mismatch");
        exit();
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        // Redirect back with an error message if email exists
        header("Location: ../frontend/signup.html?status=email_exists");
        exit();
    }

    // Get other values
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $location = $_POST["location"];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash password

    // Insert new user
    $insertUser = $conn->prepare("INSERT INTO users (full_name, email, phone, location, password) VALUES (?, ?, ?, ?, ?)");
    $insertUser->bind_param("sssss", $full_name, $email, $phone, $location, $hashedPassword);

    if ($insertUser->execute()) {
        // Set session variables for profile display
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_location'] = $location;

        // Redirect to profile page
        header("Location: ../frontend/profile.php");
        exit();
    } else {
        header("Location: ../frontend/signup.html?status=error");
        exit();
    }
}

// If accessed directly, redirect to signup page
header("Location: ../frontend/signup.html");
exit();
?>
