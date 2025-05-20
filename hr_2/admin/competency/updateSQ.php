<?php
include '../../../phpcon/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skill_id = $_POST['SKILL_ID'];
    $employee_id = $_POST['EMPLOYEE_ID'];
    $skill = $_POST['SKILL'];
    $skill_level = $_POST['SKILL_LEVEL'];
    $recommended_training = $_POST['RECOMMENDED_TRAINING'];
    $deadline = $_POST['DEADLINE'];

    $stmt = $connection->prepare("UPDATE skill_qualification SET EMPLOYEE_ID = ?, SKILL = ?, SKILL_LEVEL = ?, RECOMMENDED_TRAINING = ?, DEADLINE = ? WHERE SKILL_ID = ?");
    $stmt->bind_param("issssi", $employee_id, $skill, $skill_level, $recommended_training, $deadline, $skill_id);

    if ($stmt->execute()) {
        header("Location: competency.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
