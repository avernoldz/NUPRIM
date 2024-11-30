<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

if (isset($_POST['submit'])) {
    $entered = date('y-m-d', strtotime(mysqli_real_escape_string($conn, $_POST['entered'])));
    $permanency = date('y-m-d', strtotime(mysqli_real_escape_string($conn, $_POST['permanency'])));
    $appStatus = mysqli_real_escape_string($conn, $_POST['appStatus']);
    $lastPromotion = date('y-m-d', strtotime(mysqli_real_escape_string($conn, $_POST['lastPromotion'])));
    $stepIncrement = mysqli_real_escape_string($conn, $_POST['stepIncrement']);
    $lastStepIncrement = date('y-m-d', strtotime(mysqli_real_escape_string($conn, $_POST['lastStepIncrement'])));
    $userid = $_POST['userid'];

    $query = "UPDATE `service` SET `entered`='$entered', `permanency`='$permanency',
        `appStatus`='$appStatus',`lastPromotion`='$lastPromotion', `stepIncrement`='$stepIncrement',`lastStepIncrement`='$lastStepIncrement' WHERE userid = '$userid'";

    if (mysqli_query($conn, $query)) {
        logAction($conn, $_SESSION['userid'], 'Update service record', $_SESSION['type']);
        echo "<script>window.location.href='../service.php?userid=$userid&alert=success&message=Saved Successfully';</script>";
        exit();
    } else {
        echo mysqli_error($conn);
    }
}
