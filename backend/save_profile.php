<?php
session_start();
include("config.php"); // Adjust path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_email'])) {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $email = $_SESSION['user_email'];

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, location = ? WHERE email = ?");
    $stmt->bind_param("ssss", $full_name, $phone, $location, $email);

    if ($stmt->execute()) {
        // Update session data
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_location'] = $location;

        // Redirect with success message
        header("Location: http://localhost/AI_project/frontend/profile.php?status=success");
        exit();
    } else {
        echo "Error updating profile.";
    }
} else {
    header("Location: http://localhost/AI_project/frontend/profile.php");
    exit();
}
?>
