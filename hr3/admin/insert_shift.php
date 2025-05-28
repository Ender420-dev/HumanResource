<?php
include '../connections.php'; // PDO assumed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $shift_name = $_POST['shift_name'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $description = $_POST['description'] ?? null;

    try {
        $stmt = $conn->prepare("
            INSERT INTO hr3.shifts (shift_name, start_time, end_time, description)
            VALUES (:shift_name, :start_time, :end_time, :description)
        ");

        $stmt->bindValue(':shift_name', $shift_name);
        $stmt->bindValue(':start_time', $start_time);
        $stmt->bindValue(':end_time', $end_time);
        $stmt->bindValue(':description', $description);

        $stmt->execute();
        echo "<script>alert('Shift added successfully');</script>";
        header("Location: ../admin/shifting schedule.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
