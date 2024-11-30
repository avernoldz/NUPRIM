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
    <title>IPCR</title>
    <style>
        body {
            background: #efefef;
        }

        .ipcr:nth-of-type(odd) {
            background-color: #f3f9ff;
        }

        .ipcr:hover {
            box-shadow: inset 1px 0 0 #dadce0, inset -1px 0 0 #dadce0, 2px 2px 2px 0 rgba(60, 64, 67, .3), 0 1px 3px 1px rgba(60, 64, 67, .15);
            z-index: 2;
        }
    </style>
</head>

<body>
    <div class="loader loading hidden">
        <div class="justify-content-center jimu-primary-loading"></div>
    </div>
    <?php
    $userid = $_SESSION['userid'];
    $active = "IPCR";
    $on = "on";
    include "sideBar.php";
    include "../../Connections/Include.php";

    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }

    ?>

    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">IPCR</span></h1>
            </div>
        </div>

        <div>
            <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
                <div class="col flex flex-wrap justify-between items-center">
                    <h1>IPCR Data</h1>
                    <div id="div-child-btn">
                        <a href="generate.php?userid=<?php echo $userid ?>"><button class="btn btn-success  text-[white] w-24" type="button" id="edit">Generate</button></a>
                    </div>
                </div>
            </div>
            <form action="action/training.php" method="POST" id="edit-training">
                <div class="row bg column-gap-3 items-end">
                    <?php

                    $query2 = "SELECT * FROM ipcr WHERE userid = '$userid' ORDER BY created_at DESC";
                    $results2 = mysqli_query($conn, $query2);

                    if (mysqli_num_rows($results2) > 0) {
                        while ($row2 = mysqli_fetch_array($results2)) {
                            $i = 1;

                            if ($row2['status'] == 'Waiting for Approval') {
                                $stat = '<label class="form-label bg-yellow-100 text-xs rounded p-1 mb-0 text-yellow-700 w-500 px-2 border-1 border-yellow-300">Pending</label>';
                            } elseif ($row2['status'] == 'Approved') {
                                $stat = '<label class="form-label bg-green-100 text-xs rounded p-1 mb-0 text-green-700 w-500 px-2 border-1 border-green-300">Approved</label>';
                            } else {
                                $stat = '<label class="form-label bg-red-100 text-xs rounded p-1 mb-0 text-red-700 w-500 px-2 border-1 border-red-300">Rejected</label>';
                            }
                    ?>
                            <div class="ipcr col-12 p-2 flex flex-wrap justify-between items-center bg-[#e4f2ff]">
                                <h2 class="font-bold ml-5">IPCR -
                                    <span class="font-normal"><?php echo emptyData("semester", $row2) . ' (' . emptyData("year", $row2) . ')' ?></span>
                                </h2>
                                <?php if ($row2['status'] == 'Approved'): ?>
                                    <h2 class="font-bold">
                                        <span class="font-normal">Rating - </span> <?php echo emptyData("finalRating", $row2) ?>
                                    </h2>
                                <?php endif; ?>
                                <?php if ($row2['status'] == 'Rejected'): ?>
                                    <h2 class="font-bold">
                                        <span class="font-normal">Reason </span> <?php echo emptyData("rejectReason", $row2) ?>
                                    </h2>
                                <?php endif; ?>
                                <div class="col-3 text-center">
                                    <span class="mr-5"><?php echo $stat; ?></span>
                                    <a href="viewipcr.php?ipcrid=<?php echo $row2["ipcrid"] ?>&userid=<?php echo $userid ?>&delete" class=" hover:bg-gray-300 hover:rounded-full p-2"><i class="fa-regular fa-eye fa-fw text-gray-600"></i></a>
                                    <!-- <a href="action/ipcr.php?ipcrid=<?php echo $row2["ipcrid"] ?>&userid=<?php echo $userid ?>&delete" class="ml-3 hover:bg-gray-300 hover:rounded-full p-2"><i class="fa-regular fa-trash-can fa-fw text-gray-600"></i></a> -->
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
    </div>
    <script src="../JS/app.js"></script>
</body>

</html>