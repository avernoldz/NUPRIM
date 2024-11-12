<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";


if (isset($_POST['old'])) {
    $old = $_POST['old'];
    $input = $_POST['input'];

    if (password_verify($old, $input)) {
        echo "correct";
    } else {
        echo "invalid.";
    }
}

if (isset($_POST['submit'])) {
    $options = ['cost' => 12,];

    // Retrieve and escape POST data
    $userid = $_SESSION['userid'];
    $name = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $old_password = isset($_POST['opassword']) ? mysqli_real_escape_string($conn, $_POST['opassword']) : '';
    $new_password = isset($_POST['npassword']) ? mysqli_real_escape_string($conn, $_POST['npassword']) : '';

    if ($new_password != '') {
        $hash_pass = password_hash("$new_password", PASSWORD_BCRYPT, $options);
        $password = $hash_pass;
    } else {
        $hash_pass = password_hash("$old_password", PASSWORD_BCRYPT, $options);
        $password = $hash_pass;
    }


    $stmt = $conn->prepare("UPDATE account SET `username` = ?, email = ?, `password` = ? WHERE userid = ?");
    $stmt->bind_param("ssss", $name, $email, $password, $userid);

    // Execute the statement
    $errorMessage = "Account changes saved succesfully";
    echo "<script>window.location.href='../settings.php?alert=success&message=" . urlencode($errorMessage) . "';</script>";

    $stmt->execute();
    $stmt->close();
}
