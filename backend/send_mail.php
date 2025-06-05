<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

function sendEmailOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP Server
        $mail->SMTPAuth = true;
        $mail->Username = 'ashikasubhashidam@gmail.com'; // Your email
        $mail->Password = 'hach pgvr ohyl yura'; // Your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('ashikasubhashidam@gmail.com', 'AI Marketing Automation');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP Code';

        // Enable HTML email format
        $mail->isHTML(true); 

        // Email Body with Clickable Link
        $mail->Body = "
            <p>We received a request to reset the password for the AI MARKETING AUTOMATION user associated with this email address.</p>
            <p>If you did not request to reset this password, you can ignore this request.</p>
            <p><a href='http://localhost/ai_project/frontend/verify_reset_password.html' style='color: blue; text-decoration: underline;'>Click Here</a> to reset the password for your account.</p>
            <p><strong>Your OTP code is: $otp</strong></p>
            <p>Best regards,<br>AI MARKETING AUTOMATION Team</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
    }
}
?>
