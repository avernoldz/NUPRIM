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
function getTotalAbsencesLastMonth($conn, $tableName, $startLastMonth, $endLastMonth, $station)
{

    $query = "SELECT COUNT(*) AS total 
              FROM `$tableName` 
              INNER JOIN account ON `$tableName`.userid = account.userid
              INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
              WHERE status = 'Approve' 
              AND dateStart BETWEEN ? AND ? 
              AND plantilla.station = ?";

    $stmt = $conn->prepare($query);
    $total = 0;
    if ($stmt) {
        $stmt->bind_param("sss", $startLastMonth, $endLastMonth, $station);
        $stmt->execute();
        $stmt->bind_result($total);
        if ($stmt->fetch()) {
            $stmt->close();
            return $total;
        } else {
            $stmt->close();
            return 0;
        }
    } else {
        return 0;
    }
}

$leaves = getTotalAbsencesLastMonth($conn, 'leaves', $startLastMonth, $endLastMonth, $station);
$detail = getTotalAbsencesLastMonth($conn, 'detail', $startLastMonth, $endLastMonth, $station);
$case = getTotalAbsencesLastMonth($conn, 'case', $startLastMonth, $endLastMonth, $station);

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
                    BETWEEN '$weekStart' AND '$weekEnd'
                    AND plantilla.station = '$station'";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);

        $weeklyCounts[] = $data['totalApproved'];
    }

    return $weeklyCounts;
}

$formatStart = date('F d, Y', strtotime($startLastMonth)); // e.g., 2024-08-01
$formatEnd = date('F d, Y', strtotime($endLastMonth));

// Prepare data for the chart
$data = [
    'leaves' => $leaves,
    'detail' => $detail,
    'case' => $case,
    'months' => $formatStart . ' - ' . $formatEnd,
];

// error_log(print_r($data, true));


echo json_encode($data);
