<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location: ../index.php?login-first");
}
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
    <title>Home</title>
    <style>
        body {
            background: #efefef;
        }

        .data:nth-of-type(odd) {
            background-color: #f3f9ff;
        }

        .data:hover {
            box-shadow: inset 1px 0 0 #dadce0, inset -1px 0 0 #dadce0, 2px 2px 2px 0 rgba(60, 64, 67, .3), 0 1px 3px 1px rgba(60, 64, 67, .15);
            z-index: 2000;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="loader loading hidden">
        <div class="justify-content-center jimu-primary-loading"></div>
    </div>
    <?php
    $userid = $_SESSION['userid'];
    $active = "Home";
    $on = "off";
    include "../../Connections/Include.php";
    include "components/index.php";
    include "sideBar.php";

    $firstname = getFullName($conn, $userid);
    list($semester, $dateRange) = getSemester();
    $station = getSupervisorStation($conn, $userid);
    $announcements = getAnnouncement($conn, $station);
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

    $thresholdDate = strtotime('-2 days');
    ?>
    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Home</span></h1>
            </div>
        </div>

        <div class="flex mt-3 ">
            <div class="div ml-2">
                <h1 class="h3 font-bold">Hello, <?php echo $firstname ?></h1>
                <p><?php echo $semester . ' of the year ' . date('Y')  ?></p>
            </div>
            <div class="flex align-items-center">
                <p class="font-medium"><?php echo date('j F, Y') ?> </p>
                <span class="inline-block ml-4 bg-gray-200 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        </div>

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
            <div class="col-span-2 row-span-3 col-start-4">
                <div class="box border border-gray-500 h-100 border border-gray-500">
                    <div class="title mb-2">
                        <p class="font-medium">Announcement</p>
                        <p class="text-[11px] text-gray-500">Updates and latest announcements</p>
                    </div>
                    <div class="p-3 h-[45vh] overflow-auto">
                        <?php foreach ($announcements as $announcement):
                            $isNew = strtotime($announcement['created_at']) >= $thresholdDate;
                        ?>
                            <div class="mb-2 ">
                                <div class="flex justify-content-between">
                                    <div class="flex items-center">
                                        <p class="font-semibold"><?php echo htmlspecialchars($announcement['title']); ?></p>
                                        <?php if ($isNew): ?>
                                            <span class="ml-2 text-xs text-white bg-red-500 rounded-sm px-2">New</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="font-semibold"><?php echo date('F j, Y', strtotime(htmlspecialchars($announcement['created_at']))); ?></p>
                                </div>
                                <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($announcement['message'])); ?></p>
                            </div>
                            <hr class="border-1 border-gray-400 mb-2">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-span-2 row-span-2 col-start-4 row-start-4">
                <div class="box border border-gray-500 h-100">
                    <div class="title mb-2">
                        <p class="font-medium">Detail Orders</p>
                        <p class="text-[11px] text-gray-500">User pending and approved orders</p>
                    </div>
                    <div class="w-100 mt-3 overflow-auto h-64">
                        <?php renderLeaveList($res4, 'details'); ?>
                    </div>
                </div>
            </div>
            <div class="col-span-2 row-span-2 row-start-4 col-start-2">
                <div class="box border border-gray-500 h-full flex flex-col">
                    <div class="title mb-2">
                        <p class="font-medium">Filed Leaves</p>
                        <p class="text-[11px] text-gray-500">User pending and approved leaves</p>
                    </div>
                    <div class="w-100 mt-3 overflow-auto h-64">
                        <?php renderLeaveList($res2, 'leaves'); ?>
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
    <script>
        var labels = <?php echo $labelsJson; ?>; // Use the labels from PHP
        var data = <?php echo $dataJson; ?>; // This will be a 2D array

        function createChart(labels, data) {
            var ctx = document.getElementById('myChart').getContext('2d');

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
                    barThickness: 25,
                    borderWidth: 2,
                    borderRadius: 5,
                },
                {
                    label: 'Q Rating',
                    data: data.map(row => row[1]), // Accessing the second column for Q Rating
                    backgroundColor: colors[1].backgroundColor,
                    borderColor: colors[1].borderColor,
                    barThickness: 25,
                    borderWidth: 2,
                    borderRadius: 5,
                },
                {
                    label: 'T Rating',
                    data: data.map(row => row[2]), // Accessing the third column for T Rating
                    backgroundColor: colors[2].backgroundColor,
                    borderColor: colors[2].borderColor,
                    barThickness: 25,
                    borderWidth: 2,
                    borderRadius: 5,
                },
                {
                    label: 'E Rating',
                    data: data.map(row => row[3]), // Accessing the fourth column for E Rating
                    backgroundColor: colors[3].backgroundColor,
                    borderColor: colors[3].borderColor,
                    barThickness: 25,
                    borderWidth: 2,
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
    </script>

</body>

</html>