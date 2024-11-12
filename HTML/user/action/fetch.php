<?php
session_start();
include "../../../Connections/Include.php";
include "../components/index.php";

if (isset($_POST['ipcrid'])) {
    $ipcrid = mysqli_real_escape_string($conn, $_POST['ipcrid']);
    $userid = $_SESSION['userid'];

    $coreQuery = "SELECT core, support FROM ipcr WHERE userid = '$userid' AND ipcrid = '$ipcrid'";
    $resss = mysqli_query($conn, $coreQuery);

    $combinedData = [];

    if (mysqli_num_rows($resss) > 0) {
        $row = mysqli_fetch_assoc($resss);
        $coreData = json_decode($row['core'], true);
        $supportData = json_decode($row['support'], true);

        // Add core values
        if (!empty($coreData)) {
            foreach ($coreData as $item) {
                $combinedData[] = [
                    'type' => 'core',
                    'value' => htmlspecialchars($item['core'])
                ];
            }
        }

        // Add support values
        if (!empty($supportData)) {
            foreach ($supportData as $item) {
                $combinedData[] = [
                    'type' => 'support',
                    'value' => htmlspecialchars($item['support'])
                ];
            }
        }
    }

    // Output the options for the select box
    if (!empty($combinedData)) {
        echo '<option selected disabled>Select function</option>';
        foreach ($combinedData as $item) {
            $firstLetter = strtoupper(substr($item['value'], 0, 1));
            echo "<option value='" . $firstLetter . "'>" . $item['value'] . "</option>";
        }
    } else {
        echo '<option>No functions available</option>';
    }
}
