<?php
session_start();
require_once '../../../vendor/autoload.php';
include "../../../Connections/Include.php";
include "../components/index.php";

$station = $_GET['station'];

// Fetch announcements for the specified station
$announcements = getAnnouncement($conn, $station);

// Prepare the HTML for the announcements
$html = '';
foreach ($announcements as $announcement) {
    $isNew = strtotime($announcement['created_at']) >= strtotime('2023-01-01');  // Adjust threshold date if needed
    $html .= '<div class="mb-2">';
    $html .= '<div class="flex justify-content-between">';
    $html .= '<div class="inline">';
    $html .= '<p class="font-semibold">' . htmlspecialchars($announcement['title']);
    if ($isNew) {
        $html .= '<span class="ml-2 text-xs text-white bg-red-500 rounded-sm px-2">New</span>';
    }
    $html .= '</p>';
    $html .= '</div>';
    $html .= '<p class="font-semibold">' . date('F j, Y', strtotime(htmlspecialchars($announcement['created_at']))) . '</p>';
    $html .= '</div>';
    $html .= '<p class="text-gray-600">' . nl2br(htmlspecialchars($announcement['message'])) . '</p>';
    $html .= '</div><hr class="border-1 border-gray-400 mb-2">';
}

// Return the generated HTML
echo $html;
