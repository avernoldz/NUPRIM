<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

if (isset($_POST['submit'])) {
    $region = $_POST['region'];
    $sanitized_region = mysqli_real_escape_string($conn, $region);
    $province = $_POST['province'];
    $sanitized_province = mysqli_real_escape_string($conn, $province);
    $city = $_POST['city'];
    $sanitized_city = mysqli_real_escape_string($conn, $city);
    $brgy = $_POST['brgy'];
    $sanitized_brgy = mysqli_real_escape_string($conn, $brgy);
    $houseno = $_POST['houseno'];
    $sanitized_houseno = mysqli_real_escape_string($conn, $houseno);
    $userid = $_POST['userid'];

    $select = "SELECT * FROM useraddress WHERE userid = '$userid'";
    $res = mysqli_query($conn, $select);

    if (empty($row = mysqli_fetch_array($res))) {
        $insert = "INSERT INTO useraddress (userid, houseno, barangay, city, province, region) 
        VALUES ('$userid', '$sanitized_houseno', '$sanitized_brgy', '$sanitized_city', '$sanitized_province', '$sanitized_region')";

        if (mysqli_query($conn, $insert)) {
            logAction($conn, $_SESSION['userid'], 'Add new address', $_SESSION['type']);
            echo "<script>window.location.href='../address.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
            exit();
        } else {
            echo mysqli_error($conn);
        }
    } else {
        $query = "UPDATE `useraddress` SET `region`='$sanitized_region', `province`='$sanitized_province',
        `city`='$sanitized_city',`barangay`='$sanitized_brgy', `houseno`='$sanitized_houseno' WHERE userid = '$userid'";

        if (mysqli_query($conn, $query)) {
            logAction($conn, $_SESSION['userid'], 'Update address', $_SESSION['type']);
            echo "<script>window.location.href='../address.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
            exit();
        } else {
            echo mysqli_error($conn);
        }
    }
}
