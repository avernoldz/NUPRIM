<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

if (isset($_POST['add-eligibility'])) {
    $eligibility = $_POST['eligibility'];
    $sanitized_eligibility = mysqli_real_escape_string($conn, $eligibility);
    $rating = $_POST['rating'];
    $sanitized_rating = mysqli_real_escape_string($conn, $rating);
    $date = $_POST['date'];
    $sanitized_date = mysqli_real_escape_string($conn, $date);
    $place = $_POST['place'];
    $sanitized_place = mysqli_real_escape_string($conn, $place);
    $license = $_POST['license'];
    $sanitized_license = mysqli_real_escape_string($conn, $license);
    $vdate = $_POST['vdate'];
    $sanitized_vdate = mysqli_real_escape_string($conn, $vdate);
    $sdate = $_POST['sdate'];
    $sanitized_sdate = mysqli_real_escape_string($conn, $sdate);
    $edate = $_POST['edate'];
    $sanitized_edate = mysqli_real_escape_string($conn, $edate);
    $userid = $_POST['userid'];

    $insert = "INSERT INTO eligibility (userid, rating, eligibility, licenseNo, dateOfExam, placeOfExam, validity, dateStart, dateEnd) 
        VALUES ('$userid', '$sanitized_rating', '$sanitized_eligibility', '$sanitized_license', '$sanitized_date', '$sanitized_place' , '$sanitized_vdate', '$sanitized_sdate', '$sanitized_edate')";

    if (mysqli_query($conn, $insert)) {
        logAction($conn, $_SESSION['userid'], 'Add new eligibility', $_SESSION['type']);
        echo "<script>window.location.href='../eligibility.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
    } else {
        echo mysqli_error($conn);
    }
}

if (isset($_GET['delete'])) {
    $userid = $_GET['userid'];
    $eligibilityid = $_GET['eligibilityid'];

    $delete = "DELETE FROM eligibility WHERE eligibilityid = $eligibilityid";

    if (mysqli_query($conn, $delete)) {
        logAction($conn, $_SESSION['userid'], 'Delete eligibility', $_SESSION['type']);
        header("Location:../eligibility.php?userid=$userid&alert=success&message=Deleted Successfully");
    } else {
        echo mysqli_errno($conn);
    }
}
