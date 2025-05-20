<?php
include '../../../phpcon/conn.php'; // Adjust path to your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $program_id = intval($_POST['PROGRAM_ID']);
    $program_name = trim($_POST['PROGRAM_NAME']);
    $trainer_name = trim($_POST['TRAINER_NAME']); // assuming it's plain text
    $status = trim($_POST['STATUS']);

    // Basic validation
    if (empty($program_id) || empty($program_name) || empty($trainer_name) || empty($status)) {
        die("Error: All fields are required.");
    }

    // Optional: Get trainer_id from trainer name (if normalized)
    $trainer_id = null;
    $trainerLookup = $connection->prepare("SELECT TRAINER_ID FROM trainer_faculty WHERE FULLNAME = ?");
    $trainerLookup->bind_param("s", $trainer_name);
    $trainerLookup->execute();
    $trainerLookup->bind_result($trainer_id_result);
    if ($trainerLookup->fetch()) {
        $trainer_id = $trainer_id_result;
    } else {
        die("Error: Trainer not found.");
    }
    $trainerLookup->close();

    // Update the training_program table
    $update = $connection->prepare("
        UPDATE training_program 
        SET PROGRAM_NAME = ?, TRAINER = ?, STATUS = ? 
        WHERE PROGRAM_ID = ?
    ");
    if (!$update) {
        die("Prepare failed: " . $connection->error);
    }

    $update->bind_param("sisi", $program_name, $trainer_id, $status, $program_id);

    if ($update->execute()) {
        header("Location: learning.php?update=success");
        exit();
    } else {
        echo "Update failed: " . $update->error;
    }

    $update->close();
    $connection->close();
} else {
    die("Invalid request method.");
}
?>
