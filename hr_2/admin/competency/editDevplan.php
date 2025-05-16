<?php
include '../../../phpcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug first
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Get and sanitize inputs carefully (no conversion to int yet)
    $plan_id = $_POST['PLAN_ID'];
    $employee_id = $_POST['EMPLOYEE_ID'];
    $goal_description = trim($_POST['GOAL_DESCRIPTION']);
    $assigned_training = $_POST['ASSIGNED_TRAINING'];
    $milestone_start = $_POST['MILESTONE_START'];
    $milestone_end = $_POST['MILESTONE_END'];
    $status = trim($_POST['STATUS']);

    // Check raw first to avoid intval turning to 0 falsely
    if (!$plan_id || !$employee_id || !$goal_description || !$assigned_training || !$milestone_start || !$milestone_end || !$status) {
        die("Error: All fields are required.");
    }

    // Now safely convert IDs to integer after validation
    $plan_id = intval($plan_id);
    $employee_id = intval($employee_id);
    $assigned_training = intval($assigned_training);

    // Prepare the update statement
    $sql = "UPDATE competancy_development 
            SET EMPLOYEE_ID = ?, GOAL_DESCRIPTION = ?, ASSIGNED_TRAINING = ?, MILESTONE_START = ?, MILESTONE_END = ?, STATUS = ?
            WHERE PLAN_ID = ?";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        die("Error in preparing statement: " . $connection->error);
    }

    $stmt->bind_param("isisssi", $employee_id, $goal_description, $assigned_training, $milestone_start, $milestone_end, $status, $plan_id);

    if ($stmt->execute()) {
        header("Location: competency.php?edit=success");
        exit();
    } else {
        echo "Failed to update development plan: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
} else {
    die("Invalid request method.");
}
?>
