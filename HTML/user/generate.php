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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <title>IPCR Generator</title>
    <style>
        body {
            background: #efefef;
            padding: 24px;
        }

        td {
            border: 1px solid #9ca3af;
            padding: 0.5rem;
        }

        * {
            font-size: 14px;
        }

        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 2s linear infinite;
            /* Adjust the duration (2s) as needed */
        }

        @keyframes pulse {
            0% {
                outline: #569ff7 solid 2px;
                outline-offset: 0;
            }

            50% {
                outline: #569ff7 solid 2px;
                outline-offset: 1px;
                /* Adjust for more visible pulse */
            }

            100% {
                outline: #569ff7 solid 2px;
                outline-offset: 0;
            }
        }

        td[contentEditable="true"]:focus,
        p[contentEditable="true"]:focus {
            animation: pulse 1s infinite;
            /* Adjust duration and infinite for continuous pulse */
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
    $userid = $_SESSION['userid'];
    include "../../Connections/Include.php";

    $query = "SELECT * FROM user 
        RIGHT JOIN account ON user.userid = account.userid 
        LEFT JOIN plantilla ON account.itemNumber = plantilla.itemNumber
        WHERE user.userid = '$userid'";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_array($res);
    } else {
        $errorMessage = "Please fill in personal information first.";
        echo "<script>window.location.href='ipcr.php?userid=$userid&alert=error&message=" . urlencode($errorMessage) . "';</script>";
        exit();
    }

    $visor = "SELECT * FROM account 
    INNER JOIN supervisor ON account.userid = supervisor.userid
    INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber
    WHERE plantilla.station = '$row[station]' AND account.type = 'Supervisor'";
    $vs = mysqli_query($conn, $visor);
    $row2 = mysqli_fetch_array($vs);

    $middlename = htmlspecialchars($row['middlename']);
    $middlename2 = htmlspecialchars($row2['middlename']);
    $middleInitial = !empty($middlename) ? substr($middlename, 0, 1) . '.' : '';
    $middleInitial2 = !empty($middlename2) ? substr($middlename2, 0, 1) . '.' : '';

    list($semester, $dateRange) = getSemester();

    $supervisor = htmlspecialchars($row2['firstname']) . " " . $middleInitial2 . " " . htmlspecialchars($row2['lastname']);

    ?>
    <div class="overlay loading">
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>
    <div class="row w-100">
        <div class="row w-100">
            <h1 class="font-bold text-center">PHILIPPINE NATIONAL POLICE</h1>
            <h1 class="font-bold text-center">NUP INDIVIDUAL PERFORMANCE COMMITMENT AND REVIEW (NUP IPCR)</h1>
            <h1 class="font-bold text-center">____<?php echo $semester . ' ' . date('Y') ?></h1>
        </div>
        <div class="row w-100 mt-4">
            <p>
                I <span class="underline underline-offset-2 text-uppercase"><?php echo htmlspecialchars($row['firstname']) . " " . $middleInitial . " " . htmlspecialchars($row['lastname']) ?></span>,
                <span class="underline underline-offset-2 text-uppercase"><?php echo htmlspecialchars($row['position']) . " " . htmlspecialchars($row['sgrade']) ?></span> of
                <span class="underline underline-offset-2 text-uppercase"><?php echo htmlspecialchars($row['designation'])  ?></span>
                commit to deliver and agree to be rated on the attainment of the following targets in accordance with the indicated measures for the period <span class="underline underline-offset-2"><?php echo $dateRange ?></span>.
            </p>
        </div>
        <div class="grid grid-cols-4 gap-4 mt-4">
            <div>
                <p class="w-700">Ratee:</p>
                <p class="mt-4 underline underline-offset-2 text-uppercase"><?php echo  htmlspecialchars($row['firstname']) . " " . $middleInitial . " " . htmlspecialchars($row['lastname']) ?></p>
                <p>Print Name and Signature</p>
            </div>
            <div>
                <p class="w-700">Date:</p>
                <p class="mt-4 sdateA" contenteditable="true"><?php echo date('F d, Y'); ?></p>
            </div>
            <div>
                <p class="w-700">Approved by:</p>
                <p class="mt-4 underline underline-offset-2 rater" contenteditable="true"><?php echo $supervisor ?></p>
                <p>Rater/Immediate Supervisor</p>
            </div>
            <div>
                <p class="w-700">Date:</p>
                <p class="mt-4 sdateB" contenteditable="true"><?php echo date('F d, Y'); ?></p>
            </div>
        </div>

        <div class="row mt-3">

            <table class="min-w-full border-collapse border border-gray-400">
                <tr>
                    <th rowspan="2" class="border-1 border-gray-400 text-center align-middle p-2 w-1/6">Output/Activity</th>
                    <th rowspan="2" class="border-1 border-gray-400 text-center align-middle p-2 w-1/6">Success Indicators (Measures)</th>
                    <th colspan="3" class="border-1 border-gray-400 text-center align-middle p-2 w-2/6">TARGETS</th>
                    <th colspan="3" class="border-1 border-gray-400 text-center align-middle p-2 w-2/6">ACCOMPLISHMENTS</th>
                    <th colspan="4" class="border-1 border-gray-400 text-center align-middle p-2 w-1/4">RATINGS</th>
                </tr>
                <tr>
                    <th class="border-1 border-gray-400 text-center p-2 w-2/12">Q</th>
                    <th class="border-1 border-gray-400 text-center p-2 w-1/12">T</th>
                    <th class="border-1 border-gray-400 text-center p-2 w-2/12">E</th>
                    <th class="border-1 border-gray-400 text-center p-2 w-2/12">Q</th>
                    <th class="border-1 border-gray-400 text-center p-2 w-1/12">T</th>
                    <th class="border-1 border-gray-400 text-center p-2 w-1/12">E</th>
                    <th class="border-1 border-gray-400 text-center p-2 w-1/12">Q</th>
                    <th class="border-1 border-gray-400 text-center p-2 w-1/12">T</th>
                    <th class="border-1 border-gray-400 text-center p-2 w-1/12">E</th>
                    <th class="border-1 border-gray-400 text-center p-2 w-1/12">AVERAGE</th>
                </tr>
                <tr>
                    <td class="border-1 w-700 border-gray-400 p-0" colspan="12">Core Functions:</td>
                </tr>
                <tbody>
                    <tr class="text-center align-middle core">
                        <td class="text-left function" contenteditable="true" data-column="core">1. Prepares weekly NUP DPAR for submision to Regional Headquarters</td>
                        <td class="success" contenteditable="true" data-column="success">No of accepted/approved prepared weekly report on DPAR on second submission with minimal changes one day before TD</td>
                        <td class="target-q" contenteditable="true" data-column="target-q">100% (1 of 1) Accepted/Approved weekly report on 2nd submission with minimal changes</td>
                        <td class="target-t" contenteditable="true" data-column="target-t">one day before the target date</td>
                        <td class="target-e" contenteditable="true" data-column="target-e">100% of the planned target prepared and submitted on the target date</td>
                        <td class="acc-q" contenteditable="true" data-column="acc-q">weekly report prepared accepted/approved on 2nd submission with minimal errors</td>
                        <td class="acc-t" contenteditable="true" data-column="acc-t">one day before TD</td>
                        <td class="acc-e" contenteditable="true" data-column="acc-e">1 per week</td>
                        <td class="ratings-q" data-column="ratings-q"></td>
                        <td class="ratings-t" data-column="ratings-t"></td>
                        <td class="ratings-e" data-column="ratings-e"></td>
                        <td class="w-700 avg-row"></td>
                    </tr>

                    <!-- Support  -->
                    <tr class="text-center align-middle">
                        <td class="text-right p-0" rowspan="2" colspan="8">Sub-total (70%)</td>
                        <td class="text-center align-middle p-0" colspan="3">Total</td>
                        <td class="sum-avg-row w-700 text-center align-middle p-0"></td>
                    </tr>
                    <tr class="text-center align-middle">
                        <td colspan="3" class="text-center align-middle p-0">Total x 70%</td>
                        <td class="sum-70 text-center align-middle w-700 p-0"></td>
                    </tr>

                    <tr>
                        <td class="border-1 w-700 border-gray-400 p-0" colspan="12">Support Functions:</td>
                    </tr>


                    <tr class="text-center align-middle support">
                        <td class="text-left function" contenteditable="true" data-column="support">1. Prepares weekly NUP DPAR for submision to Regional Headquarters</td>
                        <td class="success" contenteditable="true" data-column="success">No of accepted/approved prepared weekly report on DPAR on second submission with minimal changes one day before TD</td>
                        <td class="target-q" contenteditable="true" data-column="target-q">100% (1 of 1) Accepted/Approved weekly report on 2nd submission with minimal changes</td>
                        <td class="target-t" contenteditable="true" data-column="target-t">one day before the target date</td>
                        <td class="target-e" contenteditable="true" data-column="target-e">100% of the planned target prepared and submitted on the target date</td>
                        <td class="acc-q" contenteditable="true" data-column="acc-q">weekly report prepared accepted/approved on 2nd submission with minimal errors</td>
                        <td class="acc-t" contenteditable="true" data-column="acc-t">one day before TD</td>
                        <td class="acc-e" contenteditable="true" data-column="acc-e">1 per week</td>
                        <td class="ratings-q" data-column="ratings-q"></td>
                        <td class="ratings-t" data-column="ratings-t"></td>
                        <td class="ratings-e" data-column="ratings-e"></td>
                        <td class="w-700 avg-row"></td>
                    </tr>

                    <tr class="text-center align-middle">
                        <td class="text-right p-0" rowspan="2" colspan="8">Sub-total (30%)</td>
                        <td class="text-center align-middle p-0" colspan="3">Total</td>
                        <td class="total-70 w-700 text-center align-middle p-0"></td>
                    </tr>
                    <tr class="text-center align-middle">
                        <td colspan="3" class="text-center align-middle p-0">Total x 30%</td>
                        <td class="total-30 text-center align-middle w-700 p-0"></td>
                    </tr>

                    <tr class="text-center align-middle">
                        <td class="text-right p-2 w-700" colspan="8">Final Average Rating (70% + 30% = 100%)</td>
                        <td class="text-center align-middle p-0" colspan="3"></td>
                        <td class="final-avg w-700 text-center align-middle p-0"></td>
                    </tr>
                    <tr class="text-center align-middle">
                        <td class="text-right p-2 w-700" colspan="8">Adjectival Rating:</td>
                        <td colspan="3" class="text-center align-middle p-0"></td>
                        <td class="vs-rating text-center align-middle w-700 p-0">VS</td>
                    </tr>
                </tbody>
            </table>
            <div class="grid grid-cols-2">
                <div class="border-1 border-top-0 border-gray-400">
                    <p class="border-b border-gray-400 p-1">I <span class="underline underline-offset-2 text-uppercase"><?php echo htmlspecialchars($row['firstname']) . " " . $middleInitial . " " . htmlspecialchars($row['lastname']) ?></span>,
                        <span class="underline underline-offset-2 text-uppercase"><?php echo htmlspecialchars($row['position']) . " " . htmlspecialchars($row['sgrade']) ?></span> of
                        <span class="underline underline-offset-2 text-uppercase"><?php echo htmlspecialchars($row['designation'])  ?></span> commit to deliver and agree to be
                        rated on the attainment of the above-mentioned targets in accordance with the indicated measures for the period <span class="underline underline-offset-2"><?php echo $dateRange ?></span>.
                    </p>
                    <div class="grid grid-cols-2 p-1">
                        <div class="mt-40">
                            <p>Approved by:</p>
                            <p class="mt-5 underline underline-offset-2 rater" contenteditable="true"><?php echo $supervisor ?></p>
                            <p>Rater/Immediate Supervisor</p>
                        </div>
                        <div class="mt-40">
                            <p>Ratee</p>
                            <p class="mt-5 edateA underline underline-offset-2" contenteditable="true"><?php echo date('F d, Y'); ?></p>
                            <p>Date</p>
                        </div>
                    </div>
                </div>
                <div class="border-b border-r border-gray-400">
                    <div class="grid grid-cols-2">
                        <div class="border-r border-b border-gray-400">
                            <p class="p-3">Comments and Recommendations for Development Purposes:</p>
                        </div>
                        <div class="border-b border-gray-400">
                            <p class="text-center align-middle p-3" contenteditable="true"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-4">
                        <div class="col-span-2 p-2">
                            <p>Discussed with:</p>
                            <p class="mt-3 underline underline-offset-2 "> <?php echo htmlspecialchars($row['firstname']) . " " . $middleInitial . " " . htmlspecialchars($row['lastname']) ?></span></p>
                            <p>Ratee</p>
                        </div>
                        <div class="p-2">
                            <p>Assessed by:</p>
                            <p class="mt-3 underline underline-offset-2 rater" contenteditable="true"><?php echo $supervisor ?></p>
                            <p>Rater</p>
                        </div>
                        <div class="p-2">
                            <p>Date:</p>
                            <p class="mt-3 edateB underline underline-offset-2" contenteditable="true"><?php echo date('F d, Y'); ?></p>
                            <p>Date</p>
                        </div>
                    </div>
                    <div class="border-y border-gray-400">
                        <p class="p-2">Performance Management Team Actions: </p>
                        <p class="text-center align-middle pb-2"> </p>
                    </div>
                    <div class="relative mt-5 p-4">
                        <p class="text-center underline underline-offset-4 pmt-head" contenteditable="true">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <p class="text-center pmt-pos" contenteditable="true"></p>
                        <p class="text-center">PMT Head</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed bottom-2 right-5 drag">
        <div class="relative inline-block text-left">
            <div class="p-3 bg-[#4f46e5] rounded-full cursor-move ">
                <i class="animate-spin-slow fa-solid fa-gear fa-fw cursor-pointer text-white drop cursor-pointer"></i>
            </div>
            <div class="drop-down absolute right-0 bottom-full z-10 mb-2 w-56 origin-bottom-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 transition-all duration-200 ease-in-out hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                    <a href="#" class="dropdown-link hover:bg-gray-100 text-gray-900 block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="add-core" role="menuitem" tabindex="-1"><i class="fa-solid fa-add fa-fw mr-2 text-[#7b8087]"></i>Add Core Function</a>
                    <a href="#" class="dropdown-link hover:bg-gray-100  block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="remove-core" role="menuitem" tabindex="-1"><i class="fa-solid fa-add fa-fw mr-2 rotate-45 text-[#7b8087]"></i>Remove Core Function</a>
                    <a href="#" class="dropdown-link hover:bg-gray-100  block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="add-support" role="menuitem" tabindex="-1"><i class="fa-solid fa-add fa-fw mr-2 text-[#7b8087]"></i>Add Support Function</a>
                    <a href="#" class="dropdown-link hover:bg-gray-100 block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="remove-support" role="menuitem" tabindex="-1"><i class="fa-solid fa-add fa-fw mr-2  rotate-45 text-[#7b8087]"></i>Remove Core Function</a>
                    <a href="#" class="dropdown-link hover:bg-gray-100 block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="save" role="menuitem" tabindex="-1"><i class="fa-solid fa-check fa-fw mr-2 text-[#7b8087]"></i>Save</a>
                </div>
            </div>
        </div>
    </div>

    <div class="relative z-10 confirm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base text-left font-semibold leading-6 text-gray-900" id="modal-title">Save Changes</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-left  text-gray-500">Are you sure you want to save this file? Once saved, this action cannot be undone, and any unsaved changes will be permanently lost.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" class=" inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Save</button>
                        <button type="button" id="cancel" class=" inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-300 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative z-10 saved" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    <div class="bg-white p-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start flex-wrap">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-[#16a34a]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </div>
                            <div class="text-center sm:ml-4 sm:mt-0 sm:text-left mt-3">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">File Saved</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Your file has been saved and is awaiting approval from your supervisor.</p>
                                </div>
                            </div>
                            <a href="dashboard.php?userid=<?php echo $userid ?>" class="w-100  mt-4"><button type="button" class=" w-100 inline-flex w-full justify-center bg-[#4f46e5] tranisition-all duration-200 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-indigo-500 sm:mt-0 sm:w-auto">Go back to dashboard</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.confirm, .saved').hide();
        $(document).ready(function() {

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

            initializeFlatpickr(".sdateA");
            initializeFlatpickr(".sdateB");
            initializeFlatpickr(".edateA");
            initializeFlatpickr(".edateB");

            $('#save').click(function() {
                $('.confirm').removeClass('hidden').hide().fadeIn(300);
            });

            // Close modal on Cancel button click
            $('#cancel').click(function() {
                $('.confirm').fadeOut(200, function() {
                    $(this).addClass('hidden');
                });
            });

            // Save button action
            $('button:contains("Save")').click(function() {
                $('.confirm').fadeOut(200, function() {
                    $(this).addClass('hidden');
                    saveIPCR();
                });
            });

            $('.drag').draggable({
                handle: '.p-3', // Set a handle for dragging
                containment: 'false' // Optional: restrict to the window
            });

            $('.drop').click(function(e) {
                e.stopPropagation(); // Prevent the click event from bubbling up
                const dropdown = $('.drop-down');
                dropdown.toggle();
                // dropdown.toggleClass('hidden'); // Toggle hidden class
                // dropdown.toggleClass('opacity-0 scale-95'); // Apply transition effects
                // dropdown.toggleClass('opacity-100 scale-100'); // Show full opacity and scale

            });

            $(document).click(function() {
                const dropdown = $('.drop-down');
                if (!dropdown.hasClass('hidden')) {
                    dropdown.addClass('hidden opacity-0 scale-95'); // Hide on outside click
                    dropdown.removeClass('opacity-100 scale-100');
                }
            });

            function saveIPCR() {
                // if ($(this).prop('disabled')) {
                //     return; // Exit the function if it's disabled
                // }

                $(this).prop('disabled', true);
                const rowsData = [];

                const jsonData = {
                    "Data": [],
                    "Core": [],
                    "Support": []
                };

                // Assuming you have a way to collect the user data separately
                const userData = {
                    userid: <?php echo $userid; ?>,
                    semester: <?php echo json_encode($semester); ?>,
                    year: <?php echo date('Y'); ?>,
                    sdateA: $('.sdateA').text(),
                    sdateB: $('.sdateB').text(),
                    edateA: $('.edateA').text(),
                    edateB: $('.edateB').text(),
                    pmt: $('.pmt-head').text(), // Adjust as needed
                    pmtPos: $('.pmt-pos').text(), // Adjust as needed
                    rater: $('.rater').first().text(), // Adjust as needed
                    supervisorid: <?php echo $row2['supervisorid']; ?>, // Adjust as needed
                };

                // Push user data to the Data array
                jsonData.Data.push(userData);

                // Collect core and support data
                $('tbody tr.core').each(function() {
                    const coreData = {};
                    $(this).find('td[data-column]').each(function() {
                        const columnName = $(this).data('column');
                        coreData[columnName] = $(this).text(); // Adjust according to your table structure
                    });
                    jsonData.Core.push(coreData);
                });

                $('tbody tr.support').each(function() {
                    const supportData = {};
                    $(this).find('td[data-column]').each(function() {
                        const columnName = $(this).data('column');
                        supportData[columnName] = $(this).text(); // Adjust according to your table structure
                    });
                    jsonData.Support.push(supportData);
                });

                // Convert to JSON string if needed
                const jsonString = JSON.stringify(jsonData, null, 2);
                console.log(jsonString); // Output to console or send to server

                // Send the JSON data to your server
                $.ajax({
                    url: 'action/saveIPCR.php', // Your PHP file that handles the update
                    type: 'POST',
                    data: {
                        data: jsonString
                    },
                    success: function(response) {
                        console.log('Data saved successfully:', response);
                        // Optionally re-enable the button here if you want
                        // $('#save').prop('disabled', true);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving data:', error);
                        // Optionally re-enable the button here if you want
                        // $('#save').prop('disabled', false);
                    }
                }).done(function() {
                    $(".loading").fadeOut(500, function() {
                        $('.saved').removeClass('hidden').hide().fadeIn(300);
                    });

                });
            }

            function addRow(type) {
                // Create a new row with empty cells
                const newRow = `
                <tr class="text-center align-middle ${type}">
                    <td class="text-left function" contenteditable="true" data-column="${type}"></td>
                        <td class="success" contenteditable="true" data-column="success"></td>
                        <td class="target-q" contenteditable="true" data-column="target-q"></td>
                        <td class="target-t" contenteditable="true" data-column="target-t"></td>
                        <td class="target-e" contenteditable="true" data-column="target-e"></td>
                        <td class="acc-q" contenteditable="true" data-column="acc-q"></td>
                        <td class="acc-t" contenteditable="true" data-column="acc-t"></td>
                        <td class="acc-e" contenteditable="true" data-column="acc-e"></td>
                        <td class="ratings-q" data-column="ratings-q"></td>
                        <td class="ratings-t" data-column="ratings-t"></td>
                        <td class="ratings-e" data-column="ratings-e"></td>
                        <td class="w-700 avg-row"></td>
                </tr>
            `;

                // Find the last row of the specified type and insert the new row after it
                $(`tbody tr.${type}`).last().after(newRow);
            }

            // Event listener for adding a core row
            $('#add-core').click(function(e) {
                e.preventDefault(); // Prevent default anchor behavior
                addRow('core'); // Call the function for core
            });

            // Event listener for adding a support row
            $('#add-support').click(function(e) {
                e.preventDefault(); // Prevent default anchor behavior
                addRow('support'); // Call the function for support
            });

            function removeRow(type) {
                const rows = $(`tbody tr.${type}`); // Select all rows of the specified type
                if (rows.length > 1) {
                    rows.last().remove(); // Remove the last row of that type
                }
            }

            // Event listener for removing a core row
            $('#remove-core').click(function(e) {
                e.preventDefault(); // Prevent default anchor behavior
                removeRow('core'); // Call the function for core
            });

            // Event listener for removing a support row
            $('#remove-support').click(function(e) {
                e.preventDefault(); // Prevent default anchor behavior
                removeRow('support'); // Call the function for support
            });
        })
    </script>
</body>

</html>