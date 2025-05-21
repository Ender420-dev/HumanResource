<?php
include '../../../phpcon/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $successor = $_POST['SUCCESION_NAME'];
    $currentRole = $_POST['CURRENT_ROLE'];
    $readiness = $_POST['READINESS_LEVEL'];
    $actions = $_POST['DEVELOPMENT_ACTIONS'];
    $targetDate = $_POST['TARGET_READINESS_DATE'];
    $progress = $_POST['PROGRESS'];

    // Escape reserved keywords like CURRENT_ROLE using backticks
    $stmt = $connection->prepare("
        INSERT INTO succession_plan_dev 
        (`SUCCESION_NAME`, `CURRENT_ROLE`, `READINESS_LEVEL`, `DEVELOPMENT_ACTIONS`, `TARGET_READINESS_DATE`, `PROGRESS`) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param("sssssi", $successor, $currentRole, $readiness, $actions, $targetDate, $progress);

    if ($stmt->execute()) {
        header("Location: succession.php?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
