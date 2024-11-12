<?php
$requestC = "SELECT COUNT(userid) as total FROM account WHERE isArchive = FALSE AND type = 'User'";
$rc = mysqli_query($conn, $requestC);
$rw2 = mysqli_fetch_array($rc);

?>
<div class="side-bar">
    <div class="wrapper">
        <div class="row">
            <div class="col head">
                <div class="title">
                    <div class="row">
                        <div class="col">
                            <h1>NUPRIM</h1>
                        </div>
                    </div>
                </div>
                <!-- <hr> -->
                <ul>
                    <a
                        href="dashboard.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "dashboard") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-border-all fa-fw"></i>
                            <span>Dashboard</span>
                        </li>
                    </a>
                    <a
                        href="personnel.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "Personnel") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-user-group fa-fw"></i>
                            <span>Personnel</span>
                        </li>
                    </a>

                    <a
                        href="requests.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "Requests") {
                                            echo "active";
                                        } ?>">
                            <div class="flex">
                                <span class="!ml-0"><i class="fa-solid fa-clock fa-fw mr-3"></i>Request</span>
                                <span class="!ml-0 bg-red-600 rounded-full w-6 h-6 text-center font-medium"><?php echo $rw2['total'] ?></span>
                            </div>
                        </li>
                    </a>

                    <a
                        href="supervisor.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "Supervisors") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-user-lock fa-fw"></i>
                            <span>Supervisors</span>
                        </li>
                    </a>
                    <a
                        href="plantilla.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "Plantilla") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-user-shield fa-fw"></i> <span>Plantilla</span>
                        </li>
                    </a>

                    <a
                        href="userLogs.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "User Logs") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-clipboard fa-fw"></i> <span>User Logs</span>
                        </li>
                    </a>

                    <a
                        href="account.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "Account") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-lock fa-fw"></i> <span>Account</span>
                        </li>
                    </a>

                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col footer">
                <ul>
                    <a href="../index.php?logout-admin">
                        <li class="navi bg-[var(--black-800)] text-center text-[var(--black-300)] <?php if ($active == "signout") {
                                                                                                        echo "active";
                                                                                                    } ?>">
                            <i class="fa-solid fa-right-from-bracket fa-fw"></i> <span>Logout</span>
                        </li>
                    </a>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#profile-drop').click(function() {
            $('.drop-profile').slideToggle("fast", "linear");
        });
    })
</script>