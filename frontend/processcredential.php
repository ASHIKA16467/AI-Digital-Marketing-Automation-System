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
                $platform = $_POST['platform'] ?? '';
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';

                if ($db->isCredentialExists($userId, $platform, $username, $id)) {
                    $response['success'] = false;
                    $response['message'] = 'Credential already exists!';
                } else {
                if ($db->addCredential($userId, $platform, $username, $password)) {
                    $response['success'] = true;
                    $response['message'] = 'Credential added successfully!';
                } else {
                    $response['message'] = 'Failed to add credential.';
                }
            }
                break;

            case 'edit':
                $id = $_POST['id'] ?? '';
                $platform = $_POST['platform'] ?? '';
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';

                // Check if the credential already exists
                if ($db->isCredentialExists($userId, $platform, $username, $id)) {
                    $response['success'] = false;
                    $response['message'] = 'Credential already exists!';
                } else {
                    if ($db->editCredential($id, $platform, $username, $password)) {
                        $response['success'] = true;
                        $response['message'] = 'Credential updated successfully!';
                    } else {
                        $response['message'] = 'Failed to update credential.';
                    }
                }
                break;

            case 'delete':
                $id = $_POST['id'] ?? '';

                if ($db->deleteCredential($id)) {
                    $response['success'] = true;
                    $response['message'] = 'Credential deleted successfully!';
                } else {
                    $response['message'] = 'Failed to delete credential.';
                }
                break;

            case 'fetch':
                $credentials = $db->fetchCredentials($userId);
                if ($credentials) {
                    $response['success'] = true;
                    $response['data'] = $credentials;
                } else {
                    $response['message'] = 'Failed to fetch credentials.';
                }
                break;

            case 'fetch-single':
                $id = $_POST['id'] ?? '';
                $credential = $db->fetchCredentialById($id);
                if ($credential) {
                    $response['success'] = true;
                    $response['data'] = $credential;
                } else {
                    $response['message'] = 'Failed to fetch credential.';
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
