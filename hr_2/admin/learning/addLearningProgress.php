<?php
include '../../../phpcon/conn.php'; // Make sure this includes your $connection variable

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id = $_POST['EMPLOYEE_ID'] ?? '';
    $course_id   = $_POST['COURSE'] ?? '';
    $progress    = $_POST['PROGRESS'] ?? 0;
    $start_date  = $_POST['START'] ?? '';
    $end_date    = $_POST['END'] ?? '';
    $status      = $_POST['STATUS'] ?? 'Pending';

    // Basic input validation
    if ($employee_id && $course_id && is_numeric($progress) && $start_date && $status) {
        // Prepare statement to avoid SQL injection
        $stmt = $connection->prepare("
            INSERT INTO learning_progress 
            (EMPLOYEE_ID, COURSE, PROGRESS, START, END, STATUS) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("iiisss", $employee_id, $course_id, $progress, $start_date, $end_date, $status);

        if ($stmt->execute()) {
            // Redirect with success message (optional)
            header("Location: learning.php?success=1");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid input. Please fill all required fields correctly.";
    }
} else {
    echo "Invalid request method.";
}
?>
