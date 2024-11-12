<?php

use Dompdf\Dompdf;
use Dompdf\Options;

function emptyData(string $emp, $row1 = null, $select = null)
{
    if (!empty($row1["$emp"])) {

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        if (preg_match($pattern, $row1["$emp"]) === 1) {
            $date = date_create("$row1[$emp]");
            return date_format($date, "F d, Y");
        } else {
            return $row1["$emp"];
        }
    } else {
        return $select;
    }
}

function formatSize($size)
{
    if ($size < 1024) return $size . ' bytes';
    elseif ($size < 1048576) return round($size / 1024, 2) . ' KB';
    else return round($size / 1048576, 2) . ' MB';
}

function logAction($mysqli, $userid, $action, $account_type)
{
    $stmt = $mysqli->prepare("INSERT INTO userlogs (action, userid, accountType) VALUES (?, ?, ?)");
    $stmt->bind_param('sis', $action, $userid, $account_type);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        echo "Failed to log action.";
    }

    $stmt->close();
}

function showToastr($message, $type)
{
    echo '<script>
        var alertMessage = "' . addslashes($message) . '";
        if (alertMessage) {
                toastr.' . $type . '(alertMessage);
        }
    </script>';
}

function getFileType($filename)
{
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    switch (strtolower($extension)) {
        case 'jpg':
        case 'jpeg':
        case 'png':
            return 'image';
        case 'pdf':
            return 'pdf';
        case 'docx':
        case 'xlsx':
            return 'document';
        default:
            return 'unknown';
    }
}

function selectName($conn, $userid)
{
    $query = "SELECT userid, firstname, lastname FROM user WHERE userid = '$userid'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($res);

    $name = $row['userid'] . $row['firstname'] . $row['lastname'];
    return $name;
}

function selectAssessed($conn, $userid)
{
    $query = "SELECT * FROM assessed WHERE userid = '$userid'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($res);

    return $row;
}

function countPendingIPCR($conn, $station)
{
    // Prepare and execute the query for IPCR count
    $ipcr_count = '';
    $leave_count = '';
    $query = "SELECT COUNT(ipcr.status) AS ipcr_count
              FROM ipcr
              INNER JOIN account ON account.userid = ipcr.userid
              INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber
              WHERE ipcr.status = 'Waiting for Approval'";

    if ($station !== 'PHQ') {
        $query .= " AND plantilla.station = '$station'";
    }

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->bind_result($ipcr_count);
    $stmt->fetch();
    $stmt->close();

    // Prepare and execute the query for leaves count
    $query2 = "SELECT COUNT(leaves.status) AS leave_count
                FROM leaves
                INNER JOIN account ON account.userid = leaves.userid
                INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber
                WHERE leaves.status = 'Pending'";

    if ($station !== 'PHQ') {
        $query2 .= " AND plantilla.station = '$station'";
    }
    $stmt2 = $conn->prepare($query2);
    $stmt2->execute();
    $stmt2->bind_result($leave_count);
    $stmt2->fetch();
    $stmt2->close();

    // Return both counts as an associative array
    return [
        'ipcr_count' => $ipcr_count,
        'leave_count' => $leave_count,
    ];
}


function getOffice($conn, $id)
{
    $query = "SELECT station 
    FROM plantilla 
    INNER JOIN account ON account.itemNumber = plantilla.itemNumber
    WHERE account.userid = '$id'";

    $res = mysqli_query($conn, $query);

    $row = mysqli_fetch_array($res);
    return $row['station'];
}

function getFullName($conn, $userid)
{
    // Prepare the SQL query using a prepared statement
    $query = "SELECT firstname, lastname FROM supervisor WHERE userid = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $userid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($res);
        if ($row) {
            $name = $row['firstname'] . ' ' . $row['lastname'];
            return $name;
        } else {
            return "User not found";
        }

        mysqli_stmt_close($stmt);
    } else {
        // Handle query preparation error
        return "Error preparing query: " . mysqli_error($conn);
    }
}

function getSemester()
{
    $today = date('Y-m-d'); // Current date
    $month = (int)date('m', strtotime($today)); // Extract the month as an integer
    $year = date('Y', strtotime($today)); // Extract the year

    if ($month >= 1 && $month <= 6) {
        $semester = "1st Semester";
        $dateRange = "January 1, $year to June 30, $year";
    } else {
        $semester = "2nd Semester";
        $dateRange = "July 1, $year to December 31, $year";
    }

    return [$semester, $dateRange]; // Return an array with both values
}

function fetchPersonnelReports($conn, $userid, $from, $to)
{
    // Ensure $from and $to are in valid date format (YYYY-MM-DD).
    if (empty($from) || empty($to) || !strtotime($from) || !strtotime($to)) {
        return "Invalid date range provided.";
    }

    // Build the date condition using the dynamic 'from' and 'to' values.
    $dateCondition = "dateStart BETWEEN '$from' AND '$to'";

    $queries = [
        'leaves' => "SELECT * FROM leaves WHERE userid = '$userid' AND status = 'Approve' AND $dateCondition",
        'details' => "SELECT * FROM detail WHERE userid = '$userid' AND status = 'Approve' AND $dateCondition",
        'cases' => "SELECT * FROM `case` WHERE userid = '$userid' AND status = 'Approve' AND $dateCondition"
    ];

    $results = [];
    foreach ($queries as $key => $query) {
        $results[$key] = mysqli_fetch_all(mysqli_query($conn, $query), MYSQLI_ASSOC);
    }

    return $results;
}


function getDateRange($type)
{

    $today = new DateTime();

    if ($type === 'Weekly') {
        // Get the date 6 days ago
        $startDate = clone $today;
        $startDate->modify('-6 days');

        // Format the dates
        $todayFormatted = $today->format('M d, Y');
        $startDateFormatted = $startDate->format('M d ');

        $startDateFormatted2 = $startDate->format('dmY');
        // Construct the output for weekly
        $dateRange = "$startDateFormatted -  $todayFormatted";
    } elseif ($type === 'Monthly') {

        $startDate = (clone $today)->modify('first day of last month');
        $endDate = (clone $today)->modify('last day of last month');

        // Format the dates
        $startDateFormatted = $startDate->format('F Y'); // First day of last month
        $endDateFormatted = $endDate->format('M d, Y'); // Last day of last month

        // Construct the output for monthly
        $dateRange = "$startDateFormatted";
        $startDateFormatted2 = $startDate->format('dmY');
    } else {
        return "Invalid type specified. Use 'weekly' or 'monthly'.";
    }

    return [
        'dateRange' => $dateRange,
        'startDateFormatted2' => $startDateFormatted2,
    ];
}

function getDateRangeD($from, $to)
{
    // Ensure the 'from' and 'to' dates are valid DateTime objects
    $fromDate = new DateTime($from);
    $toDate = new DateTime($to);

    // Format the dates for output
    $fromFormatted = $fromDate->format('M d, Y');
    $toFormatted = $toDate->format('M d, Y');

    // Generate a simplified version for internal use (for comparison, etc.)
    $fromFormatted2 = $fromDate->format('dmY');
    $toFormatted2 = $toDate->format('dmY');

    // Construct the range output
    $dateRange = "$fromFormatted - $toFormatted";

    return [
        'dateRange' => $dateRange,
        'startDateFormatted2' => $fromFormatted2,
        'endDateFormatted2' => $toFormatted2,
    ];
}

function compareLastSemRating($conn, $station, $semester, $userid = null)
{
    // Determine the last semester based on the current semester
    // $lastSem = ($semester == '1st Semester') ? '2nd Semester' : '1st Semester';

    // Get average ratings for current semester
    $results =  getAvgRating($conn, $station, $semester, $userid, '');

    if (count($results) >= 2) {
        // Save the first row in a variable
        $avgRatings = $results[0]['average_rating'];
        // Save the second row in another variable
        $avgLastRatings = $results[1]['average_rating'];
    } else {
        // Handle the case where there are not enough rows
        $avgRatings = null;
        $avgLastRatings = null;
    }
    // $avgRatings = getAvgRating($conn, $station, $semester, $userid, '');
    $avgCurrentSem = !empty($avgRatings) ? $avgRatings : 0; // Use 'average_rating'
    // Get average ratings for last semester
    // $avgLastRatings = getAvgRating($conn, $station, $semester, $userid, '');
    $avgLastSem = !empty($avgLastRatings) ? $avgLastRatings : 0; // Use 'average_rating'

    // Calculate the percentage difference
    if ($avgLastSem != 0) { // Prevent division by zero
        $percentageDifference = (($avgCurrentSem - $avgLastSem) / $avgLastSem) * 100;
    } else {
        $percentageDifference = $avgCurrentSem > 0 ? 100 : 0; // If last rating is 0, current rating is 100% better
    }
    // Format the output
    $percentageDifferenceFormatted = number_format($percentageDifference, 2); // Format to two decimal places
    $sign = $percentageDifference < 0 ? '' : '+';

    $percentage = "{$sign}{$percentageDifferenceFormatted}%";

    return [
        'percentage' => $percentage,
        'avg' => $avgCurrentSem,
    ];
}

function getSupervisorStation($conn, $supervisorid)
{
    $query = "SELECT station 
              FROM account 
              INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber 
              WHERE userid = ?";

    $station = '';
    $stmt = $conn->prepare($query);
    if ($stmt) {
        // Bind the parameter
        $stmt->bind_param("s", $supervisorid);

        $stmt->execute();
        $stmt->bind_result($station);
        if ($stmt->fetch()) {
            $stmt->close();
            return $station;
        } else {
            $stmt->close();
            return null;
        }
    } else {
        return null;
    }
}

function getSupervisorInfo($conn, $supervisorid)
{
    $query = "SELECT * FROM account 
              INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber 
              WHERE userid = '$supervisorid'";
    $results = mysqli_query($conn, $query);
    return mysqli_fetch_array($results);
}

function getPersonnel($conn, $station)
{
    $query = "SELECT user.userid, user.firstname, user.lastname, plantilla.itemNumber 
              FROM user
              INNER JOIN account ON user.userid = account.userid
              INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
              WHERE account.isArchive = TRUE";
    if ($station !== 'PHQ') {
        $query .= " AND plantilla.station = '$station'";
    }

    $results = mysqli_query($conn, $query);
    return mysqli_fetch_all($results, MYSQLI_ASSOC);
}

function getLastPromotion($conn, $user)
{
    $service = "SELECT * FROM servicehistory WHERE serviceid = '$user[serviceid]' ORDER BY datePromotion DESC LIMIT 1";
    $results = mysqli_query($conn, $service);
    return mysqli_fetch_all($results, MYSQLI_ASSOC);
}


function getList($conn, $station)
{
    $query = "WITH MaxTraining AS (
                    SELECT 
                        userid, 
                        dateStart, 
                        dateEnd,
                        DATEDIFF(dateEnd, dateStart) AS duration, -- Calculate duration
                        ROW_NUMBER() OVER (PARTITION BY userid ORDER BY DATEDIFF(dateEnd, dateStart) DESC) AS rn -- Rank by duration
                    FROM training
                ),
                MaxEligibility AS (
                    SELECT 
                        userid, 
                        dateOfExam,
                        dateStart, 
                        dateEnd,
                        DATEDIFF(dateEnd, dateStart) AS duration, -- Calculate duration
                        ROW_NUMBER() OVER (PARTITION BY userid ORDER BY DATEDIFF(dateEnd, dateStart) DESC) AS rn -- Rank by duration
                    FROM eligibility
                ),
                MaxService AS (
                    SELECT userid, MAX(lastPromotion) AS max_lastPromotion
                    FROM service
                    GROUP BY userid
                ),
                HighestEducation AS (
                    SELECT eb.userid, eb.degree
                    FROM educationalbackground eb
                    INNER JOIN (
                        SELECT userid, MAX(yearEnded) AS max_yearEnded
                        FROM educationalbackground
                        GROUP BY userid
                    ) AS max_education ON eb.userid = max_education.userid AND eb.yearEnded = max_education.max_yearEnded
                )
                SELECT 
                    user.firstname,
                    user.middlename,
                    user.lastname,
                    user.userid AS user_id,
                    user.qualifier,
                    user.status,
                    user.dateOfBirth,
                    account.itemNumber,
                    plantilla.*,
                    training.name AS training_name,
                    eligibility.eligibility AS eligibility_status,
                    s.permanency,
                    s.entered,
                    s.appStatus AS apptStatus,
                    MaxTraining.dateStart AS training_start_date,
                    MaxTraining.dateEnd AS training_end_date,
                    MaxEligibility.dateStart AS eligibility_start_date,
                    MaxEligibility.dateEnd AS eligibility_end_date,
                    MaxTraining.duration AS training_duration, -- Select duration for training
                    MaxEligibility.duration AS eligibility_duration, -- Select duration for eligibility
                    MaxEligibility.dateOfExam AS eligibility_date,
                    (SELECT datePromotion FROM servicehistory s2 WHERE s2.serviceid = s.serviceid ORDER BY s2.datePromotion DESC LIMIT 1) AS lastPromotion,
                    (SELECT lastPosition FROM servicehistory s2 WHERE s2.serviceid = s.serviceid ORDER BY s2.datePromotion DESC LIMIT 1) AS lastPosition,
                    edu.degree AS highest_degree
                FROM user
                INNER JOIN account ON user.userid = account.userid
                INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
                LEFT JOIN MaxTraining ON MaxTraining.userid = user.userid AND MaxTraining.rn = 1 -- Get only the longest duration
                LEFT JOIN MaxEligibility ON MaxEligibility.userid = user.userid AND MaxEligibility.rn = 1 -- Get only the longest duration
                LEFT JOIN MaxService ON MaxService.userid = user.userid
                LEFT JOIN training ON training.userid = user.userid AND training.dateStart = MaxTraining.dateStart
                LEFT JOIN eligibility ON eligibility.userid = user.userid AND eligibility.dateOfExam = MaxEligibility.dateOfExam
                LEFT JOIN service s ON s.userid = user.userid 
                LEFT JOIN HighestEducation edu ON edu.userid = user.userid 
                WHERE account.isArchive = TRUE";

    if ($station !== 'PHQ') {
        $query .= "  AND plantilla.station = '$station'";
    }

    $results = mysqli_query($conn, $query);
    return mysqli_fetch_all($results, MYSQLI_ASSOC);
}

function fetchCombinedReports($conn, $personnel, $from, $to)
{
    $combinedReports = [];

    foreach ($personnel as $person) {
        $userid = $person['userid'];
        $report = fetchPersonnelReports($conn, $userid, $from, $to);

        foreach (['details', 'leaves', 'cases'] as $category) {
            foreach ($report[$category] as $leave) {
                $combinedReports[] = [
                    'type' => $leave['orderType'] ?? $leave['leaveType'] ?? $leave['sunction'],
                    'userid' => $leave['userid'],
                    'firstname' => $person['firstname'],
                    'lastname' => $person['lastname'],
                    'itemNumber' => $person['itemNumber'],
                    'authorityNo' => $leave['authorityNo'],
                    'authorityDate' => $leave['authorityDate'],
                    'dateStart' => $leave['dateStart'],
                    'dateEnd' => $leave['dateEnd'],
                ];
            }
        }
    }

    usort($combinedReports, function ($a, $b) {
        return strcmp($a['type'], $b['type']);
    });

    return $combinedReports;
}

function getRecapData($conn, $station)
{

    $sql = "SELECT DISTINCT userid, sgrade FROM plantilla 
                INNER JOIN account ON account.itemNumber = plantilla.itemNumber
                WHERE account.isArchive = TRUE ";

    if ($station !== 'PHQ') {
        $sql .= "  AND plantilla.station = '$station'";
    }

    $sql .= " ORDER BY sgrade DESC";

    $sgradeData = [];

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $results = $stmt->get_result();
        $sgradeData = $results->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    return $sgradeData;
}

function getRecapSickLeave($conn, $station, $type, $table, $column)
{
    $firstDayLastMonth = date('Y-m-01', strtotime('first day of last month'));
    $lastDayLastMonth = date('Y-m-t', strtotime('last day of last month'));

    $sql = "SELECT DISTINCT account.userid, plantilla.sgrade 
            FROM plantilla 
            INNER JOIN account ON account.itemNumber = plantilla.itemNumber
            INNER JOIN $table ON $table.userid = account.userid
            WHERE account.isArchive = TRUE ";

    if ($station !== 'PHQ') {
        $sql .= "  AND plantilla.station = '$station'";
    }

    $sql .= " AND $table.$type = ?
            AND $table.status = 'Approve'
            AND (
                ($table.dateStart BETWEEN '$firstDayLastMonth' AND '$lastDayLastMonth') OR
                ($table.dateEnd BETWEEN '$firstDayLastMonth' AND '$lastDayLastMonth') OR
                ($table.dateStart <= '$firstDayLastMonth' AND $table.dateEnd >= '$lastDayLastMonth')
            )
            ORDER BY plantilla.sgrade DESC;";

    $sgradeData = [];

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $column);
        $stmt->execute();
        $results = $stmt->get_result();
        $sgradeData = $results->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    return $sgradeData;
}



function getAvgRating($conn, $station, $semester, $userid = null, $percentage = null)
{
    // Prepare the base SQL query
    $sql = "
        SELECT 
            ipcr.year, 
            ipcr.semester, 
            AVG(ipcr.finalrating) AS average_rating
        FROM 
            ipcr 
        INNER JOIN 
            account ON ipcr.userid = account.userid
        INNER JOIN 
            plantilla ON plantilla.itemNumber = account.itemNumber
    ";

    if ($station !== 'PHQ') {
        $sql .= " WHERE plantilla.station = '$station'";
    }

    // Add the userid condition if it is provided
    if ($percentage === null) {
        $sql .= " AND ipcr.semester = '$semester'";
    }

    // Add the userid condition if it is provided
    if ($userid !== null) {
        $sql .= " AND ipcr.userid = '$userid'";
    }

    $sql .= " GROUP BY 
            ipcr.year, 
            ipcr.semester
        ORDER BY 
            ipcr.year DESC, 
            ipcr.semester DESC";

    // Limit the results if percentage is provided
    if ($percentage !== null) {
        $sql .= " LIMIT 2";
    }

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters

        // if ($percentage !== null && $userid !== null) {
        //     $stmt->bind_param("ss", $station, $userid);
        // } elseif ($userid !== null) {
        //     $stmt->bind_param("sss", $station, $semester, $userid);
        // } elseif ($percentage !== null) {
        //     $stmt->bind_param("s", $station);
        // } elseif ($station !== 'PHQ') {
        //     $stmt->bind_param("s", $station);
        // } else {
        //     $stmt->bind_param("ss", $station, $semester);
        // }

        $stmt->execute();

        // Fetch the results
        $result = $stmt->get_result();
        $ratings = [];

        while ($row = $result->fetch_assoc()) {
            $ratings[] = $row;
        }

        // Close the statement
        $stmt->close();
        return $ratings; // Return the results
    } else {
        echo "Error preparing statement: " . $conn->error;
        return [];
    }
}

function renderLeaveRow($absent, $station, $index)
{
    echo "<tr>
            <td>{$index}</td>
            <td>NUP</td>
            <td>" . htmlspecialchars($absent['firstname']) . ' ' . htmlspecialchars($absent['lastname']) . "</td>
            <td>" . htmlspecialchars($absent['itemNumber']) . "</td>
            <td>{$station}</td>
            <td>" . date('F d, Y', strtotime($absent['dateStart'])) . "</td>
            <td>" . date('F d, Y', strtotime($absent['dateEnd'])) . "</td>
            <td>" . emptyData("authorityNo", $absent) . "</td>
            <td>" . date('F d, Y', strtotime($absent['authorityDate'])) . "</td>
          </tr>";
}


function generateMonthlyAbsences($dompdf = null, $type, $combinedReports, $getDateRange, $name, $row1, $station)
{
    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reports Monthly</title>
        <style>
            * {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #737373;
                text-align: center;
            }

            .header,
            .footer {
                text-align: center;
                font-size: 12px;
            }

            .regular {
                font-size: 11px;
            }
        </style>
    </head>

    <body>
        <div style="height: auto;">
            <div style="height: auto;">
                <div class="header">
                    <p style="margin: 0; font-weight:bold;">PNP PERSONNEL ACCOUNTING REPORT</p>
                    <p style="margin: 0;">Annex to PNP Personel Accounting Report (Uniformed and Non Uniformed Personnel)</p>
                    <p style="margin: 0; font-weight: bold;"><?php echo $getDateRange['dateRange'] ?></p>
                </div>
                <div class="regular">
                    <p style=" font-size: 11px;"><?php echo $station ?> POLICE PROVINCIAL OFFICE</p>
                </div>
                <div>
                    <table style="font-size: 11px;">
                        <tbody>
                            <?php if (!empty($combinedReports)): ?>
                                <?php
                                $typesDisplayed = [];
                                $previousType = null;


                                foreach ($combinedReports as $absent):
                                    $type = $absent['type'];

                                    if ($type !== $previousType) {
                                        $i = 1;
                                ?>
                                        <tr>
                                            <td style="border: none; text-align:left;" colspan="9"><?php echo htmlspecialchars($type); ?></td>
                                        </tr>
                                        <tr>
                                            <td>#</td>
                                            <td>Rank</td>
                                            <td>Name</td>
                                            <td>ITEM #</td>
                                            <td>Unit</td>
                                            <td>Start Date</td>
                                            <td>End Date</td>
                                            <td>Authority #</td>
                                            <td>Authority Date</td>
                                        </tr>
                                        <?php
                                        $previousType = $type; // Update previousType to the current type
                                    }

                                    switch ($type) {
                                        case 'Sick Leave':
                                        case 'Mandatory Leave':
                                        case 'Service Leave':
                                        case 'Vacation Leave':
                                        case 'Special Privilage Leave':
                                        case 'Leave w/o pay':
                                        case 'AWOL':
                                        case 'Preventive Suspension':
                                        case 'Supension':
                                        case 'Detention':
                                        case 'Termination':
                                            renderLeaveRow($absent, $station, $i);
                                            $typesDisplayed[$type] = true;
                                            break;
                                        default:
                                        ?>
                                            <tr>
                                                <td colspan="9">Unknown leave type: <?php echo htmlspecialchars($type); ?></td>
                                            </tr>
                                <?php
                                            break;
                                    }
                                    $i++; // Increment index
                                endforeach; ?>
                                <?php
                                // Display a message for each type that was not displayed
                                $allTypes = [
                                    'Sick Leave',
                                    'Mandatory Leave',
                                    'Service Leave',
                                    'Vacation Leave',
                                    'Special Privilege Leave',
                                    'Leave w/o pay',
                                    'AWOL',
                                    'Preventive Suspension',
                                    'Suspension',
                                    'Detention',
                                    'Termination'
                                ];

                                foreach ($allTypes as $leaveType): ?>
                                    <?php if (!isset($typesDisplayed[$leaveType])): ?>
                                        <tr>
                                            <td style="border: none; text-align:left;" colspan="9"><?php echo htmlspecialchars($leaveType); ?></td>
                                        </tr>
                                        <tr>
                                            <td>#</td>
                                            <td>Rank</td>
                                            <td>Name</td>
                                            <td>ITEM #</td>
                                            <td>Unit</td>
                                            <td>Start Date</td>
                                            <td>End Date</td>
                                            <td>Authority #</td>
                                            <td>Authority Date</td>
                                        </tr>
                                        <tr>
                                            <td colspan="9">NEGATIVE</td>
                                        </tr>
                                <?php endif;
                                endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="100%">No absences this week</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <div class="footer" style="margin-top: 20px;">
                    <div style="width:100%;">
                        <div style="float:right;text-align:right;">
                            <p style="font-size: 11px; margin: 0;">Submitted by:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;">DAVY A HEBRON</p>
                            <p style="font-size: 12px; margin: 0;">Police Major</p>
                            <p style="font-size: 12px; margin: 0;">Acting Chief, PARMU</p>
                        </div>

                        <div style="float:right;text-align:center; margin-right: 120px;">
                            <p style="font-size: 11px; margin: 0;">Verified By:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;">FRANCO ALLEX M REGLOS</p>
                            <p style="font-size: 12px; margin: 0;">Police Lieutenant Colonel</p>
                            <p style="font-size: 12px; margin: 0;">Deputy Provincial Director for Administration</p>
                        </div>

                        <div style="text-align:left;">
                            <p style="font-size: 11px; margin: 0;">Prepared by:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;"><?php echo htmlspecialchars($name) ?></p>
                            <p style="font-size: 12px; margin: 0;"><?php echo htmlspecialchars($row1['position']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>

    <?php
    $html = ob_get_clean();

    if ($dompdf !== null) {
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // $dompdf->stream("document.pdf", ['Attachment' => false]);
        return $dompdf->output();
    } else {
        return $html;
    }
}

function generateWeeklyAbsences($dompdf = null, $type, $combinedReports, $getDateRange, $name, $row1, $station)
{
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Weekly Absences</title>
        <style>
            * {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #737373;
                text-align: center;
            }

            .header,
            .footer {
                text-align: center;
                font-size: 12px;
            }

            .regular {
                font-size: 11px;
            }
        </style>
    </head>

    <body>
        <div style="height: auto;">
            <div style="height: auto;">
                <div class="header">
                    <p style="margin: 0;">RESTRICTED</p>
                    <p style="margin: 0;">CONSOLIDATED WEEKLY PNP PERSONNEL ACCOUNTING REPORT</p>
                    <p style="margin: 0; font-weight: bold;"><?php echo $station ?> POLICE PROVINCIAL OFFICE</p>
                    <p style="margin: 0;"><?php echo $getDateRange['dateRange'] ?></p>
                </div>
                <div class="regular">
                    <p style="font-weight: bold; font-size: 11px;">Regular Working Schedule</p>
                </div>
                <div>
                    <table style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th rowspan="2">NR</th>
                                <th rowspan="2">RANK/NAME</th>
                                <th colspan="4">CAUSE OF ABSENCE AND DATES COVERED</th>
                            </tr>
                            <tr>
                                <th>Lv</th>
                                <th>Absent Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($combinedReports)):
                                $i = 1 ?>
                                <?php foreach ($combinedReports as $absent): ?>
                                    <tr>
                                        <td><?php echo $i ?></td>
                                        <td><?php echo htmlspecialchars($absent['firstname']) . ' ' . htmlspecialchars($absent['lastname']) ?></td>
                                        <td><?php echo $station ?></td>
                                        <td><?php echo emptyData("type", $absent) ?></td>
                                        <td><?php echo date('F d, Y', strtotime($absent['dateStart']))  ?></td>
                                        <td><?php echo date('F d, Y', strtotime($absent['dateEnd'])) ?></td>
                                    </tr>
                                <?php $i++;
                                endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="100%">NEGATIVE</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="footer" style="margin-top: 20px;">
                    <div style="width:100%;">
                        <div style="float:right;text-align:right;">
                            <p style="font-size: 11px; margin: 0;">Authenticated by:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;">DAVY A HEBRON</p>
                            <p style="font-size: 12px; margin: 0;">Police Major</p>
                            <p style="font-size: 12px; margin: 0;">Acting Chief, PARMU</p>
                        </div>

                        <div style="text-align:left;">
                            <p style="font-size: 11px; margin: 0;">Prepared by:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;"><?php echo htmlspecialchars($name) ?></p>
                            <p style="font-size: 12px; margin: 0;"><?php echo htmlspecialchars($row1['position']) ?></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    $html = ob_get_clean();

    if ($dompdf !== null) {
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // $dompdf->stream("document.pdf", ['Attachment' => false]);
        return $dompdf->output();
    } else {
        return $html;
    }
}


function saveReport($station, $dompdf, $type, $getDateRange, $supervisorid, $conn)
{
    $fileName = $station . '_' . $type . 'Report_' . $getDateRange['startDateFormatted2'] . '.pdf';
    $outputPath = '../reports/' . $fileName;

    // Save the PDF to the specified path
    if (file_put_contents($outputPath, $dompdf) === false) {
        return ['success' => false, 'message' => 'Failed to save PDF.'];
    }

    // Prepare SQL statement to insert report details into the database
    $stmt = $conn->prepare("INSERT INTO reports (reportFile, type, userid) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fileName, $type, $supervisorid);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $stmt->close(); // Close the statement
        return ['success' => true, 'fileName' => $fileName];
    } else {
        $stmt->close(); // Close the statement
        return ['success' => false, 'message' => "Error: " . $stmt->error];
    }
}

function generateAplha($dompdf = null, $type, $list, $getDateRange, $name, $row1, $station)
{
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Alpha</title>
        <style>
            * {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #737373;
                text-align: center;
            }

            .header,
            .footer {
                text-align: center;
                font-size: 12px;
            }

            .regular {
                font-size: 11px;
            }
        </style>
    </head>

    <body>
        <div style="height: auto;">
            <div style="height: auto;">
                <div class="header">
                    <p style="margin: 0;">Republic of the Philippines</p>
                    <p style="margin: 0;">NATIONAL POLICE COMMISSION</p>
                    <p style="margin: 0; font-weight: bold;">PHILIPPINE NATIONAL POLICE,POLICE REGIONAL OFFICE 4A</p>
                    <p style="margin: 0;"><?php echo $getDateRange['dateRange'] ?></p>
                </div>
                <div class="regular">
                    <p style="font-weight: bold; font-size: 11px; text-align:center; ">ALPHA LIST</p>
                </div>
                <div>
                    <table style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>SG</th>
                                <th>APPT STATUS</th>
                                <th>LAST NAME</th>
                                <th>FIRST NAME</th>
                                <th>MIDDLE NAME</th>
                                <th>Qfr</th>
                                <th>ITEM NO.</th>
                                <th>POSITION TITLE</th>
                                <th>DATE ENTERED SERVICE</th>
                                <th>UNIT ASSIGNMENT</th>
                                <th>DATE OF BIRTH</th>
                                <th>HIGHEST ELIGIBILITY</th>
                                <th>HIGHEST TRAINING</th>
                                <th>DESIGNATION</th>
                                <th>DATE OF LATEST PROMOTION</th>
                                <th>ACCT NO.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($list as $data): ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo htmlspecialchars($data['sgrade']) ?></td>
                                    <td><?php echo htmlspecialchars($data['apptStatus']) ?></td>
                                    <td><?php echo htmlspecialchars($data['lastname']) ?></td>
                                    <td><?php echo htmlspecialchars($data['firstname']) ?></td>
                                    <td><?php echo htmlspecialchars($data['middlename']) ?></td>
                                    <td><?php echo htmlspecialchars($data['qualifier']) ?></td>
                                    <td><?php echo htmlspecialchars($data['itemNumber']) ?></td>
                                    <td><?php echo htmlspecialchars($data['position']) ?></td>
                                    <td><?php echo !empty($data['entered']) ? date('d M, Y', strtotime($data['entered'])) : null; ?></td>
                                    <td><?php echo htmlspecialchars($data['station']) ?></td>
                                    <td><?php echo date('d M, Y', strtotime($data['dateOfBirth'])) ?></td>
                                    <td><?php echo htmlspecialchars($data['eligibility_status']) ?></td>
                                    <td><?php echo htmlspecialchars($data['training_name']) ?></td>
                                    <td><?php echo htmlspecialchars($data['designation']) ?></td>
                                    <td><?php echo !empty($data['lastPromotion']) ? date('d M, Y', strtotime($data['lastPromotion'])) : null; ?></td>
                                    <td><?php echo htmlspecialchars($data['user_id']) ?></td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="footer" style="margin-top: 20px;">
                    <div style="width:100%;">
                        <div style="text-align:left;">
                            <p style="font-size: 11px; margin: 0;">Prepared by:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;"><?php echo htmlspecialchars($name) ?></p>
                            <p style="font-size: 12px; margin: 0;"><?php echo htmlspecialchars($row1['position']) ?></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    $html = ob_get_clean();
    if ($dompdf !== null) {
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        // $dompdf->stream("document.pdf", ['Attachment' => false]);
        return $dompdf->output();
    } else {
        return $html;
    }
}

function generateRoster($dompdf = null, $type, $list, $getDateRange, $name, $row1, $station)
{
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Roster</title>
        <style>
            * {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #737373;
                text-align: center;
            }

            .header,
            .footer {
                text-align: center;
                font-size: 12px;
            }

            .regular {
                font-size: 11px;
            }
        </style>
    </head>

    <body>
        <div style="height: auto;">
            <div style="height: auto;">
                <div class="header">
                    <p style="margin: 0;">Republic of the Philippines</p>
                    <p style="margin: 0;">NATIONAL POLICE COMMISSION</p>
                    <p style="margin: 0; font-weight: bold;">PHILIPPINE NATIONAL POLICE,POLICE REGIONAL OFFICE 4A</p>
                    <p style="margin: 0; font-weight: bold;"><?php echo $station ?> POLICE PROVINCIAL OFFICE</p>
                    <p></p>
                </div>
                <div class="regular">
                    <p style="margin: 0; font-weight: bold; font-size: 11px; text-align:center; ">NON UNIFORMED PERSONNEL REPORT</p>
                    <p style="margin: 0;font-weight: bold; font-size: 11px; text-align:center; "><?php echo "As of " . $getDateRange['dateRange'] ?></p>
                </div>
                <div>
                    <p></p>
                    <table style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th>ITEM NO.</th>
                                <th>NO</th>
                                <th>LAST NAME</th>
                                <th>FIRST NAME</th>
                                <th>MIDDLE NAME</th>
                                <th>PRESENT POSITION</th>
                                <th>PREVIOUS POSITION</th>
                                <th>STATUS OF PRESENT APPMT</th>
                                <th>SG</th>
                                <th>ACTUAL MONTHLY SALARY</th>
                                <th>DATE OF LAST PROM</th>
                                <th>DATE OF PERM APPMT</th>
                                <th>UNIT</th>
                                <th>REMARKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($list as $data): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($data['itemNumber']) ?></td>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo htmlspecialchars($data['lastname']) ?></td>
                                    <td><?php echo htmlspecialchars($data['firstname']) ?></td>
                                    <td><?php echo htmlspecialchars($data['middlename']) ?></td>
                                    <td><?php echo htmlspecialchars($data['position']) ?></td>
                                    <td><?php echo htmlspecialchars($data['lastPosition']) ?></td>
                                    <td><?php echo htmlspecialchars($data['apptStatus']) ?></td>
                                    <td><?php echo htmlspecialchars($data['sgrade']) ?></td>
                                    <td><?php echo number_format(htmlspecialchars($data['msalary']), 2) ?></td>
                                    <td><?php echo !empty($data['lastPromotion']) ? date('d M, Y', strtotime($data['lastPromotion'])) : null; ?></td>
                                    <td><?php echo !empty($data['permanency']) ? date('d M, Y', strtotime($data['permanency'])) : null; ?></td>
                                    <td><?php echo htmlspecialchars($data['station']) ?></td>
                                    <td><?php echo "REMARKS" ?></td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="footer" style="margin-top: 20px;">
                    <div style="width:100%;">
                        <div style="text-align:left;">
                            <p style="font-size: 11px; margin: 0;">Prepared by:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;"><?php echo htmlspecialchars($name) ?></p>
                            <p style="font-size: 12px; margin: 0;"><?php echo htmlspecialchars($row1['position']) ?></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    $html = ob_get_clean();
    if ($dompdf !== null) {
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // $dompdf->stream("document.pdf", ['Attachment' => false]);
        return $dompdf->output();
    } else {
        return $html;
    }
}

function generateAnnexA($dompdf = null, $type, $list, $getDateRange, $name, $row1, $station)
{
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Annex A</title>
        <style>
            * {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #737373;
                text-align: center;
                padding: 4px;
            }

            .header,
            .footer {
                text-align: center;
                font-size: 12px;
            }

            .regular {
                font-size: 11px;
            }
        </style>
    </head>

    <body>
        <div style="height: auto;">
            <div style="height: auto;">
                <div class="header">
                    <p style="font-weight: bold;">SIMPLIFIED ROSTER OF THE NON-UNIFORMED PERSONNEL</p>
                    <p style="margin: 0;">Republic of the Philippines</p>
                    <p style="margin: 0;">NATIONAL POLICE COMMISSION</p>
                    <p style="margin: 0; font-weight: bold;">PHILIPPINE NATIONAL POLICE,POLICE REGIONAL OFFICE 4A</p>
                    <p style="margin: 0; font-weight: bold;"><?php echo $station ?> POLICE PROVINCIAL OFFICE</p>
                    <p style="margin: 0;font-weight: bold; font-size: 11px; text-align:center; "><?php echo "As of " . $getDateRange['dateRange'] ?></p>
                </div>
                <div>
                    <p></p>
                    <table style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAME</th>
                                <th>DIVISION/SECTION</th>
                                <th>ITEM NO.</th>
                                <th>POSITION TITLE</th>
                                <th>SG-STEP</th>
                                <th>STATUS OF APPOINTMENT</th>
                                <th>ELIGIBILITY</th>
                                <th>HIGHEST EDUCATIONAL ATTAINMENT</th>
                                <th>REMARKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($list as $data): ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo htmlspecialchars($data['lastname']) . ', ' . htmlspecialchars($data['firstname']) . ' ' .  htmlspecialchars($data['middlename']) ?></td>
                                    <td><?php echo htmlspecialchars($data['station']) ?></td>
                                    <td><?php echo htmlspecialchars($data['itemNumber']) ?></td>
                                    <td><?php echo htmlspecialchars($data['position']) ?></td>
                                    <td><?php echo htmlspecialchars($data['sgrade']) ?></td>
                                    <td><?php echo htmlspecialchars($data['apptStatus']) ?></td>
                                    <td><?php echo htmlspecialchars($data['eligibility_status']) ?></td>
                                    <td><?php echo htmlspecialchars($data['highest_degree']) ?></td>
                                    <td><?php echo "REMARKS" ?></td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="footer" style="margin-top: 20px;">
                    <div style="width:100%;">
                        <div style="text-align:left;">
                            <p style="font-size: 11px; margin: 0;">Prepared by:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;"><?php echo htmlspecialchars($name) ?></p>
                            <p style="font-size: 12px; margin: 0;"><?php echo htmlspecialchars($row1['position']) ?></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    $html = ob_get_clean();
    if ($dompdf !== null) {
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // $dompdf->stream("document.pdf", ['Attachment' => false]);
        return $dompdf->output();
    } else {
        return $html;
    }
}

function generateAnnexB($dompdf, $type, $list, $getDateRange, $name, $row1, $station)
{
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Annex A</title>
        <style>
            * {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #737373;
                text-align: center;
                padding: 4px;
            }

            .header,
            .footer {
                text-align: center;
                font-size: 12px;
            }

            .regular {
                font-size: 11px;
            }
        </style>
    </head>

    <body>
        <div style="height: auto;">
            <div style="height: auto;">
                <div class="header">
                    <p style="font-weight: bold;">SIMPLIFIED ROSTER OF THE NON-UNIFORMED PERSONNEL</p>
                    <p style="margin: 0;">Republic of the Philippines</p>
                    <p style="margin: 0;">NATIONAL POLICE COMMISSION</p>
                    <p style="margin: 0; font-weight: bold;">PHILIPPINE NATIONAL POLICE,POLICE REGIONAL OFFICE 4A</p>
                    <p style="margin: 0; font-weight: bold;">LAGUNA POLICE PROVINCIAL OFFICE</p>
                    <p style="margin: 0;font-weight: bold; font-size: 11px; text-align:center; "><?php echo "As of " . $getDateRange['dateRange'] ?></p>
                </div>
                <div>
                    <p></p>
                    <table style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th rowspan="2">NO</th>
                                <th rowspan="2">AUTHORIZED PLANTILLA POSITIONS</th>
                                <th colspan="4">FILLED POSITIONS</th>
                                <th colspan="5">VACANT POSITIONS</th>
                            </tr>
                            <tr>
                                <th>IN SERVICE</th>
                                <th>NEWLY PROMOTED</th>
                                <th>NEWLY APPOINTED</th>
                                <th>TOTAL</th>
                                <th>AWAITING SUBMISSION OF APPLICATION</th>
                                <th>IN PROCESS</th>
                                <th>FOR PUBLICATION</th>
                                <th>TOTAL</th>
                                <th>REMARKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($list as $data): ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo htmlspecialchars($data['lastname']) . ', ' . htmlspecialchars($data['firstname']) . ' ' .  htmlspecialchars($data['middlename']) ?></td>
                                    <td><?php echo htmlspecialchars($data['station']) ?></td>
                                    <td><?php echo htmlspecialchars($data['itemNumber']) ?></td>
                                    <td><?php echo htmlspecialchars($data['position']) ?></td>
                                    <td><?php echo htmlspecialchars($data['sgrade']) ?></td>
                                    <td><?php echo htmlspecialchars($data['status']) ?></td>
                                    <td><?php echo htmlspecialchars($data['eligibility_status']) ?></td>
                                    <td><?php echo htmlspecialchars($data['highest_degree']) ?></td>
                                    <td><?php echo "REMARKS" ?></td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="footer" style="margin-top: 20px;">
                    <div style="width:100%;">
                        <div style="text-align:left;">
                            <p style="font-size: 11px; margin: 0;">Prepared by:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;"><?php echo htmlspecialchars($name) ?></p>
                            <p style="font-size: 12px; margin: 0;"><?php echo htmlspecialchars($row1['position']) ?></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php
    $html = ob_get_clean();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    // $dompdf->stream("document.pdf", ['Attachment' => false]);
    return $dompdf->output();
}

function renderRow($label, $data, $margin = null)
{
    $counts = array_fill(1, 25, 0); // Initialize counts
    foreach ($data as $row) {
        $sgrade = (int)$row['sgrade'];
        if ($sgrade >= 1 && $sgrade <= 25) {
            $counts[$sgrade]++;
        }
    }

    $totalCount = 0;
    $add = null;
    if ($margin == 'y') {
        $add = "padding-left: 10px;";
    }

    echo "<tr>";
    echo "<td style='text-align: left !important;$add'>$label</td>";

    for ($i = 25; $i >= 1; $i--) {
        $countValue = $counts[$i] > 0 ? $counts[$i] : '';
        echo "<td>$countValue</td>";
        $totalCount += $counts[$i];
    }

    echo "<td style='font-weight: bold;'>" . ($totalCount === 0 ? '' : $totalCount) . "</td>";
    echo "</tr>";

    return $counts; // Return the counts for total calculations
}

function renderNetEffectiveRow($actualCounts, $ineffectiveCounts)
{
    echo "<tr>";
    echo "<td style='text-align: left !important;'>NET EFFECTIVE</td>";

    for ($i = 25; $i >= 1; $i--) {
        $netEffective = $actualCounts[$i] - $ineffectiveCounts[$i];
        $netValue = $netEffective > 0 ? $netEffective : ''; // Show empty if net is 0 or negative
        echo "<td style='font-weight: bold;'>$netValue</td>";
    }

    $totalActual = array_sum($actualCounts);
    $totalIneffective = array_sum($ineffectiveCounts);
    $netTotal = $totalActual - $totalIneffective;

    echo "<td style='font-weight: bold;'>" . ($netTotal > 0 ? $netTotal : '') . "</td>";
    echo "</tr>";
}


function generateRecap($dompdf, $type, $list, $getDateRange, $name, $row1, $station, $sickLeave, $vacation, $preventive, $suspension, $detention, $awol, $absent)
{
    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Annex A</title>
        <style>
            * {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            .padding {
                padding-left: 8px !important;
            }

            th,
            td {
                border: 1px solid #737373;
                text-align: center;
                padding: 2px;
            }

            .header,
            .footer {
                text-align: center;
                font-size: 12px;
            }

            .regular {
                font-size: 11px;
            }
        </style>
    </head>

    <body>
        <div style="height: auto;">
            <div style="height: auto;">
                <div class="header">
                    <p style="margin: 0; font-size: 14px; ">PNP Personnel Accounting Report</p>
                    <p style="margin: 0; font-size: 14px; ">(For Non-Uniformed Personnel)</p>
                    <p style="margin: 0;font-weight: bold; font-size: 13px; text-align:center; "><?php echo "As of " . $getDateRange['dateRange'] ?></p>
                </div>
                <div>
                    <p style="font-size: 12px;">Unit: <?php echo $station ?> Provincial Police Office</p>
                    <table style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th>STATUS</th>
                                <?php for ($i = 25; $i > 0; $i--) : ?>
                                    <th><?php echo $i; ?></th>
                                <?php endfor; ?>
                                <th>GRAND</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 8px;"></td>
                                <?php echo str_repeat("<td></td>", 26); ?>
                            </tr>

                            <?php
                            // Call renderRow for different statuses
                            $organicCounts = renderRow('Organic', $list);
                            echo "<tr><td style='padding: 8px;' colspan='100%'></td></tr>";
                            $totalActualCounts = renderRow('TOTAL ACTUAL', $list);
                            echo "<tr><td style='padding: 8px;' colspan='100%'></td></tr>";

                            // For statuses that don't require a total
                            renderRow('Enroute to join', []);
                            echo "<tr><td style='text-align:left !important;'>On detail to:</td><td style='padding: 8px;' colspan='26'></td></tr>";
                            renderRow('Other PNP Offices', [], 'y');
                            renderRow('Other Govt Offices', [], 'y');
                            renderRow('For Schooling', [], 'y');
                            renderRow('For Hospitalization at PNPGH', [], 'y');
                            renderRow('Missing', []);

                            // Initialize arrays to hold total counts for ineffective categories
                            $ineffectiveTotals = array_fill(1, 25, 0);

                            // Sick Leave
                            $sickCounts = renderRow('On Sick Leave', $sickLeave);
                            foreach ($sickCounts as $i => $count) {
                                $ineffectiveTotals[$i] += $count;
                            }

                            // Vacation Leave
                            $vacationCounts = renderRow('On Vacation Leave', $vacation);
                            foreach ($vacationCounts as $i => $count) {
                                $ineffectiveTotals[$i] += $count;
                            }

                            renderRow('Under Suspension:', []);
                            renderRow('Preventive', $preventive, 'y');
                            renderRow('Punishment', $suspension, 'y');

                            renderRow('Under detention', $detention);
                            $awolCounts = renderRow('On AWOL', $awol);
                            foreach ($awolCounts as $i => $count) {
                                $ineffectiveTotals[$i] += $count;
                            }

                            $absentCounts = renderRow('Absent', $absent);
                            foreach ($absentCounts as $i => $count) {
                                $ineffectiveTotals[$i] += $count;
                            }

                            echo "<tr><td style='padding: 8px;' colspan='100%'></td></tr>";
                            // Output the TOTAL INEFFECTIVE row in reverse order
                            echo "<tr>";
                            echo "<td style='text-align: left !important;'>TOTAL INEFFECTIVE</td>";
                            for ($i = 25; $i >= 1; $i--) {
                                echo "<td style='color:red;'>" . ($ineffectiveTotals[$i] === 0 ? '' : $ineffectiveTotals[$i]) . "</td>";
                            }
                            echo "<td style='font-weight: bold;color:red;'>" . array_sum($ineffectiveTotals) . "</td>"; // Grand total for ineffective
                            echo "</tr>";

                            echo "<tr><td style='padding: 8px;' colspan='100%'></td></tr>";

                            // Render NET EFFECTIVE
                            renderNetEffectiveRow($totalActualCounts, $ineffectiveTotals);
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="footer" style="margin-top: 20px;">
                    <div style="width:100%;">
                        <div style="text-align:left;">
                            <p style="font-size: 11px; margin: 0;">Prepared by:</p>
                            <p style="font-size: 12px; font-weight: bold; margin: 10px 0 0 0;"><?php echo htmlspecialchars($name) ?></p>
                            <p style="font-size: 12px; margin: 0;"><?php echo htmlspecialchars($row1['position']) ?></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    $html = ob_get_clean();
    if ($dompdf !== null) {
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // $dompdf->stream("document.pdf", ['Attachment' => false]);
        return $dompdf->output();
    } else {
        return $html;
    }
}

function renderLeaveList($result, $link = null)
{
    if ($link !== null) {
        $link = $link . '.php';
    } else {
        $link = '#';
    }

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $start = formatDate($row['dateStart']);
            $end = formatDate($row['dateEnd']);
            $statusLabel = getStatusLabel($row['status']);
    ?>
            <div class="bg-gray-100 data px-3 p-1">
                <a href="<?php echo $link ?>" class="flex align-items-center">
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

function select($conn, $table, $userid)
{
    $sql = "SELECT * FROM $table WHERE userid = '$userid' ORDER BY dateStart DESC";
    return mysqli_query($conn, $sql);
}

function formatDate($date)
{
    return date('M d, y', strtotime($date)); // Convert to timestamp and format
}


function getAnnouncement($conn, $station)
{
    $query = "SELECT *
    FROM announcement
    INNER JOIN account ON account.userid = announcement.userid
    INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber";

    if ($station !== 'PHQ') {
        $query .= " WHERE plantilla.station = '$station'";
    }

    $query .= " ORDER BY announcement.announcementid DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result(); // Get the result set

    $announcements = [];
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row; // Store each announcement
    }

    $stmt->close();

    return $announcements; // Return the array of announcements
}

function getPhonenumbers($conn, $station)
{
    $query = "SELECT phonenumber, firstname, lastname
              FROM account
              INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber
              INNER JOIN user ON account.userid = user.userid
              WHERE isArchive = TRUE AND account.type = 'User'";

    if ($station !== 'PHQ') {
        $query .= " AND plantilla.station = '$station'";
    }

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result(); // Get the result set

    $phonenumbers = []; // Initialize an array for phone numbers
    $names = [];
    while ($row = $result->fetch_assoc()) {
        $phonenumbers[] = $row['phonenumber']; // Store each phone number as a string
        $names[] = $row['firstname'] . " " . $row['lastname']; // Store name
    }

    $stmt->close();

    return ['phonenumbers' => $phonenumbers, 'names' => $names];
}
