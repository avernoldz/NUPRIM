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
    <title>Address</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <div class="loader loading hidden">
        <div class="justify-content-center jimu-primary-loading"></div>
    </div>
    <?php
    $userid = $_SESSION['userid'];
    $active = "Service Record";
    $on = "hon";
    include "sideBar.php";
    include "../../Connections/Include.php";

    $query1 = "SELECT * FROM service WHERE userid = '$userid'";
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

        <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
            <div class="col flex flex-wrap justify-between items-center">
                <h1>Service Record</h1>
                <button class="btn btn-danger  text-[white] w-24" type="button" id="edit"><i class="fa-solid fa-pen fa-fw mr-1" aria-hidden="true"></i>Edit</button>
                <div class="flex gap-x-[6px] flex-wrap justify-between items-center hidden" id="save-cancel">
                    <button class="btn btn-success w-24" type="submit" id="save" name="submit" form="forms">Save</button>
                    <button class="btn btn-danger w-24" type="button" id="cancel">Cancel</button>
                </div>
            </div>
        </div>

        <form action="action/service.php" method="POST" id="forms">
            <div class="row bg column-gap-3">
                <div class="col-6 mb-2">
                    <label for="entered" class="form-label">Date Entered Service</label>
                    <input type="text" id="entered" class="form-control" name="entered"
                        value="<?php echo emptyData("entered", $row1) ?>" disabled>
                </div>

                <div class="col mb-2">
                    <label for="permanency" class="form-label">Date of Permanency</label>
                    <input type="text" id="permanency" class="form-control" name="permanency"
                        value="<?php echo emptyData("permanency", $row1) ?>" disabled>
                </div>

                <div class="col-6 mb-2">
                    <label for="appStatus" class="form-label">Appointment Status</label>
                    <input type="text" id="appStatus" class="form-control" name="appStatus"
                        value="<?php echo emptyData("appStatus", $row1) ?>" disabled>
                </div>
                <div class="col mb-2">
                    <label for="lastPromotion" class="form-label">Date of Last Promotion</label>
                    <input type="text" id="lastPromotion" class="form-control" name="lastPromotion"
                        value="<?php echo emptyData("lastPromotion", $row1) ?>" disabled>
                </div>

                <div class="col-6 mb-2">
                    <label for="stepIncrement" class="form-label">Step increment</label>
                    <input type="text" id="stepIncrement" class="form-control" name="stepIncrement"
                        value="<?php echo emptyData("stepIncrement", $row1) ?>" disabled>
                </div>

                <div class="col mb-2">
                    <label for="lastStepIncrement" class="form-label">Date of Last Step increment</label>
                    <input type="text" id="lastStepIncrement" class="form-control" name="lastStepIncrement"
                        value="<?php echo emptyData("lastStepIncrement", $row1) ?>" disabled>
                </div>

                <input type="hidden" name="userid" value="<?php echo $userid ?>">

            </div>
        </form>
    </div>
    <script src="../JS/app.js"></script>
    <script>
        initializeFlatpickr("#entered");
        initializeFlatpickr("#permanency");
        initializeFlatpickr("#lastPromotion");
        initializeFlatpickr("#lastStepIncrement");
        $('#edit').click(function() {
            $("#forms .country, #forms input").prop("disabled", false);
            // $("#forms :input").prop("disabled", false);
            $('#save-cancel').css("display", "block");
            $('#edit').css("display", "none");
        })

        $('#cancel').click(function() {
            $("#forms :input").prop("disabled", true);
            $('#save-cancel').css("display", "none");
            $('#edit').css("display", "block");
        })
    </script>
    <script src="../JS/app.js"></script>
</body>

</html>