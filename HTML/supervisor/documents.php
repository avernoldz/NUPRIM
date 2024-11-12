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
    <title>Accomplishments</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $supervisorid = $_SESSION['supervisorid'];
    $userid = $_GET['userid'];
    $ipcrid = $_GET['ipcr'];
    $active = "IPCR";
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
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Accomplishments</span></h1>
            </div>
        </div>

        <div class="row bg column-gap-3 mt-3  text-[14px]">
            <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                <?php
                $query2 = "SELECT ipcrdoc.*, ipcr.year, ipcr.semester FROM ipcrdoc INNER JOIN ipcr ON ipcr.ipcrid = ipcrdoc.ipcrid WHERE ipcrdoc.userid = '$userid' AND ipcrdoc.ipcrid = '$ipcrid'";
                $results2 = mysqli_query($conn, $query2);
                $dirName = selectName($conn, $userid);

                if (mysqli_num_rows($results2) > 0) {
                    while ($row2 = mysqli_fetch_array($results2)) {

                        $dir = "../../HTML/user/uploads/$dirName/$row2[uploadedDoc]";
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
                                    <span class="truncate font-medium"><?php echo emptyData("uploadedDoc", $row2) ?></span>
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
                                    <span class="truncate font-medium ">No uploaded documents</span>
                                </div>
                            </div>
                        </li>';
                }
                ?>
            </ul>
        </div>
    </div>
</body>

</html>