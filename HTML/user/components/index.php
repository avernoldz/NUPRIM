<?php

function emptyData(string $emp, $row1 = null, $select = null)
{
    if (!empty($row1["$emp"])) {

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        if (preg_match($pattern, $row1["$emp"]) === 1) {
            $date = date_create("$row1[$emp]");
            return date_format($date, "F d, Y");
        } else {
            return $row1["$emp"];
        }
    } else {
        return $select;
    }
}


function logAction($mysqli, $userid, $action, $account_type)
{
    $stmt = $mysqli->prepare("INSERT INTO userlogs (action, userid, accountType) VALUES (?, ?, ?)");
    $stmt->bind_param('sis', $action, $userid, $account_type);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        echo "Failed to log action.";
    }

    $stmt->close();
}


function getFileType($filename)
{
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    switch (strtolower($extension)) {
        case 'jpg':
        case 'jpeg':
        case 'png':
            return 'image';
        case 'pdf':
            return 'pdf';
        case 'docx':
        case 'xlsx':
            return 'document';
        default:
            return 'unknown';
    }
}

function selectName($conn, $userid)
{
    // Prepare the SQL query using a prepared statement
    $query = "SELECT userid, firstname, lastname FROM user WHERE userid = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $userid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($res);
        if ($row) {
            $name = $row['userid'] . $row['firstname'] . $row['lastname'];
            return $name;
        } else {
            return "User not found";
        }

        mysqli_stmt_close($stmt);
    } else {
        // Handle query preparation error
        return "Error preparing query: " . mysqli_error($conn);
    }
}

function getFullName($conn, $userid)
{
    // Prepare the SQL query using a prepared statement
    $query = "SELECT userid, firstname, lastname FROM user WHERE userid = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $userid);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($res);
        if ($row) {
            $name = $row['firstname'] . ' ' . $row['lastname'];
            return $name;
        } else {
            return "User not found";
        }

        mysqli_stmt_close($stmt);
    } else {
        // Handle query preparation error
        return "Error preparing query: " . mysqli_error($conn);
    }
}


function getSemester()
{
    $today = date('Y-m-d'); // Current date
    $month = (int)date('m', strtotime($today)); // Extract the month as an integer
    $year = date('Y', strtotime($today)); // Extract the year

    if ($month >= 1 && $month <= 6) {
        $semester = "1st Semester";
        $dateRange = "January 1, $year to June 30, $year";
    } else {
        $semester = "2nd Semester";
        $dateRange = "July 1, $year to December 31, $year";
    }

    return [$semester, $dateRange]; // Return an array with both values
}


function formatSize($size)
{
    if ($size < 1024) return $size . ' bytes';
    elseif ($size < 1048576) return round($size / 1024, 2) . ' KB';
    else return round($size / 1048576, 2) . ' MB';
}

function showToastr($message, $type)
{
    echo '<script>
        var alertMessage = "' . addslashes($message) . '";
        if (alertMessage) {
                toastr.' . $type . '(alertMessage);
        }
    </script>';
}



function getUserDataByUserId($conn, $userid)
{
    // SQL query to join all tables with a WHERE clause
    $sql = "
        SELECT 
            u.infoid,
            u.userid,
            u.firstname,
            u.middlename,
            u.lastname,
            u.gender,
            u.qualifier,
            u.status,
            u.dateOfBirth,
            u.placeOfBirth,
            u.contactNumber,
            u.weight,
            u.height,
            u.bloodType,
            u.GSIS,
            u.Pagibig,
            u.Philhealth,
            u.SSS,
            u.TIN,
            u.PNPID,
            
            f.famid,
            f.spouseFname,
            f.spouseMdname,
            f.spouseSrname,
            f.spouseExtension,
            f.fatherFname,
            f.fatherMname,
            f.fatherSrname,
            f.fatherExtension,
            f.motherFname,
            f.motherMname,
            f.motherSrname,
            f.motherExtension,
            
            a.useraddressid,
            a.houseno,
            a.barangay,
            a.city,
            a.province,
            a.region,
            
            eb.educid,
            eb.level,
            eb.nameOfSchool,
            eb.degree,
            eb.yearStarted,
            eb.yearEnded,
            eb.awardReceived
        FROM user u
        LEFT JOIN familyBackground f ON u.userid = f.userid
        LEFT JOIN userAddress a ON u.userid = a.userid
        LEFT JOIN educationalBackground eb ON u.userid = eb.userid
        WHERE u.userid = ?
    ";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $userid); // Assuming userid is an integer
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close(); // Close the statement
        return $data; // Return fetched data
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
        return [];
    }
}

function getUserDataByTable($conn, $userid, $table)
{
    // SQL query to join all tables with a WHERE clause

    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $sql = "
        SELECT * FROM $table 
        WHERE userid = ?
    ";


    if ($table == 'educationalbackground') {
        $sql .= 'ORDER BY yearStarted';
    }

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $userid); // Assuming userid is an integer
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close(); // Close the statement
        return $data; // Return fetched data
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
        return [];
    }
}


function generatePDS($dompdf, $userData, $userChildren, $userEducational, $userEligibility)
{
    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Personal Data Sheet</title>
        <style>
            @page {
                margin: 0.5cm 0.7cm;
            }

            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 9.67px;
            }

            .page {
                page-break-after: always;
                /* Ensure a new page after each .page div */
            }

            body {
                margin: 0;
                padding: 0;
            }

            .italic {
                font-style: italic;
            }

            .bold {
                font-weight: bold;
            }

            .text-15 {
                font-size: 14px;
            }

            .margin {
                margin: 0;
                padding: 0;
            }

            .text-center {
                text-align: center;
            }

            .normal {
                font-weight: 400;
            }

            .straight {
                font-style: normal;

            }

            .narrow-font {
                letter-spacing: -0.5px;
            }

            .bgdark {
                background-color: #717171;
            }

            .white {
                color: white;
            }

            .border {
                border: 1px solid black;
            }

            .font-9 {
                font-size: 9.67px;
            }

            .border-2 {
                border: 2px solid black;
            }

            table {
                border-collapse: collapse;
            }

            td {
                border: 2px solid black;
                letter-spacing: -0.5px;
                padding-left: 8px;
            }

            .lightdark {
                background-color: #eaeaea;
            }

            .black {
                background: black;
            }
        </style>
    </head>

    <body>
        <div style="margin: 0; border: 2px solid black; border-bottom: none; height: 97.3%;" class="page">
            <header>
                <p class="italic bold padding margin">
                    CS Form No. 212
                    <br>
                    Revised 2017
                </p>
                <p class="text-center bold margin" style="font-size: 29px">PERSONAL DATA SHEET</p>
                <p class="italic bold" style="margin-bottom:0;">WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned.
                    <br>
                    READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.
                    <br>
                    <span class="normal straight narrow-font font-9">
                        Print legibly. Tick appropriate boxes [ ] and use separate sheet if necessary. Indicate N/A if not applicable. <span class="bold">DO NOT ABBREVIATE.</span>
                    </span>

                    <span class="bgdark normal straight narrow-font font-9 border" style="padding: 2px;margin-left: 3px;">
                        1. CS ID No.
                        <span class="font-9" style="text-align: right; background-color: white !important;padding: 2px; padding-left: 65px;"> (Do not fill up. For CSC use only)</span>
                    </span>
                </p>
            </header>
            <div class="personal-info">
                <p class="text-15 italic bold white bgdark margin border-2 narrow-font" style="padding-bottom: 2px;">I. PERSONAL INFORMATION</p>
                <table style="width: 100%;">
                    <tr>
                        <td rowspan="3" style="width: 21%;" class="lightdark">
                            2. <span style="margin-left: 4px;">SURNAME</span>
                            <br>
                            <br>
                            <span style="margin-left: 13px;">FIRSTNAME</span>
                            <br>
                            <br>
                            <span style="margin-left: 13px;">LASTNAME</span>
                        </td>
                        <td colspan="6" class="bold">
                            <?php echo strtoupper(htmlspecialchars($userData[0]['lastname'])) ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="bold">
                            <?php echo strtoupper(htmlspecialchars($userData[0]['firstname'])) ?>
                        </td>
                        <td class="lightdark">
                            <span style="font-size: 8.67px;margin:0;">NAME EXTENSION (JR., SR)</span>
                            <br>
                            <span class="bold">&nbsp;</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="bold">
                            <?php echo strtoupper(htmlspecialchars($userData[0]['middlename'])) ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            3. <span style="margin-left: 4px;"> DATE OF BIRTH</span>
                            <br>
                            <span style="margin-left: 25px;"> (mm/dd/yyyy)</span>
                        </td>
                        <td class="bold"><?php echo date('m/d/Y', strtotime(htmlspecialchars($userData[0]['dateOfBirth']))) ?></td>
                        <td rowspan="3" class="lightdark" colspan="2">
                            16. <span style="margin-left: 4px;"> CITIZENSHIP</span>
                            <br>
                            <br>
                            <br>
                            <br>
                            <span>If holder of dual citizenship, please indicate the details.</span>
                            <br>
                            <br>
                        </td>
                        <td rowspan="2" colspan="3">
                            <p>
                                <span style="padding: 1px 4px;" class="border">&nbsp;&nbsp;</span>&nbsp;&nbsp; Filipino
                                <span style="padding: 1px 4px; margin-left: 31px;" class="border">&nbsp;&nbsp;</span>&nbsp;&nbsp; Dual Citizenship
                                <br>
                                <br>
                                <span style="padding: 1px 4px; margin-left: 100px;" class="border">&nbsp;&nbsp;</span>&nbsp;&nbsp; by birth
                                <span style="padding: 1px 4px; " class="border">&nbsp;&nbsp;</span>&nbsp;&nbsp; by naturalization
                                <br>
                                <span style="margin-left: 100px;">Pls. indicate country:</span>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            4. <span style="margin-left: 4px;"> PLACE OF BIRTH</span>
                            <br>
                        </td>
                        <td class="bold"><?php echo strtoupper(htmlspecialchars($userData[0]['placeOfBirth'])) ?></td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            5. <span style="margin-left: 4px;"> SEX</span>
                            <br>
                        </td>
                        <td style="width: 20%;">
                            <p>

                                <span style="padding: 1px 4px; background:black;" class="border  <?php if ($userData[0]['gender'] == 'Male') echo "black"; ?>">&nbsp;&nbsp;</span>&nbsp;&nbsp; Male
                                <span style="padding: 1px 4px; margin-left: 23px;" class="border <?php if ($userData[0]['gender'] == 'Female') echo "black"; ?>">&nbsp;&nbsp;</span>&nbsp;&nbsp; Female
                            </p>
                        </td>
                        <td colspan="3">
                        </td>
                    </tr>

                    <tr>
                        <td class="lightdark" rowspan="2">
                            6. <span style="margin-left: 4px;"> CIVIL STATUS</span>
                            <br>
                            <br>
                            <br>
                            <br>
                        </td>
                        <td rowspan="2">
                            <p>
                                <span style="padding: 1px 4px;" class="border">&nbsp;&nbsp;</span>&nbsp;&nbsp; Single
                                <span style="padding: 1px 4px; margin-left: 18px;" class="border">&nbsp;&nbsp;</span>&nbsp;&nbsp; Married
                            </p>
                            <p>
                                <span style="padding: 1px 4px;" class="border">&nbsp;&nbsp;</span>&nbsp;&nbsp; Widowed
                                <span style="padding: 1px 4px; margin-left: 5px;" class="border">&nbsp;&nbsp;</span>&nbsp;&nbsp; Seperated
                            </p>
                            <p>
                                <span style="padding: 1px 4px;" class="border">&nbsp;&nbsp;</span>&nbsp;&nbsp; Other/s
                            </p>
                        </td>
                        <td style="width:16%" rowspan="4" class="lightdark">
                            17. <span style="margin-left: 4px;"> RESIDENTIAL ADDRESS</span>
                            <br><br><br> <br><br><br><br>
                            <br>
                            <span>ZIP CODE</span>
                        </td>
                        <td colspan="4" style="padding:0; ">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="border:none; border-bottom: 1px solid #e3e3e3; height: 20px;" colspan="2" class="bold text-center">
                                        <?php echo htmlspecialchars(emptyData("houseno", $userData[0])) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:none; width: 60%;" class="text-center italic">House/Block/Lot No.</td>
                                    <td style="border:none;" class="text-center italic">Street</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4" style="padding:0; ">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="border:none; border-bottom: 1px solid #e3e3e3; height: 20px;" colspan="2" class="bold text-center">
                                        <?php echo strtoupper(htmlspecialchars(emptyData("barangay", $userData[0]))) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:none; width: 60%;" class="text-center italic">Subdivision/Village</td>
                                    <td style="border:none;" class="text-center italic">Barangay</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            7. <span style="margin-left: 4px;"> HEIGHT (m)</span>
                            <br>
                        </td>
                        <td><?php echo htmlspecialchars(emptyData("height", $userData[0])) ?></td>
                        <td colspan="4" style="padding:0; ">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="border:none; border-bottom: 1px solid #e3e3e3; height: 20px;" class="bold text-center">
                                        <?php echo strtoupper(htmlspecialchars(emptyData("city", $userData[0]))) ?>
                                    </td>
                                    <td style="border:none; border-bottom: 1px solid #e3e3e3; height: 20px;" class="bold text-center">
                                        <?php echo strtoupper(htmlspecialchars(emptyData("province", $userData[0]))) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:none; width: 60%;" class="text-center italic">City/Municipality</td>
                                    <td style="border:none;" class="text-center italic">Province</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            8. <span style="margin-left: 4px;"> WEIGHT (kg)</span>
                            <br>
                        </td>
                        <td><?php echo htmlspecialchars(emptyData("weight", $userData[0])) ?></td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            9. <span style="margin-left: 4px;"> BLOOD TYPE</span>
                            <br>
                        </td>
                        <td class="bold"><?php echo htmlspecialchars(emptyData("bloodType", $userData[0])) ?></td>
                        <td rowspan="4" class="lightdark">
                            18. <span style="margin-left: 4px;"> PERMANENT ADDRESS</span>
                            <br><br><br> <br><br><br><br>
                            <br>
                            <span>ZIP CODE</span>
                        </td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            10. <span style="margin-left: 4px;"> GSIS ID NO.</span>
                            <br>
                        </td>
                        <td><?php echo htmlspecialchars(emptyData("GSIS", $userData[0])) ?></td>
                        <td colspan="4">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="border:none; border-bottom: 1px solid #e3e3e3; height: 20px;" colspan="2" class="bold text-center">
                                        <?php echo htmlspecialchars(emptyData("houseno", $userData[0])) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:none; width: 60%;" class="text-center italic">House/Block/Lot No.</td>
                                    <td style="border:none;" class="text-center italic">Street</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            11. <span style="margin-left: 4px;"> PAG-IBIG ID NO.</span>
                            <br>
                        </td>
                        <td><?php echo htmlspecialchars(emptyData("Pagibig", $userData[0])) ?></td>
                        <td colspan="4" style="padding:0; ">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="border:none; border-bottom: 1px solid #e3e3e3; height: 20px;" class="bold text-center">
                                        <?php echo strtoupper(htmlspecialchars(emptyData("city", $userData[0]))) ?>
                                    </td>
                                    <td style="border:none; border-bottom: 1px solid #e3e3e3; height: 20px;" class="bold text-center">
                                        <?php echo strtoupper(htmlspecialchars(emptyData("province", $userData[0]))) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:none; width: 60%;" class="text-center italic">City/Municipality</td>
                                    <td style="border:none;" class="text-center italic">Province</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            12. <span style="margin-left: 4px;"> PHILHEALTH NO.</span>
                            <br>
                        </td>
                        <td><?php echo htmlspecialchars(emptyData("Philhealth", $userData[0])) ?></td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td class="lightdark" style="padding: 10px;">
                            13. <span style="margin-left: 4px;"> SSS NO.</span>
                            <br>
                        </td>
                        <td><?php echo htmlspecialchars(emptyData("SSS", $userData[0])) ?></td>
                        <td class="lightdark">
                            19. <span style="margin-left: 4px;"> TELEPHONE NO.</span>
                        </td>
                        <td colspan="4">

                        </td>
                    </tr>

                    <tr>
                        <td class="lightdark" style="padding: 10px;">
                            14. <span style="margin-left: 4px;"> TIN NO.</span>
                            <br>
                        </td>
                        <td><?php echo htmlspecialchars(emptyData("TIN", $userData[0])) ?></td>
                        <td class="lightdark">
                            20. <span style="margin-left: 4px;"> MOBILE NO.</span>
                        </td>
                        <td colspan="4" class="bold">
                            <?php echo htmlspecialchars(emptyData("contactNumber", $userData[0])) ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="lightdark">
                            15. <span style="margin-left: 4px;"> AGENCY EMPLOYEE NO.</span>
                            <br>
                        </td>
                        <td><?php echo strtoupper(htmlspecialchars($userData[0]['placeOfBirth'])) ?></td>
                        <td class="lightdark">
                            21. <span style="margin-left: 4px;"> E-MAIL ADDRESS(if any)</span>
                        </td>
                        <td colspan="4"></td>
                    </tr>
                </table>

                <p class="text-15 italic bold white bgdark margin border-2 narrow-font" style="padding-bottom: 2px;">II. FAMILY BACKGROUND</p>
                <table style="width:100%;">
                    <tbody>
                        <tr>
                            <td rowspan="3" class="lightdark" style="width: 21%;">
                                22. <span style="margin-left: 4px;">SPOUSE'S SURNAME</span>
                                <br>
                                <br>
                                <span style="margin-left: 13px;">FIRSTNAME</span>
                                <br>
                                <br>
                                <span style="margin-left: 13px;">MIDDLENAME</span>
                            </td>
                            <td colspan="2" class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("spouseSrname", $userData[0]))) ?>
                            </td>
                            <td class="lightdark">
                                23. <span style="margin-left: 4px;">NAME of CHILDREN (Write full name and list all)</span>
                            </td>
                            <td class="lightdark">
                                <span style="margin-left: 4px;">DATE OF BIRTH (mm/dd/yyyy)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("spouseFname", $userData[0]))) ?>
                            </td>
                            <td class="lightdark">
                                <span style="font-size: 8.67px;margin:0;">NAME EXTENSION (JR., SR)</span>
                                <br>
                                <span>&nbsp;</span>
                            </td>
                            <td class="bold text-center"><?php echo !empty($userChildren[0]) ? strtoupper(htmlspecialchars(emptyData("fullname", $userChildren[0]))) : ''; ?></td>
                            <td class="bold text-center"><?php echo !empty($userChildren[0]) ? date('m/d/Y', strtotime(emptyData("dateOfBirth", $userChildren[0]))) : ''; ?></td>

                        </tr>
                        <tr>
                            <td colspan="2" class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("spouseMdname", $userData[0]))) ?>
                            </td>
                            <td class="bold text-center"><?php echo !empty($userChildren[1]) ? strtoupper(htmlspecialchars(emptyData("fullname", $userChildren[1]))) : ''; ?></td>
                            <td class="bold text-center"><?php echo !empty($userChildren[1]) ? date('m/d/Y', strtotime(emptyData("dateOfBirth", $userChildren[1]))) : ''; ?></td>

                        </tr>
                        <tr>
                            <td class="lightdark">
                                <span style="margin-left: 4px;">OCCUPATION</span>
                            </td>
                            <td colspan="2"></td>
                            <td class="bold text-center"><?php echo !empty($userChildren[2]) ? strtoupper(htmlspecialchars(emptyData("fullname", $userChildren[2]))) : ''; ?></td>
                            <td class="bold text-center"><?php echo !empty($userChildren[2]) ? date('m/d/Y', strtotime(emptyData("dateOfBirth", $userChildren[2]))) : ''; ?></td>
                        </tr>
                        <tr>
                            <td class="lightdark">
                                <span style="margin-left: 4px;">EMPLOYER/BUSINESS NAME</span>
                            </td>
                            <td colspan="2"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="lightdark">
                                <span style="margin-left: 4px;">BUSINESS ADDRESS</span>
                            </td>
                            <td colspan="2"></td>
                            <td class="bold text-center"><?php echo !empty($userChildren[3]) ? strtoupper(htmlspecialchars(emptyData("fullname", $userChildren[3]))) : ''; ?></td>
                            <td class="bold text-center"><?php echo !empty($userChildren[3]) ? date('m/d/Y', strtotime(emptyData("dateOfBirth", $userChildren[3]))) : ''; ?></td>

                        </tr>
                        <tr>
                            <td class="lightdark">
                                <span style="margin-left: 4px;">TELEPHONE NO.</span>
                            </td>
                            <td colspan="2"></td>
                            <td class="bold text-center"><?php echo !empty($userChildren[4]) ? strtoupper(htmlspecialchars(emptyData("fullname", $userChildren[4]))) : ''; ?></td>
                            <td class="bold text-center"><?php echo !empty($userChildren[4]) ? date('m/d/Y', strtotime(emptyData("dateOfBirth", $userChildren[4]))) : ''; ?></td>

                        </tr>
                        <tr>
                            <td rowspan="3" class="lightdark">
                                24. <span style="margin-left: 4px;">FATHER'S SURNAME</span>
                                <br>
                                <br>
                                <span style="margin-left: 13px;">FIRSTNAME</span>
                                <br>
                                <br>
                                <span style="margin-left: 13px;">MIDDLENAME</span>
                            </td>
                            <td colspan="2" class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("fatherSrname", $userData[0]))) ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("fatherFname", $userData[0]))) ?>
                            </td>
                            <td class="lightdark">
                                <span style="font-size: 8.67px;margin:0;">NAME EXTENSION (JR., SR)</span>
                                <br>
                                <span class="bold"> <?php echo strtoupper(htmlspecialchars(emptyData("fatherExtension", $userData[0]))) ?></span>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("fatherMname", $userData[0]))) ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td rowspan="4" class="lightdark">
                                25. <span style="margin-left: 4px;">MOTHER'S MAIDEN NAME</span>
                                <br>
                                <span style="margin-left: 13px;">SURNAME</span>
                                <br>
                                <span style="margin-left: 13px;">FIRSTNAME</span>
                                <br>
                                <span style="margin-left: 13px;">MIDDLENAME</span>
                            </td>
                            <td colspan="2" class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("motherFname", $userData[0]))) ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("motherSrname", $userData[0]))) ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("motherFname", $userData[0]))) ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bold">
                                <?php echo strtoupper(htmlspecialchars(emptyData("motherMname", $userData[0]))) ?>
                            </td>
                            <td colspan="2" class="lightdark text-center italic" style="color:red;">
                                (Continue on separate sheet if necessary)
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-15 italic bold white bgdark margin border-2 narrow-font" style="padding-bottom: 2px;">III. EDUCATIONAL BACKGROUND</p>
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td class="lightdark text-center" style="width: 21%;" rowspan="2">
                                26. <span>LEVEL</span>
                            </td>
                            <td class="lightdark text-center" rowspan="2" style="width: 21%;">
                                NAME OF SCHOOL
                                (Write in full)
                            </td>
                            <td class="lightdark text-center" rowspan="2">
                                BASIC EDUCATION/DEGREE/COURSE
                                (Write in full)
                            </td>
                            <td colspan="2" class="lightdark text-center">
                                PERIOD OF ATTENDANCE
                            </td>
                            <td class="lightdark text-center" rowspan="2">
                                HIGHEST
                                LEVEL/
                                UNITS
                                EARNED
                                (if not)
                            </td>
                            <td class="lightdark text-center" rowspan="2">
                                YEAR
                                GRADUATED
                            </td>
                            <td class="lightdark text-center" rowspan="2">
                                SCHOLARSHIP/
                                ACADEMIC
                                HONORS
                                RECEIVED
                            </td>
                        </tr>
                        <tr>
                            <td class="lightdark">
                                From
                            </td>
                            <td class="lightdark">
                                To
                            </td>
                        </tr>
                        <?php
                        // Map educational levels
                        $educationalLevels = [
                            'Elementary' => 'Primary School',
                            'Secondary' => 'High School',
                            'Vocational/Trade Course' => 'Vocational',
                            'College' => 'Bachelors Degree',
                            'Graduate Studies' => ['Masteral Degree', 'Doctoral Degree'],
                        ];

                        // Function to check if a level matches
                        function levelMatches($level, $educationalLevels)
                        {
                            foreach ($educationalLevels as $key => $value) {
                                if (is_array($value) && in_array($level, $value)) {
                                    return $key;
                                } elseif ($value === $level) {
                                    return $key;
                                }
                            }
                            return null;
                        }

                        // Assume $userEducational is an array of user education data
                        foreach ($userEducational as $educationalData) {
                            $level = emptyData("level", $educationalData); // Assuming "level" exists in your data
                            $mappedLevel = levelMatches($level, $educationalLevels);

                            if ($mappedLevel) {
                                // Retrieve the school name, degree, year started, year ended, and award received
                                $nameOfSchool = strtoupper(htmlspecialchars(emptyData("nameOfSchool", $educationalData)));
                                $degree = strtoupper(htmlspecialchars(emptyData("degree", $educationalData)));
                                $yearStarted = date('m/d/Y', strtotime(emptyData("yearStarted", $educationalData)));
                                $yearEnded = date('m/d/Y', strtotime(emptyData("yearEnded", $educationalData)));
                                $awardReceived = strtoupper(htmlspecialchars(emptyData("awardReceived", $educationalData)));

                                // Render the table row
                                echo "<tr>
                                        <td class='lightdark'>" . strtoupper($mappedLevel) . " </td>
                                        <td>{$nameOfSchool}</td>
                                        <td>{$degree}</td>
                                        <td>{$yearStarted}</td>
                                        <td>{$yearEnded}</td>
                                        <td></td>
                                        <td>{$yearEnded}</td>
                                        <td>{$awardReceived}</td>
                                    </tr>";
                            }
                        }

                        // Vocational/Trade Course row (if needed)
                        if (!array_filter($userEducational, function ($edu) use ($educationalLevels) {
                            return levelMatches(emptyData("level", $edu), $educationalLevels) === 'Vocational/Trade Course';
                        })) {
                            echo "<tr>
                                    <td class='lightdark'>VOCATIONAL/TRADE COURSE</td>
                                    <td colspan='7'></td>
                                  </tr>";
                        }

                        // Graduate Studies row (if needed)
                        if (!array_filter($userEducational, function ($edu) use ($educationalLevels) {
                            return levelMatches(emptyData("level", $edu), $educationalLevels) === 'Graduate Studies';
                        })) {
                            echo "<tr>
                                    <td class='lightdark'>GRADUATE STUDIES</td>
                                    <td colspan='7'></td>
                                </tr>";
                        }
                        ?>

                        <tr>
                            <td colspan="8" class="italic bold text-center lightdark" style="color:red;"> (Continue on separate sheet if necessary)</td>
                        </tr>
                        <tr>
                            <td class="lightdark bold italic text-15 text-center" style="padding: 13px;">
                                SIGNATURE
                            </td>
                            <td colspan="2"></td>
                            <td colspan="2" class="lightdark bold italic text-15 text-center" style="padding: 13px;">
                                DATE
                            </td>
                            <td colspan="3" class="bold"><?php echo date('F d, Y') ?></td>
                        </tr>
                    </tbody>
                </table>
                <p class="italic narro-font margin" style="text-align: right; font-size: 8px;">CS FORM 212 (Revised 2017), Page 1 of 4</p>
            </div>
        </div>

        <div class="page-two">
            <div class="civil">
                <p class="text-15 italic bold white bgdark margin border-2 narrow-font" style="padding-bottom: 2px;">IV. CIVIL SERVICE ELIGIBILITY</p>
                <table style="width: 100%;">
                    <tbody>
                        <tr class="lightdark text-center">
                            <td rowspan="2">
                                27. CAREER SERVICE/ RA 1080 (BOARD/ BAR) UNDER
                                SPECIAL LAWS/ CES/ CSEE
                                BARANGAY ELIGIBILITY / DRIVER'S LICENSE

                            </td>
                            <td rowspan="2">
                                RATING
                                (If Applicable)
                            </td>
                            <td rowspan="2">
                                DATE OF
                                EXAMINATION /
                                CONFERMENT
                            </td>
                            <td rowspan="2">
                                PLACE OF EXAMINATION / CONFERMENT
                            </td>
                            <td colspan="2"> LICENSE (if applicable)</td>
                        </tr>
                        <tr class="lightdark text-center">
                            <td>NUMBER</td>
                            <td>Date of
                                Validity </td>
                        </tr>
                        <?php
                        $totalRows = 7;
                        $currentCount = count($userEligibility);
                        $emptyRowsNeeded = $totalRows - $currentCount;
                        foreach ($userEligibility as $data): ?>
                            <tr>
                                <td style="padding: 7px; width: 33%;"><?php echo strtoupper(htmlspecialchars(emptyData("eligibility", $data))) ?></td>
                                <td class="text-center"><?php echo number_format(htmlspecialchars(emptyData("rating", $data)), 2) ?></td>
                                <td class="text-center"><?php echo date('m/d/Y', strtotime(emptyData("dateOfExam", $data))) ?></td>
                                <td class="text-center" style="width: 30%;"><?php echo strtoupper(htmlspecialchars(emptyData("placeOfExam", $data))) ?></td>
                                <td class="text-center"><?php echo strtoupper(htmlspecialchars(emptyData("licenseNo", $data))) ?></td>
                                <td class="text-center"><?php echo date('m/d/Y', strtotime(emptyData("validity", $data))) ?></td>
                            </tr>
                        <?php endforeach;

                        for ($i = 0; $i < $emptyRowsNeeded; $i++): ?>
                            <tr>
                                <td style="padding: 14px;"></td>
                                <td style="padding: 14px;"></td>
                                <td style="padding: 14px;"></td>
                                <td style="padding: 14px;"></td>
                                <td style="padding: 14px;"></td>
                                <td style="padding: 14px;"></td>
                            </tr>
                        <?php endfor; ?>
                        <tr>
                            <td colspan="6" class="italic bold text-center lightdark" style="color:red;"> (Continue on separate sheet if necessary)</td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-15 italic bold white bgdark margin border-2 narrow-font" style="padding-bottom: 2px;">V. WORK EXPERIENCE
                    <br>
                    <span style="font-size: 12px;">(Include private employment. Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet.</span>
                </p>
                <table>
                    <tr class="text-center lightdark">
                        <td colspan=" 2">28. INCLUSIVE DATES
                            (mm/dd/yyyy)
                        </td>
                        <td rowspan="2" style="width: 25%;">POSITION TITLE
                            (Write in full/Do not abbreviate)</td>
                        <td rowspan="2" style="width: 25%;">
                            DEPARTMENT / AGENCY / OFFICE / COMPANY
                            (Write in full/Do not abbreviate)
                        </td>
                        <td rowspan="2">
                            MONTHLY
                            SALARY
                        </td>
                        <td rowspan="2">
                            SALARY/ JOB/
                            PAY GRADE (if
                            applicable)& STEP
                            (Format "00-0")/
                            From INCREMENT
                        </td>
                        <td rowspan="2">STATUS OF
                            APPOINTMENT</td>
                        <td rowspan="2">GOV'T
                            SERVICE
                            (Y/ N)</td>
                    </tr>
                    <tr class="text-center lightdark">
                        <td style="width: 8%;">From</td>
                        <td style="width: 8%;">To</td>
                    </tr>
                    <?php for ($row = 1; $row <= 28; $row++): ?>
                        <tr>
                            <?php for ($col = 1; $col <= 8; $col++): ?>
                                <td style="padding: 13.3px;"></td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                    <tr>
                        <td colspan="8" class="italic bold text-center lightdark" style="color:red;"> (Continue on separate sheet if necessary)</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center bold italic lightdark text-15" style="padding: 13px;">SIGNATURE</td>
                        <td colspan="3"></td>
                        <td class="text-center bold italic lightdark text-15"> DATE</td>
                        <td colspan="2" class="bold"> <?php echo date('F d, Y') ?></td>
                    </tr>
                </table>
                <p class="italic narro-font margin" style="text-align: right; font-size: 8px;">CS FORM 212 (Revised 2017), Page 2 of 4</p>
            </div>

    </body>

    </html>
<?php
    $html = ob_get_clean();
    $dompdf->loadHtml($html);

    // Set paper size and margins directly
    $dompdf->setPaper('Legal', 'portrait');
    // $dompdf->render();

    // $dompdf->stream("document.pdf", ['Attachment' => false]);
}
