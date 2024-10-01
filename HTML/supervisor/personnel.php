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
    <title>Personnel</title>
    <style>
        body {
            background: #efefef;
        }
    </style>
</head>

<body>

    <?php
    $supervisorid = $_SESSION['supervisorid'];
    $active = "Personnel";
    $on = "off";
    include "../../Connections/Include.php";
    include "sideBar.php";

    $query1 = "SELECT designation FROM account INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber WHERE userid = '$supervisorid'";
    $results1 = mysqli_query($conn, $query1);
    $row1 = mysqli_fetch_array($results1);
    $designation = $row1['designation']
    ?>
    <div class="main ">
        <div class="row bg">
            <div class="col">
                <h1>IPCR /&nbsp;&nbsp;<span class="text-[#737373]">Personnel</span></h1>
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
                        <th class="border-0 font-medium">Office/Station</th>
                        <th class="border-0 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query1 = "SELECT * 
                    FROM user
                    INNER JOIN account ON user.userid = account.userid
                    INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber
                    WHERE plantilla.designation = '$designation'";
                    $results1 = mysqli_query($conn, $query1);

                    if (mysqli_num_rows($results1) > 0) {
                        while ($rows = mysqli_fetch_array($results1)) {
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
                                <td class="border-0"><?php echo "$rows[station]" ?></td>
                                <td class="text-center border-0"><a
                                        href="viewPersonnel.php<?php echo "?userid=$rows[userid]" ?>" class="p-[4px]"><i class="fa-solid fa-eye fa-fw"></i></a>
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