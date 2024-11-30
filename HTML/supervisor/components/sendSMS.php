<?php
// require_once '../../vendor/autoload.php';

use Twilio\Rest\Client;

function sendSms($phoneNumber, $message, $title = null)
{
    $sid = 'ACe9e13b0101c8c7c55d06528315d5404f';
    $token = '2c4f591e1aa26ff58c05d014dce8d6df';
    $client = new Client($sid, $token);

    $twilioNumber = '+15129692278';

    try {
        // Send SMS
        $messageSent = $client->messages->create(
            $phoneNumber, // Recipient's phone number
            [
                'from' => $twilioNumber, // Your Twilio number
                'body' => $message, // The message content
            ]
        );

        return true; // Return true if sent successfully
    } catch (Exception $e) {
        echo 'Error sending SMS: ' . $e->getMessage();
        return false; // Return false if there was an error
    }
}
