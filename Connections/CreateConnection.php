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

	if(!$conn) {
	    die("Connection Failed" . mysqli_connect_error($conn));
	}
	echo "Connected Successfully";
	?>

</body>

</html>