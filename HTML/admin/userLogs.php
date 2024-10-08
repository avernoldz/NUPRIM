<?php
session_start();
session_regenerate_id();

if (!isset($_SESSION['adminid'])) {
    header("Location:../index.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../Connections/cdn.php" ?>
    <link rel="stylesheet" href="../../CSS/root.css">
    <link rel="stylesheet" href="CSS/admin.css">
    <link rel="stylesheet" href="CSS/side-bar.css">
    <title>User Logs</title>
    <style>
        body {
            background: #efefef;
        }

        .error {
            color: var(--bs-danger);
        }
    </style>
</head>

<body>

    <?php
    $adminid = $_SESSION['adminid'];
    $active = "User Logs";
    $log = 0;
    include "../../Connections/Include.php";
    include "sideBar.php";

    ?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">User Logs</span></h1>
            </div>
        </div>

        <div class="row bg-[#ffffff] rounded-[4px] mt-3 shadow-[0_3px_5px_-3px_rgba(0,0,0,0.1)] p-[16px]">
            <!-- <button class="bg-[var(--primary-blue)] text-[#ffffff] w-[50px] rounded-[2px] p-[4px] hover:opacity-75 transition-all" data-bs-toggle="modal" data-bs-target="#newPersonnel"><i class="fa-solid fa-plus fa-fw"></i></button> -->
            <table id="table" class="display border-[1px] cell-border" style="width:100%">
                <thead class="bg-[var(--black-900)] text-[var(--black-400)]">
                    <tr>
                        <th>Account ID</th>
                        <th>Name</th>
                        <th>Account Type</th>
                        <th>Action</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT user.firstname, user.lastname, userlogs.*, account.*
                    FROM userlogs 
                    INNER JOIN account ON userlogs.userid = account.userid 
                    INNER JOIN user ON account.userid = user.userid 
                    ORDER BY userlogs.createdAt DESC";
                    $results = mysqli_query($conn, $query);

                    if (mysqli_num_rows($results) > 0) {
                        while ($rows = mysqli_fetch_array($results)) {
                            $formattedDate = date('F d, Y : h:i:s A', strtotime($rows['createdAt']));
                    ?>
                            <tr>
                                <td width="15%">
                                    <?php echo "$rows[userid]" ?>
                                </td>
                                <td width="25%">
                                    <?php echo "$rows[firstname] $rows[lastname] " ?>
                                </td>
                                <td width="15%">
                                    <?php echo "$rows[accountType]" ?>
                                </td>
                                <td width="20%">
                                    <?php echo "$rows[action]" ?>
                                </td>
                                <td width="25%">
                                    <?php echo "$formattedDate" ?>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>

    </div>

    <form action="" method="POST" id="myForm">
        <div class="modal fade" id="newPersonnel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Plantilla</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row column-gap-3">
                            <div class="col">
                                <label for="itemNumber" class="form-label">Item Number <span class="text-[red]">*</span></label>
                                <input type="text" id="itemNumber" class="form-control" name="itemNumber" required>
                            </div>
                            <div class="col">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" id="position" class="form-control" name="position">
                            </div>
                        </div>

                        <div class="row column-gap-3 mt-2">
                            <div class="col">
                                <label for="sgrade" class="form-label">Salary Grade <span class="text-[red]">*</span></label>
                                <input type="text" id="sgrade" class="form-control" name="sgrade" required>
                            </div>
                            <div class="col">
                                <label for="msalary" class="form-label">Monthly Salary <span class="text-[red]">*</span></label>
                                <input type="text" id="msalary" class="form-control" name="msalary" required>
                            </div>
                        </div>

                        <div class="row column-gap-3 mt-2">
                            <div class="col">
                                <label for="designation" class="form-label">
                                    Designation <span class="text-[red]">*</span>
                                </label>
                                <input type="text" id="designation" class="form-control" name="designation" required>
                            </div>
                            <div class="col">
                                <label for="unitAssignment" class="form-label">Unit Assignment <span class="text-[red]">*</span></label>
                                <input type="text" id="unitAssignment" class="form-control" name="unitAssignment" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="save">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <?php

    if (isset($_POST['save'])) {
        $itemNumber = $_POST['itemNumber'];
        $position = $_POST['position'];
        $sgrade = $_POST['sgrade'];
        $msalary = $_POST['msalary'];
        $designation = $_POST['designation'];
        $unitAssignment = $_POST['unitAssignment'];


        $insert = "INSERT INTO plantilla(itemNumber, position, sgrade, msalary, designation, unitAssignment)
                        VALUES('$itemNumber','$position','$position','$position','$designation', '$unitAssignment')";

        if (mysqli_query($conn, $insert)) {
            echo "<script>window.location.href='plantilla.php?adminid=$adminid&alert=1';</script>";
        } else {
            echo mysqli_error($conn);
        }
    }

    ?>

    <script>
        $('#table').DataTable({
            order: []
        });
    </script>
</body>

</html>