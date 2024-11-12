<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:../index.php?login-first");
}

include_once "components/index.php";
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
    <title>Criminal Case</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_SESSION['userid'];
    $active = "Criminal Case";
    $on = "hon";
    include "sideBar.php";
    include "../../Connections/Include.php";

    ?>
    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">My Profile</span></h1>
            </div>
        </div>

        <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
            <div class="col flex flex-wrap justify-between items-center">
                <h1>Criminal Case</h1>
            </div>
        </div>

        <form action="action/address.php" method="POST" id="forms">
            <div class="row bg column-gap-3 items-end">
                <?php

                $query2 = "SELECT * FROM `case` WHERE userid = '$userid' ORDER BY dateStart DESC";
                $results2 = mysqli_query($conn, $query2);

                if (mysqli_num_rows($results2) > 0) {
                    while ($row2 = mysqli_fetch_array($results2)) {
                        $i = 1;
                ?>
                        <div class="col-12 mt-3 flex flex-wrap justify-between items-center">
                            <h2 class="text-[18px] font-bold"><?php echo $row2['sunction'] ?>
                                <?php
                                if ($row2['status'] == 'Pending') {
                                    echo '<label class="form-label bg-yellow-100 text-sm rounded p-1 ml-4 text-yellow-700 w-500 px-2 border-1 border-yellow-300">Pending</label>';
                                } elseif ($row2['status'] == 'Approve') {
                                    echo '<label class="form-label bg-green-100 text-sm rounded p-1 ml-4 text-green-700 w-500 px-2 border-1 border-green-300">Approved</label>';
                                } else {
                                    echo '<label class="form-label bg-red-100 text-sm rounded p-1 ml-4 text-red-700 w-500 px-2 border-1 border-red-300">Rejected</label>';
                                }
                                ?>
                            </h2>
                        </div>

                        <div class="row p-3 column-gap-3 w-100">
                            <div class="col mb-2">
                                <label for="sdate" class="form-label">Date Start</label>
                                <input type="text" id="sdate" class="form-control" name="sdate" value="<?php echo emptyData("dateStart", $row2) ?>" disabled>
                            </div>

                            <div class="col mb-2">
                                <label for="edate" class="form-label">Date End</label>
                                <input type="text" id="edate" class="form-control" name="edate" value="<?php echo emptyData("dateEnd", $row2) ?>" disabled>
                            </div>

                            <div class="w-100"></div>
                            <div class="col mb-2">
                                <label for="authno" class="form-label">Authority Number</label>
                                <input type="text" id="authno" class="form-control" name="authno" value="<?php echo emptyData("authorityNo", $row2) ?>" disabled>
                            </div>

                            <div class="col mb-2">
                                <label for="authdate" class="form-label">Authority Date</label>
                                <input type="text" id="authdate" class="form-control" name="authdate" value="<?php echo emptyData("authorityDate", $row2) ?>" disabled>
                            </div>
                        </div>
                <?php
                        $i++;
                    }
                } else {
                    echo "<p class='text-center'>No data</p>";
                }
                ?>
                <input type="hidden" name="userid" value="<?php echo $userid ?>">

            </div>
        </form>
    </div>

    <script>
        $('#edit').click(function() {
            $("#forms .country, #forms input").prop("disabled", false);
            // $("#forms :input").prop("disabled", false);
            $('#save-cancel').css("display", "block");
            $('#edit').css("display", "none");
        })

        $('#cancel').click(function() {
            $("#forms :input").prop("disabled", true);
            $('#save-cancel').css("display", "none");
            $('#edit').css("display", "block");
        })
    </script>
    <script src="../JS/app.js"></script>
</body>

</html>