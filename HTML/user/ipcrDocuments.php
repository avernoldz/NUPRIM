<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
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
    <title>Accomplishments</title>
    <style>
        body {
            background: #efefef;
        }

        .ipcr:nth-of-type(odd) {
            background-color: #f3f9ff;
        }

        .ipcr:hover {
            box-shadow: inset 1px 0 0 #dadce0, inset -1px 0 0 #dadce0, 2px 2px 2px 0 rgba(60, 64, 67, .3), 0 1px 3px 1px rgba(60, 64, 67, .15);
            z-index: 2;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_SESSION['userid'];
    $active = "IPCR Docs";
    $on = "on";
    include "../../Connections/Include.php";
    include "sideBar.php";

    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }

    ?>

    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Accomplishments</span></h1>
            </div>
        </div>

        <div>
            <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
                <div class="col flex flex-wrap justify-between items-center">
                    <h1>Accomplishments</h1>
                    <div id="div-child-btn">
                        <button class="btn btn-success  text-[white] w-24" type="button" id="add" data-bs-target="#upload" data-bs-toggle="modal">Upload</button>
                    </div>
                </div>
            </div>
            <div class="row bg column-gap-3 items-end">
                <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                    <?php
                    $query2 = "SELECT ipcrdoc.*, ipcr.year, ipcr.semester FROM ipcrdoc INNER JOIN ipcr ON ipcr.ipcrid = ipcrdoc.ipcrid WHERE ipcrdoc.userid = '$userid' ORDER BY ipcrdoc.ipcrid DESC";
                    $results2 = mysqli_query($conn, $query2);
                    $dirName = selectName($conn, $userid);

                    if (mysqli_num_rows($results2) > 0) {
                        while ($row2 = mysqli_fetch_array($results2)) {

                            $dir = "uploads/$dirName/$row2[uploadedDoc]";
                            $fileName = $row2['uploadedDoc']; // Get the file name
                            $sizeFile = file_exists($dir) ? filesize($dir) : 0;
                            $sizeFileFormatted = $sizeFile > 0 ? formatSize($sizeFile) : 'File not found';

                    ?>
                            <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                <div class="flex w-0 flex-1 items-center">
                                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                    </svg>

                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <span class="truncate font-medium">IPCR - <?php echo emptyData("semester", $row2) . ' ' .  emptyData("year", $row2) ?></span>
                                        <span class="flex-shrink-0 text-gray-400"> <?php echo emptyData("uploadedDoc", $row2) ?></span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <a href="<?php echo $dir ?>" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                </div>
                            </li>
                    <?php
                        }
                    } else {
                        echo "<li class='flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6'>
                                    <div class='flex w-0 flex-1 items-center'>
                                        <div class='ml-4 flex min-w-0 flex-1 gap-2 justify-content-center'>
                                            <span class='truncate font-medium'>No uploaded documents</span>
                                        </div>
                                    </div>
                                </li>";
                    }
                    ?>
                </ul>
            </div>

            <form action="action/ipcrDoc.php" method="POST" id="uploadDoc" enctype="multipart/form-data">
                <div class="modal fade" id="upload" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Upload Document</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row column-gap-3">
                                    <div class="col">
                                        <label for="ipcrid" class="form-label">IPCR</label>
                                        <select id="ipcrid" class="form-control" name="ipcrid" required onchange="fetchFunctions()">
                                            <?php
                                            $ipcr = "SELECT ipcrid, year, semester FROM ipcr WHERE userid = '$userid' ORDER BY ipcrid DESC";
                                            $ress = mysqli_query($conn, $ipcr);
                                            if (mysqli_num_rows($ress) > 0) {
                                                while ($ip = mysqli_fetch_array($ress)) {
                                                    echo "<option value='$ip[ipcrid]'>IPCR $ip[semester] $ip[year]</option>";
                                                }
                                            } else {
                                                echo '<option>No IPCR report </option>';
                                            }
                                            ?>

                                        </select>
                                    </div>

                                    <div class="w-100"></div>
                                    <div class="col mt-3">
                                        <label for="function" class="form-label">Functions</label>
                                        <select id="function" class="form-control" name="function" required>
                                            <option selected disabled>Select function</option>
                                        </select>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-6 mt-3">
                                        <label for="dateSubmission" class="form-label">Target Date</label>
                                        <input type="date" id="dateSubmission" class="form-control" name="dateSubmission" required>
                                    </div>

                                    <div class="col  mt-3">
                                        <label for="dateSubmitted" class="form-label">Date Submitted</label>
                                        <input type="date" id="dateSubmitted" class="form-control" name="dateSubmitted" required>
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col mt-3">
                                        <label for="uploadedDoc" class="form-label">Document </label>
                                        <input type="file" id="uploadedDoc" class="form-control" name="uploadedDoc" required>
                                    </div>
                                </div>

                            </div>

                            <input type="hidden" name="userid" value="<?php echo $userid ?>">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <script src="../JS/app.js"></script>
        <script>
            function fetchFunctions() {
                var ipcrId = $('#ipcrid').val(); // Get selected IPCR ID

                $.ajax({
                    url: 'action/fetch.php', // The PHP file that handles the request
                    type: 'POST',
                    data: {
                        ipcrid: ipcrId
                    },
                    success: function(response) {
                        $('#function').html(response);
                    },
                    error: function() {
                        alert('Error retrieving functions. Please try again.');
                    }
                });
            }

            fetchFunctions();
        </script>
</body>

</html>