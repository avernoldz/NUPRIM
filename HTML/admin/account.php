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
    <title>Account</title>
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
    $active = "Account";
    $log = 0;
    include "../../Connections/Include.php";
    include "components/components.php";
    include "sideBar.php";

    if (isset($_GET['alert']) && $_GET['alert'] == '1') {
        echo '<script>var alertMessage = "Account has been added successfully!";</script>';
    } elseif (isset($_GET['alert']) && $_GET['alert'] == '2') {
        echo '<script>var alertMessage = "Account has been edited successfully!";</script>';
    }

    $pquery = "SELECT * FROM plantilla";
    $pres = mysqli_query($conn, $pquery);
    ?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Account</span></h1>
            </div>
        </div>

        <div class="row bg-[#ffffff] rounded-[4px] mt-3 shadow-[0_3px_5px_-3px_rgba(0,0,0,0.1)] p-[16px]">
            <button class="bg-[var(--primary-blue)] text-[#ffffff] w-[50px] rounded-[2px] p-[4px] hover:opacity-75 transition-all" data-bs-toggle="modal" data-bs-target="#newPersonnel"><i class="fa-solid fa-plus fa-fw"></i></button>
            <table id="table" class="display border-[1px] cell-border" style="width:100%">
                <thead class="bg-[var(--black-900)] text-[var(--black-400)]">
                    <tr>
                        <th>Username</th>
                        <th>Account Type</th>
                        <th>Item Number</th>
                        <th>Email</th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query1 = "SELECT * FROM account";
                    $results1 = mysqli_query($conn, $query1);

                    if (mysqli_num_rows($results1) > 0) {
                        while ($rows = mysqli_fetch_array($results1)) {
                            $random = create_random_string(4);
                    ?>
                            <tr>
                                <td><?php echo " $rows[username]" ?></td>
                                <td><?php echo " $rows[type]" ?></td>
                                <td><?php echo " $rows[itemNumber]" ?></td>
                                <td><?php echo " $rows[email]" ?></td>
                                <!-- <td class="text-center">
                                    <i class="fa-solid fa-pen fa-fw cursor-pointer" data-bs-toggle="modal" data-bs-target="#<?php echo $random ?>"></i>
                                </td> -->
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
                                    if (mysqli_num_rows($pres) > 0) {
                                        while ($rows = mysqli_fetch_array($pres)) {
                                    ?>
                                            <option value="<?php echo "$rows[itemNumber]" ?>"><?php echo "$rows[itemNumber] - $rows[position]" ?></option>
                                    <?php
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="type" class="form-label">Type <span class="text-[red]">*</span></label>
                                <select class="form-select" aria-label="Default select example" id="type" name="type">
                                    <option value="Admin">Admin</option>
                                    <!-- <option value="Supervisor">Supervisor</option>
                                    <option value="User" selected>User</option> -->
                                </select>
                            </div>
                        </div>

                        <div class="row column-gap-3 mt-2" id="super" style="display: none;">
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
                                <label for="password" class="form-label">Confirm Password <span class="text-[red]">*</span></label>
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
        $type = $conn->real_escape_string($_POST['type']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $lastname = $conn->real_escape_string($_POST['lastname']);

        $hash_pass = password_hash($password, PASSWORD_BCRYPT, $options);

        $insert = "INSERT INTO account(username, itemNumber, type, email, password)
                        VALUES('$username','$item','$type','$email','$hash_pass')";

        if (mysqli_query($conn, $insert)) {
            $id = mysqli_insert_id($conn);

            $insert2 = "INSERT INTO supervisor(firstname, lastname, userid)
                        VALUES('$firstname','$lastname','$id')";

            $insert3 = "INSERT INTO service(userid)
                        VALUES('$id')";
            mysqli_query($conn, $insert3);

            if (!mysqli_query($conn, $insert2)) {
                echo "<script>window.location.href='account.php?adminid=$adminid&alert=1';</script>";
                exit();
            }
        } else {
            echo mysqli_error($conn);
        }
    }

    ?>

    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                order: []
            });

            $('#type').change(function() {
                const type = $(this).val();

                if (type == 'Supervisor') {
                    $('#super').show();
                } else {
                    $('#super').hide();
                }
            })

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
        })
    </script>
</body>

</html>