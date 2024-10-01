<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";


if (isset($_POST['request'])) {

    $userid = $_POST['userid'];

    $firstName = selectName($conn, $userid);
    $tol = mysqli_real_escape_string($conn, $_POST['tol']);
    $edate = mysqli_real_escape_string($conn, $_POST['edate']);
    $sdate = mysqli_real_escape_string($conn, $_POST['sdate']);
    $status = 'Pending';

    $targetDir = "../uploads/";
    $file = $_FILES['uploadedDoc'];

    // Allowed file types
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel'];

    // Check if file type is allowed
    if (!in_array($file['type'], $allowedTypes)) {
        die("Error: Only JPG, PNG, DOCX, PDF, and XLSX files are allowed.");
    }

    // Create the upload directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Create a directory for the user's first name if it doesn't exist
    $userDir = $targetDir . $firstName . "/";
    if (!file_exists($userDir)) {
        mkdir($userDir, 0777, true);
    }

    $uploadedDoc = basename($file['name']);
    $newDate = date('jgsu');

    $uploadDoc = pathinfo($uploadedDoc, PATHINFO_FILENAME) . "_" . $newDate . "." . pathinfo($uploadedDoc, PATHINFO_EXTENSION);

    // Set the target file path
    $targetFile = $userDir . $uploadDoc;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // echo "The file " . htmlspecialchars(basename($file['name'])) . " has been uploaded to " . $userDir;

        $insert = "INSERT INTO leaves (userid, dateStart, dateEnd, uploadedDoc, leaveType, status) 
        VALUES ('$userid', '$sdate', '$edate', '$uploadDoc', '$tol', '$status')";

        if (mysqli_query($conn, $insert)) {
            logAction($conn, $_SESSION['userid'], 'File new leave', $_SESSION['type']);
            echo "<script>window.location.href='../leaves.php?userid=$userid&alert=1';</script>";
        } else {
            echo mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

if (isset($_GET['delete'])) {
    $userid = $_GET['userid'];
    $leaveid = $_GET['leaveid'];

    $delete = "DELETE FROM leaves WHERE leaveid = $leaveid";

    if (mysqli_query($conn, $delete)) {
        logAction($conn, $_SESSION['userid'], 'Delete leave', $_SESSION['type']);
        header("Location:../leaves.php?userid=$userid&alert=1");
    } else {
        echo mysqli_errno($conn);
    }
}
