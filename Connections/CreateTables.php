<!DOCTYPE html>
<html>

<head>
	<title>IPCR</title>
</head>

<body>

	<?php
	include "Include.php";

	$options = ['cost' => 12,];


	$case = "CREATE TABLE `case`(
		caseid int AUTO_INCREMENT PRIMARY KEY,
		userid int NOT NULL,
		sunction varchar(150) NOT NULL,
		dateStart DATE NOT NULL,
		dateEnd DATE NOT NULL,
		uploadedDoc varchar(100)  NULL,
		authorityNo varchar(50)  NULL,
		authorityDate date  NULL,
		status varchar(50) NOT NULL
		)";

	if (mysqli_query($conn, $case)) {
		echo "Table case";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$service = "CREATE TABLE `service`(
		serviceid int AUTO_INCREMENT PRIMARY KEY,
		userid int NOT NULL,
		entered DATE NULL,
		appStatus varchar(150) NULL,
		permanency DATE NULL,
		lastPromotion DATE NULL,
		stepIncrement varchar(100) NULL,
		lastStepIncrement varchar(50) NULL
		)";

	if (mysqli_query($conn, $service)) {
		echo "Table service";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$details = "CREATE TABLE `detail`(
		detailid int AUTO_INCREMENT PRIMARY KEY,
		userid int NOT NULL,
		orderType varchar(150) NOT NULL,
		dateStart DATE NOT NULL,
		dateEnd DATE NOT NULL,
		office varchar(100) NOT NULL,
		uploadedDoc varchar(100)  NULL,
		authorityNo varchar(50)  NULL,
		authorityDate date  NULL,
		status varchar(50) NOT NULL
		)";

	if (mysqli_query($conn, $details)) {
		echo "Table details";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$ipcr = "CREATE TABLE `ipcr`(
		ipcrid int AUTO_INCREMENT PRIMARY KEY,
		userid int NOT NULL,
		year INT NOT NULL,
		semester varchar(50) NOT NULL,
		sdateA TEXT NOT NULL,
		sdateB TEXT NOT NULL,
		edateA TEXT NOT NULL,
		edateB TEXT NOT NULL,
		pmt varchar(100) NOT NULL,
		pmtPos varchar(100) NOT NULL,
		rater varchar(150) NOT NULL,
		core TEXT NULL,
		support TEXT NULL,
		comments varchar(250) NULL,
		action varchar(250) NULL,
		supervisorid int NOT NULL,
		status varchar(50) NULL,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
		)";

	if (mysqli_query($conn, $ipcr)) {
		echo "Table ipcr";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$leaves = "CREATE TABLE leaves(
		leaveid int(100) AUTO_INCREMENT PRIMARY KEY,
		userid int(100) NOT NULL,
		leaveType varchar(50) NOT NULL,
		dateStart date NOT NULL,
		dateEnd date NOT NULL,
		uploadedDoc varchar(100) NOT NULL,
		authorityNo varchar(50)  NULL,
		authorityDate date  NULL,
		status varchar(50) NOT NULL
		)";

	if (mysqli_query($conn, $leaves)) {
		echo "Table leaves";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}



	$userlogs = "CREATE TABLE userlogs(
		logsid int(100) AUTO_INCREMENT PRIMARY KEY,
		action varchar(50) NOT NULL,
		accountType varchar(50) NOT NULL,
		createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		userid int(100) NULL
		)";

	if (mysqli_query($conn, $userlogs)) {
		echo "Table userlogs";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}




	$supervisor = "CREATE TABLE supervisor(
		supervisorid int(100) AUTO_INCREMENT PRIMARY KEY,
		firstname varchar(50) NOT NULL,
		middlename varchar(50) NULL,
		lastname varchar(50) NOT NULL,
		userid int(100) NULL
		)";

	if (mysqli_query($conn, $supervisor)) {
		echo "Table supervisor";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$plantilla = "CREATE TABLE plantilla(
		plantillaid int(100) AUTO_INCREMENT PRIMARY KEY,
		itemNumber varchar(50) UNIQUE NOT NULL,
		position varchar(50) NOT NULL,
		sgrade varchar(50) NOT NULL,
		msalary varchar(50) NOT NULL,
		designation varchar(100) NOT NULL,
		station varchar(100) NOT NULL
		)";

	if (mysqli_query($conn, $plantilla)) {
		echo "Table plantilla";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$account = "CREATE TABLE account(
		userid int(100) AUTO_INCREMENT PRIMARY KEY,
		username varchar(50) NOT NULL,
		type varchar(50) NOT NULL,
		itemNumber varchar(50) NOT NULL,
		email varchar(50) NOT NULL,
		password varchar(100) NOT NULL
		)";

	if (mysqli_query($conn, $account)) {
		echo "Table account";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$hash_pass2 = password_hash("Admin123", PASSWORD_BCRYPT, $options);
	$query2 = "INSERT INTO account(password, username, email, type)
	                    VALUES('$hash_pass2', 'Administrator', 'admin@gmail.com' ,'Admin')";

	if (mysqli_query($conn, $query2)) {
		echo "Insert";
	} else {
		echo mysqli_error($conn);
	}

	$user = "CREATE TABLE user(
		infoid int(100) AUTO_INCREMENT PRIMARY KEY,
		userid int(100) NOT NULL,
		firstname varchar(50) NOT NULL,
		middlename varchar(50) NULL,
		lastname varchar(50) NOT NULL,
		gender varchar(20) NOT NULL,
		qualifier varchar(20) NOT NULL,
		status varchar(20) NOT NULL,
		dateOfBirth date NOT NULL,
		placeOfBirth varchar(50)NOT NULL,
		contactNumber varchar(20) NOT NULL,
		weight varchar(20) NOT NULL,
		height varchar(20) NOT NULL,
		bloodType varchar(20) NULL,
		GSIS varchar(50) NOT NULL,
		Pagibig varchar(50) NOT NULL,
		Philhealth varchar(50) NOT NULL,
		SSS varchar(50) NOT NULL,
		TIN varchar(50) NOT NULL,
		PNPID varchar(50) NOT NULL
		)";

	if (mysqli_query($conn, $user)) {
		echo "Table user";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$training = "CREATE TABLE training(
		trainingid int(100) AUTO_INCREMENT PRIMARY KEY,
		userid int(100) NOT NULL,
		name varchar(50) NOT NULL,
		dateStart date NOT NULL,
		dateEnd date NOT NULL,
		uploadedDoc varchar(100) NOT NULL,
		authorityNo varchar(50) NOT NULL,
		authorityDate date NOT NULL
		)";

	if (mysqli_query($conn, $training)) {
		echo "Table training";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$eligibility = "CREATE TABLE eligibility(
		eligibilityid int(100) AUTO_INCREMENT PRIMARY KEY,
		userid int(100) NOT NULL,
		eligibility varchar(50) NOT NULL,
		rating float(50) NOT NULL,
		dateOfExam date NOT NULL,
		placeOfExam varchar(50) NOT NULL,
		licenseNo varchar(50) NOT NULL,
		validity varchar(50) NOT NULL
		)";

	if (mysqli_query($conn, $eligibility)) {
		echo "Table eligibility";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$children = "CREATE TABLE children(
		childrenid int(100) AUTO_INCREMENT PRIMARY KEY,
		userid int(100) NOT NULL,
		fullname varchar(50) NOT NULL,
		dateOfBirth date NOT NULL
		)";

	if (mysqli_query($conn, $children)) {
		echo "Table children";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$familyBackground = "CREATE TABLE familyBackground(
		famid int(100) AUTO_INCREMENT PRIMARY KEY,
		userid int(100) NOT NULL,
		spouseFname varchar(50) NULL,
		spouseMdname varchar(50)  NULL,
		spouseSrname varchar(50)  NULL,
		spouseExtension varchar(50) NULL,
		fatherFname varchar(50) NOT NULL,
		fatherMname varchar(50) NULL,
		fatherSrname varchar(50) NOT NULL,
		fatherExtension varchar(50) NULL,
		motherFname varchar(50) NOT NULL,
		motherMname varchar(50) NULL,
		motherSrname varchar(50) NOT NULL,
		motherExtension varchar(50) NULL
		)";

	if (mysqli_query($conn, $familyBackground)) {
		echo "Table familyBackground";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$userAddress = "CREATE TABLE userAddress(
		useraddressid int(100) AUTO_INCREMENT PRIMARY KEY,
		userid int(100) NOT NULL,
		houseno varchar(50) NULL,
		barangay varchar(50) NOT NULL,
		city varchar(50) NOT NULL,
		province varchar(50) NOT NULL,
		region varchar(50) NOT NULL
		)";

	if (mysqli_query($conn, $userAddress)) {
		echo "Table userAddress";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$educationalBackground = "CREATE TABLE educationalBackground(
		educid int(100) AUTO_INCREMENT PRIMARY KEY,
		userid int(100) NOT NULL,
		level varchar(50) NOT NULL,
		nameOfSchool varchar(50)  NOT NULL,
		degree varchar(50)  NOT NULL,
		yearStarted date NOT NULL,
		yearEnded date NOT NULL,
		awardReceived varchar(50) NULL
		)";

	if (mysqli_query($conn, $educationalBackground)) {
		echo "Table educationalBackground";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	mysqli_close($conn);
	?>

</body>

</html>