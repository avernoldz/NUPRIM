<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";
header('Content-Type: application/json');


$station = getSupervisorStation($conn, $_SESSION['supervisorid']);
$startCurrentMonth = date('Y-m-01'); // e.g., 2024-09-01
$endCurrentMonth = date('Y-m-t'); // e.g., 2024-09-30
$startLastMonth = date('Y-m-01', strtotime('first day of last month')); // e.g., 2024-08-01
$endLastMonth = date('Y-m-t', strtotime('last day of last month')); // e.g., 2024-08-31

// Fetch all leaves count for last month
$queryLastMonth = "SELECT COUNT(*) AS total FROM leaves 
                INNER JOIN account ON leaves.userid = account.userid
                INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
                WHERE status = 'Approve' 
                AND dateStart 
                BETWEEN '$startLastMonth' AND '$endLastMonth'
                AND plantilla.station = '$station'";

$resultLastMonth = mysqli_query($conn, $queryLastMonth);
$rowLastMonth = mysqli_fetch_assoc($resultLastMonth);
$totalLastMonth = $rowLastMonth['total'];

// Fetch all leaves count for current month
$queryCurrentMonth = "SELECT COUNT(*) AS total FROM leaves 
                        INNER JOIN account ON leaves.userid = account.userid
                        INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
                        WHERE status = 'Approve' 
                        AND dateStart 
                        BETWEEN '$startCurrentMonth' AND '$endCurrentMonth'
                       ";
if ($station !== 'PHQ') {
    $queryCurrentMonth .= " AND plantilla.station = '$station'";
}

$resultCurrentMonth = mysqli_query($conn, $queryCurrentMonth);
$rowCurrentMonth = mysqli_fetch_assoc($resultCurrentMonth);
$totalCurrentMonth = $rowCurrentMonth['total'];

function getWeeklyApprovedLeaves($conn, $startDate, $endDate, $station)
{
    $weeklyCounts = [];

    // Iterate through the weeks of the month
    for ($week = 0; $week < 4; $week++) {
        $weekStart = date('Y-m-d', strtotime($startDate . " +$week week"));
        $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));

        if ($weekEnd > $endDate) {
            $weekEnd = $endDate;
        }

        $query = "SELECT COUNT(*) as totalApproved FROM leaves 
                    INNER JOIN account ON leaves.userid = account.userid
                    INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
                    WHERE status = 'Approve' 
                    AND dateStart 
                    BETWEEN '$weekStart' AND '$weekEnd'";
        if ($station !== 'PHQ') {
            $query .= " AND plantilla.station = '$station'";
        }
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);

        $weeklyCounts[] = $data['totalApproved'];
    }

    return $weeklyCounts;
}

$currentMonthData = getWeeklyApprovedLeaves($conn, $startCurrentMonth, $endCurrentMonth, $station);
$lastMonthData = getWeeklyApprovedLeaves($conn, $startLastMonth, $endLastMonth, $station);


$formatStart = date('F d, Y', strtotime($startLastMonth)); // e.g., 2024-08-01
$formatEnd = date('F d, Y', strtotime($endCurrentMonth));

// Prepare data for the chart
$data = [
    'lastMonth' => $lastMonthData,
    'currentMonth' => $currentMonthData,
    'months' => $formatStart . ' - ' . $formatEnd,
];

echo json_encode($data);
