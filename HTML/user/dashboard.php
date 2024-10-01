<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:signin.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../Connections/cdn.php" ?>
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="../../CSS/root.css">
    <link rel="stylesheet" href="CSS/side-bar.css">
    <title>Home</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_SESSION['userid'];
    $active = "Home";
    $on = "off";
    include "sideBar.php";
    include "../../Connections/Include.php";
    include "components/index.php";

    // $query1 = "SELECT COUNT(studentid) as total FROM student";
    // $results1 = mysqli_query($conn, $query1);
    // $row1 = mysqli_fetch_array($results1);

    // $query2 = "SELECT COUNT(facultyid) as total FROM faculty";
    // $results2 = mysqli_query($conn, $query2);
    // $row2 = mysqli_fetch_array($results2);
    ?>
    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">My Profile</span></h1>
            </div>
        </div>

        <div class="row" style="margin-top:16px;">
            <div class="col card justify-center h-[200px] bg-[var(--primary-yellow)]">
                <div class="row items-center text-[#ffffff]">
                    <div class="col">
                        <i class="fa-solid fa-user-group fa-fw text-[72px]"></i>
                    </div>
                    <div class="col flex flex-col items-end">
                        <h3>48</h3>
                        <h4 class="opacity-75">Total Personnels</h4>
                    </div>
                </div>
            </div>
            <div class="col card mid justify-center bg-[var(--primary-blue)]">
                <div class="row items-center text-[#ffffff]">
                    <div class="col">
                        <i class="fa-solid fa-user-lock fa-fw text-[72px]"></i>
                    </div>
                    <div class="col flex flex-col items-end">
                        <h3>4</h3>
                        <h4 class="opacity-75">Total Supervisors</h4>
                    </div>
                </div>
            </div>
            <div class="col card justify-center bg-[var(--primary-red)]">
                <div class="row items-center text-[#ffffff]">
                    <div class="col">
                        <i class="fa-solid fa-user-shield fa-fw text-[72px]"></i>
                    </div>
                    <div class="col flex flex-col items-end">
                        <h3>10</h3>
                        <h4 class="opacity-75">Total Plantilla</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row calendar">
            <div class="col">
                <div id="calendarContainer"></div>
            </div>
        </div>
    </div>
</body>

</html>