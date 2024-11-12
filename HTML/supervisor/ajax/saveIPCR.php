<?php

include "../../../Connections/Include.php";
include "../components/index.php";

$data = json_decode($_POST['data'], true);

// Extract Data fields
$userData = $data['Data'][0];
$sdateA = $userData['sdateA'];
$sdateB = $userData['sdateB'];
$edateA = $userData['edateA'];
$edateB = $userData['edateB'];
$pmt = $userData['pmt'];
$pmtPos = $userData['pmtPos'];
$rater = $userData['rater'];
$comments = $userData['comments'];
$action = $userData['action'];
$q = $userData['q'];
$t = $userData['t'];
$e = $userData['e'];
$finalRating = $userData['finalRating'];
$status = 'Approved';
$supervisorid = isset($userData['supervisorid']) ? $userData['supervisorid'] : null;
$ipcrid = isset($_POST['ipcrid']) ? $_POST['ipcrid'] : null;

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
$sqlUpdate = "UPDATE ipcr SET sdateA = ?, sdateB = ?, edateA = ?, edateB = ?, pmt = ?, pmtPos = ?, rater = ?, comments = ?, action = ?,  core = ?, support = ?, status = ?, q = ?, t = ?, e = ?, finalRating = ? WHERE ipcrid = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("ssssssssssssssssi", $sdateA, $sdateB, $edateA, $edateB, $pmt, $pmtPos, $rater, $comments, $action, $coreJson, $supportJson, $status, $q, $t, $e, $finalRating, $ipcrid);
$stmtUpdate->execute();

// Return success response
echo json_encode(['status' => 'success', 'message' => 'Data saved successfully.']);

// Close the database connection
$conn->close();
