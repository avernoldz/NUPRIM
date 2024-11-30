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
    <title>Eligibility</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>
    <div class="loader loading hidden">
        <div class="justify-content-center jimu-primary-loading"></div>
    </div>
    <?php
    $userid = $_SESSION['userid'];
    $active = "Eligibility";
    $on = "hon";
    include "sideBar.php";
    include "../../Connections/Include.php";

    $query1 = "SELECT * FROM eligibility WHERE userid = '$userid'";
    $results1 = mysqli_query($conn, $query1);
    $row1 = mysqli_fetch_array($results1);

    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }
    ?>
    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">My Profile</span></h1>
            </div>
        </div>

        <div>
            <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
                <div class="col flex flex-wrap justify-between items-center">
                    <h1>Eligibility</h1>
                    <div id="div-child-btn">
                        <button data-bs-toggle="modal" data-bs-target="#newEducation" class="btn btn-success  text-[white] w-24" type="button" id="edit">Add</button>
                    </div>
                </div>
            </div>
            <form action="action/eligibility.php" method="POST" id="edit-eligibility">
                <div class="row bg column-gap-3 items-end">

                    <?php

                    $query2 = "SELECT * FROM eligibility WHERE userid = '$userid' ORDER BY dateOfExam DESC";
                    $results2 = mysqli_query($conn, $query2);

                    if (mysqli_num_rows($results2) > 0) {
                        while ($row2 = mysqli_fetch_array($results2)) {
                            $i = 1;
                    ?>
                            <div class="col-12 mt-3 flex flex-wrap justify-between items-center">
                                <h2 class="text-[18px] font-bold"><?php echo emptyData("eligibility", $row2) ?></h2>
                                <div class="col-1 mb-[8px] text-center">
                                    <a href="action/eligibility.php?eligibilityid=<?php echo $row2["eligibilityid"] ?>&userid=<?php echo $userid ?>&delete" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                                </div>
                            </div>

                            <div class="row p-3 column-gap-3 w-100">
                                <div class="col mb-2">
                                    <label for="rating" class="form-label">Rating</label>
                                    <input type="text" id="rating" class="form-control" name="rating" value="<?php echo emptyData("rating", $row2) ?>" disabled>
                                </div>


                                <div class="col mb-2">
                                    <label for="placeOfExam" class="form-label">Examination Place</label>
                                    <input type="text" id="placeOfExam" class="form-control" name="placeOfExam" value="<?php echo emptyData("placeOfExam", $row2) ?>" disabled>
                                </div>
                                <div class="w-100"></div>

                                <div class="col mb-2">
                                    <label for="dateOfExam" class="form-label">Examination Date</label>
                                    <input type="text" id="dateOfExam" class="form-control" name="dateOfExam" value="<?php echo emptyData("dateOfExam", $row2) ?>" disabled>
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
                                    <label for="licenseNo" class="form-label">License No.</label>
                                    <input type="text" id="licenseNo" class="form-control" name="licenseNo" value="<?php echo emptyData("licenseNo", $row2) ?>" disabled>
                                </div>

                                <div class="col mb-2">
                                    <label for="validity" class="form-label">Validity</label>
                                    <input type="text" id="validity" class="form-control" name="validity" value="<?php echo emptyData("validity", $row2) ?>" disabled>
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

        <form action="action/eligibility.php" method="POST" id="education">
            <div class="modal fade" id="newEducation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Eligibility</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row column-gap-3">
                                <div class="col-12 mb-2">
                                    <label for="eligibility" class="form-label">Eligibility</label>
                                    <input type="text" id="eligibility" class="form-control" name="eligibility">
                                </div>

                                <div class="col mb-2">
                                    <label for="rating" class="form-label">Rating </label>
                                    <input type="text" id="rating" class="form-control" name="rating">
                                </div>

                                <div class="col mb-2">
                                    <label for="date" class="form-label">Examination Date</label>
                                    <input type="date" id="date" class="form-control" name="date">
                                </div>

                                <div class="w-100"></div>
                                <div class="col mb-4">
                                    <label for="sdate" class="form-label">Date Start </label>
                                    <input type="date" id="sdate" class="form-control" name="sdate">
                                </div>

                                <div class="col mb-4">
                                    <label for="edate" class="form-label">Date End</label>
                                    <input type="date" id="edate" class="form-control" name="edate">
                                </div>

                                <div class="w-100"></div>

                                <div class="col mb-2">
                                    <label for="place" class="form-label">Examination Place</label>
                                    <input type="text" id="place" class="form-control" name="place">
                                </div>
                                <div class="w-100"></div>

                                <div class="col mb-2">
                                    <label for="license" class="form-label">License No.</label>
                                    <input type="text" id="license" class="form-control" name="license">
                                </div>

                                <div class="col mb-2">
                                    <label for="vdate" class="form-label">Validity Date</label>
                                    <input type="date" id="vdate" class="form-control" name="vdate">
                                </div>

                            </div>

                        </div>

                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add-eligibility">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script src="../JS/app.js"></script>
</body>

</html>