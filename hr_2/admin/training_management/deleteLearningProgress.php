<?php
include '../../../phpcon/conn.php'; // Update with your actual DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['LP_ID'])) {
    $lp_id = intval($_POST['LP_ID']);

    $stmt = $connection->prepare("DELETE FROM learning_progress WHERE LP_ID = ?");
    $stmt->bind_param("i", $lp_id);

    if ($stmt->execute()) {
        header("Location: training_management.php?message=deleted"); // Replace with actual page
        exit;
    } else {
        echo "Error deleting record: " . $connection->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
