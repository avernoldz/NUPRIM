<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

require '../../../vendor/autoload.php'; // Change the path as needed if you're not using Composer

use Dompdf\Dompdf;
use Dompdf\Options;
use setasign\Fpdi\Fpdi;

$pdf = new Fpdi();

// Setup DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Retrieve user data
$userid = $_SESSION['userid'];
$userData = getUserDataByUserId($conn, $userid);
$userChildren = getUserDataByTable($conn, $userid, 'children');
$userEducational = getUserDataByTable($conn, $userid, 'educationalbackground');
$userEligibility = getUserDataByTable($conn, $userid, 'eligibility');

generatePDS($dompdf, $userData, $userChildren, $userEducational, $userEligibility);

$dompdf->render();
// $dompdf->stream("Personal Data Sheet.pdf", ['Attachment' => false]);

$domPdfOutput = $dompdf->output();

$tempFilePath = tempnam(sys_get_temp_dir(), 'dompdf_') . '.pdf';
file_put_contents($tempFilePath, $domPdfOutput);


$pageCount = $pdf->setSourceFile($tempFilePath);
for ($i = 1; $i <= $pageCount; $i++) {
    $pdf->AddPage('P', [352, 216]); // Portrait orientation, legal size
    $pdf->useTemplate($pdf->importPage($i));
}

// Add another legal-sized page for the external PDF
$pageCount = $pdf->setSourceFile('Personal Data Sheet.pdf'); // Change this to your PDF path
for ($i = 1; $i <= $pageCount; $i++) {
    $pdf->AddPage('P', [352, 216]); // Portrait orientation, legal size
    $pdf->useTemplate($pdf->importPage($i));
}

$pdf->Output('Personal Data Sheet.pdf', 'I');

// $saveResult = saveReport($save, $type, $getDateRange, $userid, $conn);
// // Execute the statement
// if ($saveResult['success']) {
//     header("Location: ../report.php");
//     exit();
// } else {
//     echo $saveResult['message'];
// }

$conn->close();
