<?php
include '../../../phpcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize inputs
    $employee_id = intval($_POST['EMPLOYEE_ID']);
    $goal_description = trim($_POST['GOAL_DESCRIPTION']);
    $assigned_training = intval($_POST['ASSIGN_TRAINING']);
    $milestone_start = $_POST['MILESTONE_START'];
    $milestone_end = $_POST['MILESTONE_END'];
    $status = trim($_POST['STATUS']);

    // Validation
    if (empty($employee_id) || empty($goal_description) || empty($assigned_training) || empty($milestone_start) || empty($milestone_end) || empty($status)) {
        die("Error: All fields are required.");
    }

    // Prepare the statement
    $sql = "INSERT INTO competancy_development (EMPLOYEE_ID, GOAL_DESCRIPTION, ASSIGNED_TRAINING, MILESTONE_START, MILESTONE_END, STATUS)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        die("Error in preparing statement: " . $connection->error);
    }

    // Bind parameters
    $stmt->bind_param("isisss", $employee_id, $goal_description, $assigned_training, $milestone_start, $milestone_end, $status);

    // Execute and check
    if ($stmt->execute()) {
        header("Location: competency.php?add=success");
        exit();
    } else {
        echo "Failed to add development plan: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
} else {
    die("Invalid request method.");
}
?>
