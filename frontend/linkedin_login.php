<?php
$client_id = 'YOUR_CLIENT_ID';
$redirect_uri = 'http://localhost/AI_PROJECT/frontend/linkedin_callback.php';
$scope = 'w_member_social';

$state = $_GET['id']; // Track the company or user

$auth_url = "https://www.linkedin.com/oauth/v2/authorization"
    . "?response_type=code"
    . "&client_id=$client_id"
    . "&redirect_uri=" . urlencode($redirect_uri)
    . "&state=$state"
    . "&scope=" . urlencode($scope);

header("Location: $auth_url");
exit;
?>
