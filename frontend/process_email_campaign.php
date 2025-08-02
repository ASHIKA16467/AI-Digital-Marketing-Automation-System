<?php
require 'vendor/autoload.php';
require_once 'db.php';
require_once 'gemini_api.php';

use PHPMailer\PHPMailer\PHPMailer;
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '';
    $about = $_POST['about'] ?? '';
    $groupId = $_POST['group_id'] ?? '';
    $inputType = $_POST['input_type'] ?? '';

    if (empty($subject) || empty($about)) {
        exit("❌ Subject and About are required.");
    }

    $contacts = [];

    if ($inputType === 'manual') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $remarks = $_POST['remarks'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit("❌ Invalid email address.");
        }

        $contacts[] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'remarks' => $remarks
        ];

    } elseif ($inputType === 'excel' && isset($_FILES['excel'])) {
        $spreadsheet = IOFactory::load($_FILES['excel']['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getRowIterator(2) as $row) {
            $cells = $row->getCellIterator();
            $cells->setIterateOnlyExistingCells(true);

            $name = $cells->current()->getValue(); $cells->next();
            $email = $cells->current()->getValue(); $cells->next();
            $phone = $cells->current()->getValue(); $cells->next();
            $remarks = $cells->current()->getValue();

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $contacts[] = compact('name', 'email', 'phone', 'remarks');
            }
        }
    } else {
        exit("❌ Invalid input type.");
    }

    // Prepare and send emails
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ashikasubhashidam@gmail.com';
        $mail->Password = 'hach pgvr ohyl yura';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('ashikasubhashidam@gmail.com', 'Marketing Team');
        $mail->isHTML(true);

        foreach ($contacts as $contact) {
            $prompt = "
Generate a professional email with this:
Subject: $subject
About: $about
Contact Name: {$contact['name']}
Phone: {$contact['phone']}
Remark: {$contact['remarks']}

Include:
- Greeting using name
- Info about service
- Mention phone or remark if appropriate
- Close with CTA
- Add 2-3 hashtags
";

            $emailBody = generate_text($prompt);

            $mail->clearAddresses();
            $mail->addAddress($contact['email'], $contact['name']);
            $mail->Subject = $subject;
            $mail->Body = nl2br(trim($emailBody));

            $mail->send();
        }

        echo "✅ Email(s) sent successfully!";
    } catch (Exception $e) {
        echo "❌ Failed: " . $mail->ErrorInfo;
    }
}
?>
