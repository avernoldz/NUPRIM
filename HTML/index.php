<?php
session_start();
session_regenerate_id();

// if (isset($_SESSION['userid'])) {
//     header("Location:dashboard.php?userid=$_SESSION[userid]");
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../Connections/cdn.php" ?>
    <link rel="stylesheet" href="../CSS/root.css">
    <link rel="stylesheet" href="user/CSS/index.css">
    <title>Sign in</title>
</head>

<body>
    <?php
    include "../Connections/Include.php";
    include "user/components/index.php";

    if (isset($_GET['logout-admin'])) {
        session_destroy();
        unset($_SESSION['adminid']);
        header("Location:index.php");
    }

    if (isset($_GET['logout-user'])) {
        // session_destroy();
        unset($_SESSION['userid']);
        header("Location:index.php");
    }

    ?>

    <div class="row bg sign">
        <div class="col-6">
        </div>
        <div class="col-6">
            <div class="row sign-in">
                <div class="col sign" style="width:60%">
                    <h1 class="mb-4 w-700" style="font-size: 32px;">Sign in</h1>
                    <?php
                    if (isset($_GET['login-first'])) {
                        $errorMessage = "You must log in first to continue.";
                        echo "<script>window.location.href='index.php?alert=warning&message=" . urlencode($errorMessage) . "';</script>";
                    }

                    if (isset($_GET['wrong-password-or-email'])) {
                        $errorMessage = "Email or password is incorrect, please try again.";
                        echo "<script>window.location.href='index.php?alert=error&message=" . urlencode($errorMessage) . "';</script>";
                    }
                    ?>

                    <form action="" method="POST" class="w-full flex flex-col flex-wrap content-center items-end">
                        <div class="mb-4 w-9/12">
                            <label for="username" class="form-label">Username or Email</label>
                            <input type="text" class="form-control bg-[#ebebeb] p-[0.75rem] w-full"
                                id="username" placeholder="name@example.com" required name="username">
                        </div>

                        <div class="mb-4 w-9/12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control bg-[#ebebeb] p-[0.75rem]"
                                placeholder="********" required name="password">
                        </div>

                        <a href="#" id="create-new" class="text-[var(--link)] mb-4">Create new account</a>

                        <button
                            class="bg-[var(--primary-blue)] p-[0.75rem] btn text-[#ffffff] w-9/12 hover:bg-[var(--blue-900)] hover:text-[#ffffff]"
                            type="submit" name="sign-in">Sign
                            in</button>
                    </form>
                </div>
            </div>

            <div class="row sign-in create hidden">
                <div class="col sign" style="width:60%">
                    <h1 class="mb-4 w-700" style="font-size: 32px;">Sign Up</h1>
                    <?php
                    if (isset($_GET['created'])) {
                        echo "
                                <div class='row login-first mb-4'>
                                  <div class='col'>
                                    <label>Account created, please wait for the admin approval.</label>
                                    </div>
                                </div>
                                ";
                    }
                    ?>

                    <form action="" method="POST" class="w-full flex flex-col flex-wrap content-center items-end ">

                        <div class="div w-9/12 grid grid-cols-2 grid-rows-1 gap-4">
                            <div class="mb-4 w-12/12">
                                <label for="firstname" class="form-label">Firstname</label>
                                <input type="text" class="form-control bg-[#ebebeb] p-[0.75rem] w-full"
                                    placeholder="Example" required name="firstname">
                            </div>
                            <div class="mb-4 w-12/12">
                                <label for="lastname" class="form-label">Lastname</label>
                                <input type="text" class="form-control bg-[#ebebeb] p-[0.75rem] w-full"
                                    placeholder="Example" required name="lastname">
                            </div>
                        </div>

                        <div class="mb-4 w-9/12">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control bg-[#ebebeb] p-[0.75rem] w-full"
                                placeholder="name_example" required name="username">
                        </div>

                        <div class="div w-9/12 grid grid-cols-2 grid-rows-1 gap-4">
                            <div class="mb-4 w-12/12">
                                <label for="username" class="form-label">Email</label>
                                <input type="email" class="form-control bg-[#ebebeb] p-[0.75rem] w-full"
                                    placeholder="name@example.com" required name="email">
                            </div>

                            <div class="mb-4 w-12/12">
                                <label for="itemNumber" class="form-label">Item Number</label>
                                <input type="text" class="form-control bg-[#ebebeb] p-[0.75rem] w-full"
                                    placeholder="name@example.com" required name="itemNumber">
                            </div>
                        </div>

                        <div class="mb-4 w-9/12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control bg-[#ebebeb] p-[0.75rem]"
                                placeholder="********" required name="password">
                        </div>

                        <div class="mb-4 w-9/12">
                            <label for="password" class="form-label">Confirm Password</label>
                            <input type="password" id="cpassword" class="form-control bg-[#ebebeb] p-[0.75rem]"
                                placeholder="********" required name="cpassword">
                            <small id="error-message"></small>
                        </div>

                        <a href="#" id="sign" class="text-[var(--link)] mb-4">Sign in here</a>

                        <button
                            class="bg-[var(--primary-blue)] p-[0.75rem] btn text-[#ffffff] w-9/12 hover:bg-[var(--blue-900)] hover:text-[#ffffff]"
                            type="submit" id="rege" name="sign-up">Sign
                            Up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['sign-in'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM account where (email = '$username' OR username = '$username') AND isArchive = TRUE";
        $results = mysqli_query($conn, $query);

        if (mysqli_num_rows($results) > 0) {
            $rows = mysqli_fetch_array($results);
            $pwd_hashed = $rows['password'];

            if (password_verify($password, $pwd_hashed)) {

                if ($rows["type"] == "Admin") {
                    $_SESSION['adminid'] = $rows['userid'];
                    echo "<script>window.location.href='admin/dashboard.php?adminid=$rows[userid]';</script>";
                } elseif ($rows["type"] == "Supervisor") {
                    $_SESSION['supervisorid'] = $rows['userid'];
                    $_SESSION['type'] = $rows['type'];
                    echo "<script>window.location.href='supervisor/dashboard.php?supervisorid=$rows[userid]';</script>";
                    logAction($conn, $_SESSION['supervisorid'], 'Logged In', $rows["type"]);
                } else {
                    $_SESSION['userid'] = $rows['userid'];
                    $_SESSION['type'] = $rows['type'];
                    logAction($conn, $_SESSION['userid'], 'Logged In', $rows["type"]);
                    echo "<script>window.location.href='user/dashboard.php?userid=$rows[userid]';</script>";
                }
            } else {
                echo "<script>window.location.href='index.php?wrong-password-or-email';</script>";
            }
        } else {
            echo "<script>window.location.href='index.php?wrong-password-or-email';</script>";
        }
    }

    if (isset($_POST['sign-up'])) {
        $options = ['cost' => 12];

        $username = mysqli_escape_string($conn, $_POST['username']);
        $itemNumber = mysqli_escape_string($conn, $_POST['itemNumber']);
        $email = mysqli_escape_string($conn, $_POST['email']);
        $password = mysqli_escape_string($conn, $_POST['password']);
        $type = 'User';

        $sql = "INSERT INTO account(username, email, password, type, itemNumber) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $hash_pass = password_hash($password, PASSWORD_BCRYPT, $options);
        $stmt->bind_param("sssss", $username, $email, $hash_pass, $type, $itemNumber);

        if ($stmt->execute()) {
            $id = mysqli_insert_id($conn);

            $insert3 = "INSERT INTO service(userid)
                        VALUES('$id')";
            mysqli_query($conn, $insert3);

            $errorMessage = "Account created. Please wait for admin approval";
            echo "<script>window.location.href='index.php?alert=success&message=" . urlencode($errorMessage) . "';</script>";
        }
    }

    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }

    ?>
    <script src="js/app.js"></script>
    <script>
        $('#create-new').click(function() {
            $('.create').removeClass('hidden');
            $('.sign-in').first().hide();
        })

        $('#sign').click(function() {
            $('.create').addClass('hidden');
            $('.sign-in').first().show();
        })
    </script>
</body>

</html>