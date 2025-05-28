<?php
include '../Database/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id    = $_POST['employee_id'];
$base_salary    = $_POST['base_salary'];
$effective_date = $_POST['effective_date'];
$end_date       = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

// Validate inputs
if (empty($employee_id) || empty($base_salary) || empty($effective_date)) {
    die("All required fields must be filled.");
}

// Prepare SQL
$stmt = $conn->prepare("
    INSERT INTO salary_records (employee_id, base_salary, effective_date, end_date)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("idss", $employee_id, $base_salary, $effective_date, $end_date);

if ($stmt->execute()) {
    echo "Salary record assigned successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
