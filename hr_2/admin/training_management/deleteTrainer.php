<?php
include '../../../phpcon/conn.php';

if (isset($_POST['TRAINER_ID'])) {
    $trainerID = $_POST['TRAINER_ID'];

    $sql = "DELETE FROM trainer_faculty WHERE TRAINER_ID = ?";
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
