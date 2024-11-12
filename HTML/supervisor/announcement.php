<?php
session_start();
session_regenerate_id();

if (!$_SESSION['supervisorid']) {
    header("Location:../index.php?login-first");
}
require_once "../../vendor/autoload.php";
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
    <title>Announcement</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $supervisorid = $_SESSION['supervisorid'];
    $active = "Announcement";
    $on = "off";
    include "../../Connections/Include.php";
    include "components/sendSMS.php";
    include "sideBar.php";

    $query1 = "SELECT station FROM account INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber WHERE userid = '$supervisorid'";
    $results1 = mysqli_query($conn, $query1);
    $row1 = mysqli_fetch_array($results1);
    $station = $row1['station'];
    $announcements = getAnnouncement($conn, $station);

    ?>
    <div class="main ">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Announcement</span></h1>
            </div>
        </div>

        <div class="grid grid-cols-7 grid-rows-6 gap-3 mt-3">
            <div class="col-span-3 box row-span-3">
                <div class="title mb-2">
                    <p class="font-medium">SMS Notifications</p>
                    <p class="text-[11px] text-gray-500">Create and Send a New SMS</p>
                </div>

                <form action="" method="GET" class="p-2 mt-3">
                    <div class="mb-3 row">
                        <label for="title" class="col-sm-2 col-form-label">To</label>
                        <div class="col-sm-10">
                            <!-- <input type="text" class="form-control form-control-sm" name="title" id="title"> -->
                            <select name="phonenumbers[]" id="phonenumbers" multiple class="form-control form-control-sm">
                                <?php
                                // Get the phone numbers and names
                                $phonenumbers = getPhonenumbers($conn, $station);

                                // Loop through and display the options in the select element
                                foreach ($phonenumbers['phonenumbers'] as $index => $phoneNumber) {
                                    $name = $phonenumbers['names'][$index];  // Corresponding name
                                    echo "<option value='$phoneNumber'>$name - $phoneNumber</option>";
                                }
                                ?>
                                <option value="all">Sent to All</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="content" class="form-label col-sm-2">Message</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="content" rows="10" name="message"></textarea>
                        </div>
                    </div>

                    <div class="sm:flex sm:flex-row-reverse">
                        <button type="submit" name="sendSMS" class=" inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Send</button>
                        <button type="reset" class=" inline-flex w-full ml-3 justify-center bg-gray-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Reset</button>
                    </div>
                </form>
            </div>
            <div class="col-span-4 box  row-span-6 col-start-4 h-[100vh]">
                <div class="title ">
                    <p class="font-medium">Announcements</p>
                    <p class="text-[11px] text-gray-500">See Your Latest Announcements</p>
                </div>
                <div class="p-3 h-[92vh] overflow-auto">
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="mb-2 ">
                            <div class="flex justify-content-between">
                                <p class="font-semibold"><?php echo htmlspecialchars($announcement['title']); ?></p>
                                <p class="font-semibold"><?php echo date('F j, Y', strtotime(htmlspecialchars($announcement['created_at']))); ?></p>
                            </div>
                            <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($announcement['message'])); ?></p>
                        </div>
                        <hr class="border-1 border-gray-400 mb-2">
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-span-3 box  row-span-3 row-start-4">
                <div class="title mb-2">
                    <p class="font-medium">Official Announcement</p>
                    <p class="text-[11px] text-gray-500">Share Your Latest Announcements Here</p>
                </div>
                <form action="" method="GET" class="p-2 mt-3">
                    <div class="mb-3 row">
                        <label for="title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="title" name="title" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="content" class="form-label col-sm-2">Message</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="content" rows="8" name="message" required></textarea>
                        </div>
                    </div>

                    <div class="sm:flex sm:flex-row-reverse">
                        <button type="submit" name="announcement" class=" inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Publish</button>
                        <button type="reset" class=" inline-flex w-full ml-3 justify-center bg-gray-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    if (isset($_GET['announcement'])) {
        $title = $_GET['title'];
        $message = $_GET['message'];
        $id = $_SESSION['supervisorid'];

        $sql = "INSERT INTO announcement(title, `message`, userid) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sss", $title, $message, $id);

        if ($stmt->execute()) {
            $errorMessage = "Announcement created successfully";
            echo "<script>window.location.href='announcement.php?alert=success&message=" . urlencode($errorMessage) . "';</script>";
        } else {
            $stmt->error();
        }
    }

    if (isset($_GET['sendSMS'])) {
        $phonenumbers = $_GET['phonenumbers'];
        $message = $_GET['message'];
        $id = $_SESSION['supervisorid'];

        if ($phonenumbers == 'all') {
            $allNumber = getPhonenumbers($conn, $station);

            foreach ($allNumber['phonenumbers'] as $phoneNumber) {
                // Format the phone number
                if (strpos($phoneNumber, '0') === 0) {
                    $phoneNumber = '+63' . substr($phoneNumber, 1);
                }

                // Send SMS to the formatted phone number
                if (!sendSms($phoneNumber, $message)) {
                    echo "<script>window.location.href='announcement.php?alert=error&message=Failed to send SMS to $phoneNumber';</script>";
                    return; // Stop further processing if there's an error
                } else {
                    $errorMessage = "SMS sent successfully";
                    echo "<script>window.location.href='announcement.php?alert=success&message=" . urlencode($errorMessage) . "';</script>";
                }
            }
        } else {
            foreach ($phonenumbers as $phoneNumber) {
                // Format the phone number
                if (strpos($phoneNumber, '0') === 0) {
                    $phoneNumber = '+63' . substr($phoneNumber, 1);
                }

                // Send SMS to the formatted phone number
                if (!sendSms($phoneNumber, $message)) {
                    echo "<script>window.location.href='announcement.php?alert=error&message=Failed to send SMS to $phoneNumber';</script>";
                    return; // Stop further processing if there's an error
                } else {
                    $errorMessage = "SMS sent successfully";
                    echo "<script>window.location.href='announcement.php?alert=success&message=" . urlencode($errorMessage) . "';</script>";
                }
            }
        }
    }


    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }
    ?>
    <script src="../JS/app.js"></script>
</body>

</html>