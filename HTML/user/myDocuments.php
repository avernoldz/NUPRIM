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
    <title>My Documents</title>
    <style>
        body {
            background: #efefef;
        }

        .file-preview {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
        }

        .preview-image {
            width: 200px;
            height: 250px;
            object-fit: cover;
            border-radius: 4px;
        }

        .preview-pdf {
            width: 200px;
            height: 250px;
            border-radius: 4px;
        }

        .download-link {
            text-decoration: none;
            color: #007BFF;
            width: 200px;
            height: 250px;
            border-radius: 4px;
            display: flex;
            text-align: center;
            vertical-align: middle;
            /* Bootstrap primary color */
        }

        .download-link:hover {
            text-decoration: underline;
        }

        .unsupported-file {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="loader loading hidden">
        <div class="justify-content-center jimu-primary-loading"></div>
    </div>

    <?php
    $userid = $_SESSION['userid'];
    $active = "My Documents";
    $on = "off";
    include "sideBar.php";
    include "../../Connections/Include.php";

    function displayDocuments($conn, $userid, $tableName, $title)
    {
        $query = "SELECT * FROM $tableName WHERE userid = '$userid'";
        $results = mysqli_query($conn, $query);
        $dirName = selectName($conn, $userid);

        echo "<div class='mt-3'>";
        echo "<h1 class='font-medium mb-3 h6'>$title</h1>";
        echo "<div class='px-3'><ul role='list' class='divide-y divide-gray-100 rounded-md border border-gray-200'>";

        if (mysqli_num_rows($results) > 0) {
            while ($row = mysqli_fetch_array($results)) {
                $dir = "uploads/$dirName/{$row['uploadedDoc']}";
                $fileName = $row['uploadedDoc'];
                $sizeFile = file_exists($dir) ? filesize($dir) : 0;
                $sizeFileFormatted = $sizeFile > 0 ? formatSize($sizeFile) : 'File not found';

                echo "<li class='flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6'>";
                echo "<div class='flex w-0 flex-1 items-center'>";
                echo "<svg class='h-5 w-5 flex-shrink-0 text-gray-400' viewBox='0 0 20 20' fill='currentColor' aria-hidden='true'>
                        <path fill-rule='evenodd' d='M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z' clip-rule='evenodd' />
                      </svg>";
                echo "<div class='ml-4 flex min-w-0 flex-1 gap-2'>";
                echo "<span class='truncate font-medium'>$fileName</span>";
                echo "<span class='flex-shrink-0 text-gray-400'> $sizeFileFormatted</span>";
                echo "</div></div>";
                echo "<div class='ml-4 flex-shrink-0'>";
                echo "<a href='$dir' target='_blank' class='font-medium text-indigo-600 hover:text-indigo-500'>Download</a>";
                echo "</div></li>";
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

        echo "</ul></div></div>";
    }

    ?>
    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">My Documents</span></h1>
            </div>
        </div>

        <div>
            <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
                <div class="col flex flex-wrap justify-between items-center">
                    <h1>My Documents</h1>
                </div>
            </div>
            <div class="row bg column-gap-3 items-end">
                <?php
                displayDocuments($conn, $userid, 'training', 'Training Documents');
                displayDocuments($conn, $userid, 'leaves', 'Leaves Documents');
                displayDocuments($conn, $userid, 'detail', 'Detail Orders Documents');
                displayDocuments($conn, $userid, '`case`', 'Criminal Case Documents');
                ?>
            </div>
        </div>

        <!-- Modal Structure -->
        <div class="modal fade" id="fileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>

                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add-training">Save</button>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>