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

$query = "
    SELECT 
        COUNT(CASE WHEN sunction = 'Preventive Suspension' THEN 1 END) AS count_preventive_suspension,
        COUNT(CASE WHEN sunction = 'Suspension' THEN 1 END) AS count_suspension,
        COUNT(CASE WHEN sunction = 'Detention' THEN 1 END) AS count_detention,
        COUNT(CASE WHEN sunction = 'Termination' THEN 1 END) AS count_termination
    FROM 
        `case`
    INNER JOIN account ON `case`.userid = account.userid
    INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
    WHERE 
        status = 'Approve' 
        AND dateStart BETWEEN ? AND ?";

if ($station !== 'PHQ') {
    $query .= " AND plantilla.station = '$station'";
}


$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("ss", $startLastMonth, $endLastMonth);
    $stmt->execute();
    $stmt->bind_result($count_preventive_suspension, $count_suspension, $count_detention, $count_termination);

    if ($stmt->fetch()) {
        $stmt->close();

        $formatStart = date('F d, Y', strtotime($startLastMonth)); // e.g., 2024-08-01
        $formatEnd = date('F d, Y', strtotime($endLastMonth));

        // Prepare data for the chart
        $data = [
            'count_preventive_suspension' => $count_preventive_suspension,
            'count_suspension' => $count_suspension,
            'count_detention' => $count_detention,
            'count_termination' => $count_termination,
            'date_range' => $formatStart . ' - ' . $formatEnd, // Optional: include the formatted date range
        ];
        echo json_encode($data);
    } else {
        $stmt->close();
    }
} else {
    // Handle error if the statement couldn't be prepared
    echo json_encode(['error' => 'Failed to prepare statement.']);
    $stmt->close();
}
