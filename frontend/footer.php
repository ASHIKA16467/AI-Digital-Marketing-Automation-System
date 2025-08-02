<?php
// Load config from ai_config.ini
$config = parse_ini_file('C:/wamp64/private/ai_config.ini', true);

$content = $_POST['content'] ?? '';
if (empty($content)) {
    echo "No content to share.";
    exit;
}

// Encode content
$encodedText = urlencode($content);

// Get shared-content URL from config (fallback if not found)
$baseUrl = $config['urls']['shared_content_page'] ?? 'http://localhost/AI_PROJECT/frontend/shared-content.php';

// Append encoded content
$redirectUrl = $baseUrl . '?text=' . $encodedText;

// Redirect
header("Location: $redirectUrl");
exit;
