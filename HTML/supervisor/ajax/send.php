<?php
session_start();
require_once '../../../vendor/autoload.php';
include "../../../Connections/Include.php";
include "../components/index.php";
include "../components/sendEmail.php";
include "../components/sendSMS.php";

if (isset($_POST['announcement'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $id = $_SESSION['supervisorid'];
    $station = getSupervisorStation($conn, $id);
    $emails = getAllEmail($conn, $station);

    foreach ($emails['email'] as $data) {
        sendEmail($data, $message, $title);
    }

    $sql = "INSERT INTO announcement(title, `message`, userid) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("sss", $title, $message, $id);

    if ($stmt->execute()) {
        // $errorMessage = "Announcement created successfully";
        // echo "<script>window.location.href='../announcement.php?alert=success&message=" . urlencode($errorMessage) . "';</script>";
        echo "Success";
    } else {
        echo "Fail";
        $stmt->error();
    }
}

if (isset($_POST['sendSMS'])) {
    $phonenumbers = $_POST['phonenumbers'];
    $message = $_POST['message'];
    $id = $_SESSION['supervisorid'];

    if ($phonenumbers == 'all') {
        $allNumber = getPhonenumbers($conn, $station);

        foreach ($allNumber['phonenumbers'] as $phoneNumber) {
            // Format the phone number
            if (strpos($phoneNumber, '0') === 0) {
                $phoneNumber = '+63' . substr($phoneNumber, 1);
            }

            // Send SMS to the formatted phone number
            if (!sendSms($phoneNumber, $message)) {
                // echo "<script>window.location.href='../announcement.php?alert=error&message=Failed to send SMS to $phoneNumber';</script>";
                echo "Error";
                // return; // Stop further processing if there's an error
            } else {
                echo "Success";
                // $errorMessage = "SMS sent successfully";
                // echo "<script>window.location.href='../announcement.php?alert=success&message=" . urlencode($errorMessage) . "';</script>";
            }
        }
    } else {
        foreach ($phonenumbers as $phoneNumber) {
            // Format the phone number
            if (strpos($phoneNumber, '0') === 0) {
                $phoneNumber = '+63' . substr($phoneNumber, 1);
            }

            // Send SMS to the formatted phone number
            if (!sendSms($phoneNumber, $message)) {
                echo "Error";
                // echo "<script>window.location.href='../announcement.php?alert=error&message=Failed to send SMS to $phoneNumber';</script>";
                // return; // Stop further processing if there's an error
            } else {
                echo "Success";
                // $errorMessage = "SMS sent successfully";
                // echo "<script>window.location.href='../announcement.php?alert=success&message=" . urlencode($errorMessage) . "';</script>";
            }
        }
    }
}
