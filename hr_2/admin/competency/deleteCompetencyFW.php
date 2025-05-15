<?php
include '../../../phpcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['COMPETENCY_ID'])) {
    $id = intval($_POST['COMPETENCY_ID']);

    $sql = "DELETE FROM competency_framework WHERE COMPETENCY_ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: competency.php?delete=success");
        exit();
    } else {
        echo "Delete failed: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
}
?>
