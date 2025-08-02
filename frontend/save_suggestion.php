<?php
// Enable error reporting (for development only)
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database class
require_once 'db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Only POST is allowed."
    ]);
    exit;
}

// Get POST data
$userId = isset($_POST['user_id']) ? trim($_POST['user_id']) : null;
$suggestion = isset($_POST['suggestion']) ? trim($_POST['suggestion']) : null;

// Validate input
if (empty($userId) || empty($suggestion)) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing user ID or suggestion."
    ]);
    exit;
}

// Save suggestion to the database
try {
    $db = new MySqlDB();
    $result = $db->saveCompanySuggestion($userId, $suggestion);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Suggestion saved successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to save suggestion."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Exception: " . $e->getMessage()
    ]);
}
exit;
