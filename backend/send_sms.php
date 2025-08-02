<?php
require __DIR__ . '/vendor/autoload.php'; // Twilio SDK

use Twilio\Rest\Client;

function sendSMSOTP($phone, $otp) {
    $sid = "your_twilio_sid";
    $token = "your_twilio_auth_token";
    $twilioNumber = "your_twilio_phone_number";

    $client = new Client($sid, $token);

    try {
        $client->messages->create(
            $phone,
            [
                'from' => $twilioNumber,
                'body' => "Your OTP code is: $otp"
            ]
        );
    } catch (Exception $e) {
        error_log("SMS sending failed: " . $e->getMessage());
    }
}
?>
