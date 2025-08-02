<?php
ob_start();
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
require_once 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        exit;
    }

    // ✅ Load config.ini
    $config = parse_ini_file('C:/wamp64/private/ai_config.ini', true);
    if (!$config || !isset($config['mailer']['email']) || !isset($config['mailer']['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Mailer configuration not found.']);
        exit;
    }
    $mailerEmail = trim($config['mailer']['email']);
    $mailerPassword = trim($config['mailer']['password']);

    $from = $_POST['fromEmail'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $emailContent = $_POST['generated_email'] ?? '';
    $groupId = $_POST['group_id'] ?? '';
    $rowStart = isset($_POST['row_start']) ? (int)$_POST['row_start'] : 0;
    $rowEnd = isset($_POST['row_end']) ? (int)$_POST['row_end'] : 0;
    $selectedEmails = $_POST['toEmails'] ?? [];

    $contacts = [];
    $db = new MySqlDB();

    // ✅ Case 1: Selected emails
    if (!empty($selectedEmails)) {
        foreach ($selectedEmails as $entry) {
            [$name, $email] = explode('|', $entry);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $contacts[] = ['name' => trim($name), 'email' => trim($email)];
            }
        }
    }
    // ✅ Case 2: Excel upload
    elseif (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === 0) {
        $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $startIndex = max($rowStart - 1, 1);
        $endIndex = $rowEnd > 0 ? min($rowEnd - 1, count($rows) - 1) : count($rows) - 1;

        for ($i = $startIndex; $i <= $endIndex; $i++) {
            $row = $rows[$i];
            $name = $row[0] ?? '';
            $email = $row[1] ?? '';
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $contacts[] = ['name' => trim($name), 'email' => trim($email)];
            }
        }
    }
    // ✅ Case 3: Group with row filtering
    elseif (!empty($groupId)) {
        $allContacts = $db->getContactsByGroupId($groupId);
        if (!$allContacts || count($allContacts) === 0) {
            echo json_encode(['status' => 'error', 'message' => 'No contacts found for this group.']);
            exit;
        }

        $startIndex = max($rowStart - 1, 0);
        $endIndex = ($rowEnd > 0) ? min($rowEnd - 1, count($allContacts) - 1) : count($allContacts) - 1;

        for ($i = $startIndex; $i <= $endIndex; $i++) {
            $contact = $allContacts[$i];
            $email = $contact['email'] ?? '';
            $name = $contact['name'] ?? '';
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $contacts[] = ['name' => trim($name), 'email' => trim($email)];
            }
        }
    }
    // ❌ No valid input
    else {
        echo json_encode(['status' => 'error', 'message' => 'Please select a group, upload Excel, or choose emails manually.']);
        exit;
    }

    if (empty($contacts)) {
        echo json_encode(['status' => 'error', 'message' => 'No valid email addresses found.']);
        exit;
    }

    $successCount = 0;
    foreach ($contacts as $contact) {
        $email = trim($contact['email']);
        $name = trim($contact['name']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

        $personalizedBody = str_replace(
            ['[name]', '[Name]', ' [name]', ' [Name]'],
            " {$name}",
            $emailContent
        );

        $mailer = new PHPMailer(true);
        try {
            $mailer->isSMTP();
            $mailer->Host = 'smtp.gmail.com';
            $mailer->SMTPAuth = true;
            $mailer->Username = $mailerEmail;
            $mailer->Password = $mailerPassword;
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->Port = 587;

            $mailer->setFrom($from ?: $mailerEmail, 'Marketing Bot');
            $mailer->addAddress($email, $name);

            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body = nl2br($personalizedBody);

            $mailer->send();
            $successCount++;
        } catch (Exception $e) {
            continue;
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => "✅ Email sent successfully to {$successCount} contact(s)."
    ]);
    exit;
} catch (Throwable $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unexpected server error: ' . $e->getMessage()
    ]);
    exit;
}
?>
