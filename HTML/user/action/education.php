<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

if (isset($_POST['add-education'])) {
    $school = $_POST['school'];
    $sanitized_school = mysqli_real_escape_string($conn, $school);
    $level = $_POST['level'];
    $sanitized_level = mysqli_real_escape_string($conn, $level);
    $start = $_POST['start'];
    $sanitized_start = mysqli_real_escape_string($conn, $start);
    $end = $_POST['end'];
    $sanitized_end = mysqli_real_escape_string($conn, $end);
    $degree = $_POST['degree'];
    $sanitized_degree = mysqli_real_escape_string($conn, $degree);
    $award = $_POST['award'];
    $sanitized_award = mysqli_real_escape_string($conn, $award);
    $userid = $_POST['userid'];

    $insert = "INSERT INTO educationalBackground (userid, level, nameOfSchool, degree, yearStarted, yearEnded, awardReceived) 
        VALUES ('$userid', '$sanitized_level', '$sanitized_school', '$sanitized_degree', '$sanitized_start', '$sanitized_end' , '$sanitized_award')";

    if (mysqli_query($conn, $insert)) {
        logAction($conn, $_SESSION['userid'], 'Add new education', $_SESSION['type']);
        echo "<script>window.location.href='../education.php?userid=$userid&alert=1';</script>";
    } else {
        echo mysqli_error($conn);
    }
}

if (isset($_GET['delete'])) {
    $userid = $_GET['userid'];
    $educid = $_GET['educid'];

    $delete = "DELETE FROM educationalBackground WHERE educid = $educid";

    if (mysqli_query($conn, $delete)) {
        logAction($conn, $_SESSION['userid'], 'Delete education', $_SESSION['type']);
        header("Location:../education.php?userid=$userid&alert=1");
    } else {
        echo mysqli_errno($conn);
    }
}
