<!DOCTYPE html>
<html>

<head>
	<title>Connect</title>
</head>

<body>

	<?php
	$servername = "localhost";
	$username = "root";
	$pass = "";

	$conn = mysqli_connect($servername, $username, $pass);

	if (!$conn) {
		die("Connection Failed" . mysqli_connect_error($conn));
	}

	$db = "CREATE DATABASE NUPRIM";
	if (mysqli_query($conn, $db)) {
		echo "Database Created";
	} else {
		echo "Error creating database: " . mysqli_error($conn);
	}

	mysqli_close($conn);
	?>

	<h1>Hello</h1>
</body>

</html>