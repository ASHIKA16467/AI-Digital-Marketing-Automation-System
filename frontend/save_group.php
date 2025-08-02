<?php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupName = trim($_POST['group_name'] ?? '');

    if ($groupName === '') {
        echo json_encode(['status' => 'error', 'message' => 'Group name is required.']);
        exit;
    }

    $db = new MySqlDB();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("INSERT INTO email_groups (group_name) VALUES (?)");
    $stmt->bind_param("s", $groupName);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Group creation failed.']);
    }
}

?>
