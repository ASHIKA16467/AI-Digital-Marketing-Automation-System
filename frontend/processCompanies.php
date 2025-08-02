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

                // Check if the company profile already exists
                if ($db->isCompanyExists($userId, $companyName, $projectName, $socialMedia, $id)) {
                    $response['success'] = false;
                    $response['message'] = ' profile already exists!';
                } else {
                    if ($db->addCompany($userId, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords)) {
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

                // Check if the company profile already exists for another entry
                if ($db->isCompanyExists($userId, $companyName, $projectName, $socialMedia, $id)) {
                    $response['success'] = false;
                    $response['message'] = 'Company profile already exists!';
                } else {
                    if ($db->editCompany($id, $projectName, $companyName, $contactName, $email, $phone, $socialMedia, $businessInfo, $additionalInfo, $url, $tagline, $preferredKeywords)) {
                        $response['success'] = true;
                        $response['message'] = 'Company profile updated successfully!';
                    } else {
                        $response['message'] = 'Failed to update company profile.';
                    }
                }
                break;

            case 'delete':
                $id = $_POST['id'] ?? '';

                if ($db->deleteCompany($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Company profile deleted successfully!';
                } else {
                    $response['message'] = 'Failed to delete company profile.';
                }
                break;

            case 'fetch':
                $companies = $db->fetchCompanies($userId); 
                if ($companies) {
                    $response['success'] = true;
                    $response['data'] = $companies;
                } else {
                    $response['message'] = 'Failed to fetch company profiles.';
                }
                break;

            case 'fetch-single':
                $id = $_POST['id'] ?? '';
                $company = $db->fetchCompanyById($id);
                if ($company) {
                    $response['success'] = true;
                    $response['data'] = $company;
                } else {
                    $response['message'] = 'Failed to fetch company profile.';
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
