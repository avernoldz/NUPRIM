<?php
session_start();
session_regenerate_id();

if (!$_SESSION['supervisorid']) {
    header("Location:../index.php?login-first");
}
include_once "components/index.php";
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
    <title>Reports</title>
    <style>
        body {
            background: #efefef;
        }

        input[type="radio"]:checked+span {
            border: 1px solid #4f46e5;
            /* background-color: #4f46e5; */
            color: #4f46e5;
        }
    </style>
</head>

<body>

    <?php
    $supervisorid = $_SESSION['supervisorid'];

    $active = "Reports";
    $on = "off";
    include "../../Connections/Include.php";
    include "sideBar.php";

    $query1 = "SELECT station FROM account INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber WHERE userid = '$supervisorid'";
    $results1 = mysqli_query($conn, $query1);
    $row1 = mysqli_fetch_array($results1);
    $station = $row1['station'];

    ?>
    <div class="main ">
        <div class="row bg">
            <div class="col">
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">Reports </span></h1>
            </div>
        </div>

        <div class="row bg column-gap-3 mt-3 text-[14px]">
            <div class="flex mb-3">
                <h1 class="h5 font-medium">Reports</h1>
                <div class="px-4" data-bs-toggle="modal" data-bs-target="#reports">
                    <i class="fa-solid fa-bars fa-add cursor-pointer p-2 rounded-full bg-gray-200"></i>
                </div>
            </div>
            <div class="div px-4">
                <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                    <?php
                    $query2 = "SELECT * FROM reports WHERE userid = '$supervisorid' ORDER BY created_at DESC";
                    $results2 = mysqli_query($conn, $query2);

                    if (mysqli_num_rows($results2) > 0) {
                        while ($row2 = mysqli_fetch_array($results2)) {

                            $dir = "reports/$row2[reportFile]";
                            $fileName = $row2['reportFile']; // Get the file name
                            $sizeFile = file_exists($dir) ? filesize($dir) : 0;
                            $sizeFileFormatted = $sizeFile > 0 ? formatSize($sizeFile) : 'File not found';

                    ?>
                            <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                <div class="flex w-0 flex-1 items-center">
                                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                    </svg>

                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <span class="truncate font-medium"><?php echo emptyData("reportFile", $row2) ?></span>
                                        <span class="flex-shrink-0 text-gray-400"><?php echo $sizeFileFormatted ?></span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <a href="<?php echo $dir ?>" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                </div>
                            </li>
                    <?php
                        }
                    } else {
                        echo '<li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                            <div class="flex w-0 flex-1 items-center">
                                <div class="ml-4 flex min-w-0 flex-1 gap-2 justify-content-center">
                                    <span class="truncate font-medium ">No reports submitted</span>
                                </div>
                            </div>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <form action="action/reports.php" method="POST" id="save-detail-orders" enctype="multipart/form-data">
            <div class="modal fade" id="reports" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-6 font-semibold ml-2" id="staticBackdropLabel" data-table="case">Compile Report</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-[13px]">

                            <div class="grid grid-rows-4 gap-2 px-4">
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Weekly" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Weekly Absences</span>
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Monthly" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Monthly</span>
                                        </span>
                                    </label>
                                </div>

                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Recap" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Recap</span>
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Alpha" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Alpha</span>
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="Roster" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Roster</span>
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="AnnexA" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Annex A</span>
                                        </span>
                                    </label>
                                </div>
                                <!-- <div>
                                    <label class="flex items-center rounded-m cursor-pointer transition">
                                        <input type="radio" name="type" value="AnnexB" class="hidden peer" required />
                                        <span class="w-full flex items-center border-1 p-4 rounded-md transition justify-content-center">
                                            <span>Annex B</span>
                                        </span>
                                    </label>
                                </div> -->
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" data-bs-dismiss="modal" class="cancel inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-300 sm:mt-0 sm:w-auto">Cancel</button>
                            <button type="submit" name="compile" class="save-detail inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Compile</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</body>

</html>