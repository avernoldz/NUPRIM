<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";


if (isset($_POST['add-training'])) {

    $userid = $_POST['userid'];

    $firstName = selectName($conn, $userid);
    $name = $_POST['name'];
    $sanitized_name = mysqli_real_escape_string($conn, $name);
    $sdate = $_POST['sdate'];
    $sanitized_sdate = mysqli_real_escape_string($conn, $sdate);
    $edate = $_POST['edate'];
    $sanitized_edate = mysqli_real_escape_string($conn, $edate);
    $authno = $_POST['authno'];
    // $sanitized_authno = mysqli_real_escape_string($conn, $authno);
    // $authdate = $_POST['authdate'];
    // $sanitized_authdate = mysqli_real_escape_string($conn, $authdate);

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
    $targetFile = $userDir .  $uploadDoc;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // echo "The file " . htmlspecialchars(basename($file['name'])) . " has been uploaded to " . $userDir;

        $insert = "INSERT INTO training (userid, dateStart, name, dateEnd, uploadedDoc) 
        VALUES ('$userid', '$sanitized_sdate', '$sanitized_name', '$sanitized_edate', '$uploadDoc')";

        if (mysqli_query($conn, $insert)) {
            logAction($conn, $_SESSION['userid'], 'Add new training details', $_SESSION['type']);
            echo "<script>window.location.href='../training.php?userid=$userid&alert=1';</script>";
        } else {
            echo mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

if (isset($_GET['delete'])) {
    $userid = $_GET['userid'];
    $trainingid = $_GET['trainingid'];

    $delete = "DELETE FROM training WHERE trainingid = $trainingid";

    if (mysqli_query($conn, $delete)) {
        logAction($conn, $_SESSION['userid'], 'Delete training details', $_SESSION['type']);
        header("Location:../training.php?userid=$userid&alert=1");
    } else {
        echo mysqli_errno($conn);
    }
}
