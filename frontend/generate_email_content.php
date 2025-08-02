<?php
require_once 'gemini_api.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = $_POST['from'] ?? '';
    $to = $_POST['to'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $aboutText = $_POST['about'] ?? '';

    // Only subject and about are essential for generation
    if (empty($subject) || empty($aboutText)) {
        echo json_encode(['content' => 'Subject and About are required.']);
        exit;
    }

    // Clean and trim input
    $subject = trim($subject);
    $aboutText = trim($aboutText);

    // Generate email prompt for Gemini
    $fullPrompt = "
Create a well-formatted professional marketing email based on the following:

Subject: $subject
About: $aboutText

Include:
- A short engaging introduction
- Main body that highlights the product/service
- A friendly closing call-to-action
- Add 2 to 3 relevant hashtags at the bottom

Format the output in a proper email format including:
- Subject line

- Body
- Closing statement
Avoid any explanation or heading like 'Here is your email:'.
";

    $emailContent = generate_text($fullPrompt);

    // Clean response for safe JSON output
    $emailContent = trim($emailContent);
    $emailContent = preg_replace('/^(Here|Okay|Sure)[^:]*:\s*/i', '', $emailContent);

    echo json_encode(['content' => $emailContent]);
    exit;
}
?>
