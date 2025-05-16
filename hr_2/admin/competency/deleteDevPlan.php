<?php
include '../../../phpcon/conn.php';

if (isset($_GET['PLAN_ID'])) {
    $plan_id = intval($_GET['PLAN_ID']);

    if ($plan_id <= 0) {
        die("Invalid Plan ID.");
    }

    // Prepare delete statement
    $sql = "DELETE FROM competancy_development WHERE PLAN_ID = ?";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        die("Error in preparing statement: " . $connection->error);
    }

    $stmt->bind_param("i", $plan_id);

    if ($stmt->execute()) {
        header("Location: competency.php?delete=success");
        exit();
    } else {
        echo "Failed to delete development plan: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
} else {
    die("No Plan ID provided.");
}
?>
