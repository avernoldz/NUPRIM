<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

if (isset($_POST['submit'])) {
    $fathername = $_POST['fathername'];
    $sanitized_fathername = mysqli_real_escape_string($conn, $fathername);
    $fathermiddle = $_POST['fathermiddle'];
    $sanitized_fathermiddle = mysqli_real_escape_string($conn, $fathermiddle);
    $fatherlast = $_POST['fatherlast'];
    $sanitized_fatherlast = mysqli_real_escape_string($conn, $fatherlast);
    $fatherextension = $_POST['fatherextension'];
    $sanitized_fatherextension = mysqli_real_escape_string($conn, $fatherextension);
    $mothername = $_POST['mothername'];
    $sanitized_mothername = mysqli_real_escape_string($conn, $mothername);
    $mothermiddle = $_POST['mothermiddle'];
    $sanitized_mothermiddle = mysqli_real_escape_string($conn, $mothermiddle);
    $motherlast = $_POST['motherlast'];
    $sanitized_motherlast = mysqli_real_escape_string($conn, $motherlast);
    $motherextension = $_POST['motherextension'];
    $sanitized_motherextension = mysqli_real_escape_string($conn, $motherextension);
    $spousename = $_POST['spousename'];
    $sanitized_spousename = mysqli_real_escape_string($conn, $spousename);
    $spousemiddle = $_POST['spousemiddle'];
    $sanitized_spousemiddle = mysqli_real_escape_string($conn, $spousemiddle);
    $spouselast = $_POST['spouselast'];
    $sanitized_spouselast = mysqli_real_escape_string($conn, $spouselast);
    $spouseextension = $_POST['spouseextension'];
    $sanitized_spouseextension = mysqli_real_escape_string($conn, $spouseextension);
    $userid = $_POST['userid'];

    $select = "SELECT * FROM familybackground WHERE userid = '$userid'";
    $res = mysqli_query($conn, $select);

    if (empty($row = mysqli_fetch_array($res))) {
        $insert = "INSERT INTO familybackground (userid, spouseFname, spouseMdname, spouseSrname, spouseExtension, fatherFname, fatherMname, fatherSrname, fatherExtension, motherFname, motherMname, motherSrname, motherExtension) 
        VALUES ('$userid', '$sanitized_spousename', '$sanitized_spousemiddle', '$sanitized_spouselast', '$sanitized_spouseextension', '$sanitized_fathername', '$sanitized_fathermiddle', '$sanitized_fatherlast', '$sanitized_fatherextension', '$sanitized_mothername', '$sanitized_mothermiddle', '$sanitized_motherlast', '$sanitized_motherextension')";

        if (mysqli_query($conn, $insert)) {
            logAction($conn, $_SESSION['userid'], 'Add family details', $_SESSION['type']);
            echo "<script>window.location.href='../family.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
        } else {
            echo mysqli_error($conn);
        }
    } else {
        $query = "UPDATE `familybackground` SET `fatherFname`='$sanitized_fathername', `fatherMname`='$sanitized_fathermiddle',
        `fatherSrname`='$sanitized_fatherlast',`fatherExtension`='$sanitized_fatherextension', `motherFname`='$sanitized_mothername',
         `motherMname`='$sanitized_mothermiddle',`motherSrname`='$sanitized_motherlast', `motherExtension`='$sanitized_motherextension',
         `spouseFname`='$sanitized_spousename', `spouseMdname`='$sanitized_spousemiddle',`spouseSrname`='$sanitized_spouselast', `spouseExtension`='$sanitized_spouseextension'
         WHERE userid = '$userid'";

        if (mysqli_query($conn, $query)) {
            logAction($conn, $_SESSION['userid'], 'Update family details', $_SESSION['type']);
            echo "<script>window.location.href='../family.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
        } else {
            echo mysqli_error($conn);
        }
    }
}

if (isset($_POST['add-child'])) {
    $name = $_POST['name'];
    $sanitized_name = mysqli_real_escape_string($conn, $name);
    $date = $_POST['date'];
    $sanitized_date = mysqli_real_escape_string($conn, $date);

    $userid = $_POST['userid'];

    $insert = "INSERT INTO children (userid, fullname, dateOfBirth) 
        VALUES ('$userid', '$sanitized_name', '$sanitized_date')";

    if (mysqli_query($conn, $insert)) {
        logAction($conn, $_SESSION['userid'], 'Add child details', $_SESSION['type']);
        echo "<script>window.location.href='../family.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
    } else {
        echo mysqli_error($conn);
    }
}


if (isset($_GET['delete'])) {
    $userid = $_GET['userid'];
    $childrenid = $_GET['childrenid'];

    $delete = "DELETE FROM children WHERE childrenid = $childrenid";

    if (mysqli_query($conn, $delete)) {
        logAction($conn, $_SESSION['userid'], 'Delete child details', $_SESSION['type']);
        header("Location:../family.php?userid=$userid&alert=success&message=Deleted Successfully");
    } else {
        echo mysqli_errno($conn);
    }
}
