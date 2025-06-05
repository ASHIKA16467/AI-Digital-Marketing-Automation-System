<?php
include 'db.php';
$db = new MySqlDB();

header('Content-Type: application/json');

$user_id = $_POST['user_id'] ?? null;
$content = $_POST['content'] ?? '';

if (!$user_id || !$content) {
    echo json_encode(['status' => 'error', 'message' => 'Missing user ID or content.']);
    exit;
}

// Fetch the selected social media platform from user_profiles
$company = $db->getCompanyById($user_id); // user_id here is actually the company ID

if (!$company || empty($company['social_media'])) {
    echo json_encode(['status' => 'error', 'message' => 'No social media platform selected.']);
    exit;
}

$platform = strtolower(trim($company['social_media'])); // Normalize case
$redirectUrl = null;

// Handle only LinkedIn redirection
if ($platform === 'linkedin') {
    // Redirect to LinkedIn OAuth flow
    $redirectUrl = "http://localhost/AI_PROJECT/frontend/linkedin_login.php?id=" . urlencode($user_id);

    echo json_encode([
        'status' => 'success',
        'platforms' => [$platform],
        'redirect_url' => $redirectUrl
    ]);
    exit;
}

// No redirection for other platforms
echo json_encode([
    'status' => 'no_redirect',
    'message' => "Redirection is only supported for LinkedIn.",
    'platforms' => [$platform]
]);
exit;
