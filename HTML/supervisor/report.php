<?php
session_start();
session_regenerate_id();

if (!$_SESSION['supervisorid']) {
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- jQuery and Moment.js (required) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

    <!-- Daterangepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

    <title>Reports</title>
    <style>
        body {
            background: #efefef;
        }

        input[type="radio"]:checked+span {
            border: 1px solid #4f46e5;
            /* background-color: #4f46e5; */
            color: #4f46e5;
        }

        /* Base Styles for the input */
        .custom-daterange {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            padding: 4px 12px;
            margin-right: 24px;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            background-color: #fff;
            color: #333;
            transition: all 0.3s ease-in-out;
            outline: none;
            box-sizing: border-box;
        }

        /* Add focus effect */
        .custom-daterange:focus {
            border-color: #4f92f7;
            box-shadow: 0 0 10px rgba(79, 146, 247, 0.2);
        }

        /* Add subtle hover effect */
        .custom-daterange:hover {
            border-color: #bdbdbd;
        }

        /* Placeholder styling */
        .custom-daterange::placeholder {
            color: #aaa;
            font-size: 13px;
        }

        /* Adding a custom icon */
        .custom-daterange::-webkit-calendar-picker-indicator {
            background: url('https://img.icons8.com/ios-filled/50/000000/calendar.png') no-repeat center;
            background-size: 16px;
            cursor: pointer;
        }

        /* When clicked on the calendar icon, it should slightly scale */
        .custom-daterange:focus::-webkit-calendar-picker-indicator {
            transform: scale(1.2);
        }

        /* Styling for the dropdown calendar box (using flatpickr or any date range picker plugin) */
        .flatpickr-calendar {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .flatpickr-month {
            background-color: #4f92f7;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }

        .flatpickr-day:hover {
            background-color: #f0f7ff;
            color: #4f92f7;
            border-radius: 50%;
        }

        .flatpickr-day.selected {
            background-color: #4f92f7;
            color: white;
        }

        /* Arrow for the calendar */
        .flatpickr-prev-month,
        .flatpickr-next-month {
            color: #4f92f7;
        }

        /* Customize the current date */
        .flatpickr-day.today {
            background-color: #ff4081;
            color: white;
            border-radius: 50%;
        }
    </style>
</head>

<body>

    <?php
    $supervisorid = $_SESSION['supervisorid'];
    $code = isset($_GET['code']) ? $_GET['code'] : null;

    $active = $code;
    $on = "hon";
    include "../../Connections/Include.php";
    include "sideBar.php";

    $query1 = "SELECT station FROM account INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber WHERE userid = '$supervisorid'";
    $results1 = mysqli_query($conn, $query1);
    $row1 = mysqli_fetch_array($results1);
    $station = $row1['station'];

    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }

    ?>
    <div class="main ">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Reports </span></h1>
            </div>
        </div>

        <div class="row bg column-gap-3 mt-3 text-[14px]">
            <form action="action/reports.php" method="POST" id="save-detail-orders" enctype="multipart/form-data">
                <div class="flex mb-3">
                    <h1 class="h5 font-medium">Reports</h1>
                    <div class="px-4" data-bs-toggle="modal">
                        <input type="text" id="daterange" name="date" class="custom-daterange" placeholder="Select Date Range" />
                        <i class="fa-solid fa-bars fa-print cursor-pointer p-2 rounded-full bg-gray-200"></i>
                    </div>
                </div>
                <input type="hidden" name="from" value="" id="from" />
                <input type="hidden" name="to" value="" id="to" />
                <input type="hidden" name="type" id="reportType" value="Weekly" />
            </form>
            <!-- <ul class="nav nav-underline">
                <li class="nav-item">
                    <a class="nav-link !border-b-indigo-600 !text-indigo-600 border-b-2 font-bold text-secondary" data-type="Weekly" href="#" id="first">Weekly</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary ml-4" data-type="Monthly" href="#">Monthly</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary ml-4" data-type="Recap" href="#">Recap</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary ml-4" data-type="Alpha" href="#">Alpha</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary ml-4" data-type="Roster" href="#">Roster</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary ml-4" data-type="Annex" href="#">Annex A</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary ml-4" data-type="Files" href="#">Files</a>
                </li>
            </ul> -->
            <div id="content">

            </div>
        </div>

        <form action="action/reports.php" method="POST" id="save-detail-orders" enctype="multipart/form-data">
            <div class="modal fade" id="reports" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-6 font-semibold ml-2" id="staticBackdropLabel" data-table="case">Compile Report</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-[13px]">
                            <div class="grid grid-rows-4 gap-2 px-4">
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Weekly" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Weekly Personnel Accounting Report</span>
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Monthly" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Monthly</span>
                                        </span>
                                    </label>
                                </div>

                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Recap" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Recap</span>
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Alpha" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Alpha</span>
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Roster" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Roster</span>
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="AnnexA" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Annex A</span>
                                        </span>
                                    </label>
                                </div>
                                <!-- <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="AnnexB" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Annex B</span>
                                        </span>
                                    </label>
                                </div> -->
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" data-bs-dismiss="modal" class="cancel inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-300 sm:mt-0 sm:w-auto">Cancel</button>
                            <button type="submit" name="compile" class="save-detail inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Compile</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script src="../JS/app.js"></script>
    <script>
        $(document).ready(function() {

            const weeklyLink = $('.nav-link[data-type="Weekly"]');

            $('#daterange').daterangepicker({
                locale: {
                    format: 'MMM DD, YYYY', // Ensure correct format for the hidden fields
                }
            });

            // // Capture the "Apply" button click event of the date range picker
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                // Get the selected start and end dates from the picker
                const fromDate = picker.startDate.format('YYYY-MM-DD');
                const toDate = picker.endDate.format('YYYY-MM-DD');

                // Update hidden fields with the selected dates
                $('#from').val(fromDate);
                $('#to').val(toDate);

                // Trigger the AJAX request after Apply
                const reportType = $('.nav-link.active').data('type'); // Get the active report type (e.g., Weekly, Monthly)

                // Now, trigger the AJAX request with the selected dates and report type
                $.ajax({
                    url: 'action/reportsData.php',
                    type: 'POST',
                    data: {
                        type: reportType, // The report type (Weekly, Monthly, etc.)
                        from: fromDate, // The "from" date
                        to: toDate // The "to" date
                    },
                    success: function(data) {
                        // Update the content with the response from the server
                        $('#content').html(data);
                    },
                    error: function(jqXHR, textStatus) {
                        alert('Error fetching data: ' + textStatus);
                    },
                }).done(function() {
                    $(".loading").fadeOut(500); // Hide loading spinner after request is done
                });
            });

            handleNavClick(weeklyLink);

            $('.nav-link').on('click', function(e) {
                e.preventDefault();
                handleNavClick($(this));
            });

            function handleNavClick(link) {
                $('.nav-link').removeClass('!bg-[var(--blue-500)] active');
                link.addClass('!bg-[var(--blue-500)] active');

                const userId = link.data('userid');
                const type = link.data('type');

                $('.absolute').addClass('hidden');

                $.ajax({
                    url: 'action/reportsData.php',
                    type: 'POST',
                    data: {
                        userid: userId,
                        type: type
                        // from: $('#from').val(), // Get the from date from hidden input
                        // to: $('#to').val()
                    },
                    success: function(data) {
                        $('#content').html(data);
                    },
                    error: function(jqXHR, textStatus) {
                        alert('Error fetching data: ' + textStatus);
                    },
                }).done(function() {
                    $(".loading").fadeOut(500);
                });

                // const reportType = link.data('type');

                // $('#reportType').val(reportType);
            }

            $('.fa-print').on('click', function() {
                const reportType = $('#reportType').val(); // Get the selected report type

                $('<input>').attr({
                    type: 'hidden',
                    name: 'compile',
                    value: 'true' // Set the value for the hidden input if needed
                }).appendTo('#save-detail-orders');

                // Now submit the form to generate the report based on the selected type
                $('#save-detail-orders').submit(); // Submitting the form
            });

        })
    </script>
</body>

</html>