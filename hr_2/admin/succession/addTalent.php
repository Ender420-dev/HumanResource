<?php
include '../../../phpcon/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee = $_POST['EMPLOYEE'];
    $department = $_POST['DEPARTMENT'];
    $role = $_POST['CURRENT_ROLE'];
    $successor = $_POST['SUCCESSOR'];
    $readiness = $_POST['READINESS'];
    $potential = $_POST['POTENTIAL'];

    $stmt = $connection->prepare("INSERT INTO talent_identification (EMPLOYEE, DEPARTMENT, `CURRENT_ROLE`, SUCCESSOR, READINESS, POTENTIAL) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $employee, $department, $role, $successor, $readiness, $potential);

    if ($stmt->execute()) {
        header("Location: succession.php"); // redirect on success
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
