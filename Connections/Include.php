<?php
$servername = "localhost";
$username = "root";
$pass = "";
$db = "nuprim";

$conn = mysqli_connect($servername, $username, $pass, $db);

if (!$conn) {
    die("Connection Failed" . mysqli_connect_error($conn));
}
