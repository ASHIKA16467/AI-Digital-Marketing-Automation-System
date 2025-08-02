<?php
session_start();
require_once '../frontend/db.php'; // Use db.php from frontend

$db = new MySqlDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $age = $_POST["age"];
    $location = $_POST["location"];

    if ($db->updateUserProfile($email, $full_name, $phone, $age, $location)) {
        // Update session data
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_age'] = $age;
        $_SESSION['user_location'] = $location;

        header("Location: ../frontend/profile.php?status=updated");
        exit();
    } else {
        echo "Failed to update";
    }
}
