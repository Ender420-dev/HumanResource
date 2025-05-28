<?php
include '../Database/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Input values
$employee_id     = $_POST['employee_id'];
$deduction_type  = $_POST['deduction_type'];
$amount          = $_POST['amount'];
$effective_date  = $_POST['effective_date'];

// Get a deduction_id from deductions table that matches the type
$deduction = $conn->prepare("SELECT deduction_id FROM deductions WHERE deduction_type = ? LIMIT 1");
$deduction->bind_param("s", $deduction_type);
$deduction->execute();
$result = $deduction->get_result();
$deduction_row = $result->fetch_assoc();

if (!$deduction_row) {
    die("Deduction type not found in database.");
}

$deduction_id = $deduction_row['deduction_id'];

// Insert into employee_deductions
$stmt = $conn->prepare("
    INSERT INTO employee_deductions (employee_id, deduction_id, amount, effective_date) 
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("iids", $employee_id, $deduction_id, $amount, $effective_date);

if ($stmt->execute()) {
    echo "Deduction assigned successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
