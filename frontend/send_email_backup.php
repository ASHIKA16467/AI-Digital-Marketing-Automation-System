<?php
require 'vendor/autoload.php';
require_once 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = $_POST['fromEmail'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $emailContent = $_POST['generated_email'] ?? '';
    $groupId = $_POST['group_id'] ?? '';

    if (empty($groupId)) {
        echo json_encode(['status' => 'error', 'message' => 'Group ID is required.']);
        exit;
    }

    // Initialize DB
    $db = new MySqlDB();

    // Fetch contacts by group
    $contacts = $db->getContactsByGroupId($groupId);
    if (!$contacts || count($contacts) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'No contacts found for this group.']);
        exit;
    }

    $successCount = 0;
    foreach ($contacts as $contact) {
        $email = trim($contact['email']);
        $name = trim($contact['name']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            continue;
        }

       $personalizedBody = str_replace(
    ['[name]', '[Name]', ' [name]', ' [Name]'],
    " {$name}",
    $emailContent
);

        $mailer = new PHPMailer(true);
        try {
            // SMTP configuration
            $mailer->isSMTP();
            $mailer->Host = 'smtp.gmail.com';
            $mailer->SMTPAuth = true;
            $mailer->Username = 'ashikasubhashidam@gmail.com'; // replace with your email
            $mailer->Password = 'hach pgvr ohyl yura';          // App Password
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->Port = 587;

            $mailer->setFrom($from, 'Marketing Bot');
            $mailer->addAddress($email, $name);

            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body    = nl2br($personalizedBody);

            $mailer->send();
            $successCount++;
        } catch (Exception $e) {
            // Optionally log errors here
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => "✅ Email sent successfully to {$successCount} contact(s)."
    ]);
}
?>
