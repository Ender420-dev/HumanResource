<?php
include '../../../phpcon/conn.php';

if (isset($_POST['submit'])) {
    $trainerID = $_POST['TRAINER_ID'];
    $FullName = $_POST['FULLNAME'];
    $subject = $_POST['course']; // ✅ Fixed: Get 'course' from POST

    $sql = "UPDATE trainer_faculty SET FULLNAME=?, course=?, UPDATE_AT=NOW() WHERE TRAINER_ID=?";

    $stmt = $connection->prepare($sql);

    $stmt->bind_param("ssi", $FullName, $subject, $trainerID);

    if ($stmt->execute()) {
        header("Location: training_management.php?message=Edit Successful");
        exit();
        echo "Success;";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
