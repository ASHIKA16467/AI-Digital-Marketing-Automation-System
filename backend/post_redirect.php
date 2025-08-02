<?php
require_once '../frontend/db.php'; 
$db = new MySqlDB();

// Get company ID from the URL
$companyId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$companyId) {
    die("Company ID is missing.");
}

// Fetch company info
$company = $db->getCompanyById($companyId); // You must implement this in your db.php if not already

if (!$company) {
    die("Company not found.");
}

$platform = strtolower(trim($company['social_media'])); // e.g., "linkedin", "facebook", etc.

// Redirect to the platform-specific auth page
switch ($platform) {
    case 'linkedin':
        header("Location: linkedin_login.php?id=" . $companyId);
        exit;

    // You can later add support for Facebook, Twitter, Instagram, etc.
    default:
        echo "❌ Posting to this platform is not supported yet.";
        exit;
}
?>
