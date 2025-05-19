<?php
require '../../../phpcon/conn.php'; // your DB connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $programId = isset($_POST['PROGRAM_ID']) ? intval($_POST['PROGRAM_ID']) : 0;
    $programType = trim($_POST['PROGRAM_TYPE'] ?? '');
    $programName = trim($_POST['PROGRAM_NAME'] ?? '');
    $trainerId = isset($_POST['TRAINER']) ? intval($_POST['TRAINER']) : null;
    $status = trim($_POST['STATUS'] ?? '');
    $description = trim($_POST['DESCRIPTION_PROGRAM'] ?? '');

    // Basic validation
    if (!$programId || !$programType || !$programName || !$status) {
        die("Missing required fields.");
    }

    // Update query
    $stmt = $connection->prepare("
        UPDATE learning_content lc
        LEFT JOIN training_program tp ON lc.CALENDAR = tp.PROGRAM_ID
        SET 
            tp.PROGRAM_TYPE = ?, 
            tp.PROGRAM_NAME = ?, 
            tp.DESCRIPTION_PROGRAM = ?, 
            lc.STATUS = ?, 
            lc.TRAINER = ?
        WHERE lc.LEARNING_ID = ?
    ");

    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("ssssii", $programType, $programName, $description, $status, $trainerId, $programId);

    if ($stmt->execute()) {
        header("Location: learning.php?success=1"); // redirect back
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
