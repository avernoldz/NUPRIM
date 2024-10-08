<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

require '../../../vendor/autoload.php'; // Change the path as needed if you're not using Composer

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['compile'])) {

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);

    $type = $_POST['type'];
    $supervisorid = $_SESSION['supervisorid'];
    $row1 = getSupervisorInfo($conn, $supervisorid);
    $station = $row1['station'];
    $name = getFullName($conn, $supervisorid);
    $personnel = getPersonnel($conn, $station);
    $getDateRange = getDateRange($type);
    $combinedReports = fetchCombinedReports($conn, $personnel, $type);
    $list = getList($conn, $station);


    switch ($type) {
        case 'Monthly':
            $save = generateMonthlyAbsences($dompdf, $type, $combinedReports, $getDateRange, $name, $row1, $station);
            break;
        case 'Weekly':
            $save = generateWeeklyAbsences($dompdf, $type, $combinedReports, $getDateRange, $name, $row1, $station);
            break;
        case 'Recap':
            $recap = getRecapData($conn, $station);
            $sickLeave = getRecapSickLeave($conn, $station, 'leaveType', 'leaves', 'Sick Leave');
            $vacation = getRecapSickLeave($conn, $station, 'leaveType', 'leaves', 'Vacation Leave');
            $awol = getRecapSickLeave($conn, $station, 'leaveType', 'leaves', 'AWOL');
            $absent = getRecapSickLeave($conn, $station, 'leaveType', 'leaves', 'Leave w/o Leave');
            $preventive = getRecapSickLeave($conn, $station, 'sunction', '`case`', 'Preventive Suspension');
            $suspension = getRecapSickLeave($conn, $station, 'sunction', '`case`', 'Suspension');
            $detention = getRecapSickLeave($conn, $station, 'sunction', '`case`', 'Detention');
            // print_r($recap);
            $getDateRange = getDateRange('Monthly');
            generateRecap($dompdf, $type, $recap, $getDateRange, $name, $row1, $station, $sickLeave, $vacation, $preventive, $suspension, $detention, $awol, $absent);
            break;
        case 'Alpha':
            $getDateRange = getDateRange('Monthly');
            generateAplha($dompdf, $type, $list, $getDateRange, $name, $row1, $station);
            break;
        case 'Roster':
            $getDateRange = getDateRange('Monthly');
            generateRoster($dompdf, $type, $list, $getDateRange, $name, $row1, $station);
            break;
        case 'AnnexA':
            $getDateRange = getDateRange('Monthly');
            generateAnnexA($dompdf, $type, $list, $getDateRange, $name, $row1, $station);
            break;
        case 'AnnexB':
            $getDateRange = getDateRange('Monthly');
            generateAnnexB($dompdf, $type, $list, $getDateRange, $name, $row1, $station);
            break;
    }

    // $saveResult = saveReport($save, $type, $getDateRange, $supervisorid, $conn);
    // // Execute the statement
    // if ($saveResult['success']) {
    //     header("Location: ../report.php");
    //     exit();
    // } else {
    //     echo $saveResult['message'];
    // }

    $conn->close();
}
