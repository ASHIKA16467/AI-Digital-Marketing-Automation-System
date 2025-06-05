<?php
function postToLinkedIn($access_token, $text) {
    // Get user ID
    $me = file_get_contents("https://api.linkedin.com/v2/me", false, stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer $access_token"
        ]
    ]));
    $profile = json_decode($me, true);
    $urn = $profile['id'];

    // Post content
    $postData = json_encode([
        "author" => "urn:li:person:$urn",
        "lifecycleState" => "PUBLISHED",
        "specificContent" => [
            "com.linkedin.ugc.ShareContent" => [
                "shareCommentary" => [
                    "text" => $text
                ],
                "shareMediaCategory" => "NONE"
            ]
        ],
        "visibility" => [
            "com.linkedin.ugc.MemberNetworkVisibility" => "PUBLIC"
        ]
    ]);

    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => "Authorization: Bearer $access_token\r\nContent-Type: application/json",
            'content' => $postData
        ]
    ];
    $context = stream_context_create($opts);
    $result = file_get_contents("https://api.linkedin.com/v2/ugcPosts", false, $context);

    if ($result) {
        echo "✅ Post successful!";
    } else {
        echo "❌ Post failed.";
    }
}
?>
