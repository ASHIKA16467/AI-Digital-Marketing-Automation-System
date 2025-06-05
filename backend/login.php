<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Fetch all user fields you need, including role
    $stmt = $conn->prepare("SELECT id, full_name, email, phone, location, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $email, $phone, $location, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Store all session variables
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $full_name;
            $_SESSION["user_email"] = $email;
            $_SESSION["user_phone"] = $phone;
            $_SESSION["user_location"] = $location;
            

            header("Location: ../frontend/index.php");
            exit();
        } else {
            header("Location: ../frontend/login.html?status=invalid_password");
            exit();
        }
    } else {
        header("Location: ../frontend/login.html?status=user_not_found");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
