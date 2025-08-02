<?php
//require_once '../inc/check_admin.php';
require_once 'db.php';
require_once 'gemini_api.php';

if (!isset($_POST['area']) || trim($_POST['area']) === '') {
    echo "Invalid area.";
    exit;
}

$db = new DB();
$existing = $db->getAttractionsByArea($area, $scanType);

if (!is_null($existing)) {
    echo "Already exists";
    exit;
}

// Default prompt for website
//$prompt = "Generate a detailed, SEO-optimized description of $area, Bangalore, Karnataka, India, for a website focused on medical services offering $scanType scans...";

// Adjust prompt for Twitter platform
if ($platform === 'twitter') {
    $prompt = "Write only a single promotional tweet about $scanType scan services in $area, Bangalore. It must be strictly under 180 characters. Do not include explanations, headings, or extra content. Respond with just the tweet text.";
}

// Get Gemini AI response
$response = generate_text($prompt);

// Twitter specific trimming to max 180 chars without cutting words mid-way
if (strlen($response) > 180) {
    $truncated = substr($response, 0, 180);
    $lastSpace = strrpos($truncated, ' ');
    if ($lastSpace !== false) {
        $truncated = substr($truncated, 0, $lastSpace);
    }
    $response = $truncated . '...';
}

// Clean up response for website (remove unwanted boilerplate)
if ($platform !== 'twitter') {
    $response = preg_replace('/^Here.*?requirements:\s*/i', '', $response);
    $response = str_replace([
        'MRI Scan Long Tail SEO Keywords',
        'CT Scan Long Tail SEO Keywords',
        '*Note: Remember to replace "Our MRI scan center" with the actual name and accurate address of your center.*',
        '*Note: Remember to replace "Our CT scan center" with the actual name and accurate address of your center.*',
        'Here is the long tail SEO keyword content:',
        'Here are the long tail SEO keyword contents:',
    ], '', $response);
    $response = preg_replace('/#+\s*Long tail keywords.*/i', '', $response);
    $response = preg_replace('/\*\*.*?(long tail|seo keyword).*?\*\*/i', '', $response);
    $response = preg_replace('/^Long tail keywords:.*$/im', '', $response);
    $response = trim($response);
}

// Save generated content to database

?>
