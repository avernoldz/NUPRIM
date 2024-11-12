<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";
require '../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];

    @$fromFormatted =  strtotime($_POST['from']); // First day of last month
    @$toFormatted =  strtotime($_POST['to']); // Last day of last month

    // Format them to be used in the input fields (YYYY-MM-DD format)
    $from = date('Y-m-d', $fromFormatted);
    $to = date('Y-m-d', $toFormatted);

    $supervisorid = $_SESSION['supervisorid'];
    $station = getSupervisorStation($conn, $supervisorid);
    $row1 = getSupervisorInfo($conn, $supervisorid);
    $name = getFullName($conn, $supervisorid);
    $personnel = getPersonnel($conn, $station);
    $getDateRange = getDateRangeD($from, $to);
    $combinedReports = fetchCombinedReports($conn, $personnel, $from, $to);
    $list = getList($conn, $station);

    $save = '';
    switch ($type) {
        case 'Monthly':
            $save = generateMonthlyAbsences(null, $type, $combinedReports, $getDateRange, $name, $row1, $station);
            break;
        case 'Weekly':
            $save = generateWeeklyAbsences(null, $type, $combinedReports, $getDateRange, $name, $row1, $station);
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
            $save = generateRecap(null, $type, $recap, $getDateRange, $name, $row1, $station, $sickLeave, $vacation, $preventive, $suspension, $detention, $awol, $absent);
            break;
        case 'Alpha':
            $getDateRange = getDateRangeD($from, $to);
            $save = generateAplha(null, $type, $list, $getDateRange, $name, $row1, $station);
            break;
        case 'Roster':
            $getDateRange = getDateRangeD($from, $to);
            $save = generateRoster(null, $type, $list, $getDateRange, $name, $row1, $station);
            break;
        case 'Annex':
            $getDateRange = getDateRangeD($from, $to);
            $save = generateAnnexA(null, $type, $list, $getDateRange, $name, $row1, $station);
            break;
        case 'Files':
?>
            <div class="div px-4 mt-4">
                <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                    <?php
                    $query2 = "SELECT * FROM reports";

                    if ($station !== 'PHQ') {
                        $query2 .= " WHERE userid = '$supervisorid'";
                    }
                    $query2 .= " ORDER BY created_at DESC";

                    // Prepare the statement
                    $stmt = mysqli_prepare($conn, $query2);
                    mysqli_stmt_execute($stmt);
                    $results2 = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($results2) > 0) {
                        while ($row2 = mysqli_fetch_array($results2)) {
                            $dir = "../reports/{$row2['reportFile']}";
                            $dir2 = "http://localhost/IPCR/HTML/supervisor/reports/{$row2['reportFile']}";
                            $sizeFile = file_exists($dir) ? filesize($dir) : 0;
                            $sizeFileFormatted = $sizeFile > 0 ? formatSize($sizeFile) : 'File not found';
                    ?>
                            <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                <div class="flex w-0 flex-1 items-center">
                                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <span class="truncate font-medium"><?php echo emptyData("reportFile", $row2); ?></span>
                                        <span class="flex-shrink-0 text-gray-400"><?php echo $sizeFileFormatted; ?></span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <a href="<?php echo htmlspecialchars($dir2); ?>" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                </div>
                            </li>
                    <?php
                        }
                    } else {
                        echo '<li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                            <div class="flex w-0 flex-1 items-center">
                                <div class="ml-4 flex min-w-0 flex-1 gap-2 justify-content-center">
                                    <span class="truncate font-medium">No reports submitted</span>
                                </div>
                            </div>
                        </li>';
                    }

                    mysqli_stmt_close($stmt);
                    ?>
                </ul>
            </div>
<?php
            break;
    }

    echo $save;
} else {
    echo '<p class="text-center">Select Date Range first</p>';
}
?>