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

function formatSize($size)
{
    if ($size < 1024) return $size . ' bytes';
    elseif ($size < 1048576) return round($size / 1024, 2) . ' KB';
    else return round($size / 1048576, 2) . ' MB';
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

    $name = $row['userid'] . $row['firstname'] . $row['lastname'];
    return $name;
}


function countPendingIPCR($conn, $station)
{
    $query = "SELECT COUNT(ipcr.status) AS ipcr_count
                FROM ipcr
                INNER JOIN account ON account.userid = ipcr.userid
                INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber
                WHERE ipcr.status = 'Waiting for Approval' AND plantilla.station = '$station'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($res);
    return $row['ipcr_count'];
}

function getOffice($conn, $id)
{
    $query = "SELECT station 
    FROM plantilla 
    INNER JOIN account ON account.itemNumber = plantilla.itemNumber
    WHERE account.userid = '$id'";

    $res = mysqli_query($conn, $query);

    $row = mysqli_fetch_array($res);
    return $row['station'];
}
