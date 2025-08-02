<?php
include 'db.php';
$db = new MySqlDB();

header('Content-Type: application/json');

// Load ai_config.ini from private folder
$config = parse_ini_file('C:/wamp64/private/ai_config.ini', true);

$user_id = $_POST['user_id'] ?? null;
$content = $_POST['content'] ?? '';

if (!$user_id || !$content) {
    echo json_encode(['status' => 'error', 'message' => 'Missing user ID or content.']);
    exit;
}

// Fetch selected social media platform
$company = $db->getCompanyById($user_id); // user_id here is actually company ID

if (!$company || empty($company['social_media'])) {
    echo json_encode(['status' => 'error', 'message' => 'No social media platform selected.']);
    exit;
}

$platform = strtolower(trim($company['social_media']));
$redirectUrl = null;

// Redirect only for LinkedIn
if ($platform === 'linkedin') {
    $baseRedirect = $config['urls']['linkedin_redirect'] ?? '';

    if (!$baseRedirect) {
        echo json_encode(['status' => 'error', 'message' => 'LinkedIn redirect URL not configured.']);
        exit;
    }

    $redirectUrl = $baseRedirect . '?id=' . urlencode($user_id);

    echo json_encode([
        'status' => 'success',
        'platforms' => [$platform],
        'redirect_url' => $redirectUrl
    ]);
    exit;
}

echo json_encode([
    'status' => 'no_redirect',
    'message' => "Redirection is only supported for LinkedIn.",
    'platforms' => [$platform]
]);
exit;
