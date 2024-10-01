<?php
$station = getOffice($conn, $supervisorid);
$count = countPendingIPCR($conn, $station);
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
                        href="dashboard.php">
                        <li class="navi <?php if ($active == "Home") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-border-all fa-fw"></i>
                            <span>Home</span>
                        </li>
                    </a>
                    <a
                        href="personnel.php">
                        <li class="navi <?php if ($active == "Personnel") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-user fa-fw"></i>
                            <span>Personnel</span>
                        </li>
                    </a>
                    <a
                        href="ipcr.php">
                        <li class="navi <?php if ($active == "IPCR") {
                                            echo "active";
                                        } ?>">
                            <div class="flex">
                                <span class="!ml-0"><i class="fa-solid fa-file fa-fw mr-2"></i> IPCR</span>
                                <span class="!ml-0 bg-red-600 rounded-full w-6 h-6 text-center font-medium"><?php echo $count ?></span>
                            </div>
                        </li>
                    </a>


                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col footer">
                <ul>
                    <a href="../index.php?logout-user">
                        <li class="navi bg-[var(--blue-400)] text-center <?php if ($active == "signout") {
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