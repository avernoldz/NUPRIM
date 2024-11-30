<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

if (isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $sanitized_firstname = mysqli_real_escape_string($conn, $firstname);
    $middlename = $_POST['middlename'];
    $sanitized_middlename = mysqli_real_escape_string($conn, $middlename);
    $lastname = $_POST['lastname'];
    $sanitized_lastname = mysqli_real_escape_string($conn, $lastname);
    $gender = $_POST['gender'];
    $sanitized_gender = mysqli_real_escape_string($conn, $gender);
    $date_of_birth = $_POST['date-of-birth'];
    $sanitized_birth = date('y-m-d', strtotime(mysqli_real_escape_string($conn, $date_of_birth)));
    $place_of_birth = $_POST['place-of-birth'];
    $sanitized_place = mysqli_real_escape_string($conn, $place_of_birth);
    $number = $_POST['cnumber'];
    $sanitized_number = mysqli_real_escape_string($conn, $number);
    $height = $_POST['height'];
    $sanitized_height = mysqli_real_escape_string($conn, $height);
    $weight = $_POST['weight'];
    $sanitized_weight = mysqli_real_escape_string($conn, $weight);
    $blood = $_POST['blood-type'];
    $sanitized_blood = mysqli_real_escape_string($conn, $blood);
    $qualifier = $_POST['qualifier'];
    $sanitized_qualifier = mysqli_real_escape_string($conn, $qualifier);
    $status = $_POST['status'];
    $sanitized_status = mysqli_real_escape_string($conn, $status);
    $gsis = $_POST['gsis'];
    $sanitized_gsis = mysqli_real_escape_string($conn, $gsis);
    $pag_ibig = $_POST['pag-ibig'];
    $sanitized_pag_ibig = mysqli_real_escape_string($conn, $pag_ibig);
    $philhealth = $_POST['philhealth'];
    $sanitized_philhealth = mysqli_real_escape_string($conn, $philhealth);
    $sss = $_POST['sss'];
    $sanitized_sss = mysqli_real_escape_string($conn, $sss);
    $tin = $_POST['tin'];
    $sanitized_tin = mysqli_real_escape_string($conn, $tin);
    $pnpid = $_POST['pnpid'];
    $sanitized_pnpid = mysqli_real_escape_string($conn, $pnpid);
    $userid = $_POST['userid'];

    $select = "SELECT * FROM user WHERE userid = '$userid'";
    $res = mysqli_query($conn, $select);

    if (empty($row = mysqli_fetch_array($res))) {

        $insert = "INSERT INTO `user`(`userid`, `firstname`, `middlename`, `lastname`,  `gender`, `qualifier`, `status`, `dateOfBirth`, `placeOfBirth`, `contactNumber`, `weight`, `height`, `bloodType`, `GSIS`, `Pagibig`, `Philhealth`, `SSS`, `TIN`, `PNPID`) 
        VALUES ('$userid','$sanitized_firstname','$sanitized_middlename','$sanitized_lastname','$sanitized_gender','$sanitized_qualifier','$sanitized_status',
        '$sanitized_birth','$sanitized_place','$sanitized_number','$sanitized_weight','$sanitized_height','$sanitized_blood','$sanitized_gsis',
        '$sanitized_pag_ibig','$sanitized_philhealth','$sanitized_sss','$sanitized_tin','$sanitized_pnpid')";

        if (mysqli_query($conn, $insert)) {
            logAction($conn, $_SESSION['userid'], 'Add personal information', $_SESSION['type']);
            echo "<script>window.location.href='../personalInformation.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
        } else {
            echo mysqli_error($conn);
        }
    } else {

        $query = "UPDATE `user` SET `firstname`='$sanitized_firstname', `middlename`='$sanitized_middlename',
    `lastname`='$sanitized_lastname',`gender`='$sanitized_gender', `dateOfBirth`='$sanitized_birth',`placeOfBirth`='$sanitized_place',`contactNumber`='$sanitized_number', 
    `height`='$sanitized_height', `weight`='$sanitized_weight', `bloodType` = '$sanitized_blood', `qualifier` = '$sanitized_qualifier', `status` = '$sanitized_status', `GSIS` = '$sanitized_gsis', 
    `Pagibig` = '$sanitized_pag_ibig', `Philhealth` = '$sanitized_philhealth', `SSS` = '$sanitized_sss', `TIN` = '$sanitized_tin', `PNPID` = '$sanitized_pnpid'  WHERE userid = '$userid'";

        if (mysqli_query($conn, $query)) {
            logAction($conn, $_SESSION['userid'], 'Update personal information', $_SESSION['type']);
            echo "<script>window.location.href='../personalInformation.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
        } else {
            echo mysqli_error($conn);
        }
    }
}
