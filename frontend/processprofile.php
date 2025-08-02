<?php
session_start();
require_once 'db.php';

$response = ['success' => false, 'message' => '', 'data' => []];
$db = new MySqlDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = $_POST['user_id'] ?? $_SESSION['user_id'] ?? null;

    if ($userId === null) {
        $response['message'] = 'User ID is required.';
    } else {
        switch ($action) {
            case 'add':
                $id = $_POST['id'] ?? '';
                $projectName = $_POST['project_name'] ?? '';
                $companyName = $_POST['company_name'] ?? '';
                $contactName = $_POST['contact_name'] ?? '';
                $email = $_POST['email'] ?? '';
                $phone = $_POST['phone'] ?? '';
                $socialMedia = $_POST['social_media'] ?? '';

                $businessInfo = $_POST['business_info'] ?? '';
                $additionalInfo = $_POST['additional_info'] ?? '';

                $url = $_POST['url'] ?? '';
                $tagline = $_POST['tagline'] ?? '';
                $preferredKeywords = $_POST['preferred_keywords'] ?? '';

                if ($db->isCompanyExists($userId, $companyName, $projectName, $socialMedia, $id)) {
                    $response['success'] = false;
                    $response['message'] = ' profile already exists!';
                } else {
                  if ($db->addProfile($userId, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords)) {
                        $response['success'] = true;
                    $response['message'] = 'Profile added successfully!';
                } else {
                    $response['message'] = 'Failed to add profile.';
                }
            }
                break;

            case 'edit':
                $id = $_POST['id'] ?? '';
                $projectName = $_POST['project_name'] ?? '';
                $companyName = $_POST['company_name'] ?? '';
                $contactName = $_POST['contact_name'] ?? '';
                $email = $_POST['email'] ?? '';
                $phone = $_POST['phone'] ?? '';
                $socialMedia = $_POST['social_media'] ?? '';
                $businessInfo = $_POST['business_info'] ?? '';
                $additionalInfo = $_POST['additional_info'] ?? '';

                $url = $_POST['url'] ?? '';
                $tagline = $_POST['tagline'] ?? '';
                $preferredKeywords = $_POST['preferred_keywords'] ?? '';

                if ($db->isCompanyExists($userId, $companyName, $projectName, $socialMedia, $id)) {
                    $response['success'] = false;
                    $response['message'] = ' profile already exists!';
                } else {
                    if ($db->editProfile($id, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords)) {
                        $response['success'] = true;
                    $response['message'] = 'Profile updated successfully!';
                } else {
                    $response['message'] = 'Failed to update profile.';
                }
            }
                break;

            case 'delete':
                $id = $_POST['id'] ?? '';

                if ($db->deleteProfile($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Profile deleted successfully!';
                } else {
                    $response['message'] = 'Failed to delete profile.';
                }
                break;

            case 'fetch':
                $profiles = $db->fetchProfiles($userId);
                if ($profiles) {
                    $response['success'] = true;
                    $response['data'] = $profiles;
                } else {
                    $response['message'] = 'Failed to fetch profiles.';
                }
                break;

            case 'fetch-single':
                $id = $_POST['id'] ?? '';
                $profile = $db->fetchProfileById($id);
                if ($profile) {
                    $response['success'] = true;
                    $response['data'] = $profile;
                } else {
                    $response['message'] = 'Failed to fetch profile.';
                }
                break;

            default:
                $response['message'] = 'Invalid action.';
                break;
        }
    }

    $db->closeConnection();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
