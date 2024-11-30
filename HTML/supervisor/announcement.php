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

        .loader {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff73;
            z-index: 200;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .loader p {
            margin-top: 80px;
            color: #333;
            font-size: 16px;
            font-weight: bold;
            animation: fadeInOut 2s ease-in-out infinite;
        }

        .jimu-primary-loading:before,
        .jimu-primary-loading:after {
            position: absolute;
            top: 0;
            content: '';
        }

        .jimu-primary-loading:before {
            left: -19.992px;
        }

        .jimu-primary-loading:after {
            left: 19.992px;
            -webkit-animation-delay: 0.32s !important;
            animation-delay: 0.32s !important;
        }

        .jimu-primary-loading:before,
        .jimu-primary-loading:after,
        .jimu-primary-loading {
            background: #076fe5;
            -webkit-animation: loading-keys-app-loading 0.8s infinite ease-in-out;
            animation: loading-keys-app-loading 0.8s infinite ease-in-out;
            width: 13.6px;
            height: 32px;
        }

        .jimu-primary-loading {
            text-indent: -9999em;
            margin: auto;
            position: absolute;
            right: calc(50% - 6.8px);
            top: calc(50% - 16px);
            -webkit-animation-delay: 0.16s !important;
            animation-delay: 0.16s !important;
        }

        @-webkit-keyframes loading-keys-app-loading {

            0%,
            80%,
            100% {
                opacity: .75;
                box-shadow: 0 0 #076fe5;
                height: 32px;
            }

            40% {
                opacity: 1;
                box-shadow: 0 -8px #076fe5;
                height: 40px;
            }
        }

        @keyframes loading-keys-app-loading {

            0%,
            80%,
            100% {
                opacity: .75;
                box-shadow: 0 0 #076fe5;
                height: 32px;
            }

            40% {
                opacity: 1;
                box-shadow: 0 -8px #076fe5;
                height: 40px;
            }
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
                /* Start with the text invisible */
                transform: translateY(10px);
                /* Optionally, start with the text slightly lower */
            }

            50% {
                opacity: 1;
                /* Fade in the text */
                transform: translateY(0);
                /* Bring the text to its normal position */
            }

            100% {
                opacity: 0;
                /* Fade out the text */
                transform: translateY(10px);
                /* Optionally, move the text slightly down */
            }
        }
    </style>
</head>

<body>

    <div class="loader loading hidden">
        <div class="justify-content-center jimu-primary-loading"></div>
        <p>Sending... please wait.</p>
    </div>

    <?php
    $supervisorid = $_SESSION['supervisorid'];
    $active = "Announcement";
    $on = "off";
    include "../../Connections/Include.php";
    include "components/sendSMS.php";
    include "components/sendEmail.php";
    include "sideBar.php";

    $query1 = "SELECT station FROM account INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber WHERE userid = '$supervisorid'";
    $results1 = mysqli_query($conn, $query1);
    $row1 = mysqli_fetch_array($results1);
    $station = $row1['station'];
    $announcements = getAnnouncement($conn, $station);

    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }

    $thresholdDate = strtotime('-2 days');
    ?>
    <div class="main ">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Announcement</span></h1>
            </div>
        </div>

        <div class="grid grid-cols-7 grid-rows-6 gap-3 mt-3">
            <div class="col-span-3 box row-span-3">
                <div class="title mb-2 flex justify-content-between">
                    <div>
                        <p class="font-medium">SMS Notifications</p>
                        <p class="text-[11px] text-gray-500">Create and Send a New SMS</p>
                    </div>
                    <div class="sm:flex sm:flex-row-reverse">
                        <button type="submit" form="smsForm" name="sendSMS" class=" inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Send</button>
                        <button type="reset" class=" inline-flex w-full ml-3 justify-center bg-gray-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Reset</button>
                    </div>
                </div>

                <form action="" method="GET" class="p-2 mt-3" id="smsForm">
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
                            <textarea class="form-control" id="content-sms" rows="10" name="message"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-span-4 box  row-span-6 col-start-4 h-[110vh] ">
                <div class="title ">
                    <p class="font-medium">Announcements</p>
                    <p class="text-[11px] text-gray-500">See Your Latest Announcements</p>
                </div>
                <div class="p-3 h-[102vh] overflow-auto ancount">
                    <?php foreach ($announcements as $announcement):
                        $isNew = strtotime($announcement['created_at']) >= $thresholdDate;
                    ?>
                        <div class="mb-2 ">
                            <div class="flex justify-content-between">
                                <div class="inline">
                                    <p class="font-semibold"><?php echo htmlspecialchars($announcement['title']); ?>
                                        <?php if ($isNew): ?>
                                            <span class="ml-2 text-xs text-white bg-red-500 rounded-sm px-2">New</span>
                                        <?php endif; ?>
                                    </p>

                                </div>
                                <p class="font-semibold"><?php echo date('F j, Y', strtotime(htmlspecialchars($announcement['created_at']))); ?></p>

                            </div>
                            <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($announcement['message'])); ?></p>
                        </div>
                        <hr class="border-1 border-gray-400 mb-2">
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-span-3 box  row-span-3 row-start-4">
                <div class="title mb-2 flex justify-content-between">
                    <div>
                        <p class="font-medium">Official Announcement</p>
                        <p class="text-[11px] text-gray-500">Share Your Latest Announcements Here</p>
                    </div>
                    <div class="sm:flex sm:flex-row-reverse">
                        <button type="submit" form="announcementForm" name="announcement" class=" inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Publish</button>
                        <button type="reset" class=" inline-flex w-full ml-3 justify-center bg-gray-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Reset</button>
                    </div>
                </div>
                <form action="" method="GET" class="p-2 mt-3" id="announcementForm">
                    <div class="mb-3 row">
                        <label for="title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" id="title-anc" name="title" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="content" class="form-label col-sm-2">Message</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="content-anc" rows="12" name="message" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../JS/app.js"></script>
    <script>
        $(document).ready(function() {

            $('#announcementForm').submit(function(e) {
                e.preventDefault();

                var title = $('#title-anc').val();
                var message = $('#content-anc').val();

                // Perform the AJAX request
                $.ajax({
                    url: 'ajax/send.php',
                    type: 'POST',
                    data: {
                        title: title,
                        message: message,
                        announcement: true,
                    },
                    beforeSend: function() {
                        $(".loading").fadeIn(500); // Show loading spinner if needed
                    },
                    success: function(response) {
                        if (response == 'Success') {
                            Command: toastr["success"]("Announcement created successfully!");
                            reloadAnnouncements();
                        }
                        else {
                            Command: toastr["error"]("Error creating announcement!")
                        }
                        // window.location.href = '../announcement.php?alert=success&message=' + encodeURIComponent('Announcement created successfully');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' ' + errorThrown);
                    },
                    complete: function() {
                        $(".loading").fadeOut(500); // Hide loading spinner after the request is complete
                    }
                });
            });

            $('#smsForm').submit(function(e) {
                e.preventDefault();

                var phonenumbers = $('#phonenumbers').val();
                var message = $('#content-sms').val();

                // Perform the AJAX request
                $.ajax({
                    url: 'ajax/send.php',
                    type: 'POST',
                    data: {
                        phonenumbers: phonenumbers,
                        message: message,
                        sendSMS: true,
                    },
                    beforeSend: function() {
                        $(".loading").fadeIn(500); // Show loading spinner if needed
                    },
                    success: function(response) {
                        if (response == 'Success') {
                            Command: toastr["success"]("SMS sent successfully!")
                        }
                        else {
                            Command: toastr["error"]("Error sending SMS!")
                        }
                        // window.location.href = '../announcement.php?alert=success&message=' + encodeURIComponent('Announcement created successfully');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' ' + errorThrown);
                    },
                    complete: function() {
                        $(".loading").fadeOut(500); // Hide loading spinner after the request is complete
                    }
                });
            });

            function reloadAnnouncements() {
                // Show the loading indicator while fetching data
                $(".loading").fadeIn(500);

                station = '<?php echo $station ?>';

                $.ajax({
                    url: 'ajax/getAnnouncements.php', // PHP script to fetch announcements
                    type: 'GET',
                    data: {
                        station: station // Pass station dynamically if needed
                    },
                    success: function(response) {
                        // Replace the announcements content with the updated HTML
                        $('.ancount').html(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error fetching announcements: ' + textStatus);
                    },
                    complete: function() {
                        $(".loading").fadeOut(500); // Hide loading spinner after data is loaded
                    }
                });
            }
        })
    </script>
</body>

</html>