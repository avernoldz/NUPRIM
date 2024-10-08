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

    <?php
    $userid = $_SESSION['userid'];
    $active = "Home";
    $on = "off";
    include "../../Connections/Include.php";
    include "components/index.php";
    include "sideBar.php";

    $firstname = getFullName($conn, $userid);
    list($semester, $dateRange) = getSemester();

    $query = "SELECT year, semester, userid, finalRating FROM ipcr WHERE userid = '$userid'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($res);

    function select($conn, $table, $userid)
    {
        $sql = "SELECT * FROM $table WHERE userid = '$userid' ORDER BY dateStart DESC";
        return mysqli_query($conn, $sql);
    }

    function formatDate($date)
    {
        return date('M d, y', strtotime($date)); // Convert to timestamp and format
    }

    function getStatusLabel($status)
    {
        $class = '';
        switch ($status) {
            case 'Pending':
                $class = 'bg-yellow-100 text-yellow-700 border-yellow-300 w-[72px]';
                break;
            case 'Approve':
                $class = 'bg-green-100 text-green-700 border-green-300 w-[72px]';
                break;
            case 'Reject':
                $class = 'bg-red-100 text-red-700 border-red-300 w-[72px] text-center';
                $status = 'Rejected';
                break;
        }
        return '<label class="form-label ' . $class . ' text-sm rounded p-0.5 mb-0 w-500 px-2 border-1">' . $status . '</label>';
    }

    function renderLeaveList($result, $link)
    {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $start = formatDate($row['dateStart']);
                $end = formatDate($row['dateEnd']);
                $statusLabel = getStatusLabel($row['status']);
    ?>
                <div class="bg-[#e4f2ff] data px-3 p-1">
                    <a href="<?php echo $link ?>.php" class="flex align-items-center">
                        <p><?php echo $start . ' - ' . $end; ?></p>
                        <p><?php echo $statusLabel; ?></p>
                    </a>
                </div>
    <?php
            }
        } else {
            echo "<p class='text-center'>No data found</p>";
        }
    }

    $res2 = select($conn, 'leaves', $userid);
    $res3 = select($conn, '`case`', $userid);
    $res4 = select($conn, '`detail`', $userid);



    $labels = [];
    $data = [];

    // Fetch data and populate arrays
    while ($row = mysqli_fetch_assoc($res)) {
        $labels[] = $row['semester'] . " " . $row['year']; // Concatenate year and semester
        $data[] = $row['finalRating'];
    }

    ?>
    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">Home</span></h1>
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
            <div class="col-span-3 box row-span-3">
                <div>
                    <canvas id="myChart" class="w-100"></canvas>
                </div>
            </div>
            <div class="col-span-2 row-span-2 col-start-4">
                <div class="box h-100">
                    <h1 class="font-medium ">Filed Leaves</h1>
                    <div class="w-100 mt-3">
                        <?php renderLeaveList($res2, 'leaves'); ?>
                    </div>
                </div>
            </div>
            <div class="col-span-2 row-span-3 col-start-4 row-start-3">
                <div class="box h-100">
                    <h1 class="font-medium">Detail Orders</h1>
                    <div class="w-100 mt-3">
                        <?php renderLeaveList($res4, 'details'); ?>
                    </div>
                </div>
            </div>
            <div class="col-span-2 row-span-2 row-start-4">
                <div class="box h-100">
                    <h1 class="font-medium">IPCR</h1>
                    <div class="w-100 mt-3">
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
                                <div class="ipcr col-12 p-2 flex data flex-wrap justify-between items-center bg-[#e4f2ff]">
                                    <h2 class="font-bold ml-5">IPCR -
                                        <span class="font-normal"><?php echo emptyData("semester", $row2) . ' (' . emptyData("year", $row2) . ')' ?></span>
                                    </h2>
                                    <h2 class="font-bold">
                                        <span class="font-normal">Rating - </span> <?php echo emptyData("finalRating", $row2) ?>
                                    </h2>
                                    <div class="col-3 text-center">
                                        <span class="mr-5"><?php echo $stat; ?></span>
                                        <a href="viewipcr.php?ipcrid=<?php echo $row2["ipcrid"] ?>&userid=<?php echo $userid ?>&delete" class=" hover:bg-gray-300 hover:rounded-full p-2"><i class="fa-regular fa-eye fa-fw text-gray-600"></i></a>
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
            <div class="row-span-2 col-start-3 row-start-4">
                <div class="box h-100">
                    <h1 class="font-medium">Criminal Case</h1>
                    <div class="w-100 mt-3">
                        <?php renderLeaveList($res3, 'criminalCase'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('myChart');

        var labels = <?php echo json_encode($labels); ?>;
        var data = <?php echo json_encode($data); ?>;

        var colors = [];
        for (var i = 0; i < data.length; i++) {
            colors.push(i % 2 === 0 ? '#0055b5' : '#a32424');
        }


        new Chart(ctx, {
            type: 'bar',
            options: {
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 1,
                        to: 0,
                        loop: true
                    }
                },
                scales: {
                    y: { // defining min and max so hiding the dataset does not change scale range
                        min: 0,
                        max: 100
                    }
                }
            },
            data: {
                labels: labels,
                datasets: [{
                    label: 'IPCR Rating',
                    data: data,
                    barThickness: 60,
                    backgroundColor: colors,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>