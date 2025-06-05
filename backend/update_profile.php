<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $age = $_POST["age"];
    $location = $_POST["location"];

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, age = ?, location = ? WHERE email = ?");
    $stmt->bind_param("sssss", $full_name, $phone, $age, $location, $email);

    if ($stmt->execute()) {
        // Update session data
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_age'] = $age;
        $_SESSION['user_location'] = $location;

        header("Location: ../frontend/profile.php?status=updated");
    } else {
        echo "Failed to update";
    }
}
?>
