<?php
include '../../../phpcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CRITICAL_ID'])) {
    $id = intval($_POST['CRITICAL_ID']);

    $stmt = $connection->prepare("DELETE FROM critical_position WHERE CRITICAL_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: succession.php?deleted=1");
        exit;
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$connection->close();
?>
