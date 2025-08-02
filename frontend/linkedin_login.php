<?php
// Load configuration from ini file
$config = parse_ini_file('C:/wamp64/private/ai_config.ini', true);

// Get LinkedIn callback URL from config
$redirect_uri = $config['urls']['linkedin_callback'] ?? '';

// LinkedIn app details (you can move these to ai_config.ini too if you like)
$client_id = 'YOUR_CLIENT_ID';
$scope = 'w_member_social';

$state = $_GET['id']; // Track the company or user ID

// Build LinkedIn OAuth authorization URL
$auth_url = "https://www.linkedin.com/oauth/v2/authorization"
    . "?response_type=code"
    . "&client_id=$client_id"
    . "&redirect_uri=" . urlencode($redirect_uri)
    . "&state=$state"
    . "&scope=" . urlencode($scope);

// Redirect to LinkedIn for authentication
header("Location: $auth_url");
exit;
