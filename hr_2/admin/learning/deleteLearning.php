<?php
include '../../../phpcon/conn.php';

if (isset($_POST['LEARNING_ID'])) {
    $trainerID = $_POST['LEARNING_ID'];

    $sql = "DELETE FROM learning_content WHERE LEARNING_ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $trainerID);

    if ($stmt->execute()) {
        echo "Delete Successful";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
} else {
    echo "Invalid Request";
}
?>
