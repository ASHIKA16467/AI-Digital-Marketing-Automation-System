<?php
$text = $_GET['text'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    
    <meta property="og:title" content="Generated AI Marketing Content" />
    <meta property="og:description" content="<?php echo htmlspecialchars(substr($_GET['text'], 0, 200)); ?>" />
    <meta property="og:image" content="https://your-domain.com/path-to-default-preview-image.jpg" />
    <meta property="og:url" content="https://2662-192-140-152-125.ngrok-free.app/AI_project/frontend/shared-content.php?text=<?php echo urlencode($_GET['text']); ?>" />


    <meta charset="UTF-8">
    <title>AI Marketing Content</title>
    <meta property="og:title" content="AI Marketing Post" />
    <meta property="og:description" content="<?php echo htmlspecialchars($text); ?>" />
</head>
<body>
    <h2>Shared AI Content</h2>
    <p><?php echo nl2br(htmlspecialchars($text)); ?></p>
</body>
</html>
