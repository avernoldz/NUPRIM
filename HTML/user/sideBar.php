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
                        href="dashboard.php?userid=<?php echo $userid ?>" class="href">
                        <li class="navi <?php if ($active == "Home") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-border-all fa-fw"></i>
                            <span>Home</span>
                        </li>
                    </a>
                    <a
                        href="#" id="profile-drop">
                        <li class="navi <?php if ($active == "My Profile") {
                                            echo "active";
                                        }
                                        if ($on == "hon") {
                                            echo "act";
                                        }; ?>">
                            <i class="fa-solid fa-address-card fa-fw"></i>
                            <span>My Profile</span>
                        </li>
                    </a>

                    <ul class="drop-profile <?php if ($on == "hon") {
                                                echo "hon";
                                            } ?>">
                        <a
                            href="personalInformation.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Personal Information") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-circle-info fa-fw"></i> <span>Personal Information</span>
                            </li>
                        </a>
                        <a
                            href="address.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Address") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-location-dot fa-fw"></i> <span>Address</span>
                            </li>
                        </a>
                        <a
                            href="family.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Family") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-people-group fa-fw"></i> <span>Family</span>
                            </li>
                        </a>
                        <a
                            href="education.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Education") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-book-open fa-fw"></i> <span>Education</span>
                            </li>
                        </a>
                        <a
                            href="eligibility.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Eligibility") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-chart-simple fa-fw"></i> <span>Eligibility</span>
                            </li>
                        </a>
                        <a
                            href="service.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Service Record") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-file fa-fw"></i> <span>Service Record</span>
                            </li>
                        </a>
                        <a
                            href="details.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Detail Orders") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-folder-open fa-fw"></i> <span>Detail Orders</span>
                            </li>
                        </a>
                        <a
                            href="training.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Training/Seminar") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-book fa-fw"></i> <span>Training/Seminar</span>
                            </li>
                        </a>
                        <a
                            href="leaves.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Leave Records") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-clipboard fa-fw"></i> <span>Leave Records</span>
                            </li>
                        </a>

                        <a
                            href="criminalCase.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "Criminal Case") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-handcuffs fa-fw"></i> <span>Criminal Case</span>
                            </li>
                        </a>

                        <a
                            href="action/pds.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "PDS") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-file-export fa-fw"></i> <span>Personal Data Sheet</span>
                            </li>
                        </a>

                    </ul>

                    <a
                        href="myDocuments.php?userid=<?php echo $userid ?>" class="href">
                        <li class="navi <?php if ($active == "My Documents") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-folder fa-fw"></i>
                            <span>My Documents</span>
                        </li>
                    </a>

                    <a
                        href="#" id="ipcr-drop">
                        <li class="navi <?php if ($active == "My Profile") {
                                            echo "active";
                                        }
                                        if ($on == "on") {
                                            echo "block";
                                        }; ?>">
                            <i class="fa-solid fa-file fa-fw"></i>
                            <span>IPCR</span>
                        </li>
                    </a>

                    <ul class="drop-ipcr <?php if ($on == "on") {
                                                echo "block";
                                            } else {
                                                echo "hidden";
                                            } ?>">
                        <a
                            href="ipcr.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "IPCR") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-file fa-fw"></i> <span>IPCR Reports</span>
                            </li>
                        </a>

                        <a
                            href="ipcrDocuments.php?userid=<?php echo $userid ?>" class="href">
                            <li class="navi <?php if ($active == "IPCR Docs") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-folder-open fa-fw"></i> <span>Accomplishments</span>
                            </li>
                        </a>
                    </ul>

                    <a
                        href="settings.php?userid=<?php echo $userid ?>" class="href">
                        <li class="navi <?php if ($active == "Settings") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-gear fa-fw"></i> <span>Settings</span>
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

        $('#ipcr-drop').click(function() {
            $('.drop-ipcr').slideToggle("fast", "linear");
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