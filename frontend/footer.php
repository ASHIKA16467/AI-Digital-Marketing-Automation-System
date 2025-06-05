<?php
$content = $_POST['content'] ?? '';
if (empty($content)) {
    echo "No content to share.";
    exit;
}

// Encode the content and redirect to shared page
$encodedText = urlencode($content);
$redirectUrl = "http://localhost/AI_PROJECT/frontend/shared-content.php?text=" . $encodedText;
header("Location: $redirectUrl");
exit;
?>
