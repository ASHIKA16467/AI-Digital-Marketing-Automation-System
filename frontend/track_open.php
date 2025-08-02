<?php
require_once 'db.php';

$email = $_GET['email'] ?? '';
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $db = new MySqlDB();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("INSERT INTO email_opens (email, opened_at) VALUES (?, NOW())");
    $stmt->bind_param("s", $email);
    $stmt->execute();
}

header("Content-Type: image/gif");
readfile("1x1.gif");
?>
