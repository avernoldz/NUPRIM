<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:../index.php?login-first");
}

include_once "../user/components/index.php";

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
    <title>IPCR</title>
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

        .spinner-grow {
            position: absolute;
            top: 50%;
            left: 50%;
        }

        @media print {
            .drag {
                display: none;
                /* Hide the drag element during print */
            }
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $ipcr = $_GET['ipcrid'];
    include "../../Connections/Include.php";

    $query = "SELECT * FROM user 
    INNER JOIN account ON user.userid = account.userid 
    INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber
    WHERE user.userid = '$userid'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($res);

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

    $sample = "SELECT * FROM ipcr WHERE ipcrid = '$ipcr'";
    $s = mysqli_query($conn, $sample);
    $rows = mysqli_fetch_array($s);

    $coreData = json_decode($rows['core'], true);
    $supportData = json_decode($rows['support'], true);

    ?>

    <div class="row w-100">
        <div class="row w-100">
            <h1 class="font-bold text-center">PHILIPPINE NATIONAL POLICE</h1>
            <h1 class="font-bold text-center">NUP INDIVIDUAL PERFORMANCE COMMITMENT AND REVIEW (NUP IPCR)</h1>
            <h1 class="font-bold text-center">____<?php echo $rows['semester'] . ' ' . $rows['year'] ?></h1>
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
                <p class="mt-4 sdateA"><?php echo $rows['sdateA'] ?></p>
            </div>
            <div>
                <p class="w-700">Approved by:</p>
                <p class="mt-4 underline underline-offset-2 rater"><?php echo $rows['rater'] ?></p>
                <p>Rater/Immediate Supervisor</p>
            </div>
            <div>
                <p class="w-700">Date:</p>
                <p class="mt-4 sdateB"><?php echo $rows['sdateB'] ?></p>
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
                    <th class="border-1 border-gray-400 text-center p-2 w-1/12">E</th>
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
                    <!-- Core -->
                    <?php foreach ($coreData as $item): ?>
                        <tr class="text-center align-middle core">
                            <td class="text-left function" data-column="core"><?= htmlspecialchars($item['core']) ?></td>
                            <td class="success" data-column="success"><?= htmlspecialchars($item['success']) ?></td>
                            <td class="target-q" data-column="target-q"><?= htmlspecialchars($item['target-q']) ?></td>
                            <td class="target-t" data-column="target-t"><?= htmlspecialchars($item['target-t']) ?></td>
                            <td class="target-e" data-column="target-e"><?= htmlspecialchars($item['target-e']) ?></td>
                            <td class="acc-q" data-column="acc-q"><?= htmlspecialchars($item['acc-q']) ?></td>
                            <td class="acc-t" data-column="acc-t"><?= htmlspecialchars($item['acc-t']) ?></td>
                            <td class="acc-e" data-column="acc-e"><?= htmlspecialchars($item['acc-e']) ?></td>
                            <td class="ratings-q" data-column="ratings-q"><?= htmlspecialchars($item['ratings-q']) ?></td>
                            <td class="ratings-t" data-column="ratings-t"><?= htmlspecialchars($item['ratings-t']) ?></td>
                            <td class="ratings-e" data-column="ratings-e"><?= htmlspecialchars($item['ratings-e']) ?></td>
                            <td class="w-700 avg-row"></td>
                        </tr>
                    <?php endforeach; ?>

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


                    <?php foreach ($supportData as $item): ?>
                        <tr class="text-center align-middle support">
                            <td class="text-left function" data-column="support"><?= htmlspecialchars($item['support']) ?></td>
                            <td class="success" data-column="success"><?= htmlspecialchars($item['success']) ?></td>
                            <td class="target-q" data-column="target-q"><?= htmlspecialchars($item['target-q']) ?></td>
                            <td class="target-t" data-column="target-t"><?= htmlspecialchars($item['target-t']) ?></td>
                            <td class="target-e" data-column="target-e"><?= htmlspecialchars($item['target-e']) ?></td>
                            <td class="acc-q" data-column="acc-q"><?= htmlspecialchars($item['acc-q']) ?></td>
                            <td class="acc-t" data-column="acc-t"><?= htmlspecialchars($item['acc-t']) ?></td>
                            <td class="acc-e" data-column="acc-e"><?= htmlspecialchars($item['acc-e']) ?></td>
                            <td class="ratings-q" data-column="ratings-q"><?= htmlspecialchars($item['ratings-q']) ?></td>
                            <td class="ratings-t" data-column="ratings-t"><?= htmlspecialchars($item['ratings-t']) ?></td>
                            <td class="ratings-e" data-column="ratings-e"><?= htmlspecialchars($item['ratings-e']) ?></td>
                            <td class="w-700 avg-row"></td>
                        </tr>
                    <?php endforeach; ?>

                    <tr class="text-center align-middle">
                        <td class="text-right p-0" rowspan="2" colspan="8">Sub-total (30%)</td>
                        <td class="text-center align-middle p-0" colspan="3">Total</td>
                        <td class="sum-avg-row-30 w-700 text-center align-middle p-0"></td>
                    </tr>
                    <tr class="text-center align-middle">
                        <td colspan="3" class="text-center align-middle p-0">Total x 30%</td>
                        <td class="sum-30 text-center align-middle w-700 p-0"></td>
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
                    <div class="grid grid-cols-2 p-2">
                        <div class="mt-40">
                            <p>Approved by:</p>
                            <p class="mt-5 underline underline-offset-2 rater"><?php echo $rows['rater'] ?></p>
                            <p>Rater/Immediate Supervisor</p>
                        </div>
                        <div class="mt-40">
                            <p>Ratee</p>
                            <p class="mt-5 edateA underline underline-offset-2"><?php echo $rows['edateA'] ?></p>
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
                            <p class="text-center align-middle p-3 comments" data-column="comments"><?php echo $rows['comments'] ?></p>
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
                            <p class="mt-3 underline underline-offset-2 rater"><?php echo $rows['rater'] ?></p>
                            <p>Rater</p>
                        </div>
                        <div class="p-2">
                            <p>Date:</p>
                            <p class="mt-3 edateB underline underline-offset-2"><?php echo $rows['edateB'] ?></p>
                            <p>Date</p>
                        </div>
                    </div>
                    <div class="border-y border-gray-400">
                        <p class="p-2">Performance Management Team Actions: </p>
                        <p class="text-center align-middle pb-2 action" data-column="action"><?php echo $rows['action'] ?></p>
                    </div>
                    <div class="relative mt-5 p-4">
                        <p class="text-center underline underline-offset-4 pmt-head"><?php echo $rows['pmt'] ?></p>
                        <p class="text-center pmt-pos"><?php echo $rows['pmtPos'] ?></p>
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
                    <a href="#" class="dropdown-link hover:bg-gray-100 block px-4 py-2 text-sm text-gray-700" data-userid="<?php echo $userid; ?>" id="print" role="menuitem" tabindex="-1"><i class="fa-solid fa-print fa-fw mr-2 text-[#7b8087]"></i>Print Report</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#print').click(function(e) {
                e.preventDefault();
                window.print();
            })

            function calculateRowAverages() {
                $('tr.core, tr.support').each(function() {
                    const $row = $(this);

                    // Get the values from the rating cells
                    const ratingQ = parseFloat($row.find('.ratings-q').text()) || 0;
                    const ratingT = parseFloat($row.find('.ratings-t').text()) || 0;
                    const ratingE = parseFloat($row.find('.ratings-e').text()) || 0;

                    // Calculate the average
                    const average = (ratingQ + ratingT + ratingE) / 3;

                    // Update the avg-row cell
                    $row.find('.avg-row').text(average.toFixed(2)); // Show 2 decimal places
                    calculateOverallAverage();
                });
            }

            calculateRowAverages();

            function calculateOverallAverage() {
                let totals = {
                    core: {
                        total: 0,
                        count: 0
                    },
                    support: {
                        total: 0,
                        count: 0
                    }
                };

                // Calculate totals and counts for both core and support rows
                $('tr.core .avg-row, tr.support .avg-row').each(function() {
                    const value = parseFloat($(this).text());
                    if (!isNaN(value)) {
                        const isCore = $(this).closest('tr').hasClass('core');
                        totals[isCore ? 'core' : 'support'].total += value;
                        totals[isCore ? 'core' : 'support'].count++;
                    }
                });

                // Calculate averages and their respective percentages
                const coreAverage = totals.core.count > 0 ? (totals.core.total / totals.core.count).toFixed(2) : '0.00';
                const supportAverage = totals.support.count > 0 ? (totals.support.total / totals.support.count).toFixed(2) : '0.00';
                const times70 = (coreAverage > 0 ? (coreAverage * 0.70).toFixed(2) : '0.00');
                const times30 = (supportAverage > 0 ? (supportAverage * 0.30).toFixed(2) : '0.00');
                const totalAvg = (parseFloat(times70) + parseFloat(times30)).toFixed(2);
                // Display results
                $('.sum-avg-row').text(coreAverage);
                $('.sum-70').text(times70);
                $('.sum-avg-row-30').text(supportAverage);
                $('.sum-30').text(times30);
                $('.final-avg').text(totalAvg);

            }

            $('.drag').draggable({
                handle: '.p-3', // Set a handle for dragging
                containment: 'false' // Optional: restrict to the window
            });

            $('.drop').click(function(e) {
                e.stopPropagation(); // Prevent the click event from bubbling up
                const dropdown = $('.drop-down');
                dropdown.toggleClass('hidden opacity-0 scale-95'); // Use classes for better control
                dropdown.toggleClass('opacity-100 scale-100');

            });

            $(document).click(function() {
                const dropdown = $('.drop-down');

                if (dropdown.is(':visible') && !dropdown.is(event.target) && dropdown.has(event.target).length === 0) {
                    // Hide on outside click
                    dropdown.addClass('hidden opacity-0 scale-95');
                    dropdown.removeClass('opacity-100 scale-100');
                }
            });
        })
    </script>
</body>

</html>