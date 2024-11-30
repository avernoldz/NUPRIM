<?php
session_start();
session_regenerate_id();

if (!isset($_SESSION['adminid'])) {
    header("Location:../index.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../Connections/cdn.php" ?>
    <link rel="stylesheet" href="../../CSS/root.css">
    <link rel="stylesheet" href="CSS/admin.css">
    <link rel="stylesheet" href="CSS/side-bar.css">
    <title>Account Requests</title>
    <style>
        body {
            background: #efefef;
        }

        .error {
            color: var(--bs-danger);
        }
    </style>
</head>

<body>
    <div class="loader loading hidden">
        <div class="justify-content-center jimu-primary-loading"></div>
    </div>
    <?php
    $adminid = $_SESSION['adminid'];
    $active = "Requests";
    $log = 0;
    require __DIR__ . "../../../vendor/autoload.php";
    include "../../Connections/Include.php";
    include "components/components.php";
    include "../supervisor/components/sendSMS.php";
    include "sideBar.php";

    if (isset($_GET['alert']) && isset($_GET['message'])) {
        $alertType = $_GET['alert'];
        $alertMessage = urldecode($_GET['message']);
        showToastr($alertMessage, $alertType);
    }

    if (isset($_GET['approve'])) {
        $adminid = $_SESSION['adminid'];
        $id = $_GET['id'];

        $insert = "UPDATE account SET isArchive = TRUE WHERE userid = '$id'";

        if (mysqli_query($conn, $insert)) {

            $insert3 = "INSERT INTO service(userid)
                        VALUES('$id')";
            mysqli_query($conn, $insert3);

            $selectPhoneNumber = "SELECT phonenumber FROM account WHERE userid = '$id'";
            $result = mysqli_query($conn, $selectPhoneNumber);
            $row = mysqli_fetch_assoc($result);

            $phoneNumber = $row['phonenumber'];
            if (strpos($phoneNumber, '0') === 0) {
                $phoneNumber = '+63' . substr($phoneNumber, 1);
            }

            $smsMessage = "Your account request has been approved.";

            if (sendSms($phoneNumber, $smsMessage)) {
                echo "<script>window.location.href='requests.php?alert=success&message=Account approved. SMS sent to $phoneNumber';</script>";
            } else {
                echo "<script>window.location.href='requests.php?alert=success&message=Account approved but SMS failed';</script>";
            }
        } else {
            echo mysqli_error($conn);
        }
    }

    if (isset($_GET['reject'])) {
        $adminid = $_SESSION['adminid'];
        $id = $_GET['id'];
        $reason = mysqli_escape_string($conn, $_GET['reason']);

        $insert = "UPDATE account SET isArchive = 3, rejectReason = '$reason' WHERE userid = '$id'";

        if (mysqli_query($conn, $insert)) {
            $selectPhoneNumber = "SELECT phonenumber FROM account WHERE userid = '$id'";
            $result = mysqli_query($conn, $selectPhoneNumber);
            $row = mysqli_fetch_assoc($result);

            $phoneNumber = $row['phonenumber'];
            if (strpos($phoneNumber, '0') === 0) {
                $phoneNumber = '+63' . substr($phoneNumber, 1);
            }

            $smsMessage = "Your account request has been declined.";

            if (sendSms($phoneNumber, $smsMessage)) {
                echo "<script>window.location.href='requests.php?alert=success&message=Account rejected due to $reason. SMS sent to $phoneNumber';</script>";
            } else {
                echo "<script>window.location.href='requests.php?alert=success&message=Account rejected but SMS failed';</script>";
            }
        } else {
            echo mysqli_error($conn);
        }
    }

    ?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1>NUPRIM /&nbsp;&nbsp;<span class="text-[#737373]">Account Requests</span></h1>
            </div>
        </div>

        <div class="row bg-[#ffffff] rounded-[4px] mt-3 shadow-[0_3px_5px_-3px_rgba(0,0,0,0.1)] p-[16px] content">
            <table id="table" class="display border-[1px] cell-border" style="width:100%">
                <thead class="bg-[var(--black-900)] text-[var(--black-400)]">
                    <tr>
                        <th>Username</th>
                        <th>Item Number</th>
                        <th>Salary Grade</th>
                        <th>Designation</th>
                        <th>Office/Station</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query1 = "SELECT * 
                    FROM account
                    INNER JOIN plantilla ON plantilla.itemNumber = account.itemNumber WHERE account.isArchive IS NOT TRUE OR account.isArchive = 3 ORDER BY userid DESC";
                    $results1 = mysqli_query($conn, $query1);

                    if (mysqli_num_rows($results1) > 0) {
                        while ($rows = mysqli_fetch_array($results1)) {
                            $random = create_random_string(4);
                            $stat = $rows['isArchive'];
                    ?>
                            <tr>
                                <td><?php echo "$rows[username]" ?></td>
                                <td><?php echo "$rows[itemNumber]" ?></td>
                                <td><?php echo "$rows[sgrade]" ?></td>
                                <td><?php echo "$rows[designation]" ?></td>
                                <td><?php echo "$rows[station]" ?></td>
                                <td><?php echo "$rows[rejectReason]" ?></td>
                                <td class="text-center"><?php if ($stat == false) {
                                                            echo '<span class="bg-yellow-100 text-xs rounded p-1 mb-0 text-yellow-700 w-500 px-2 border-1 border-yellow-300">For Approval</span>';
                                                        } else {
                                                            echo '<span class="bg-red-100 text-xs rounded p-1 mb-0 text-red-700 w-500 px-2 border-1 border-red-300">Rejected</span>';
                                                        } ?></td>
                                <td class="text-center">
                                    <i class="fa-solid fa-pen fa-fw cursor-pointer save" data-id="<?php echo $rows['userid'] ?>"></i>
                                    <i class="fa-solid fa-trash fa-fw cursor-pointer dis" data-id="<?php echo $rows['userid'] ?>"></i>
                                </td>
                            </tr>
                    <?php
                        }
                    } ?>
                    </tr>

                </tbody>
            </table>
        </div>

        <form action="" method="POST" id="myForm">
            <div class="relative z-10 confirm hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <div class="text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <h3 class="text-base text-left font-semibold leading-6 text-gray-900" id="modal-title">Approve Request</h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-left  text-gray-500">Are you sure you want to approve this account request? Once approved, this action cannot be undone, and the request will be finalized..</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="button" id="approve" class=" inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Save</button>
                                <!-- <button type="button" id="reject" class=" inline-flex w-full ml-3 justify-center bg-red-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-red-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Reject</button> -->
                                <button type="button" class="cancel inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-300 sm:mt-0 sm:w-auto">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form action="" method="POST" id="myForm">
            <div class="relative z-10 reject hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <div class="text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <h3 class="text-base text-left font-semibold leading-6 text-gray-900" id="modal-title">Approve Request</h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-left  text-gray-500">Are you sure you want to approve this account request? Once approved, this action cannot be undone, and the request will be finalized..</p>
                                            <h3 class="text-base text-left font-semibold leading-6 text-gray-900 mt-3">Reason for rejection:</h3>
                                            <textarea class="form-control" id="r" rows="2" name="reason"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <!-- <button type="button" id="approve" class=" inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Save</button> -->
                                <button type="button" id="reject" class=" inline-flex w-full ml-3 justify-center bg-red-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-red-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Reject</button>
                                <button type="button" class="cancel inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-300 sm:mt-0 sm:w-auto">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script src="../JS/app.js"></script>
    <script>
        $(document).ready(function() {
            let id = '';
            $('#table').DataTable({
                order: []
            });

            $('.save').click(function() {
                id = $(this).data('id');
                $('.confirm').removeClass('hidden').hide().fadeIn(300);
                console.log(id);
            });


            $('.dis').click(function() {
                id = $(this).data('id');
                $('.reject').removeClass('hidden').hide().fadeIn(300);
                console.log(id);
            });


            $('#approve').click(function() {
                window.location.href = 'requests.php?approve&id=' + id;
            });


            $('#reject').click(function() {
                var reason = $('#r').val();
                window.location.href = 'requests.php?reject&id=' + id + '&reason=' + reason;
            });


            // Close modal on Cancel button click
            $('.cancel').click(function() {
                $('.confirm').fadeOut(200, function() {
                    $(this).addClass('hidden');
                });
                $('.error').fadeOut(200, function() {
                    $(this).addClass('hidden');
                });
            });



        })
    </script>
</body>

</html>