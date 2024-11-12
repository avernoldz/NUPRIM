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
    <title>Training</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_SESSION['userid'];
    $active = "Leave Records";
    $on = "hon";
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
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Leaves Records</span></h1>
            </div>
        </div>

        <div>
            <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
                <div class="col flex flex-wrap justify-between items-center">
                    <h1>Leaves Records</h1>
                    <div id="div-child-btn">
                        <button data-bs-toggle="modal" data-bs-target="#leaveRecords" class="btn btn-success  text-[white] w-24" type="button" id="edit">Add</button>
                    </div>
                </div>
            </div>
            <form action="action/leaves.php" method="POST" id="edit-training">
                <div class="row bg column-gap-3 items-end">
                    <?php

                    $query2 = "SELECT * FROM leaves WHERE userid = '$userid' ORDER BY dateStart DESC";
                    $results2 = mysqli_query($conn, $query2);

                    if (mysqli_num_rows($results2) > 0) {
                        while ($row2 = mysqli_fetch_array($results2)) {
                            $i = 1;
                    ?>
                            <div class="col-12 mb-3 mt-3 flex flex-wrap justify-between items-center">
                                <h2 class="text-[18px] font-bold"><?php echo $row2['leaveType'] ?>
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
                                <div class="col-1 mb-[8px] text-center">
                                    <a href="action/leaves.php?leaveid=<?php echo $row2["leaveid"] ?>&userid=<?php echo $userid ?>&delete" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                                </div>
                            </div>

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

        <form action="action/leaves.php" method="POST" id="training" enctype="multipart/form-data">
            <div class="modal fade" id="leaveRecords" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Request new leave</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row column-gap-3">
                                <div class="col mb-4">
                                    <label for="sdate" class="form-label">Date Start </label>
                                    <input type="date" id="sdate" class="form-control" name="sdate">
                                </div>

                                <div class="col mb-4">
                                    <label for="edate" class="form-label">Date End</label>
                                    <input type="date" id="edate" class="form-control" name="edate">
                                </div>

                                <div class="w-100"></div>

                                <div class="col mb-4">
                                    <label for="tol" class="form-label">Leave Type</label>
                                    <select type="text" id="tol" class="form-control" name="tol">
                                        <option>Sick Leave</option>
                                        <option>Mandatory Leave</option>
                                        <option>Service Leave</option>
                                        <option>Vacation Leave</option>
                                        <option>Special Privilage Leave</option>
                                        <option>Parental Leave</option>
                                        <option>Leave w/o Leave</option>
                                        <option>Maternity Leave</option>
                                        <option>AWOL</option>
                                    </select>
                                </div>

                                <div class="w-100"></div>

                                <div class="col mb-4">
                                    <label for="uploadedDoc" class="form-label">Upload File</label><br>
                                    <input type="file" id="uploadedDoc" class="form-control-file" name="uploadedDoc">
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="request">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script src="../JS/app.js"></script>
</body>

</html>