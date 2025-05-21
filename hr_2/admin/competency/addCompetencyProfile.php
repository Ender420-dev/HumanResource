<?php
include '../../../phpcon/conn.php';// include your DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeId = $_POST['EMPLOYEE_ID'];
    $competency = $_POST['COMPETECY'];
    $proficiency = $_POST['PROFICIENCY'];
    $certification = $_POST['CERTIFICATION'];
    $lastUpdated = date('Y-m-d');

    $stmt = $connection->prepare("INSERT INTO employee_competency (EMPLOYEE_ID, COMPETECY, PROFICIENCY, CERTIFICATION, LASTUPDATED) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $employeeId, $competency, $proficiency, $certification, $lastUpdated);

    if ($stmt->execute()) {
        header("Location: competency.php?status=added"); // Redirect after successful add
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
