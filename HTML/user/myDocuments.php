<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:signin.php?login-first");
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

    <?php
    $userid = $_SESSION['userid'];
    $active = "My Documents";
    $on = "off";
    include "sideBar.php";
    include "../../Connections/Include.php";

    // $query1 = "SELECT * FROM training WHERE userid = '$userid'";
    // $results1 = mysqli_query($conn, $query1);
    ?>
    <div class="main">
        <div class="row bg">
            <div class="col">
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">My Documents</span></h1>
            </div>
        </div>

        <div>
            <div class="row bg-[var(--blue-600)] p-[18px] !mt-[16px] text-[white] rounded-t-[4px] ">
                <div class="col flex flex-wrap justify-between items-center">
                    <h1>My Documents</h1>
                </div>
            </div>
            <form action="action/training.php" method="POST" id="edit-training">
                <div class="row bg column-gap-3 items-end">
                    <?php
                    $userDir = selectName($conn, $userid); // Get the user's directory
                    $directory = "uploads/$userDir";

                    if (is_dir($directory)) {
                        // Scan the directory for files
                        $files = scandir($directory);
                        // Filter out the current and parent directory references
                        $files = array_diff($files, array('.', '..'));

                        if (!empty($files)) {
                            foreach ($files as $file) {
                                $filePath = "$directory/$file"; // Full path to the file
                                $fileType = getFileType($file); // Get file type for each file

                                // Render the appropriate HTML based on the file type
                                switch ($fileType) {
                                    case 'image':
                                        echo "<img class='preview-image' src='$filePath' alt='Uploaded Image'>";
                                        break;
                                    case 'pdf':
                                        echo "<iframe class='preview-pdf' src='$filePath' frameborder='0' onclick='openModal(\"$filePath\", \"$fileType\")'></iframe>";
                                        break;
                                    case 'document':
                                        echo "<div style='background:#e3e3e3;' class='download-link'><a  href='$filePath' target='_blank' style='margin:auto;' >View/Download Document</a></div>";
                                        break;
                                    default:
                                        echo "<p class='unsupported-file'>Unsupported file type: $file</p>";
                                }
                            }
                        } else {
                            echo "<p class='text-center'>No files available.</p>";
                        }
                    } else {
                        echo "<p class='text-center'>No data available.</p>";
                    }
                    ?>
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">

                </div>
            </form>
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

        <script>

        </script>

</body>

</html>