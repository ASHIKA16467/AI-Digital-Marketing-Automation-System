<?php
// Load configuration
$config = parse_ini_file('C:/wamp64/private/ai_config.ini', true);

// Get redirect URI from config
$redirect_uri = $config['urls']['linkedin_callback'] ?? '';

// Normally you'd load these from a secure config as well
$client_id = 'YOUR_CLIENT_ID';
$client_secret = 'YOUR_CLIENT_SECRET';

$state = $_GET['state']; // companyId

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $token_url = "https://www.linkedin.com/oauth/v2/accessToken";

    $data = [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirect_uri,
        'client_id' => $client_id,
        'client_secret' => $client_secret
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($token_url, false, $context);
    $result = json_decode($response, true);

    if (isset($result['access_token'])) {
        $access_token = $result['access_token'];

        // Optional: Save to DB
        // $db->saveLinkedInToken($state, $access_token);

        // Post content
        include 'linkedin_post.php';
        postToLinkedIn($access_token, "Your AI-generated content here");

    } else {
        echo "Error retrieving access token.";
    }
} else {
    echo "Authorization failed.";
}
?>
