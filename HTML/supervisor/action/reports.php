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

    @$fromFormatted =  strtotime($_POST['from']); // First day of last month
    @$toFormatted =  strtotime($_POST['to']); // Last day of last month

    $from = date('Y-m-d', $fromFormatted);
    $to = date('Y-m-d', $toFormatted);

    $type = $_POST['type'];
    $supervisorid = $_SESSION['supervisorid'];
    $row1 = getSupervisorInfo($conn, $supervisorid);
    $station = $row1['station'];
    $name = getFullName($conn, $supervisorid);
    $personnel = getPersonnel($conn, $station);
    $getDateRange = getDateRangeD($from, $to);
    $combinedReports = fetchCombinedReports($conn, $personnel, $from, $to);
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
            $getDateRange = getDateRangeD($from, $to);
            $save = generateRecap($dompdf, $type, $recap, $getDateRange, $name, $row1, $station, $sickLeave, $vacation, $preventive, $suspension, $detention, $awol, $absent);
            break;
        case 'Alpha':
            $getDateRange = getDateRangeD($from, $to);
            $save = generateAplha($dompdf, $type, $list, $getDateRange, $name, $row1, $station);
            break;
        case 'Roster':
            $getDateRange = getDateRangeD($from, $to);
            $save = generateRoster($dompdf, $type, $list, $getDateRange, $name, $row1, $station);
            break;
        case 'Annex':
            $getDateRange = getDateRangeD($from, $to);
            $save = generateAnnexA($dompdf, $type, $list, $getDateRange, $name, $row1, $station);
            break;
        case 'AnnexB':
            $getDateRange = getDateRangeD($from, $to);
            $save = generateAnnexB($dompdf, $type, $list, $getDateRange, $name, $row1, $station);
            break;
    }

    $saveResult = saveReport($station, $save, $type, $getDateRange, $supervisorid, $conn);
    // Execute the statement
    if ($saveResult['success']) {
        echo "<script>window.location.href='../report.php?alert=success&message=Report saved and compiled';</script>";
        exit();
    } else {
        echo $saveResult['message'];
    }

    $conn->close();
}
