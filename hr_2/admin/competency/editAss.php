<?php
include '../../../phpcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['ASSESSMENT_ID']);
    $employee = trim($_POST['EMPLOYEE']);
    $type = trim($_POST['ASSESSMENT_TYPE']);
    $score = intval($_POST['SCORE']);
    $competency = trim($_POST['COMPETENCY']);

    if (empty($employee) || empty($type) || empty($competency) || $score < 0 || $score > 100) {
        die("Error: All fields are required and score must be between 0-100.");
    }

    $sql = "UPDATE competency_assessment SET EMPLOYEE = ?, ASSESSMENT_TYPE = ?, SCORE = ?, COMPETENCY = ? WHERE ASSESSMENT_ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssisi", $employee, $type, $score, $competency, $id);

    if ($stmt->execute()) {
        header("Location: competency.php?update=success");
        exit();
    } else {
        echo "Update failed: " . $connection->error;
    }

    $stmt->close();
    $connection->close();
}
?>
