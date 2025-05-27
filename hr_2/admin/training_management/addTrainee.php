<?php
include '../../../phpcon/conn.php'; // Adjust path as needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve data from POST request
    $employee_id = $_POST['EMPLOYEE_ID'] ?? '';
    $course_program = $_POST['COURSE_PROGRAM'] ?? '';
    $status = 'Pending'; // Default status
    $trainer = $_POST['TRAINER'] ?? ''; // Optional: assign trainer logic if applicable

    // Validate required fields
    if (!empty($employee_id) && !empty($course_program)) {
        // Optional: Generate trainee ID or fetch it from related table
        $trainee_id = $employee_id; // For now, assume same as employee ID

        // Prepare SQL query
        $stmt = $connection->prepare("
            INSERT INTO trainee_enrollment_approval 
            (TRAINEE_ID, EMPLOYEE_ID, COURSE_PROGRAM, TRAINER, STATUS) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iiiss", $trainee_id, $employee_id, $course_program, $trainer, $status);

        if ($stmt->execute()) {
            // Redirect with success flag
            header("Location: training_management.php?success=1");
            exit;
        } else {
            echo "Database error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Missing required fields.";
    }
} else {
    echo "Invalid request method.";
}
?>
