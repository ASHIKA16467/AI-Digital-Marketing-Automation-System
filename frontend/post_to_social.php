<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include 'db.php';
$db = new MySqlDB();

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $userId = intval($_POST["user_id"]);
        $content = $_POST["content"];

        if (!$userId || trim($content) === '') {
            echo json_encode(["status" => "error", "message" => "Missing data"]);
            exit;
        }

        // Get platform from DB
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT social_media FROM user_profiles WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();


        if (!$row) {
            echo json_encode(["status" => "error", "message" => "User not found"]);
            exit;
        }

        $platform = strtolower(trim($row['social_media']));
        $redirectUrl = "";

        // Generate appropriate redirect/share link
        switch ($platform) {
            case 'linkedin':
                // Share URL with embedded content
                $baseUrl = "https://2662-192-140-152-125.ngrok-free.app"; // Update this
                $shareUrl = $baseUrl . "/AI_project/frontend/shared-content.php?text=" . urlencode($content);
                $redirectUrl = "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($shareUrl);


                break;


            case 'twitter':
                $redirectUrl = "https://twitter.com/intent/tweet?text=" . urlencode($content);
                break;

            case 'facebook':
                $redirectUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode("https://example.com?text=" . urlencode($content));
                break;

            case 'instagram':
                // Inform the user that Instagram cannot be posted via browser
                echo json_encode(["status" => "error", "message" => "Instagram does not support browser-based sharing. Please use the mobile app."]);
                exit;

            default:
                echo json_encode(["status" => "error", "message" => "Unsupported platform: $platform"]);
                exit;
        }

        echo json_encode([
            "status" => "success",
            "platforms" => [$platform],
            "redirect_url" => $redirectUrl
        ]);
        exit;
    }
} catch (Throwable $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
    exit;
}
?>
