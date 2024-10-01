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
    $query = "SELECT userid, firstname, lastname FROM user WHERE userid = '$userid'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($res);

    if ($res) {
        $row = mysqli_fetch_array($res);

        // Check if $row is not null
        if ($row) {
            // Construct the name
            $name = $row['userid'] . ' ' . $row['firstname'] . ' ' . $row['lastname'];
            return $name;
        } else {
            // Handle case where no data is found
            return "User not found";
        }
    } else {
        // Handle query error
        return "Error in query: " . mysqli_error($conn);
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
