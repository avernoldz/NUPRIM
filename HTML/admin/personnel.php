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
    $active = "Personnel";
    $log = 0;
    include "../../Connections/Include.php";
    include "sideBar.php";
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
                        <!-- <th>Action</th> -->
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
                                <!-- <td class="text-center"><a
                                        href="view.php?facultyid=<?php echo "$adminid&studentid=$rows[userid]" ?>" class="btn bg-[var(--blue-900)] text-[white] p-[4px]"><i class="fa-solid fa-eye fa-fw"></i></a>
                                </td> -->
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


    <?php
    $options = ['cost' => 12];
    if (isset($_POST['save'])) {
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $middlename = $conn->real_escape_string($_POST['middlename']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);

        $hash_pass = password_hash($password, PASSWORD_BCRYPT, $options);

        $insert = "INSERT INTO user(firstname, middlename, lastname, email, password)
                        VALUES('$firstname','$middlename','$lastname','$email','$hash_pass')";

        if (mysqli_query($conn, $insert)) {
            echo "<script>window.location.href='personnel.php?adminid=$adminid&alert=1';</script>";
        } else {
            echo mysqli_error($conn);
        }
    }

    ?>

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