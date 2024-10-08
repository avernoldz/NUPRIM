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
    <title>Personal Information</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_SESSION['userid'];
    $active = "Personal Information";
    $on = "hon";
    include "sideBar.php";
    include "../../Connections/Include.php";

    $query1 = "SELECT * FROM user WHERE userid = '$userid'";
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
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">My Profile</span></h1>
            </div>
        </div>

        <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
            <div class="col flex flex-wrap justify-between items-center">
                <h1>Personal Information</h1>
                <button class="btn btn-danger  text-[white] w-24" type="button" id="edit">Edit</button>
                <div class="flex gap-x-[6px] flex-wrap justify-between items-center hidden" id="save-cancel">
                    <button class="btn btn-success w-24" type="submit" id="save" name="submit" form="forms">Save</button>
                    <button class="btn btn-danger w-24" type="button" id="cancel">Cancel</button>
                </div>
            </div>
        </div>

        <form action="action/info.php" method="POST" id="forms">
            <div class="row bg column-gap-3">
                <div class="col-6 mb-2">
                    <label for="firstname" class="form-label">Firstname</label>
                    <input type="text" id="firstname" class="form-control" name="firstname" value="<?php echo emptyData("firstname", $row1) ?>" required disabled>
                </div>
                <div class="col mb-2">
                    <label for="middlename" class="form-label">Middlename</label>
                    <input type="text" id="middlename" class="form-control" name="middlename" value="<?php echo emptyData("middlename", $row1) ?>" disabled>
                </div>
                <div class="col-6 mb-2">
                    <label for="lastname" class="form-label">Lastname</label>
                    <input type="text" id="lastname" class="form-control" name="lastname" value="<?php echo emptyData("lastname", $row1) ?>" required disabled>
                </div>
                <div class="col mb-2">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" aria-label="Default select example" id="gender" name="gender" disabled>
                        <option value="Male" <?php if (emptyData("gender", $row1) == "Male") echo "selected" ?>>Male</option>
                        <option value="Female" <?php if (emptyData("gender", $row1) == "Female") echo "selected" ?>>Female</option>
                    </select>
                    <!-- <input type="text" id="gender" class="form-control" name="gender" value="<?php echo $row1["gender"] ?>" disabled> -->
                </div>
                <div class="col mb-2">
                    <label for="date-of-birth" class="form-label">Date of Birth</label>
                    <input type="text" id="date-of-birth" class="form-control" name="date-of-birth" value="<?php echo emptyData("dateOfBirth", $row1) ?>" required disabled>
                </div>
                <div class="col-6 mb-2">
                    <label for="place-of-birth" class="form-label">Place of Birth</label>
                    <input type="text" id="place-of-birth" class="form-control" name="place-of-birth" value="<?php echo emptyData("placeOfBirth", $row1) ?>" disabled>
                </div>
                <div class="col mb-2">
                    <label for="cnumber" class="form-label">Contact Number</label>
                    <input type="text" id="cnumber" class="form-control" name="cnumber" value="<?php echo emptyData("contactNumber", $row1) ?>" disabled>
                </div>
                <div class="w-100"></div>
                <div class="col mb-2">
                    <label for="height" class="form-label">Height</label>
                    <input type="text" id="height" class="form-control" name="height" value="<?php echo emptyData("height", $row1) ?>" disabled>
                </div>
                <div class="col mb-2">
                    <label for="weight" class="form-label">Weight</label>
                    <input type="text" id="weight" class="form-control" name="weight" value="<?php echo emptyData("weight", $row1) ?>" disabled>
                </div>
                <div class="col mb-2">
                    <label for="blood-type" class="form-label">Blood Type</label>
                    <input type="text" id="blood-type" class="form-control" name="blood-type" value="<?php echo emptyData("bloodType", $row1) ?>" disabled>
                </div>
                <div class="w-100"></div>
                <div class="col-6 mb-2">
                    <label for="qualifier" class="form-label">Qualifier</label>
                    <input type="text" id="qualifier" class="form-control" name="qualifier" value="<?php echo emptyData("qualifier", $row1) ?>" disabled>
                </div>
                <div class="col mb-2">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" id="status" class="form-control" name="status" value="<?php echo emptyData("status", $row1) ?>" disabled>
                </div>
                <div class="col-6 mb-2">
                    <label for="gsis" class="form-label">GSIS No.</label>
                    <input type="text" id="gsis" class="form-control" name="gsis" value="<?php echo emptyData("GSIS", $row1) ?>" disabled>
                </div>
                <div class="col mb-2">
                    <label for="pag-ibig" class="form-label">Pag-ibig No.</label>
                    <input type="text" id="pag-ibig" class="form-control" name="pag-ibig" value="<?php echo emptyData("Pagibig", $row1) ?>" disabled>
                </div>
                <div class="col-6 mb-2">
                    <label for="philhealth" class="form-label">Philhealth No.</label>
                    <input type="text" id="philhealth" class="form-control" name="philhealth" value="<?php echo emptyData("Philhealth", $row1) ?>" disabled>
                </div>
                <div class="col mb-2">
                    <label for="sss" class="form-label">SSS No.</label>
                    <input type="text" id="sss" class="form-control" name="sss" value="<?php echo emptyData("SSS", $row1) ?>" disabled>
                </div>
                <div class="col-6 mb-2">
                    <label for="tin" class="form-label">TIN No.</label>
                    <input type="text" id="tin" class="form-control" name="tin" value="<?php echo emptyData("TIN", $row1) ?>" disabled>
                </div>
                <div class="col mb-2">
                    <label for="pnpid" class="form-label">PNP ID No.</label>
                    <input type="text" id="pnpid" class="form-control" name="pnpid" value="<?php echo emptyData("PNPID", $row1) ?>" disabled>
                </div>

                <input type="hidden" name="userid" value="<?php echo $userid ?>">

            </div>
        </form>
    </div>
    <script src="../JS/app.js"></script>
    <script>
        $('#edit').click(function() {
            $("#forms :input").prop("disabled", false);
            $("#forms #date-of-birth").prop("type", "date");
            $('#save-cancel').css("display", "block");
            $('#edit').css("display", "none");
        })

        $('#cancel').click(function() {
            $("#forms :input").prop("disabled", true);
            $('#save-cancel').css("display", "none");
            $("#forms #date-of-birth").prop("type", "text");
            var dateOfBirth = '<?php echo emptyData("dateOfBirth", $row1) ?>';
            var date = new Date(dateOfBirth);

            // Format the date to 'yyyy-MM-dd'
            var date = new Date(dateOfBirth);

            // Check if the date is valid
            if (!isNaN(date)) {
                // Format the date to 'yyyy-MM-dd'
                var formattedDate = date.getFullYear() + '-' +
                    ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                    ('0' + date.getDate()).slice(-2);

                // Set the value in the input field
                $("#forms #date-of-birth").val(formattedDate);
            } else {
                console.error("Invalid date format: " + dateOfBirth);
            }

            $('#edit').css("display", "block");
        })
    </script>
</body>

</html>