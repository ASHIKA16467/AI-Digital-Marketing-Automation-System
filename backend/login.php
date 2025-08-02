<?php
session_start();

// Adjust path to reach db.php from backend
require_once '../frontend/db.php';

$db = new MySqlDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $user = $db->getUserByEmail($email);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION["user_id"] = $user['id'];
            $_SESSION["user_name"] = $user['full_name'];
            $_SESSION["user_email"] = $user['email'];
            $_SESSION["user_phone"] = $user['phone'];
            $_SESSION["user_location"] = $user['location'];

            header("Location: ../frontend/index.php");
            exit();
        } else {
            header("Location: ../frontend/login.php?status=invalid_password");
            exit();
        }
    } else {
        header("Location: ../frontend/login.php?status=user_not_found");
        exit();
    }
}
?>