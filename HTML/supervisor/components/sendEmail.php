<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

function sendEmail($email, $message, $title)
{
    // Check if the email was found
    if ($email) {

        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            // Server settings
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'gremoryyxx@gmail.com'; // SMTP username
            $mail->Password = 'lxnaxcxdslvzxhqi'; // SMTP password
            $mail->SMTPSecure = 'ssl'; // Enable SSL encryption
            $mail->Port = 465; // TCP port to connect to

            // Recipients
            $mail->setFrom('gremoryyxx@gmail.com', 'NEW ANNOUNCEMENT');
            $mail->addAddress($email); // Send the OTP to the email found in the database

            // Content
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body    = '<html>
                                <body>
                                    <p>' . nl2br($message) . '</p>
                                </body>
                                </html>';

            // Send the email
            $mail->send();

            // Return a success response
            return json_encode(['status' => 'valid', 'message' => 'Email sent']);
        } catch (Exception $e) {
            // In case of error, return the error message
            return json_encode(['status' => 'error', 'message' => 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
    } else {
        // If the email was not found, return an invalid email message
        return json_encode(['status' => 'invalid', 'message' => 'Invalid email address']);
    }

    // Close the statement
    $stmt->close();
}
