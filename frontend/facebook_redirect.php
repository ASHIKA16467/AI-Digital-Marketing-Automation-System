<?php
$content = $_POST['content'] ?? '';

if (empty($content)) {
    echo "❌ No content to share.";
    exit;
}

$encodedText = urlencode($content);

// Create a shareable internal preview URL that contains your content
$shareableUrl = "http://localhost/AI_PROJECT/frontend/shared-content.php?text=" . $encodedText;

// Facebook expects a URL to share, so we pass our preview page as the shared link
$facebookShareUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($shareableUrl);

// Redirect to Facebook's share dialog
header("Location: $facebookShareUrl");
exit;
?>
