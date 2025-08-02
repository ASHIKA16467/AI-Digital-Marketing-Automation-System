<?php
session_start();
require_once '../frontend/db.php';

$db = new MySqlDB();

// Load ai_config.ini from private folder
$config = parse_ini_file('C:/wamp64/private/ai_config.ini', true);

// Get profile page URL from config, or fallback to default if not found
$profileUrl = $config['urls']['profile_page'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_email'])) {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $email = $_SESSION['user_email'];

    $success = $db->updateUserProfileByEmail($email, $full_name, $phone, $location);

    if ($success) {
        // Update session data
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_location'] = $location;

        // Redirect to profile page using value from config
        header("Location: " . $profileUrl . "?status=success");
        exit();
    } else {
        echo "Error updating profile.";
    }
} else {
    // Redirect to profile page even on invalid access
    header("Location: " . $profileUrl);
    exit();
}
