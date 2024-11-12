<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";


if (isset($_POST['save']) && isset($_POST['function'])) {

    $userid = mysqli_real_escape_string($conn, $_POST['userid']);
    $dateSubmitted = mysqli_real_escape_string($conn, $_POST['dateSubmitted']);
    $dateSubmission = mysqli_real_escape_string($conn, $_POST['dateSubmission']);
    $ipcrid = mysqli_real_escape_string($conn, $_POST['ipcrid']);
    $function = mysqli_real_escape_string($conn, $_POST['function']);

    $firstName = selectName($conn, $userid);

    $targetDir = "../uploads/";
    $file = $_FILES['uploadedDoc'];

    // Allowed file types
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel'];

    // Check if file type is allowed
    if (!in_array($file['type'], $allowedTypes)) {
        echo "<script>window.location.href='../ipcrDocuments.php?userid=$userid&alert=error&message=Only JPG, PNG, DOCX, PDF, and XLSX files are allowed.';</script>";
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
    $targetFile = $userDir .  $uploadDoc;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {

        $insert = "INSERT INTO ipcrdoc (userid, dateSubmitted, dateSubmission, ipcrid, functionid, uploadedDoc) 
        VALUES ('$userid', '$dateSubmitted', '$dateSubmission', '$ipcrid', '$function', '$uploadDoc')";

        if (mysqli_query($conn, $insert)) {
            logAction($conn, $_SESSION['userid'], 'Upload file for ipcr', $_SESSION['type']);
            echo "<script>window.location.href='../ipcrDocuments.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
        } else {
            echo "<script>window.location.href='../ipcrDocuments.php?userid=$userid&alert=error&message=Sorry, there was an error uploading your file.';</script>";
        }
    } else {
        echo "<script>window.location.href='../ipcrDocuments.php?userid=$userid&alert=error&message=Sorry, there was an error uploading your file.';</script>";
    }
} else {
    echo "<script>window.location.href='../ipcrDocuments.php?userid=$userid&alert=error&message=Sorry, there was an error uploading your file.';</script>";
}

if (isset($_GET['delete'])) {
    $userid = $_GET['userid'];
    $trainingid = $_GET['trainingid'];

    $delete = "DELETE FROM training WHERE trainingid = $trainingid";

    if (mysqli_query($conn, $delete)) {
        logAction($conn, $_SESSION['userid'], 'Delete training details', $_SESSION['type']);
        header("Location:../training.php?userid=$userid&alert=success&message=Deleted Successfully");
    } else {
        echo mysqli_errno($conn);
    }
}
