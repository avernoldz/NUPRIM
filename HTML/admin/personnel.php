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
    <title>Home</title>
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
    <div class="loader loading hidden">
        <div class="justify-content-center jimu-primary-loading"></div>
    </div>
    <?php
    $adminid = $_SESSION['adminid'];
    $active = "Personnel";
    $log = 0;
    include "../../Connections/Include.php";
    include "components/components.php";
    include "sideBar.php";

    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }

    $opt = ['cost' => 12];
    if (isset($_POST['save'])) {
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $middlename = $conn->real_escape_string($_POST['middlename']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);

        $hash_pass = password_hash($password, PASSWORD_BCRYPT, $opt);

        $insert = "INSERT INTO user(firstname, middlename, lastname, email, password)
                        VALUES('$firstname','$middlename','$lastname','$email','$hash_pass')";

        if (mysqli_query($conn, $insert)) {
            echo "<script>window.location.href='personnel.php?adminid=$adminid&alert=1';</script>";
        } else {
            echo mysqli_error($conn);
        }
    }

    if (isset($_POST['edit-personnel'])) {
        // Escape all incoming form values to prevent SQL Injection
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $userid = $conn->real_escape_string($_POST['userid']);
        $item = $conn->real_escape_string($_POST['item']);

        // Build the UPDATE query
        $update_query = "UPDATE user SET 
                            firstname = '$firstname', 
                            lastname = '$lastname'
                        WHERE userid = '$userid'";

        $update_query = "UPDATE account SET 
                                itemNumber = '$item'
                                WHERE userid = '$userid'";

        // Execute the query
        if (mysqli_query($conn, $update_query)) {
            echo "<script>window.location.href='personnel.php?alert=success&message=Account updated successfully';</script>";
        } else {
            echo "<script>window.location.href='personnel.php?alert=error&message=Error updating account';</script>";
        }
    }

    $pquery = "SELECT * FROM plantilla";
    $pres = mysqli_query($conn, $pquery);

    $options = []; // Initialize an array to store options

    if (mysqli_num_rows($pres) > 0) {
        while ($rows2 = mysqli_fetch_array($pres)) {
            // Store the item number and position in the array
            $options[] = [
                'itemNumber' => $rows2['itemNumber'],
                'position' => $rows2['position'],
            ];
        }
    }

    ?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Personnel</span></h1>
            </div>
        </div>

        <div class="row bg-[#ffffff] rounded-[4px] mt-3 shadow-[0_3px_5px_-3px_rgba(0,0,0,0.1)] p-[16px]">
            <!-- <button class="bg-[var(--primary-blue)] text-[#ffffff] w-[50px] rounded-[2px] p-[4px] hover:opacity-75 transition-all" data-bs-toggle="modal" data-bs-target="#newPersonnel"><i class="fa-solid fa-plus fa-fw"></i></button> -->
            <table id="table" class="display border-[1px] cell-border" style="width:100%">
                <thead class="bg-[var(--black-900)] text-[var(--black-400)]">
                    <tr>
                        <th>Name</th>
                        <th>Item Number</th>
                        <th>Salary Grade</th>
                        <th>Designation</th>
                        <th>Office/Station</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query1 = "SELECT * 
                    FROM user
                    INNER JOIN account ON user.userid = account.userid
                    INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber WHERE account.isArchive = TRUE";
                    $results1 = mysqli_query($conn, $query1);

                    if (mysqli_num_rows($results1) > 0) {
                        while ($rows = mysqli_fetch_array($results1)) {
                            $random = create_random_string(4);
                            $middle = substr($rows['middlename'], 0, 1);
                    ?>
                            <tr>
                                <td><?php echo "$rows[firstname]";
                                    if (empty($rows['middlename'])) {
                                        echo "";
                                    } else {
                                        echo "  $middle.";
                                    }
                                    echo " $rows[lastname]" ?></td>
                                <td><?php echo "$rows[itemNumber]" ?></td>
                                <td><?php echo "$rows[sgrade]" ?></td>
                                <td><?php echo "$rows[designation]" ?></td>
                                <td><?php echo "$rows[station]" ?></td>
                                <td class="text-center">
                                    <i class="fa-solid fa-pen fa-fw cursor-pointer" data-bs-toggle="modal" data-bs-target="#<?php echo $random ?>"></i>

                                    <form action="" method="POST" id="myForm">
                                        <div class="modal fade text-left" id="<?php echo $random ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Personnel</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row column-gap-3">
                                                            <div class="col">
                                                                <label for="username" class="form-label">Firstname <span class="text-[red]">*</span></label>
                                                                <input type="text" class="form-control" value="<?php echo "$rows[firstname]" ?>" name="firstname" required>
                                                            </div>
                                                            <div class="col">
                                                                <label for="username" class="form-label">Lastname <span class="text-[red]">*</span></label>
                                                                <input type="text" class="form-control" value="<?php echo "$rows[lastname]" ?>" name="lastname" required>
                                                            </div>
                                                            <div class="col">
                                                                <label for="email" class="form-label">Salary Grade<span class="text-[red]">*</span></label>
                                                                <input type="number" class="form-control" value="<?php echo "$rows[sgrade]" ?>" name="sgrade" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row column-gap-3 mt-2">
                                                            <div class="col">
                                                                <label for="item" class="form-label">Item Number <span class="text-[red]">*</span></label>
                                                                <select type="text" class="form-control" name="item" required>
                                                                    <option disabled selected>Select Item Number</option>
                                                                    <option selected value="<?php echo "$rows[itemNumber]" ?>"><?php echo "$rows[itemNumber] - $rows[position]" ?></option>
                                                                    <?php
                                                                    foreach ($options as $option) {
                                                                        echo '<option value="' . htmlspecialchars($option['itemNumber']) . '">' . htmlspecialchars($option['itemNumber'] . ' - ' . $option['position']) . '</option>';
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row column-gap-3 mt-2" id="super">
                                                            <div class="col">
                                                                <label for="firstname" class="form-label">Designation <span class="text-[red]">*</span></label>
                                                                <input type="text" class="form-control" value="<?php echo "$rows[designation]" ?>" name="designation" disabled>
                                                            </div>
                                                            <div class="col">
                                                                <label for="lastname" class="form-label">Office <span class="text-[red]">*</span></label>
                                                                <input type="text" class="form-control" value="<?php echo "$rows[station]" ?>" name="station" disabled>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="userid" value="<?php echo $rows['userid'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" name="edit-personnel">Save</button>
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
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

    <form action="" method="POST" id="myForm">
        <div class="modal fade" id="newPersonnel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Account Requests</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row column-gap-3">
                            <div class="col">
                                <label for="firstname" class="form-label">Firstname <span class="text-[red]">*</span></label>
                                <input type="text" id="firstname" class="form-control" name="firstname" required>
                            </div>
                            <div class="col">
                                <label for="middlename" class="form-label">Middlename</label>
                                <input type="text" id="middlename" class="form-control" name="middlename">
                            </div>
                        </div>

                        <div class="row column-gap-3 mt-2">
                            <div class="col">
                                <label for="lastname" class="form-label">Lastname <span class="text-[red]">*</span></label>
                                <input type="text" id="lastname" class="form-control" name="lastname" required>
                            </div>
                            <div class="col">
                                <label for="email" class="form-label">Email <span class="text-[red]">*</span></label>
                                <input type="email" id="email" class="form-control" name="email" required>
                            </div>
                        </div>

                        <div class="row column-gap-3 mt-2">
                            <div class="col">
                                <label for="password" class="form-label">Password <span class="text-[red]">*</span></label>
                                <input type="password" id="password" class="form-control" name="password" required>
                            </div>
                            <div class="col">
                                <label for="password" class="form-label">Confirm Password <span class="text-[red]">*</span></label>
                                <input type="password" id="cpassword" class="form-control" name="cpassword" required>
                            </div>
                        </div>

                        <div class="row column-gap-3 mt-2">
                            <div class="col">
                                <label for="salary-grade" class="form-label">Salary Grade</label>
                                <input type="salary-grade" id="salary-grade" class="form-control" name="salary-grade" required>
                            </div>
                            <div class="col">
                                <label for="item-number" class="form-label">Item Number</label>
                                <input type="item-number" id="item-number" class="form-control" name="item-number" required>
                            </div>
                        </div>

                        <div class="row column-gap-3 mt-2">
                            <div class="col">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="designation" id="designation" class="form-control" name="designation" required>
                            </div>
                            <div class="col">
                                <label for="office" class="form-label">Office/Station</label>
                                <input type="office" id="office" class="form-control" name="office" required>
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
    <script>
        $('#table').DataTable({
            order: []
        });
    </script>
</body>

</html>