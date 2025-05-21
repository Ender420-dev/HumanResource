<?php
include '../../../phpcon/conn.php';

if (isset($_POST['TALENT_ID'])) {
    $id = $_POST['TALENT_ID'];

    $stmt = $connection->prepare("DELETE FROM talent_identification WHERE TALENT_ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
