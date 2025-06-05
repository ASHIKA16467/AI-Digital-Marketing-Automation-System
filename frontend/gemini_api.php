<?php
require_once 'db.php';

function generate_text($prompt) {
    error_log($prompt);
    $api_key = "AIzaSyCa8utjjQulKCilSyglv8kosgrlnVbdEFw";
    $model_name = "gemini-2.0-flash";
    $url = "https://generativelanguage.googleapis.com/v1beta/models/$model_name:generateContent?key=$api_key";

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt]
                ]
            ]
        ]
    ];

    $payload = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return "cURL Error: " . curl_error($ch);
    }

    curl_close($ch);

    if ($http_code !== 200) {
        return "API Request Failed. Status Code: $http_code";
    }

    $response_data = json_decode($response, true);
    return $response_data['candidates'][0]['content']['parts'][0]['text'] ?? "Failed to parse response.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prompt'])) {
    echo generate_text($_POST['prompt']);
    exit;
}