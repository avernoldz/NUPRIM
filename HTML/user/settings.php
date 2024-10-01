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
    <title>Settings</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_SESSION['userid'];
    $active = "Settings";
    $on = "off";
    include "sideBar.php";
    include "../../Connections/Include.php";

    $query1 = "SELECT * FROM account WHERE userid = '$userid'";
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
                    <h1>Settings</h1>
                    <button class="btn btn-danger edit text-[white] w-24" type="button" id="edit">Edit</button>
                    <div class="flex gap-x-[6px] flex-wrap justify-between items-center hidden" id="save-cancel">
                        <button class="btn btn-success w-24" type="submit" id="save" name="submit" form="forms">Save</button>
                        <button class="btn btn-danger w-24" type="button" id="cancel">Cancel</button>
                    </div>
                </div>
            </div>

            <form action="" method="POST" id="forms">
                <div class="row bg column-gap-3">
                    <div class="col-6 mb-2">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" class="form-control" name="username" value="<?php echo emptyData("username", $row1) ?>" required disabled>
                    </div>

                    <div class="col mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" name="email" value="<?php echo emptyData("email", $row1) ?>" disabled>
                    </div>

                    <div class="col-6 mb-2">
                        <label for="opassword" class="form-label">Old Password</label>
                        <input type="password" id="opassword" class="form-control" name="opassword" value="<?php echo emptyData("fatherSrname", $row1) ?>" required disabled>
                    </div>
                    <div class="w-100"></div>

                    <div class="col-6 mb-2">
                        <label for="npassword" class="form-label">New Password</label>
                        <input type="password" id="npassword" class="form-control" name="npassword" disabled>
                    </div>

                    <div class="col mb-2">
                        <label for="cpassword" class="form-label">Confirm New Password</label>
                        <input type="password" id="cpassword" class="form-control" name="cpassword" disabled>
                    </div>
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                </div>
            </form>
        </div>
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
        </script>
</body>

</html>