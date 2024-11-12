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

    <?php
    $adminid = $_SESSION['adminid'];
    $active = "Supervisors";
    $log = 0;
    include "../../Connections/Include.php";
    include "components/components.php";
    include "sideBar.php";

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


    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }

    ?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Supervisors</span></h1>
            </div>
        </div>

        <div class="row bg-[#ffffff] rounded-[4px] mt-3 shadow-[0_3px_5px_-3px_rgba(0,0,0,0.1)] p-[16px]">
            <button class="bg-[var(--primary-blue)] text-[#ffffff] w-[50px] rounded-[2px] p-[4px] hover:opacity-75 transition-all" data-bs-toggle="modal" data-bs-target="#newPersonnel"><i class="fa-solid fa-plus fa-fw"></i></button>
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
                    FROM supervisor
                    INNER JOIN account ON supervisor.userid = account.userid
                    INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
                    WHERE account.type = 'Supervisor'";
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
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Supervisor</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row column-gap-3">
                                                            <div class="col">
                                                                <label for="username" class="form-label">Username <span class="text-[red]">*</span></label>
                                                                <input type="text" class="form-control" value="<?php echo "$rows[username]" ?>" name="username" required>
                                                            </div>
                                                            <div class="col">
                                                                <label for="email" class="form-label">Email<span class="text-[red]">*</span></label>
                                                                <input type="email" class="form-control" value="<?php echo "$rows[email]" ?>" name="email" required>
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
                                                                <label for="firstname" class="form-label">Firstname <span class="text-[red]">*</span></label>
                                                                <input type="text" class="form-control" value="<?php echo "$rows[firstname]" ?>" name="firstname" required>
                                                            </div>
                                                            <div class="col">
                                                                <label for="lastname" class="form-label">Lastname <span class="text-[red]">*</span></label>
                                                                <input type="text" class="form-control" value="<?php echo "$rows[lastname]" ?>" name="lastname" required>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="userid" value="<?php echo $rows['userid'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary" name="edit-supervisor">Save</button>
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
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Account</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row column-gap-3">
                            <div class="col">
                                <label for="username" class="form-label">Username <span class="text-[red]">*</span></label>
                                <input type="text" id="username" class="form-control" name="username" required>
                            </div>
                            <div class="col">
                                <label for="email" class="form-label">Email<span class="text-[red]">*</span></label>
                                <input type="email" id="email" class="form-control" name="email" required>
                            </div>
                        </div>

                        <div class="row column-gap-3 mt-2">
                            <div class="col">
                                <label for="item" class="form-label">Item Number <span class="text-[red]">*</span></label>
                                <select type="text" id="item" class="form-control" name="item" required>
                                    <option disabled selected>Select Item Number</option>
                                    <?php
                                    foreach ($options as $option) {
                                        echo '<option value="' . htmlspecialchars($option['itemNumber']) . '">' . htmlspecialchars($option['itemNumber'] . ' - ' . $option['position']) . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <!-- <div class="row column-gap-3 mt-2">
                            <div class="col">
                                <label for="item" class="form-label">Chief <span class="text-[red]">*</span></label>
                                <input type="text" id="chief" class="form-control" name="chief" required>
                            </div>
                        </div> -->

                        <div class="row column-gap-3 mt-2" id="super">
                            <div class="col">
                                <label for="firstname" class="form-label">Firstname <span class="text-[red]">*</span></label>
                                <input type="text" id="firstname" class="form-control" name="firstname" required>
                            </div>
                            <div class="col">
                                <label for="lastname" class="form-label">Lastname <span class="text-[red]">*</span></label>
                                <input type="text" id="lastname" class="form-control" name="lastname" required>
                            </div>
                        </div>

                        <div class="row column-gap-3 mt-2">
                            <div class="col">
                                <label for="password" class="form-label">Password <span class="text-[red]">*</span></label>
                                <input type="password" id="password" class="form-control" name="password" required>
                            </div>
                            <div class="col">
                                <label for="cpassword" class="form-label">Confirm Password <span class="text-[red]">*</span></label>
                                <input type="password" id="cpassword" class="form-control" name="cpassword" required>
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
    $options = ['cost' => 12];
    if (isset($_POST['save'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $item = $conn->real_escape_string($_POST['item']);
        $type = 'Supervisor'; // This is a static value and doesn't need escaping
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $chief = $conn->real_escape_string($_POST['chief']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $isArchive = TRUE;

        // Check if the supervisor already exists
        $check_query = "SELECT * FROM account WHERE (username='$username' OR itemNumber='$item' OR email='$email')";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Supervisor already exists
            echo "<script>window.location.href='supervisor.php?alert=error&message=Username or email alreay taken';</script>";
            exit();
        }

        $hash_pass = password_hash($password, PASSWORD_BCRYPT, $options);

        $insert = "INSERT INTO account(username, itemNumber, type, email, password, isArchive)
                        VALUES('$username','$item','$type','$email','$hash_pass', '$isArchive')";

        if (mysqli_query($conn, $insert)) {
            $id = mysqli_insert_id($conn);

            $insert2 = "INSERT INTO supervisor(firstname, lastname, userid)
                        VALUES('$firstname','$lastname','$id')";

            $insert3 = "INSERT INTO `service`(userid)
                        VALUES('$id')";

            $insert4 = "INSERT INTO `assessed`(userid)
                        VALUES('$id')";
            mysqli_query($conn, $insert3);
            mysqli_query($conn, $insert4);

            if (mysqli_query($conn, $insert2)) {
                echo "<script>window.location.href='supervisor.php?alert=success&message=Supervisor Account addedd successfully';</script>";
                exit();
            } else {
                echo "<script>window.location.href='supervisor.php?alert=error&message=Error creating supervisor account';</script>";
            }
        } else {
            echo mysqli_error($conn);
        }
    }

    if (isset($_POST['edit-supervisor'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $item = $conn->real_escape_string($_POST['item']);
        $email = $conn->real_escape_string($_POST['email']);
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $chief = $conn->real_escape_string($_POST['chief']);
        $userid = $conn->real_escape_string($_POST['userid']);
        // Check if the supervisor already exists
        $check_query = "SELECT * FROM account WHERE username='$username'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Supervisor already exists
            echo "<script>window.location.href='supervisor.php?alert=error&message=Username or email alreay taken';</script>";
            exit();
        }

        $update = "UPDATE supervisor SET firstname = '$firstname', lastname = '$lastname' WHERE userid = '$userid'";
        $update2 = "UPDATE account SET username = '$username', email = '$email', itemNumber = '$item' WHERE userid = '$userid'";

        if (mysqli_query($conn, $update)) {
            if (mysqli_query($conn, $update2)) {
                echo "<script>window.location.href='supervisor.php?alert=success&message=Supervisor Account updating successfully';</script>";
                // exit();
            } else {
                // echo mysqli_error($conn);
                echo "<script>window.location.href='supervisor.php?alert=error&message=Error updating supervisor account';</script>";
            }
        } else {
            // echo mysqli_error($conn);
            echo "<script>window.location.href='supervisor.php?alert=error&message=Error updating supervisor account';</script>";
        }
    }
    ?>
    <script src="../JS/app.js"></script>
    <script>
        $('#table').DataTable({
            order: []
        });

        $('#myForm').validate({
            rules: {
                password: {
                    minlength: 8
                },
                cpassword: {
                    minlength: 8,
                    equalTo: "#password"
                }
            }
        });
    </script>
</body>

</html>