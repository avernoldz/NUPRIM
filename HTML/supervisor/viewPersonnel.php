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
    <title>Personnel</title>
    <style>
        body {
            background: #efefef;
        }

        nav .flex li {
            background: white;
            list-style-type: none;
        }

        nav ul {
            display: none;
            list-style: none;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .spinner-grow {
            position: absolute;
            top: 50%;
            left: 50%;
        }

        .data:nth-of-type(odd) {
            background-color: #f9fafb;
        }

        .data:hover {
            box-shadow: inset 1px 0 0 #dadce0, inset -1px 0 0 #dadce0, 2px 2px 2px 0 rgba(60, 64, 67, .3), 0 1px 3px 1px rgba(60, 64, 67, .15);
            z-index: 2000;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <?php
    $supervisorid = $_SESSION['supervisorid'];
    $userid = $_GET['userid'];
    $active = "Personnel";
    $on = "off";
    include "../../Connections/Include.php";
    include "sideBar.php";

    list($semester, $year) = getSemester();
    $query1 = "SELECT * FROM user WHERE userid = '$userid'";
    $results1 = mysqli_query($conn, $query1);
    $rows = mysqli_fetch_array($results1);

    $chart = "SELECT year, semester, userid, finalRating, q, t, e FROM ipcr WHERE userid = '$userid' ORDER BY year DESC LIMIT 6";
    $resChart = mysqli_query($conn, $chart);

    // Initialize arrays to hold data
    $labels = [];
    $dataArray = [
        'Final Rating' => [],
        'Q Rating' => [],
        'T Rating' => [],
        'E Rating' => []
    ];

    // Fetch all rows
    while ($rowChart = mysqli_fetch_assoc($resChart)) {
        $parts = explode(' ', $rowChart['semester']);
        $sem = $parts[0];
        $labels[] = $sem . " " . $rowChart['year']; // Assuming semester is the label
        $dataArray['Final Rating'][] = isset($rowChart['finalRating']) ? $rowChart['finalRating'] : 0;
        $dataArray['Q Rating'][] = isset($rowChart['q']) ? $rowChart['q'] : 0;
        $dataArray['T Rating'][] = isset($rowChart['t']) ? $rowChart['t'] : 0;
        $dataArray['E Rating'][] = isset($rowChart['e']) ? $rowChart['e'] : 0;
    }

    // Convert to JSON
    $labelsJson = json_encode($labels);
    $dataJson = json_encode(array_map(null, ...array_values($dataArray)));

    $res2 = select($conn, 'leaves', $userid);
    $res3 = select($conn, '`case`', $userid);
    $res4 = select($conn, '`detail`', $userid);

    ?>
    <div class="overlay loading">
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>

    <div class="main ">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]"><?php echo $rows['firstname'] . ' ' . ($rows['middlename'] ? $rows['middlename'] . ' ' : '') . $rows['lastname']; ?></span></h1>
            </div>
        </div>

        <div class="row bg mt-3">
            <div>
                <div class="px-4 sm:px-0 flex">
                    <div>
                        <h3 class="text-base font-semibold leading-7 text-gray-900 header">Overview</h3>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500 sub-header">Overview of personal details and information.</p>
                    </div>
                    <div class="relative inline-block text-left">
                        <div>
                            <i class="fa-solid fa-bars fa-fw cursor-pointer"></i>
                        </div>
                        <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 transition-all duration-200 ease-in-out hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <div class="py-1" role="none">
                                <a href="#" class="dropdown-link bg-gray-100 text-gray-900 block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="overview" role="menuitem" tabindex="-1"><i class="fa-solid fa-rectangle-list fa-fw mr-2 text-[#7b8087]"></i>Overview</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="personal" role="menuitem" tabindex="-1"><i class="fa-solid fa-circle-info fa-fw mr-2 text-[#7b8087]"></i>Personal Information</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="address" role="menuitem" tabindex="-1"><i class="fa-solid fa-location-dot fa-fw mr-2 text-[#7b8087]"></i>Address</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="family" role="menuitem" tabindex="-1"><i class="fa-solid fa-people-group fa-fw mr-2 text-[#7b8087]"></i>Family</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="education" role="menuitem" tabindex="-1"><i class="fa-solid fa-book-open fa-fw mr-2 text-[#7b8087]"></i>Education</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="eligibility" role="menuitem" tabindex="-1"><i class="fa-solid fa-chart-simple fa-fw mr-2 text-[#7b8087]"></i>Eligibility</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="service" role="menuitem" tabindex="-1"><i class="fa-solid fa-file fa-fw mr-2 text-[#7b8087]"></i>Service Record</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="detail" role="menuitem" tabindex="-1"><i class="fa-solid fa-folder-open fa-fw mr-2 text-[#7b8087]"></i>Detail Orders</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="training" role="menuitem" tabindex="-1"><i class="fa-solid fa-book fa-fw mr-2 text-[#7b8087]"></i>Training/Seminar</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="leave" role="menuitem" tabindex="-1"><i class="fa-solid fa-clipboard fa-fw mr-2 text-[#7b8087]"></i>Leave Records</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="criminal" role="menuitem" tabindex="-1"><i class="fa-solid fa-handcuffs fa-fw mr-2 text-[#7b8087]"></i>Criminal Case</a>
                                <a href="#" class="dropdown-link block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="documents" role="menuitem" tabindex="-1"><i class="fa-solid fa-file fa-fw mr-2 text-[#7b8087]"></i>Documents</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-100" id="user-info">
                    <div class="grid grid-cols-5 grid-rows-5 gap-4 mt-3">
                        <div class="col-span-3 box row-span-3 border border-gray-500">
                            <div class="title mb-2">
                                <p class="font-medium">IPCR Latest Rating</p>
                                <p class="text-[11px] text-gray-500">Current Semester Ratings</p>
                            </div>
                            <div>
                                <canvas id="myChart" class="w-100"></canvas>
                            </div>
                        </div>
                        <div class="col-span-2 row-span-2 col-start-4">
                            <div class="box border border-gray-500 h-100 border border-gray-500">
                                <div class="title mb-2">
                                    <p class="font-medium">Filed Leaves</p>
                                    <p class="text-[11px] text-gray-500">User pending and approved leaves</p>
                                </div>
                                <div class="w-100 mt-3 overflow-auto h-64">
                                    <?php renderLeaveList($res2, null); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 row-span-3 col-start-4 row-start-3">
                            <div class="box border border-gray-500 h-100">
                                <div class="title mb-2">
                                    <p class="font-medium">Detail Orders</p>
                                    <p class="text-[11px] text-gray-500">User pending and approved orders</p>
                                </div>
                                <div class="w-100 mt-3 overflow-auto h-64">
                                    <?php renderLeaveList($res4, null); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 row-span-2 row-start-4 col-start-2">
                            <div class="box border border-gray-500 h-full flex flex-col">
                                <div class="title mb-2">
                                    <p class="font-medium">IPCR</p>
                                    <p class="text-[11px] text-gray-500">User pending and approved IPCR</p>
                                </div>
                                <div class="w-100 mt-3 overflow-auto h-64">
                                    <?php

                                    $query2 = "SELECT * FROM ipcr WHERE userid = '$userid' ORDER BY created_at DESC";
                                    $results2 = mysqli_query($conn, $query2);

                                    if (mysqli_num_rows($results2) > 0) {
                                        while ($row2 = mysqli_fetch_array($results2)) {
                                            $i = 1;

                                            if ($row2['status'] == 'Waiting for Approval') {
                                                $stat = '<label class="form-label bg-yellow-100 text-xs rounded p-1 mb-0 text-yellow-700 w-500 px-2 border-1 border-yellow-300">Pending</label>';
                                            } elseif ($row2['status'] == 'Approved') {
                                                $stat = '<label class="form-label bg-green-100 text-xs rounded p-1 mb-0 text-green-700 w-500 px-2 border-1 border-green-300">Approved</label>';
                                            } else {
                                                $stat = '<label class="form-label bg-red-100 text-xs rounded p-1 mb-0 text-red-700 w-500 px-2 border-1 border-red-300">Rejected</label>';
                                            }
                                    ?>
                                            <div class="ipcr col-12 p-2 flex data flex-wrap justify-between items-center bg-gray-100">
                                                <h2 class="font-bold ml-5">IPCR -
                                                    <span class="font-normal"><?php echo emptyData("semester", $row2) . ' (' . emptyData("year", $row2) . ')' ?></span>
                                                </h2>
                                                <h2 class="font-bold">
                                                    <span class="font-normal">Rating - </span> <?php echo emptyData("finalRating", $row2) ?>
                                                </h2>
                                                <div class="col-3 text-center">
                                                    <span class="mr-5"><?php echo $stat; ?></span>
                                                    <a href="generate.php?ipcr=<?php echo $row2["ipcrid"] ?>&userid=<?php echo $userid ?>" class=" hover:bg-gray-300 hover:rounded-full p-2"><i class="fa-regular fa-eye fa-fw text-gray-600"></i></a>
                                                </div>
                                            </div>
                                    <?php
                                            $i++;
                                        }
                                    } else {
                                        echo "<p class='text-center'>No data</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row-span-2 row-start-4  ">
                            <div class="box border border-gray-500 h-full flex flex-col">
                                <div class="title mb-2">
                                    <p class="font-medium">Percentage Difference Ratings</p>
                                    <p class="text-[11px] text-gray-500" id="dlm">Last Semester and Current Semester</p>
                                </div>

                                <?php
                                $percentageRes = compareLastSemRating($conn, $station, $semester, $userid);
                                $isPositive = strpos($percentageRes['percentage'], '+') !== false; // Check if the difference is positive
                                ?>

                                <div class="flex-grow flex items-center justify-center">
                                    <h3 class="text-8xl font-bold <?php echo $isPositive ? 'text-green-600' : 'text-red-600'; ?>"><?php echo number_format($percentageRes['avg'], 2) ?></h3>
                                    <h3 class="text-center text-2xl font-bold 
                                        <?php echo $isPositive ? 'text-green-600' : 'text-red-600'; ?>
                                        flex items-center">
                                        <span class="<?php echo $isPositive ? 'text-green-600' : 'text-red-600'; ?> mr-2">
                                            <?php echo $isPositive ? '<i class="fa-solid fa-arrow-up-long rotate-45"></i>' : '<i class="fa-solid fa-arrow-down-long rotate-45"></i>'; ?> <!-- Arrow icon -->
                                        </span>
                                        <?php echo $percentageRes['percentage'] ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {

            var labels = <?php echo $labelsJson; ?>; // Use the labels from PHP
            var data = <?php echo $dataJson; ?>;

            function createChart(labels, data) {
                var ctx = document.getElementById('myChart').getContext('2d');

                // Lighter transparent colors with borders
                var colors = [{
                        backgroundColor: 'rgba(242, 143, 155, 0.5)',
                        borderColor: '#f28f9b'
                    }, // Transparent pinkish-red
                    {
                        backgroundColor: 'rgba(106, 141, 245, 0.5)',
                        borderColor: '#6a8df5'
                    }, // Transparent blue
                    {
                        backgroundColor: 'rgba(98, 216, 140, 0.5)',
                        borderColor: '#62d88c'
                    }, // Transparent green
                    {
                        backgroundColor: 'rgba(169, 107, 247, 0.5)',
                        borderColor: '#a96bf7'
                    }, // Transparent purple
                ];

                var datasets = [{
                        label: 'Final Rating',
                        data: data.map(row => row[0]), // Accessing the first column for Final Rating
                        backgroundColor: colors[0].backgroundColor,
                        borderColor: colors[0].borderColor,
                        borderWidth: 2, // Border thickness
                        barThickness: 25,
                        borderRadius: 5, // Rounded corners for the bars (optional)
                    },
                    {
                        label: 'Q Rating',
                        data: data.map(row => row[1]), // Accessing the second column for Q Rating
                        backgroundColor: colors[1].backgroundColor,
                        borderColor: colors[1].borderColor,
                        borderWidth: 2,
                        barThickness: 25,
                        borderRadius: 5,
                    },
                    {
                        label: 'T Rating',
                        data: data.map(row => row[2]), // Accessing the third column for T Rating
                        backgroundColor: colors[2].backgroundColor,
                        borderColor: colors[2].borderColor,
                        borderWidth: 2,
                        barThickness: 25,
                        borderRadius: 5,
                    },
                    {
                        label: 'E Rating',
                        data: data.map(row => row[3]), // Accessing the fourth column for E Rating
                        backgroundColor: colors[3].backgroundColor,
                        borderColor: colors[3].borderColor,
                        borderWidth: 2,
                        barThickness: 25,
                        borderRadius: 5,
                    }
                ];

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: 5 // Adjust as needed
                            }
                        },
                        animations: {
                            tension: {
                                duration: 1000,
                                easing: 'linear',
                                from: 1,
                                to: 0,
                                loop: true
                            }
                        }
                    }
                });
            }

            createChart(labels, data);


            $(document).ajaxSend(function() {
                $(".loading").fadeIn(300);
            });

            function initializeFlatpickr(selector) {
                $(selector).flatpickr({
                    dateFormat: "F j, Y",
                    onChange: function(selectedDates, dateStr, instance) {
                        $(selector).text(dateStr); // Update the element with the formatted date
                    }
                });
            }

            var userId = null;

            const tableMappings = {
                personal: 'personal',
                address: 'address',
                family: 'family',
                education: 'education',
                eligibility: 'eligibility',
                service: 'service',
                detail: 'detail',
                training: 'training',
                leaves: 'leave',
                case: 'criminal',
                documents: 'documents',
            };


            $('.dropdown-link').on('click', function(e) {
                e.preventDefault();
                userId = $(this).data('userid');
                const type = $(this).attr('id');

                $('.absolute').addClass('hidden');

                let header, subheader;

                switch (type) {
                    case 'overview':
                        header = 'Overview';
                        subheader = 'Overview of personal details and information.';
                        break;
                    case 'personal':
                        header = 'Personnel Information';
                        subheader = 'Personal details and information.';
                        break;
                    case 'address':
                        header = 'Address Information';
                        subheader = 'Details of the user’s address.';
                        break;
                    case 'family':
                        header = 'Family Information';
                        subheader = 'Details of the user’s family.';
                        break;
                    case 'education':
                        header = 'Educational Background';
                        subheader = 'Information about the user’s education.';
                        break;
                    case 'eligibility':
                        header = 'Eligibility Information';
                        subheader = 'User’s eligibility details.';
                        break;
                    case 'service':
                        header = 'Service Record';
                        subheader = 'Details of user’s service record.';
                        break;
                    case 'detail':
                        header = 'Detail Orders';
                        subheader = 'Details of user’s orders.';
                        break;
                    case 'training':
                        header = 'Training and Seminar';
                        subheader = 'Information on training and seminars attended.';
                        break;
                    case 'leave':
                        header = 'Leave Records';
                        subheader = 'Records of user’s leave.';
                        break;
                    case 'criminal':
                        header = 'Criminal Case Information';
                        subheader = 'Details regarding any criminal cases.';
                        break;
                    case 'documents':
                        header = 'Documents';
                        subheader = 'User’s uploaded documents.';
                        break;
                    default:
                        header = '';
                        subheader = '';
                }

                $('.header').text(header);
                $('.sub-header').text(subheader);

                $.ajax({
                    url: 'ajax/fetchData.php',
                    type: 'POST',
                    data: {
                        userid: userId,
                        type: type
                    },
                    success: function(data) {
                        $('#user-info').html(data);

                        if (type == 'overview') {
                            var labels = <?php echo $labelsJson; ?>; // Use the labels from PHP
                            var data = <?php echo $dataJson; ?>;
                            createChart(labels, data);
                        }

                        makeEditable();
                        saveDetailOrders(userId);
                        initializeFlatpickr(".flat-pickrAd");
                        initializeFlatpickr(".flat-pickrEd");
                        initializeFlatpickr(".flat-pickrSd");
                        initializeFlatpickr(".flat-pickr");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error fetching data: ' + textStatus);
                    },
                }).done(function() {
                    $(".loading").fadeOut(500);
                });
            });

            $('.fa-bars').click(function(e) {
                e.stopPropagation(); // Prevent the click event from bubbling up
                const dropdown = $('.absolute');
                dropdown.toggleClass('hidden'); // Toggle hidden class
                dropdown.toggleClass('opacity-0 scale-95'); // Apply transition effects
                dropdown.toggleClass('opacity-100 scale-100'); // Show full opacity and scale
            });

            $(document).click(function() {
                const dropdown = $('.absolute');
                if (!dropdown.hasClass('hidden')) {
                    dropdown.addClass('hidden opacity-0 scale-95'); // Hide on outside click
                    dropdown.removeClass('opacity-100 scale-100');
                }
            });

            function makeEditable() {
                $('[contenteditable="true"]').on('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault(); // Prevent default behavior (new line)
                        updateContent($(this)); // Update content
                        $(this).data('enterPressed', true); // Set the flag
                        $(this).blur(); // Deselect the contenteditable
                    }
                });
                // $('[contenteditable="true"]').on('blur', function() {
                //     // Only load user info if Enter was not pressed
                //     if (!$(this).data('enterPressed')) {
                //         updateContent($(this)); // Update on blur if not Enter
                //     }
                //     $(this).data('enterPressed', false); // Reset the flag
                // });
                $('select[data-table]').on('change', function() {

                    updateContent($(this));

                    var tables = $(this).data('table');
                    if (tableMappings[tables]) {
                        loadUserInfo(userId, tableMappings[tables]);
                    }

                    // Call updateContent on change
                });
            }

            function saveDetailOrders(userId) {
                $('#save-detail-orders').on('submit', function(e) {
                    e.preventDefault(); // Prevent the default form submission

                    var tables = $('#staticBackdropLabel').data('table');
                    var formData = $(this).serialize(); // Serialize the form data
                    var formData = new FormData(this); // Use FormData to include file data
                    formData.append('detail_orders', 'detail_orders'); // Add detail_orders to FormData
                    formData.append('tables', tables);

                    $.ajax({
                        type: 'POST', // Use 'GET' or 'POST' as needed
                        url: 'ajax/saveData.php', // Change to your server-side script
                        data: formData, // Use FormData
                        contentType: false, // Prevent jQuery from overriding the content type
                        processData: false, // Combine both data// Serialize form data
                        success: function(response) {
                            $('#addOrder').modal('hide');

                            if (tables == 'detail') {
                                loadUserInfo(userId, 'detail');
                            } else if (tables == 'case') {
                                loadUserInfo(userId, 'criminal');
                            } else {
                                loadUserInfo(userId, 'service');
                            }

                            console.log(response);
                            Command: toastr["success"]("Data Saved successfully");
                        },
                        error: function(xhr, status, error) {
                            // Handle any errors
                            Command: toastr["error"]("Error adding data");
                            $('#response').html('An error occurred: ' + error);
                        }
                    }).done(function() {
                        $(".loading").fadeOut(500);
                    });
                })
            }

            function loadUserInfo(userid, type) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/fetchData.php',
                    data: {
                        userid: userid,
                        type: type
                    },
                    success: function(data) {
                        $("#user-info").html(data); // Load updated data into the div
                        saveDetailOrders(userid);
                        makeEditable();
                        initializeFlatpickr(".flat-pickrAd");
                        initializeFlatpickr(".flat-pickrEd");
                        initializeFlatpickr(".flat-pickrSd");
                        initializeFlatpickr(".flat-pickr");
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + error);
                    }
                }).done(function() {
                    $(".loading").fadeOut(500);
                });
            }

            function updateContent(field) {
                var content;

                var content = field.html(); // Use .html() to get the content   
                // Determine if the field is a select or contenteditable
                if (field.is('select')) {
                    content = field.val(); // Get the selected value
                } else {
                    content = field.html(); // Use .html() to get the content
                }

                var fieldId = field.data('id'); // Use .data() to get the id
                var fieldName = field.data('field'); // Use .data() to get the field name
                var fieldDate = field.data('date'); // Use .data() to get the field name
                var table = field.data('table'); // Use .data() to get the field name

                if (fieldDate) {
                    if (!isValidDate(content)) {
                        toastr.error('Please enter a valid date (YYYY-MM-DD).', 'Invalid Date');
                        field.focus(); // Refocus to allow correction
                        return; // Stop execution if invalid
                    }
                }
                // Send the content to your server via AJAX
                $.ajax({
                    type: "POST",
                    url: "ajax/saveData.php",
                    data: {
                        id: fieldId,
                        field: fieldName,
                        content: content,
                        table: table
                    },
                    success: function(response) {
                        console.log(response);
                        Command: toastr["success"]("Content saved successfully");
                    },
                    error: function() {
                        Command: toastr["error"]("Error updating content");
                        console.error("Error saving content: ", textStatus, errorThrown, jqXHR.responseText);
                    }
                }).done(function() {
                    $(".loading").fadeOut(500);
                });
            }

            makeEditable();

            function isValidDate(dateString) {
                // Regular expression for various date formats
                var regexFormats = [
                    /^(January|February|March|April|May|June|July|August|September|October|November|December) \d{1,2}, \d{4}$/, // Full month name
                    /^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{1,2}, \d{2}$/, // Abbreviated month name with two-digit year
                    /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/, // MM/DD/YYYY
                    /^(\d{1,2})\/(\d{1,2})\/(\d{2})$/ // MM/DD/YY
                ];

                // Check against all regex formats
                return regexFormats.some(regex => regex.test(dateString));
            }


            $('.absolute a').click(function() {
                $('.absolute a').removeClass('bg-gray-100 text-gray-900');
                $(this).addClass('bg-gray-100 text-gray-900');
            });

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
                $('#edit').css("display", "block");
            })

            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

        });
    </script>
</body>

</html>