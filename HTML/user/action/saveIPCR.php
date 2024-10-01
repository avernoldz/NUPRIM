<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

$data = json_decode($_POST['data'], true);

// Extract Data fields
$userData = $data['Data'][0];
$userid = $userData['userid'];
$semester = $userData['semester'];
$year = $userData['year'];
$sdateA = $userData['sdateA'];
$sdateB = $userData['sdateB'];
$edateA = $userData['edateA'];
$edateB = $userData['edateB'];
$pmt = $userData['pmt'];
$pmtPos = $userData['pmtPos'];
$rater = $userData['rater'];
$status = 'Waiting for Approval';
$supervisorid = isset($userData['supervisorid']) ? $userData['supervisorid'] : null;

// Insert into ipcr table
$sql = "INSERT INTO ipcr (userid, year, semester, sdateA, sdateB, edateA, edateB, pmt, pmtPos, rater, supervisorid, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissssssssis", $userid, $year, $semester, $sdateA, $sdateB, $edateA, $edateB, $pmt, $pmtPos, $rater, $supervisorid, $status);

if ($stmt->execute()) {
    $ipcrid = $conn->insert_id;
    logAction($conn, $_SESSION['userid'], 'Generate new IPCR', $_SESSION['type']);
}

// Prepare to insert Core data
$coreData = [];
foreach ($data['Core'] as $coreItem) {
    $coreData[] = $coreItem; // Keep items as associative arrays
}

$supportData = [];
foreach ($data['Support'] as $supportItem) {
    $supportData[] = $supportItem; // Keep items as associative arrays
}

// Encode entire data as JSON
$coreJson = json_encode($coreData);
$supportJson = json_encode($supportData);

// Update the core and support fields in one go
$sqlUpdate = "UPDATE ipcr SET core = ?, support = ? WHERE ipcrid = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("ssi", $coreJson, $supportJson, $ipcrid);

if (!$stmtUpdate->execute()) {
    echo "Error executing statement: " . $stmtUpdate->error;
}

// Return success response
echo json_encode(['status' => 'success', 'message' => 'Data saved successfully.']);

// Close the database connection
$conn->close();
