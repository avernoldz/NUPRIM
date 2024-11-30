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
                        href="dashboard.php" class="href">
                        <li class="navi <?php if ($active == "Home") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-border-all fa-fw"></i>
                            <span>Home</span>
                        </li>
                    </a>
                    <a
                        href="personnel.php" class="href">
                        <li class="navi <?php if ($active == "Personnel") {
                                            echo "active";
                                        } ?>">
                            <div class="flex">
                                <span class="!ml-0"><i class="fa-solid fa-user fa-fw mr-2"></i> Personnel</span>
                                <span class="!ml-0 bg-red-600 rounded-full w-6 h-6 text-center font-medium"><?php echo $count['leave_count'] ?></span>
                            </div>
                        </li>
                    </a>
                    <a
                        href="ipcr.php" class="href">
                        <li class="navi <?php if ($active == "IPCR") {
                                            echo "active";
                                        } ?>">
                            <div class="flex">
                                <span class="!ml-0"><i class="fa-solid fa-file fa-fw mr-2"></i> IPCR</span>
                                <span class="!ml-0 bg-red-600 rounded-full w-6 h-6 text-center font-medium"><?php echo $count['ipcr_count'] ?></span>
                            </div>
                        </li>
                    </a>
                    <!-- <a
                        href="report.php">
                        <li class="navi <?php if ($active == "Reports") {
                                            echo "active";
                                        } ?>">
                            <div class="flex">
                                <span class="!ml-0"><i class="fa-solid fa-folder-open fa-fw mr-2"></i> Reports</span>
                            </div>
                        </li>
                    </a> -->
                    <a
                        href="#" id="profile-drop">
                        <li class="navi <?php if ($active == "My Profile") {
                                            echo "active";
                                        }
                                        if ($on == "hon") {
                                            echo "act";
                                        }; ?>">
                            <i class="fa-solid fa-folder-open fa-fw"></i>
                            <span>Reports</span>
                        </li>
                    </a>

                    <ul class="drop-profile <?php if ($on == "hon") {
                                                echo "hon";
                                            } ?>">
                        <a
                            href="report.php">
                            <li class="navi nav-link active <?php if ($active == "DPAR") {
                                                                echo "active";
                                                            } ?>" data-type="Weekly">
                                <i class="fa-solid fa-circle-info fa-fw"></i> <span>DPAR</span>
                            </li>
                        </a>
                        <a
                            href="report.php">
                            <li class="navi nav-link  <?php if ($active == "Monthly") {
                                                            echo "active";
                                                        } ?>" data-type="Monthly">
                                <i class="fa-solid fa-location-dot fa-fw"></i> <span>Monthly</span>
                            </li>
                        </a>
                        <a
                            href="report.php">
                            <li class="navi nav-link <?php if ($active == "Recap") {
                                                            echo "active";
                                                        } ?>" data-type="Recap">
                                <i class="fa-solid fa-people-group fa-fw"></i> <span>Recap</span>
                            </li>
                        </a>
                        <a
                            href="report.php">
                            <li class="navi nav-link <?php if ($active == "Alpha") {
                                                            echo "active";
                                                        } ?>" data-type="Alpha">
                                <i class="fa-solid fa-book-open fa-fw"></i> <span>Alpha</span>
                            </li>
                        </a>
                        <a
                            href="report.php">
                            <li class="navi nav-link <?php if ($active == "Roster") {
                                                            echo "active";
                                                        } ?>" data-type="Roster">
                                <i class="fa-solid fa-chart-simple fa-fw"></i> <span>Roster</span>
                            </li>
                        </a>
                        <a
                            href="report.php">
                            <li class="navi nav-link <?php if ($active == "Annex") {
                                                            echo "active";
                                                        } ?>" data-type="Annex">
                                <i class="fa-solid fa-file fa-fw"></i> <span>Annex A</span>
                            </li>
                        </a>
                        <a
                            href="report.php">
                            <li class="navi nav-link <?php if ($active == "Files") {
                                                            echo "active";
                                                        } ?>" data-type="Files">
                                <i class="fa-solid fa-folder-open fa-fw"></i> <span>Files</span>
                            </li>
                        </a>

                    </ul>

                    <a
                        href="announcement.php" class="href">
                        <li class="navi <?php if ($active == "Announcement") {
                                            echo "active";
                                        } ?>">
                            <div class="flex">
                                <span class="!ml-0"><i class="fa-solid fa-bell fa-fw mr-2"></i> Announcement</span>
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

        $(document).on('click', '.href', function(e) {
            e.preventDefault(); // Prevent default link behavior (which would cause a reload)

            // Show the loading spinner dynamically
            $(".loader.loading").removeClass('hidden').fadeIn(300);

            // Get the target URL of the clicked link
            const targetUrl = $(this).attr('href');

            // Simulate a brief delay (so the user sees the loader) before redirecting
            setTimeout(function() {
                window.location.href = targetUrl; // Redirect to the new page
            }, 500); // You can adjust this delay (500ms is just an example)
        });

        // Optionally, show the loader when any AJAX request starts
        $(document).ajaxStart(function() {
            $(".loader.loading").removeClass('hidden').fadeIn(300); // Show loader on AJAX start
        });

        // Hide the loader when AJAX requests are completed
        $(document).ajaxStop(function() {
            $(".loader.loading").fadeOut(300, function() {
                $(this).addClass('hidden'); // Hide the loader after AJAX is done
            });
        });

    })
</script>