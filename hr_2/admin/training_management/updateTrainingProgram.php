<?php
// DB Connection
include '../../../phpcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $program_id = $_POST['PROGRAM_ID'];
    $program_type = $_POST['PROGRAM_TYPE'];
    $program_name = $_POST['PROGRAM_NAME'];
    $trainer_id = $_POST['TRAINER_I'];
    $status = $_POST['STATUS'];
    $start_date = $_POST['START_DATE'];
    $end_date = $_POST['END_DATE'];
    $description = $_POST['DESCRIPTION_PROGRAM'];

    // Sanitize input (basic)
    $program_id = intval($program_id);
    $program_type = trim($program_type);
    $program_name = trim($program_name);
    $trainer_id = intval($trainer_id);
    $status = trim($status);
    $start_date = trim($start_date);
    $end_date = trim($end_date);
    $description = trim($description);

    // Update Query
    $sql = "UPDATE training_program 
            SET PROGRAM_TYPE = ?, 
                PROGRAM_NAME = ?, 
                TRAINER = ?, 
                STATUS = ?, 
                START = ?, 
                END = ?, 
                DESCRIPTION_PROGRAM = ?
            WHERE PROGRAM_ID = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssissssi", $program_type, $program_name, $trainer_id, $status, $start_date, $end_date, $description, $program_id);

    if ($stmt->execute()) {
        // Success - Redirect or Return Success Response
        header("Location: training_management.php?success=1"); // Change to your page
        exit();
    } else {
        // Error Handling
        echo "Error updating record: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
}
?>
