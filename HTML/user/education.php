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
    <title>Education</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_SESSION['userid'];
    $active = "Education";
    $on = "hon";
    include "sideBar.php";
    include "../../Connections/Include.php";

    $query1 = "SELECT * FROM educationalBackground WHERE userid = '$userid'";
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
                    <h1>Education</h1>
                    <div id="div-child-btn">
                        <button data-bs-toggle="modal" data-bs-target="#newEducation" class="btn btn-success  text-[white] w-24" type="button" id="edit">Add</button>
                        <!-- <button class="btn btn-danger child-btn text-[white] w-24" type="button" id="edit">Edit</button> -->
                    </div>

                    <div class="flex gap-x-[6px] flex-wrap justify-between items-center hidden" id="save-cancel-child">
                        <button class="btn btn-success w-24" type="submit" id="save" name="edit-children" form="edit-child">Save</button>
                        <button class="btn btn-danger w-24" type="button" id="cancel-child">Cancel</button>
                    </div>
                </div>
            </div>
            <form action="action/family.php" method="POST" id="edit-child">
                <div class="row bg column-gap-3 items-end">

                    <?php

                    $query2 = "SELECT * FROM educationalBackground WHERE userid = '$userid'";
                    $results2 = mysqli_query($conn, $query2);

                    if (mysqli_num_rows($results2) > 0) {
                        while ($row2 = mysqli_fetch_array($results2)) {
                            $i = 1;
                    ?>
                            <div class="col-12 mt-3 flex flex-wrap justify-between items-center">
                                <h2 class="text-[18px] font-bold"><?php echo emptyData("level", $row2) ?></h2>
                                <div class="col-1 mb-[8px] text-center">
                                    <a href="action/education.php?educid=<?php echo $row2["educid"] ?>&userid=<?php echo $userid ?>&delete" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                                </div>
                            </div>
                            <div class="row p-3 column-gap-3 w-100">
                                <div class="col-12 mb-2">
                                    <label for="school" class="form-label">Name of School</label>
                                    <input type="text" id="school" class="form-control" name="school" value="<?php echo emptyData("nameOfSchool", $row2) ?>" disabled>
                                </div>

                                <div class="col mb-2">
                                    <label for="level" class="form-label">Level</label>
                                    <input type="text" id="level" class="form-control" name="level" value="<?php echo emptyData("level", $row2) ?>" disabled>
                                </div>

                                <div class="col mb-2">
                                    <label for="start" class="form-label">Year Started</label>
                                    <input type="text" id="start" class="form-control" name="start" value="<?php echo emptyData("yearStarted", $row2) ?>" disabled>
                                </div>

                                <div class="col mb-2">
                                    <label for="end" class="form-label">Year Ended</label>
                                    <input type="text" id="end" class="form-control" name="end" value="<?php echo emptyData("yearEnded", $row2) ?>" disabled>
                                </div>
                                <div class="w-100"></div>

                                <div class="col-6 mb-2">
                                    <label for="degree" class="form-label">Degree/Course</label>
                                    <input type="text" id="degree" class="form-control" name="degree" value="<?php echo emptyData("degree", $row2) ?>" disabled>
                                </div>

                                <div class="col mb-2">
                                    <label for="award" class="form-label">Awards/Achievements</label>
                                    <input type="text" id="award" class="form-control" name="award" value="<?php echo emptyData("awardReceived", $row2) ?>" disabled>
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

        <form action="action/education.php" method="POST" id="education">
            <div class="modal fade" id="newEducation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Education</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row column-gap-3">
                                <div class="col-12 mb-2">
                                    <label for="school" class="form-label">Name of School</label>
                                    <input type="text" id="school" class="form-control" name="school">
                                </div>

                                <div class="col mb-2">
                                    <label for="end" class="form-label">Level</label>
                                    <select class="form-select" aria-label="Default select example" id="level" name="level">
                                        <option value="Primary School" selected>Primary School</option>
                                        <option value="High School">High School</option>
                                        <option value="College Under Graduate">College Under Graduate</option>
                                        <option value="College Graduate">College Graduate</option>
                                        <option value="Bachelors Degree">Bachelors Degree</option>
                                        <option value="Masteral Degree">Masteral Degree</option>
                                        <option value="Doctoral Degree">Doctoral Degree</option>
                                    </select>
                                </div>

                                <div class="col mb-2">
                                    <label for="start" class="form-label">Year Started</label>
                                    <input type="date" id="start" class="form-control" name="start">
                                </div>

                                <div class="col mb-2">
                                    <label for="end" class="form-label">Year Ended</label>
                                    <input type="date" id="end" class="form-control" name="end">
                                </div>
                                <div class="w-100"></div>

                                <div class="col mb-2">
                                    <label for="degree" class="form-label">Degree/Course</label>
                                    <input type="text" id="degree" class="form-control" name="degree">
                                </div>


                                <div class="col mb-2">
                                    <label for="award" class="form-label">Awards/Achievements</label>
                                    <input type="text" id="award" class="form-control" name="award">
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add-education">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script src="../JS/app.js"></script>
        <script>
            $('#edit').click(function() {
                $("#forms .country, #forms input").prop("disabled", false);
                $('#save-cancel').css("display", "block");
                $('#edit').css("display", "none");
            })

            $('#cancel').click(function() {
                $("#forms :input").prop("disabled", true);
                $('#save-cancel').css("display", "none");
                $('#edit').css("display", "block");
            })

            $('.child-btn').click(function() {
                $("#edit-child .country, #edit-child input").prop("disabled", false);
                $('#save-cancel-child').css("display", "block");
                $('#div-child-btn').css("display", "none");
            })

            $('#cancel-child').click(function() {
                $("#edit-child :input").prop("disabled", true);
                $('#save-cancel-child').css("display", "none");
                $('#div-child-btn').css("display", "block");
            })
        </script>
</body>

</html>