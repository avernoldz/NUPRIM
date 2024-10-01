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
    <title>IPCR</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $supervisorid = $_SESSION['supervisorid'];
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
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">IPCR Data</span></h1>
            </div>
        </div>

        <div class="row bg column-gap-3 mt-3  text-[14px]">
            <table id="myTable" class="display border-[1px]" style="width:100%">
                <thead>
                    <tr>
                        <th class="border-0 font-medium">ID</th>
                        <th class="border-0 font-medium">Name</th>
                        <th class="border-0 font-medium">Item Number</th>
                        <th class="border-0 font-medium">Position</th>
                        <th class="border-0 font-medium">Period Cover</th>
                        <th class="border-0 font-medium">Status</th>
                        <th class="border-0 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query1 = "SELECT * 
                    FROM user
                    INNER JOIN account ON user.userid = account.userid
                    INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
                    INNER JOIN ipcr ON ipcr.userid = user.userid
                    WHERE plantilla.station = '$station'";

                    $results1 = mysqli_query($conn, $query1);

                    if (mysqli_num_rows($results1) > 0) {
                        while ($rows = mysqli_fetch_array($results1)) {

                            if ($rows['status'] == 'Waiting for Approval') {
                                $stat = '<label class="form-label bg-yellow-100 text-xs rounded p-1 mb-0 text-yellow-700 w-500 px-2 border-1 border-yellow-300">Pending</label>';
                            } elseif ($rows['status'] == 'Approved') {
                                $stat = '<label class="form-label bg-green-100 text-xs rounded p-1 mb-0 text-green-700 w-500 px-2 border-1 border-green-300">Approved</label>';
                            } else {
                                $stat = '<label class="form-label bg-red-100 text-xs rounded p-1 mb-0 text-red-700 w-500 px-2 border-1 border-red-300">Rejected</label>';
                            }

                            $middle = substr($rows['middlename'], 0, 1);
                    ?>
                            <tr>
                                <td class="border-0"><?php echo "$rows[userid]" ?></td>
                                <td class="border-0"><?php echo "$rows[firstname]";
                                                        if (empty($rows['middlename'])) {
                                                            echo "";
                                                        } else {
                                                            echo "  $middle.";
                                                        }
                                                        echo " $rows[lastname]" ?></td>
                                <td class="border-0"><?php echo "$rows[itemNumber]" ?></td>
                                <td class="border-0"><?php echo "$rows[position]" ?></td>
                                <td class="border-0"><?php echo "$rows[semester] $rows[year]" ?></td>
                                <td class="border-0"><?php echo "$stat" ?></td>
                                <td class="text-center border-0"><a
                                        href="generate.php<?php echo "?userid=$rows[userid]&ipcr=$rows[ipcrid]" ?>" class="p-[4px]"><i class="fa-solid fa-eye fa-fw text-[#7b8087]"></i></a>
                                </td>
                            </tr>
                    <?php
                        }
                    } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $('#myTable').DataTable({
            order: [],
            layout: {
                topEnd: {
                    search: {
                        placeholder: 'Search',
                        text: ''
                    }
                }
            }
        });
        $('#edit').click(function() {
            $("#forms :input").prop("disabled", false);
            $("#forms #date-of-birth").prop("type", "date");
            $('#save-cancel').css("display", "block");
            $('#edit').css("display", "none");
        })

        $('#cancel').click(function() {
            $("#forms :input").prop("disabled", true);
            $('#save-cancel').css("display", "none");
            $("#forms #date-of-birth").prop("type", "text");
            $('#edit').css("display", "block");
        })
    </script>
</body>

</html>