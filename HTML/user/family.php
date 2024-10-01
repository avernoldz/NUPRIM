<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:signin.php?login-first");
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
    <title>Family</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_SESSION['userid'];
    $active = "Family";
    $on = "hon";
    include "sideBar.php";
    include "../../Connections/Include.php";

    $query1 = "SELECT * FROM familybackground WHERE userid = '$userid'";
    $results1 = mysqli_query($conn, $query1);
    $row1 = mysqli_fetch_array($results1);
    ?>
    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">My Profile</span></h1>
            </div>
        </div>

        <div>
            <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
                <div class="col flex flex-wrap justify-between items-center">
                    <h1>Family</h1>
                    <button class="btn btn-danger edit text-[white] w-24" type="button" id="edit">Edit</button>
                    <div class="flex gap-x-[6px] flex-wrap justify-between items-center hidden" id="save-cancel">
                        <button class="btn btn-success w-24" type="submit" id="save" name="submit" form="forms">Save</button>
                        <button class="btn btn-danger w-24" type="button" id="cancel">Cancel</button>
                    </div>
                </div>
            </div>

            <form action="action/family.php" method="POST" id="forms">
                <div class="row bg column-gap-3">
                    <div class="col-12 mb-3">
                        <h2 class="text-[18px] font-bold">Father's Information</h2>
                    </div>
                    <div class="col-6 mb-2">
                        <label for="fathername" class="form-label">Firstname</label>
                        <input type="text" id="fathername" class="form-control" name="fathername" value="<?php echo emptyData("fatherFname", $row1) ?>" required disabled>
                    </div>

                    <div class="col mb-2">
                        <label for="fathermiddle" class="form-label">Middlename</label>
                        <input type="text" id="fathermiddle" class="form-control" name="fathermiddle" value="<?php echo emptyData("fatherMname", $row1) ?>" disabled>
                    </div>

                    <div class="col-9 mb-2">
                        <label for="fatherlast" class="form-label">Lastname</label>
                        <input type="text" id="fatherlast" class="form-control" name="fatherlast" value="<?php echo emptyData("fatherSrname", $row1) ?>" required disabled>
                    </div>
                    <div class="col mb-2">
                        <label for="fatherextension" class="form-label">Extension</label>
                        <input type="text" id="fatherextension" class="form-control" name="fatherextension" value="<?php echo emptyData("fatherExtension", $row1) ?>" disabled>
                    </div>

                    <div class="col-12 mb-3 mt-3">
                        <h2 class="text-[18px] font-bold">Mother's Information</h2>
                    </div>
                    <div class="col-6 mb-2">
                        <label for="mothername" class="form-label">Firstname</label>
                        <input type="text" id="mothername" class="form-control" name="mothername" value="<?php echo emptyData("motherFname", $row1) ?>" required disabled>
                    </div>

                    <div class="col mb-2">
                        <label for="mothermiddle" class="form-label">Middlename</label>
                        <input type="text" id="mothermiddle" class="form-control" name="mothermiddle" value="<?php echo emptyData("motherMname", $row1) ?>" disabled>
                    </div>

                    <div class="col-9 mb-2">
                        <label for="motherlast" class="form-label">Lastname</label>
                        <input type="text" id="motherlast" class="form-control" name="motherlast" value="<?php echo emptyData("motherSrname", $row1) ?>" required disabled>
                    </div>
                    <div class="col mb-2">
                        <label for="motherextension" class="form-label">Extension</label>
                        <input type="text" id="motherextension" class="form-control" name="motherextension" value="<?php echo emptyData("motherExtension", $row1) ?>" disabled>
                    </div>

                    <div class="col-12 mb-3 mt-3">
                        <h2 class="text-[18px] font-bold">Spouse's Information</h2>
                    </div>
                    <div class="col-6 mb-2">
                        <label for="spousename" class="form-label">Firstname</label>
                        <input type="text" id="spousename" class="form-control" name="spousename" value="<?php echo emptyData("spouseFname", $row1) ?>" disabled>
                    </div>

                    <div class="col mb-2">
                        <label for="spousemiddle" class="form-label">Middlename</label>
                        <input type="text" id="spousemiddle" class="form-control" name="spousemiddle" value="<?php echo emptyData("spouseMdname", $row1) ?>" disabled>
                    </div>

                    <div class="col-9 mb-2">
                        <label for="spouselast" class="form-label">Lastname</label>
                        <input type="text" id="spouselast" class="form-control" name="spouselast" value="<?php echo emptyData("spouseSrname", $row1) ?>" disabled>
                    </div>
                    <div class="col mb-2">
                        <label for="spouseextension" class="form-label">Extension</label>
                        <input type="text" id="spouseextension" class="form-control" name="spouseextension" value="<?php echo emptyData("spouseExtension", $row1) ?>" disabled>
                    </div>

                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                </div>
            </form>
        </div>

        <div>
            <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
                <div class="col flex flex-wrap justify-between items-center">
                    <h1>Children</h1>
                    <div id="div-child-btn">
                        <button data-bs-toggle="modal" data-bs-target="#newPersonnel" class="btn btn-success  text-[white] w-24" type="button" id="edit">Add</button>
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

                    $query2 = "SELECT * FROM children WHERE userid = '$userid'";
                    $results2 = mysqli_query($conn, $query2);

                    if (mysqli_num_rows($results2) > 0) {
                        while ($row2 = mysqli_fetch_array($results2)) {
                            $i = 1;
                    ?>
                            <div class="col-8 mb-2">
                                <label for="name" class="form-label">Fullname</label>
                                <input type="text" id="name" class="form-control" name="name<?php echo $i ?>" value="<?php echo emptyData("fullname", $row2) ?>" disabled>
                            </div>

                            <div class="col mb-2">
                                <label for="date" class="form-label">Date of Birth</label>
                                <input type="text" id="date" class="form-control" name="date<?php echo $i ?>" value="<?php echo emptyData("dateOfBirth", $row2) ?>" disabled>
                            </div>

                            <div class="col-1 mb-[8px] text-center">
                                <a href="action/family.php?childrenid=<?php echo $row2["childrenid"] ?>&userid=<?php echo $userid ?>&delete" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                            </div>

                            <div class="w-100"></div>

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

        <form action="action/family.php" method="POST" id="children">
            <div class="modal fade" id="newPersonnel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Children</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row column-gap-3">
                                <div class="col-9">
                                    <label for="name" class="form-label">Fullname <span class="text-[red]">*</span></label>
                                    <input type="text" id="name" class="form-control" name="name" required>
                                </div>
                                <div class="col">
                                    <label for="date" class="form-label">Date of Birth <span class="text-[red]">*</span></label>
                                    <input type="date" id="date" class="form-control" name="date" required>
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add-child">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

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