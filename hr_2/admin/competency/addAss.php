<?php
include '../../../phpcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee = trim($_POST['EMPLOYEE']);
    $assessment_type = trim($_POST['ASSESSMENT_TYPE']);
    $score = intval($_POST['SCORE']);
    $competency = trim($_POST['COMPETENCY']);
    $date = date('Y-m-d'); // Current date

    $stmt = $connection->prepare("INSERT INTO competency_assessment (EMPLOYEE, ASSESSMENT_TYPE, SCORE, DATE, COMPETENCY) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $employee, $assessment_type, $score, $date, $competency);

    if ($stmt->execute()) {
        header("Location: competency.php?success=1"); // Redirect back to table
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
