<?php
include '../../../phpcon/conn.php';

if (isset($_GET['id'])) {
    $profileId = intval($_GET['id']);
    $stmt = $connection->prepare("DELETE FROM employee_competency WHERE PROFILE_ID = ?");
    $stmt->bind_param("i", $profileId);

    if ($stmt->execute()) {
        header("Location: competency.php?status=deleted");
    } else {
        echo "Error deleting profile.";
    }
}
?>
