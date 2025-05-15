<?php
include '../../../phpcon/conn.php';

if (isset($_GET['id'])) {
    $assessment_id = intval($_GET['id']);

    $stmt = $connection->prepare("DELETE FROM competency_assessment WHERE ASSESSMENT_ID = ?");
    $stmt->bind_param("i", $assessment_id);

    if ($stmt->execute()) {
        header("Location: competency.php?deleted=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
