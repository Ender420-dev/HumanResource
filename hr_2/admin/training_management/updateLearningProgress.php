<?php
include '../../../phpcon/conn.php'; // Adjust path as needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lp_id = intval($_POST['LP_ID']);
    $progress = intval($_POST['PROGRESS']);
    $status = trim($_POST['STATUS']);

    if (empty($lp_id) || $progress < 0 || $progress > 100 || empty($status)) {
        die("Error: All fields are required and progress must be between 0 and 100.");
    }

    $sql = "UPDATE learning_progress 
            SET PROGRESS = ?, STATUS = ?
            WHERE LP_ID = ?";

    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("isi", $progress, $status, $lp_id);

    if ($stmt->execute()) {
        header("Location: training_management.php?update=success");
        exit();
    } else {
        echo "Update failed: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
} else {
    die("Invalid request method.");
}
?>
