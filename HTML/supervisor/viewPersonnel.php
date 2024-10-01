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

    $query1 = "SELECT * FROM user WHERE userid = '$userid'";
    $results1 = mysqli_query($conn, $query1);
    $rows = mysqli_fetch_array($results1);

    ?>
    <div class="overlay loading">
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>

    <div class="main ">
        <div class="row bg">
            <div class="col">
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]"><?php echo $rows['firstname'] . ' ' . ($rows['middlename'] ? $rows['middlename'] . ' ' : '') . $rows['lastname']; ?></span></h1>
            </div>
        </div>

        <div class="row bg mt-3">
            <div>
                <div class="px-4 sm:px-0 flex">
                    <div>
                        <h3 class="text-base font-semibold leading-7 text-gray-900 header">Personnel Information</h3>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500 sub-header">Personal details and information.</p>
                    </div>
                    <div class="relative inline-block text-left">
                        <div>
                            <i class="fa-solid fa-bars fa-fw cursor-pointer"></i>
                        </div>
                        <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 transition-all duration-200 ease-in-out hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <div class="py-1" role="none">
                                <a href="#" class="dropdown-link bg-gray-100 text-gray-900 block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="personal" role="menuitem" tabindex="-1"><i class="fa-solid fa-circle-info fa-fw mr-2 text-[#7b8087]"></i>Personal Information</a>
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
                    <dl class="divide-y divide-gray-100">
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">User ID</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo $userid ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Full Name</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo $rows['firstname'] . ' ' . ($rows['middlename'] ? $rows['middlename'] . ' ' : '') . $rows['lastname']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Gender</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo $rows['gender']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Qualifier</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="qualifier" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['qualifier']; ?></dd>
                        </div>
                        <div class=" px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Status</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="status" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['status']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Date of Birth</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo emptyData("dateOfBirth", $rows) ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Place of Birth</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"><?php echo $rows['placeOfBirth']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Contact Number</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="contactNumber" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['contactNumber']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Weight</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="weight" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['weight']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Height</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="height" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['height']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Blood Type</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="bloodType" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['bloodType']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">GSIS</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="GSIS" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['GSIS']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Pag-IBIG</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="Pagibig" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['Pagibig']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">PhilHealth</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="Philhealth" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['Philhealth']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">SSS</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="SSS" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['SSS']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">TIN</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="TIN" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['TIN']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">PNPID</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="PNPID" data-id="<?php echo $rows['infoid'] ?>"><?php echo $rows['PNPID']; ?></dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Attachments</dt>
                            <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                                    <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                        <div class="flex w-0 flex-1 items-center">
                                            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                <span class="truncate font-medium">resume_back_end_developer.pdf</span>
                                                <span class="flex-shrink-0 text-gray-400">2.4mb</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                        </div>
                                    </li>
                                    <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                        <div class="flex w-0 flex-1 items-center">
                                            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                <span class="truncate font-medium">coverletter_back_end_developer.pdf</span>
                                                <span class="flex-shrink-0 text-gray-400">4.5mb</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                        </div>
                                    </li>
                                </ul>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {

            $(document).ajaxSend(function() {
                $(".loading").fadeIn(300);
            });

            $('.dropdown-link').on('click', function(e) {
                e.preventDefault();
                const userId = $(this).data('userid');
                const type = $(this).attr('id');

                $('.absolute').addClass('hidden');

                let header, subheader;

                switch (type) {
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
                        makeEditable();
                        saveDetailOrders(userId);
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

                $('[contenteditable="true"]').on('blur', function() {
                    // Check if Enter was pressed before calling updateContent
                    if (!$(this).data('enterPressed')) {
                        updateContent($(this)); // Update on blur if not Enter
                    }
                    $(this).data('enterPressed', false); // Reset the flag
                });

                $('select[data-table]').on('change', function() {
                    updateContent($(this)); // Call updateContent on change
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
                            } else {
                                loadUserInfo(userId, 'criminal');
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