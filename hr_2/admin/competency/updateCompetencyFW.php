<?php
include '../../../phpcon/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['COMPETENCY_ID']);
    $name = trim($_POST['Name']);
    $role = trim($_POST['ROLE']);
    $department = trim($_POST['DEPARTMENT']);

    if (empty($name) || empty($role) || empty($department)) {
        die("Error: All fields are required.");
    }

    $sql = "UPDATE competency_framework SET NAME = ?, ROLE = ?, DEPARTMENT = ?, LASTUPDATE = NOW() WHERE COMPETENCY_ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssi", $name, $role, $department, $id);

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
