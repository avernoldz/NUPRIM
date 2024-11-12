<?php
include "../../../Connections/Include.php";
include "../components/index.php";

if (isset($_POST['table'])) {
    $id = $_POST['id'];
    $field = $_POST['field'];
    $content = $_POST['content'];
    $table = $_POST['table'];
    $s = isset($_POST['supervisor']);

    // Function to check and convert date
    function convertToDate($dateString)
    {
        // Try to create a DateTime object
        $date = DateTime::createFromFormat('Y-m-d', $dateString)
            ?: DateTime::createFromFormat('m/d/Y', $dateString)
            ?: DateTime::createFromFormat('M d, Y', $dateString)
            ?: DateTime::createFromFormat('M d, y', $dateString)
            ?: DateTime::createFromFormat('n/j/Y', $dateString)
            ?: DateTime::createFromFormat('n/j/y', $dateString);

        // If a valid date was created, return it in Y-m-d format
        return $date ? $date->format('Y-m-d') : false;
    }

    // Check if the content is a valid date and convert it
    $formattedContent = convertToDate($content);

    // If conversion fails, use the original content as is
    if ($formattedContent === false) {
        $formattedContent = $content; // Use as plain string
    }

    $primaryKeyQuery = "SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'";
    $primaryKeyResult = $conn->query($primaryKeyQuery);

    if ($primaryKeyResult) {
        $row = $primaryKeyResult->fetch_assoc();
        $primaryKeyColumn = $row['Column_name']; // Fetch the primary key column name

        // Prepare the SQL statement
        $stmt = $conn->prepare("UPDATE `$table` SET $field = ? WHERE $primaryKeyColumn = ?");

        // Determine the parameter types based on the content type
        if ($field === 'dateColumnName') { // Replace 'dateColumnName' with the actual date column name
            $stmt->bind_param("si", $formattedContent, $id); // Assuming date is a string and id is an integer
        } else {
            $stmt->bind_param("si", $formattedContent, $id); // Assuming other content is a string
        }

        if ($stmt->execute()) {
            echo "Content updated successfully.";
        } else {
            echo "Error updating content: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error retrieving primary key: " . $conn->error;
    }

    $conn->close();
}

if (isset($_POST['supervisor'])) {
    $id = $_POST['id'];
    $field = $_POST['field'];
    $content = $_POST['content'];
    $table = $_POST['tables'];
    $s = isset($_POST['supervisor']);

    if ($s) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("UPDATE `$table` SET $field = ? WHERE userid = ?");

        $stmt->bind_param("si", $content, $id);
        if ($stmt->execute()) {
            echo "Content updated successfully.";
        } else {
            echo "Error updating content: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}

if (isset($_POST['detail_orders'])) {
    $table = mysqli_real_escape_string($conn, $_POST['tables']);
    $userid = mysqli_real_escape_string($conn, $_POST['userid']);
    $details_orders = mysqli_real_escape_string($conn, $_POST['detail_orders']);

    if ($table != 'service') {
        $orderType = mysqli_real_escape_string($conn, $_POST['orderType']);
        $dateStart = mysqli_real_escape_string($conn, $_POST['dateStart']);
        $dateEnd = mysqli_real_escape_string($conn, $_POST['dateEnd']);
        $authNo = mysqli_real_escape_string($conn, $_POST['authNo']);
        $office = isset($_POST['office']) ? mysqli_real_escape_string($conn, $_POST['office']) : '';
        $authDate = mysqli_real_escape_string($conn, $_POST['authDate']);
        $pending = 'Pending';


        $firstName = selectName($conn, $userid);

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../user/uploads/';
            $file = $_FILES['file']; // Assign the file to a variable

            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel'];

            // Check if file type is allowed
            if (!in_array($file['type'], $allowedTypes)) {
                die("Error: Only JPG, PNG, DOCX, PDF, and XLSX files are allowed.");
            }

            // Check if directory exists, if not create it
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Create a directory for the user's first name if it doesn't exist
            $userDir = $uploadDir . $firstName . "/";
            if (!file_exists($userDir)) {
                mkdir($userDir, 0777, true);
            }

            $uploadedDoc = basename($file['name']);
            $newDate = date('jgsu');

            $uploadDoc = pathinfo($uploadedDoc, PATHINFO_FILENAME) . "_" . $newDate . "." . pathinfo($uploadedDoc, PATHINFO_EXTENSION);

            $targetFile = $userDir . $uploadDoc;

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {

                // Prepare the SQL statement based on the selected table
                if ($table == 'detail') {
                    $stmt = $conn->prepare("INSERT INTO detail (uploadedDoc, status, userid, orderType, dateStart, dateEnd, authorityNo, office, authorityDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    // Bind parameters
                    $stmt->bind_param("sssssssss", $uploadDoc, $pending, $userid, $orderType, $dateStart, $dateEnd, $authNo, $office, $authDate);
                } else {
                    $stmt = $conn->prepare("INSERT INTO `case` (uploadedDoc, status, userid, sunction, dateStart, dateEnd, authorityNo, authorityDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                    // Bind parameters
                    $stmt->bind_param("ssssssss", $uploadDoc, $pending, $userid, $orderType, $dateStart, $dateEnd, $authNo, $authDate);
                }

                // Execute the statement
                if ($stmt->execute()) {
                    echo "Record inserted successfully.";
                } else {
                    echo "Error inserting record: " . $stmt->error;
                }

                $stmt->close();
                $conn->close();
                exit;
            } else {
                echo "Error moving uploaded file.";
                exit;
            }
        } else {
            echo "No file was uploaded or there was an upload error.";
            exit;
        }
    } else {
        $newPosition = mysqli_real_escape_string($conn, $_POST['newPosition']);
        $datePromotion = mysqli_real_escape_string($conn, $_POST['datePromotion']);
        $serviceid = mysqli_real_escape_string($conn, $_POST['serviceid']);

        $query = "SELECT position 
          FROM account 
          INNER JOIN plantilla ON account.itemNumber = plantilla.itemNumber 
          WHERE userid = ?";

        // Prepare the first statement
        $stmtSelect = $conn->prepare($query);
        $stmtSelect->bind_param("s", $userid);
        $stmtSelect->execute();
        $stmtSelect->store_result(); // Store the result to avoid "commands out of sync"
        $stmtSelect->bind_result($position);
        $stmtSelect->fetch();

        $lastPosition = $position;

        // Prepare the second statement to insert into servicehistory
        $insert = "INSERT INTO servicehistory (serviceid, lastPosition, newPosition, datePromotion) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insert);
        $stmtInsert->bind_param("ssss", $serviceid, $lastPosition, $newPosition, $datePromotion);

        if ($stmtInsert->execute()) {
            // Prepare the update statement
            $update = "UPDATE plantilla 
                            INNER JOIN account ON plantilla.itemNumber = account.itemNumber 
                            SET plantilla.position = ?
                            WHERE account.userid = ?;
                            ";
            $stmtUpdate = $conn->prepare($update);
            $stmtUpdate->bind_param("ss", $newPosition, $userid);

            if ($stmtUpdate->execute()) {
                echo "Record inserted successfully and position updated.";
            } else {
                echo "Error updating position: " . $stmtUpdate->error;
            }

            $stmtUpdate->close(); // Close the update statement
        } else {
            echo "Error inserting record: " . $stmtInsert->error;
        }

        // Close the select and insert statements
        $stmtSelect->close();
        $stmtInsert->close();
    }
}
