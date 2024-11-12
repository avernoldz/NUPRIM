<?php
session_start();
session_regenerate_id();

if (!$_SESSION['supervisorid']) {
    header("Location:../index.php?login-first");
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

        #calendar {
            scrollbar-width: thin;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25em;
            font-weight: 600;
        }

        .fc .fc-button:not(:disabled) {
            background: transparent;
            border: none;
            color: black;
        }

        .fc .fc-daygrid-more-link {
            background-color: #00590c;
            color: white;
        }

        .data a:nth-child(odd) {
            background-color: #f3f9ff;
        }

        .data a:hover {
            box-shadow: inset 1px 0 0 #dadce0, inset -1px 0 0 #dadce0, 2px 2px 2px 0 rgba(60, 64, 67, .3), 0 1px 3px 1px rgba(60, 64, 67, .15);
            z-index: 2;
        }
    </style>
</head>

<body>

    <?php
    $supervisorid = $_SESSION['supervisorid'];
    $active = "Home";
    $on = "off";
    include "../../Connections/Include.php";
    include "components/index.php";
    include "sideBar.php";

    list($semester, $year) = getSemester();
    $station = getSupervisorStation($conn, $supervisorid);

    $query2 = "SELECT COUNT(account.userid) AS total_users
                    FROM user
                    INNER JOIN account ON user.userid = account.userid
                    INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
                    WHERE account.isArchive = TRUE";
    if ($station !== 'PHQ') {
        $query2 .= " AND plantilla.station = '$station'";
    }

    $results2 = mysqli_query($conn, $query2);
    $row2 = mysqli_fetch_array($results2);

    $firstname = getFullName($conn, $supervisorid);


    $sql = "SELECT leaves.*, firstname, lastname FROM leaves 
            INNER JOIN user ON leaves.userid = user.userid 
            INNER JOIN account ON leaves.userid = account.userid
            INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
            WHERE leaves.status = 'Approve'";

    if ($station !== 'PHQ') {
        $sql .= " AND plantilla.station = '$station'";
    }

    $result3 = $conn->query($sql);

    $sql2 = "SELECT leaves.*, firstname, lastname FROM leaves 
            INNER JOIN user ON leaves.userid = user.userid 
            INNER JOIN account ON leaves.userid = account.userid
            INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
            WHERE leaves.status = 'Pending'";

    if ($station !== 'PHQ') {
        $sql2 .= " AND plantilla.station = '$station'";
    }

    $result4 = mysqli_query($conn, $sql2);

    $queryip = "SELECT 
                    ipcr.year, 
                    ipcr.semester, 
                    AVG(ipcr.finalrating) AS average_rating
                FROM 
                    ipcr 
                INNER JOIN 
                    account ON ipcr.userid = account.userid
                INNER JOIN 
                    plantilla ON plantilla.itemNumber = account.itemNumber";

    if ($station !== 'PHQ') {
        $queryip .= " WHERE plantilla.station = '$station'";
    }

    $queryip .= " GROUP BY 
                    ipcr.year, 
                    ipcr.semester
                ORDER BY 
                    ipcr.year, 
                    ipcr.semester
                LIMIT 6;";

    $resip = mysqli_query($conn, $queryip);

    $labelsIp = [];
    $dataIp = [];

    // Fetch data and populate arrays
    while ($row = mysqli_fetch_assoc($resip)) {
        $parts = explode(' ', $row['semester']);
        $sem = $parts[0];
        $labelsIp[] =  $sem . " " . $row['year']; // Concatenate year and semester
        $dataIp[] = $row['average_rating'];
    }

    $events = [];

    if ($result3->num_rows > 0) {
        while ($row = $result3->fetch_assoc()) {
            $events[] = [
                'id' => $row['leaveid'],
                'title' => $row['firstname'] . ' ' . $row['lastname'],
                'description' => $row['leaveType'],
                'start' => $row['dateStart'],
                'end' => $row['dateEnd'],
                'color' => '#00590c'
            ];
        }
    }

    ?>
    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Dashboard</span></h1>
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


        <div class="grid grid-cols-5 grid-rows-5 gap-3 mt-3 h-[80vh]">
            <div class="box">
                <div class="row items-center">
                    <div class="col flex flex-col">
                        <h4 class="font-medium">Total Personnels</h4>
                        <h3 class="text-center text-5xl font-bold mt-2"><?php echo $row2['total_users'] ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-span-2  box row-span-3 h-full flex flex-col">
                <div>
                    <p class="font-medium">IPCR Ratings Average by Semester </p>
                    <p class="text-[11px] text-gray-500"><?php echo $semester . ' of the year ' . date('Y')  ?></p>
                    <p class="text-[11px] text-gray-500"></p>
                </div>
                <div class="flex-grow flex items-center justify-center">
                    <canvas id="myChart" class="w-100 max-h-80  m-auto"></canvas>
                </div>
            </div>
            <div class="col-span-2 box row-span-3 col-start-4 row-start-3 h-full">
                <div class="h-full">
                    <div id="calendar" class="h-full overflow-auto"></div>
                </div>
            </div>
            <div class="col-span-2 box row-span-2 col-start-4 row-start-1 h-full flex flex-col">
                <div>
                    <p class="font-medium">Approved Leaves (Last Month - Current Month) </p>
                    <p class="text-[11px] text-gray-500" id="pLm"></p>
                </div>
                <div class="flex-grow flex items-center justify-center">
                    <canvas id="lineChart" class="w-full max-h-44 m-auto"></canvas>
                </div>
            </div>
            <div class="box row-span-2 col-start-2 row-start-4 h-full flex flex-nowrap flex-col">
                <div>
                    <p class="font-medium">Monthly Representation: Absences</p>
                    <p class="text-[11px] text-gray-500" id="dlm"></p>
                </div>
                <div class="flex-grow flex items-center justify-center">
                    <canvas id="doughChart" class="w-full max-h-40 m-auto"></canvas>
                </div>
            </div>

            <div class="col-start-1 box row-start-2 row-span-2 h-full flex flex-col">
                <div class="title mb-2">
                    <p class="font-medium">Percentage Difference Ratings</p>
                    <p class="text-[11px] text-gray-500" id="dlm">Last Semester and Current Semester</p>
                </div>

                <?php
                $percentageRes = compareLastSemRating($conn, $station, $semester);
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
            <div class="col-start-1 box row-start-4 row-span-2 overflow-auto">
                <div>
                    <p class="font-medium">Leave Requests</p>
                    <p class="text-[11px] text-gray-500" id="elm">Filed leaves waiting for approval</p>
                </div>
                <div class="flex-grow flex items-center justify-center mt-3 text-[12px] data overflow-auto h-52">
                    <?php
                    if (mysqli_num_rows($result4) > 0) {
                        while ($row = mysqli_fetch_array($result4)) {
                    ?>
                            <a href="viewPersonnel.php?userid=<?php echo $row['userid']; ?>" class="w-100 bg-sky-100 p-2 flex justtify-content-between">
                                <p><?php echo htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']) ?></p>
                                <label class="form-label bg-yellow-100 rounded p-0.5 mb-0  text-yellow-700 w-500 px-2 border-1 border-yellow-300">Pending</label>
                            </a>
                    <?php
                        }
                    } else {
                        echo "No filed leaves";
                    }
                    ?>
                </div>
            </div>
            <div class="row-span-2 box col-start-3 row-start-4 h-full flex flex-col">
                <div>
                    <p class="font-medium">Criminal Case Types</p>
                    <p class="text-[11px] text-gray-500" id="clm">From September 01, 2024 - September 31, 2024</p>
                </div>
                <div class="flex-grow flex items-center justify-center">
                    <canvas id="polarChart" class="w-full max-h-40 m-auto"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('myChart');
        const pie = document.getElementById('pieChart');
        const dough = document.getElementById('doughChart');
        const polar = document.getElementById('polarChart');

        var events = <?php echo json_encode($events); ?>;

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                events: events, // Your fetched events from PHP
                editable: false,
                dayMaxEvents: true, // Use dayMaxEvents instead of eventLimit
                themeSystem: 'bootstrap5',

                // Use eventContent to customize how events are displayed
                eventContent: function(arg) {
                    const title = arg.event.title; // Get event title
                    const description = arg.event.extendedProps.description; // Get custom description
                    return {
                        html: `<div class='p-2'>${title}<br/><span>${description}</span></div>` // Customize HTML output
                    };
                }
            });
            calendar.render();
        });

        // Line Chart
        fetch('action/line.php')
            .then(response => response.json())
            .then(data => {
                const labels = ['Last Month', 'Current Month'];
                const chartData = [data.lastMonth, data.currentMonth, data.months];

                const plm = "From " + chartData[2];
                $('#pLm').text(plm);

                new Chart(document.getElementById('lineChart'), {
                    type: 'line',
                    data: {
                        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // Adjust labels according to your data
                        datasets: [{
                                label: 'Last Month',
                                data: chartData[0],
                                fill: false,
                                borderColor: 'rgba(106, 141, 245, 1)', // Transparent blue border color
                                backgroundColor: 'rgba(106, 141, 245, 0.5)', // Transparent blue background
                                tension: 0.1,
                                borderWidth: 2,
                                borderRadius: 5,
                            },
                            {
                                label: 'Current Month',
                                data: chartData[1],
                                fill: false,
                                borderColor: 'rgba(242, 143, 155, 1)', // Transparent pinkish-red border color
                                backgroundColor: 'rgba(242, 143, 155, 0.5)', // Transparent pinkish-red background
                                tension: 0.1,
                                borderWidth: 2,
                                borderRadius: 5,
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));


        // Doughnut Chart
        fetch('action/dough.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const labels = ['Last Month', 'Current Month'];
                const chartData = [data.leaves, data.detail, data.case, data.months];

                const dlm = "From " + chartData[3];
                $('#dlm').text(dlm);

                new Chart(dough, {
                    type: 'doughnut',
                    data: {
                        labels: ['Leaves', 'Detail Order', 'Criminal Case'],
                        datasets: [{
                            label: labels,
                            data: chartData,
                            backgroundColor: [
                                'rgba(106, 141, 245, 0.5)', // Transparent blue
                                'rgba(242, 143, 155, 0.5)', // Transparent pinkish-red
                                'rgba(169, 107, 247, 0.5)', // Transparent purple
                            ],
                            borderWidth: 2,
                            borderColor: [
                                '#6a8df5',
                                '#f28f9b',
                                '#a96bf7'
                            ],
                        }]
                    },
                    options: {
                        rotation: Math.PI, // Start from the left side
                        animation: {
                            animateRotate: true,
                            onProgress: function(animation) {
                                const chartInstance = animation.chart;
                                chartInstance.options.rotation += 0.01;
                            },
                            duration: 2000,
                            easing: 'easeInOutQuad'
                        },
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });


        // Polar Area Chart
        fetch('action/polar.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const labels = ['Last Month', 'Current Month'];
                const chartData = [data.count_preventive_suspension, data.count_suspension, data.count_detention, data.count_termination, data.date_range];

                const clm = "From " + chartData[4];
                $('#clm').text(clm);

                new Chart(polar, {
                    type: 'polarArea',
                    data: {
                        labels: ['Preventive Suspension', 'Suspension Order', 'Detention', 'Termination'],
                        datasets: [{
                            label: labels,
                            data: chartData,
                            backgroundColor: [
                                'rgba(242, 143, 155, 0.5)', // Transparent pinkish-red
                                'rgba(98, 216, 140, 0.5)', // Transparent green
                                'rgba(106, 141, 245, 0.5)', // Transparent blue
                                'rgba(169, 107, 247, 0.5)', // Transparent purple
                            ],
                            borderWidth: 2,
                            borderColor: [
                                '#f28f9b',
                                '#6a8df5',
                                '#62d88c',
                                '#a96bf7'
                            ],
                        }]
                    },
                    options: {
                        rotation: Math.PI,
                        animation: {
                            animateRotate: true,
                            onProgress: function(animation) {
                                const chartInstance = animation.chart;
                                chartInstance.options.rotation += 0.01;
                            },
                            duration: 2000,
                            easing: 'easeInOutQuad'
                        },
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                                position: 'bottom'
                            },
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });


        var labelsIP = <?php echo json_encode($labelsIp); ?>;
        var dataIP = <?php echo json_encode($dataIp); ?>;

        var colors = [];
        for (var i = 0; i < dataIP.length; i++) {
            colors.push(i % 2 === 0 ? 'rgba(106, 141, 245, 0.5)' : 'rgba(242, 143, 155, 0.5)');
        }

        var border = [];
        for (var i = 0; i < dataIP.length; i++) {
            border.push(i % 2 === 0 ? '#6a8df5' : '#f28f9b');
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsIP,
                datasets: [{
                    label: 'IPCR Rating',
                    data: dataIP,
                    barThickness: 40,
                    backgroundColor: colors,
                    borderColor: border,
                    borderWidth: 2, // Border color and width
                    borderRadius: 5, // Rounded corners for bars
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