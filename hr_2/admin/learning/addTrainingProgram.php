<?php
// DB Connection
include '../../../phpcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and assign POST values
    $program_type = trim($_POST['PROGRAM_TYPE']);
    $program_name = trim($_POST['PROGRAM_NAME']);
    $trainer_id = intval($_POST['TRAINER']);
    $status = trim($_POST['STATUS']);
    $start_date = trim($_POST['START_DATE']);
    $end_date = trim($_POST['END_DATE']);
    $description = trim($_POST['DESCRIPTION_PROGRAM']);

    // Validate required fields
    if (empty($program_type) || empty($program_name) || empty($trainer_id) || empty($status)) {
        die("Error: All fields are required except description.");
    }

    // ✅ Check if Trainer exists in trainer_faculty
    $checkTrainer = $connection->prepare("SELECT TRAINER_ID FROM trainer_faculty WHERE TRAINER_ID = ?");
    $checkTrainer->bind_param("i", $trainer_id);
    $checkTrainer->execute();
    $checkTrainer->store_result();

    if ($checkTrainer->num_rows === 0) {
        die("Error: Trainer ID $trainer_id does not exist in trainer_faculty.");
    }
    $checkTrainer->close();

    // ✅ Insert Query
    $sql = "INSERT INTO training_program (PROGRAM_TYPE, PROGRAM_NAME, TRAINER, STATUS, START, END, DESCRIPTION_PROGRAM) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssissss", $program_type, $program_name, $trainer_id, $status, $start_date, $end_date, $description);

    if ($stmt->execute()) {
        // Success - redirect back
        header("Location: learning.php?success=1");
        exit();
    } else {
        echo "Error inserting record: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
} else {
    echo "Invalid request method.";
}
?>
