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
    <title>Plantilla</title>
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
    $active = "Plantilla";
    $log = 0;
    include "../../Connections/Include.php";
    include "components/components.php";
    include "sideBar.php";

    if (isset($_GET['alert']) && $_GET['alert'] == '1') {
        echo '<script>var alertMessage = "Plantilla has been added successfully!";</script>';
    } elseif (isset($_GET['alert']) && $_GET['alert'] == '2') {
        echo '<script>var alertMessage = "Plantilla has been edited successfully!";</script>';
    }

    ?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">Plantilla</span></h1>
            </div>
        </div>

        <div class="row bg-[#ffffff] rounded-[4px] mt-3 shadow-[0_3px_5px_-3px_rgba(0,0,0,0.1)] p-[16px]">
            <button class="bg-[var(--primary-blue)] text-[#ffffff] w-[50px] rounded-[2px] p-[4px] hover:opacity-75 transition-all" data-bs-toggle="modal" data-bs-target="#newPersonnel"><i class="fa-solid fa-plus fa-fw"></i></button>
            <table id="table" class="display border-[1px] cell-border" style="width:100%">
                <thead class="bg-[var(--black-900)] text-[var(--black-400)]">
                    <tr>
                        <th>Item Number</th>
                        <th>Position</th>
                        <th>Salary Grade</th>
                        <th>Monthly Salary</th>
                        <th>Designation</th>
                        <th>Office/Station</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query1 = "SELECT * FROM plantilla";
                    $results1 = mysqli_query($conn, $query1);

                    if (mysqli_num_rows($results1) > 0) {
                        while ($rows = mysqli_fetch_array($results1)) {
                            $random = create_random_string(4);
                    ?>
                            <tr>
                                <td><?php echo "$rows[itemNumber]" ?></td>
                                <td><?php echo "$rows[position]" ?></td>
                                <td><?php echo "SG - $rows[sgrade]" ?></td>
                                <td><?php echo "PHP " . number_format($rows['msalary'], 2) ?></td>
                                <td><?php echo "$rows[designation]" ?></td>
                                <td><?php echo "$rows[station]" ?></td>
                                <td class="text-center">
                                    <i class="fa-solid fa-pen fa-fw cursor-pointer" data-bs-toggle="modal" data-bs-target="#<?php echo $random ?>"></i>

                                    <form action="" method="POST" id="myForm">
                                        <div class="modal fade text-left" id="<?php echo $random ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Plantilla</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row column-gap-3">
                                                            <!-- <div class="col">
                                                            <label for="itemNumber" class="form-label">Item Number <span class="text-[red]">*</span></label>
                                                            <input type="text" id="itemNumber" class="form-control" name="itemNumber" required>
                                                        </div> -->
                                                            <div class="col-7">
                                                                <label for="position" class="form-label">Position</label>
                                                                <input type="text" id="position" class="form-control" name="position" value="<?php echo $rows['position'] ?>">
                                                            </div>
                                                        </div>

                                                        <div class="row column-gap-3 mt-2">
                                                            <div class="col">
                                                                <label for="sgrade" class="form-label">Salary Grade <span class="text-[red]">*</span></label>
                                                                <input type="text" id="sgrade" class="form-control" name="sgrade" value="<?php echo $rows['sgrade'] ?>" required>
                                                            </div>
                                                            <div class="col">
                                                                <label for="msalary" class="form-label">Monthly Salary <span class="text-[red]">*</span></label>
                                                                <input type="number" id="msalary" class="form-control" name="msalary" min="0" step="1" value="<?php echo $rows['msalary'] ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="row column-gap-3 mt-2">
                                                            <div class="col">
                                                                <label for="designation" class="form-label">
                                                                    Designation <span class="text-[red]">*</span>
                                                                </label>
                                                                <input type="text" id="designation" class="form-control" name="designation" value="<?php echo $rows['designation'] ?>" required>
                                                            </div>
                                                            <div class="col">
                                                                <label for="station" class="form-label">Office/Station <span class="text-[red]">*</span></label>
                                                                <input type="text" id="station" class="form-control" name="station" value="<?php echo $rows['station'] ?>" required>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="plantillaid" value="<?php echo $rows['plantillaid'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" name="edit-plantilla">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                    <?php
                        }
                    } ?>

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
                                <input type="number" id="sgrade" class="form-control" name="sgrade" required>
                            </div>
                            <div class="col">
                                <label for="msalary" class="form-label">Monthly Salary <span class="text-[red]">*</span></label>
                                <input type="number" id="msalary" class="form-control" name="msalary" min="0" step="1" required>
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
                                <label for="station" class="form-label">Office/Station <span class="text-[red]">*</span></label>
                                <input type="text" id="station" class="form-control" name="station" required>
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
        $itemNumber = htmlspecialchars($_POST['itemNumber'], ENT_QUOTES, 'UTF-8');
        $position = htmlspecialchars($_POST['position'], ENT_QUOTES, 'UTF-8');
        $sgrade = htmlspecialchars($_POST['sgrade'], ENT_QUOTES, 'UTF-8');
        $msalary = htmlspecialchars($_POST['msalary'], ENT_QUOTES, 'UTF-8');
        $designation = htmlspecialchars($_POST['designation'], ENT_QUOTES, 'UTF-8');
        $station = htmlspecialchars($_POST['station'], ENT_QUOTES, 'UTF-8');


        $insert = "INSERT INTO plantilla(itemNumber, position, sgrade, msalary, designation, station)
                        VALUES('$itemNumber','$position','$sgrade','$msalary','$designation', '$station')";

        if (mysqli_query($conn, $insert)) {
            echo "<script>window.location.href='plantilla.php?adminid=$adminid&alert=1';</script>";
            exit();
        } else {
            echo mysqli_error($conn);
        }
    }

    if (isset($_POST['edit-plantilla'])) {
        $id = htmlspecialchars($_POST['plantillaid'], ENT_QUOTES, 'UTF-8');
        $position = htmlspecialchars($_POST['position'], ENT_QUOTES, 'UTF-8');
        $sgrade = htmlspecialchars($_POST['sgrade'], ENT_QUOTES, 'UTF-8');
        $msalary = htmlspecialchars($_POST['msalary'], ENT_QUOTES, 'UTF-8');
        $designation = htmlspecialchars($_POST['designation'], ENT_QUOTES, 'UTF-8');
        $station = htmlspecialchars($_POST['station'], ENT_QUOTES, 'UTF-8');


        $insert = "UPDATE plantilla 
                    SET position = '$position', 
                        sgrade = '$sgrade', 
                        msalary = '$msalary', 
                        designation = '$designation', 
                        station = '$station' 
                    WHERE plantillaid = '$id'";

        if (mysqli_query($conn, $insert)) {
            echo "<script>window.location.href='plantilla.php?adminid=$adminid&alert=2';</script>";
            exit();
        } else {
            echo mysqli_error($conn);
        }
    }

    ?>

    <script src="../JS/app.js"></script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                order: []
            });
        })
    </script>
</body>

</html>