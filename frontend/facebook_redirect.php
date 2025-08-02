<?php
// Load config from ai_config.ini
$config = parse_ini_file('C:/wamp64/private/ai_config.ini', true);

$content = $_POST['content'] ?? '';

if (empty($content)) {
    echo "❌ No content to share.";
    exit;
}

// Encode content for URL
$encodedText = urlencode($content);

// Get base shared-content URL from config
$baseUrl = $config['urls']['shared_content_page'] ?? '';

// Final shareable URL (e.g., http://localhost/AI_PROJECT/frontend/shared-content.php?text=...)
$shareableUrl = $baseUrl . '?text=' . $encodedText;

// Facebook sharer expects a URL to share
$facebookShareUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($shareableUrl);

// Redirect to Facebook's share dialog
header("Location: $facebookShareUrl");
exit;
