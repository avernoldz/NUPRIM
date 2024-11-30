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
    $active = "Address";
    $on = "hon";
    include "sideBar.php";
    include "../../Connections/Include.php";

    $query1 = "SELECT * FROM useraddress WHERE userid = '$userid'";
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
                <h1>Address</h1>
                <button class="btn btn-danger  text-[white] w-24" type="button" id="edit"><i class="fa-solid fa-pen fa-fw mr-1" aria-hidden="true"></i>Edit</button>
                <div class="flex gap-x-[6px] flex-wrap justify-between items-center hidden" id="save-cancel">
                    <button class="btn btn-success w-24" type="submit" id="save" name="submit" form="forms">Save</button>
                    <button class="btn btn-danger w-24" type="button" id="cancel">Cancel</button>
                </div>
            </div>
        </div>

        <form action="action/address.php" method="POST" id="forms">
            <div class="row bg column-gap-3">
                <div class="col-6 mb-2">
                    <label for="region" class="form-label">Region</label>
                    <input type="hidden" name="region" value="<?php echo emptyData("region", $row1, "Select Region") ?>">
                    <select class="form-control country" name="region" onchange="loadProvince()" disabled>
                        <option selected><?php echo emptyData("region", $row1, "Select Region") ?></option>
                    </select>
                </div>

                <div class="col mb-2">
                    <label for="province" class="form-label">Province</label>
                    <input type="hidden" name="province" value="<?php echo emptyData("province", $row1, "Select Province") ?>">
                    <select class="form-control state" aria-label="Default select example" name="province"
                        onchange="loadCities()" disabled>
                        <option selected><?php echo emptyData("province", $row1, "Select Province") ?></option>
                    </select>
                </div>

                <div class="col-6 mb-2">
                    <label for="city" class="form-label">City</label>
                    <input type="hidden" name="city" value="<?php echo emptyData("city", $row1, "Select City") ?>">
                    <select class="form-control city" name="city" onchange="loadBrgy()" disabled>
                        <option selected><?php echo emptyData("city", $row1, "Select City") ?></option>
                    </select>
                </div>
                <div class="col mb-2">
                    <label for="barangay" class="form-label">Barangay</label>
                    <input type="hidden" name="brgy" value="<?php echo emptyData("barangay", $row1, "Select Barangay") ?>">
                    <select class="form-control brgy" name="brgy" onchange="load()" disabled>
                        <option selected><?php echo emptyData("barangay", $row1, "Select Barangay") ?></option>
                    </select>
                </div>

                <div class="col-12 mb-2">
                    <label for="houseno" class="form-label">House No./Lot No./Street</label>
                    <input type="text" id="houseno" class="form-control" name="houseno"
                        value="<?php echo emptyData("houseno", $row1, "Enter House No./Lot No./Street") ?>" disabled>
                </div>

                <input type="hidden" name="userid" value="<?php echo $userid ?>">

            </div>
        </form>
    </div>

    <script src="../JS/app.js"></script>
    <script>
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
        window.onload = loadCountries;
    </script>
</body>

</html>